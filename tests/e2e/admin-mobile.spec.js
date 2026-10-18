import { test, expect } from '@playwright/test';

// The admin CRM on a phone and on a wide desktop screen. Needs a local admin login:
//   E2E_ADMIN_EMAIL=... E2E_ADMIN_PASSWORD=... npx playwright test admin-mobile
// Pages that need a record (a booking, client, ...) use the first one found on its list page.

const email = process.env.E2E_ADMIN_EMAIL;
const password = process.env.E2E_ADMIN_PASSWORD;

test.skip(!email || !password, 'Set E2E_ADMIN_EMAIL and E2E_ADMIN_PASSWORD to run the admin checks');

const staticPaths = [
    '/admin', '/admin/bookings', '/admin/bookings/create', '/admin/clients', '/admin/expenses', '/admin/expenses/create',
    '/admin/invoices', '/admin/services', '/admin/services/create', '/admin/service-addons/create', '/admin/users',
    '/admin/users/create', '/admin/profile', '/admin/password', '/admin/notifications', '/admin/company-settings',
    '/admin/fuel-settings',
];

// First link on a list page that matches the pattern, e.g. the first booking's detail page.
const detailLinks = [
    ['/admin/bookings', /^\/admin\/bookings\/\d+$/],
    ['/admin/clients', /^\/admin\/clients\/\d+$/],
    ['/admin/invoices', /^\/admin\/invoices\/\d+$/],
    ['/admin/expenses', /^\/admin\/expenses\/\d+\/edit$/],
    ['/admin/services', /^\/admin\/services\/\d+\/edit$/],
    ['/admin/users', /^\/admin\/users\/\d+\/edit$/],
];

async function signIn(page) {
    await page.goto('/admin/login');
    await page.fill('input[name=email]', email);
    await page.fill('input[name=password]', password);
    await Promise.all([page.waitForURL(/\/admin(\/|$)/), page.click('button[type=submit]')]);
}

async function allPaths(page) {
    const paths = [...staticPaths];
    for (const [list, pattern] of detailLinks) {
        await page.goto(list);
        const hrefs = await page.$$eval('main a[href]', (as) => as.map((a) => new URL(a.href).pathname));
        const hit = hrefs.find((h) => pattern.test(h));
        if (hit) paths.push(hit);
    }
    return paths;
}

test('every admin page fits a phone screen and the sidebar is tucked away', async ({ page }) => {
    test.setTimeout(5 * 60_000);
    await page.setViewportSize({ width: 390, height: 844 });
    await signIn(page);
    const failures = [];

    for (const path of await allPaths(page)) {
        const res = await page.goto(path, { waitUntil: 'networkidle' });
        const m = await page.evaluate(() => {
            const scroller = document.querySelector('main > div.overflow-y-auto');
            const aside = document.querySelector('#admin-sidebar').getBoundingClientRect();
            return {
                page: document.documentElement.scrollWidth - document.documentElement.clientWidth,
                content: scroller ? scroller.scrollWidth - scroller.clientWidth : 0,
                sidebarOnScreen: aside.right > 0,
            };
        });
        if (res.status() >= 400) failures.push(`${path}: HTTP ${res.status()}`);
        if (m.page > 1 || m.content > 1) failures.push(`${path}: content is wider than the screen (${Math.max(m.page, m.content)}px)`);
        if (m.sidebarOnScreen) failures.push(`${path}: sidebar covers the page on a phone`);
    }

    expect(failures, failures.join('\n')).toEqual([]);
});

test('phone menu opens and closes', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await signIn(page);
    await page.goto('/admin/bookings');
    const sidebar = page.locator('#admin-sidebar');

    await page.getByRole('button', { name: 'Open menu' }).click();
    await expect.poll(async () => (await sidebar.boundingBox()).x).toBe(0);
    await expect(sidebar.getByRole('link', { name: 'Clients' })).toBeVisible();

    await page.getByRole('button', { name: 'Close menu' }).click();
    await expect.poll(async () => { const b = await sidebar.boundingBox(); return b.x + b.width; }).toBeLessThanOrEqual(0);
});

test('admin content uses the full width on a large screen', async ({ page }) => {
    await page.setViewportSize({ width: 1920, height: 1080 });
    await signIn(page);
    for (const path of ['/admin/bookings', '/admin/services/create', '/admin/expenses/create']) {
        await page.goto(path, { waitUntil: 'networkidle' });
        const { content, available } = await page.evaluate(() => {
            const scroller = document.querySelector('main > div.overflow-y-auto');
            const inner = scroller.firstElementChild;
            const pad = parseFloat(getComputedStyle(scroller).paddingLeft) + parseFloat(getComputedStyle(scroller).paddingRight);
            return { content: inner.getBoundingClientRect().width, available: scroller.clientWidth - pad };
        });
        expect(content, `${path} content is capped narrower than the screen`).toBeGreaterThanOrEqual(available - 2);
    }
});
