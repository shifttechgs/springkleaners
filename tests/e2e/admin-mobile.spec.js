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
            const scroller = document.getElementById('admin-content');
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

test('phone: tab bar navigates and the More sheet opens and closes', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await signIn(page);
    await page.goto('/admin/bookings');
    const tabbar = page.locator('#admin-tabbar');
    await expect(tabbar).toBeVisible();
    await expect(page.locator('#admin-sidebar')).toBeHidden();

    await tabbar.getByRole('link', { name: 'Invoices' }).click();
    await expect(page).toHaveURL(/\/admin\/invoices$/);
    await expect(tabbar.getByRole('link', { name: 'Invoices' })).toHaveAttribute('aria-current', 'page');

    // The raised centre button starts a new quote.
    await tabbar.getByRole('link', { name: 'New quote' }).click();
    await expect(page).toHaveURL(/\/admin\/bookings\/create$/);
    // Building a quote is a focused screen: the tab bar steps aside for the pinned Continue bar.
    await expect(page.locator('#admin-tabbar')).toHaveCount(0);
    await page.goto('/admin/bookings');

    await tabbar.getByRole('button', { name: 'More' }).click();
    const sheet = page.getByRole('dialog', { name: 'More' });
    await expect(sheet.getByRole('link', { name: 'Clients' })).toBeVisible();
    await expect(sheet.getByRole('link', { name: 'Expenses' })).toBeVisible();
    await sheet.getByRole('button', { name: 'Done' }).click();
    await expect(sheet).toBeHidden();

    // Detail pages get an iOS back button instead of breadcrumbs.
    await page.goto('/admin/bookings/create');
    await page.locator('#admin-navbar').getByRole('link', { name: 'Bookings' }).click();
    await expect(page).toHaveURL(/\/admin\/bookings$/);
});

test('admin content uses the full width on a large screen', async ({ page }) => {
    await page.setViewportSize({ width: 1920, height: 1080 });
    await signIn(page);
    for (const path of ['/admin/bookings', '/admin/services/create', '/admin/expenses/create']) {
        await page.goto(path, { waitUntil: 'networkidle' });
        const { content, available } = await page.evaluate(() => {
            const scroller = document.getElementById('admin-content');
            const inner = scroller.firstElementChild;
            const pad = parseFloat(getComputedStyle(scroller).paddingLeft) + parseFloat(getComputedStyle(scroller).paddingRight);
            return { content: inner.getBoundingClientRect().width, available: scroller.clientWidth - pad };
        });
        expect(content, `${path} content is capped narrower than the screen`).toBeGreaterThanOrEqual(available - 2);
    }
});

test('phone: tables turn into cards, rows open their record, and the page itself scrolls', async ({ page }) => {
    await page.setViewportSize({ width: 360, height: 740 });
    await signIn(page);

    for (const path of ['/admin/bookings', '/admin/clients', '/admin/invoices', '/admin/expenses', '/admin/services', '/admin/users']) {
        await page.goto(path, { waitUntil: 'networkidle' });
        const m = await page.evaluate(() => {
            const table = document.querySelector('table.data-table');
            const rows = table ? [...table.querySelectorAll('tbody tr')] : [];
            return {
                hasTable: !!table,
                theadHidden: table ? getComputedStyle(table.querySelector('thead')).display === 'none' : true,
                widest: Math.max(0, ...rows.map((r) => r.getBoundingClientRect().right)),
                bodyOverflow: getComputedStyle(document.body).overflowY,
            };
        });
        expect(m.bodyOverflow, `${path} locks page scrolling`).not.toBe('hidden');
        if (!m.hasTable) continue;
        expect(m.theadHidden, `${path} still shows the table header on a phone`).toBe(true);
        expect(m.widest, `${path} rows run off the screen`).toBeLessThanOrEqual(360);
    }

    // Tapping anywhere on a booking card opens that booking.
    await page.goto('/admin/bookings');
    const firstRow = page.locator('table.data-table tbody tr').first();
    if (await firstRow.count()) {
        await Promise.all([page.waitForURL(/\/admin\/bookings\/\d+$/), firstRow.click({ position: { x: 40, y: 50 } })]);
        // Price and status are edited in sheets that slide up from the bottom.
        await page.getByRole('button', { name: /^Price/ }).click();
        const priceSheet = page.getByRole('dialog', { name: 'Quote price' });
        await expect(priceSheet.getByLabel('Price in rand')).toBeVisible();
        await priceSheet.getByRole('button', { name: 'Cancel' }).click();
        await expect(priceSheet).toBeHidden();
        await page.getByRole('button', { name: /^Status/ }).click();
        await expect(page.getByRole('dialog', { name: 'Status and notes' }).getByRole('radiogroup')).toBeVisible();
    }
});

test('phone: the quote builder has a pinned total + Continue bar that validates each step', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 700 });
    await signIn(page);
    await page.goto('/admin/bookings/create', { waitUntil: 'networkidle' });
    await expect(page.locator('#admin-tabbar')).toHaveCount(0);
    const bar = page.locator('.ios-actionbar');
    await expect(page.getByText(/^Step 1 of 5:/)).toBeVisible();

    await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
    await bar.getByRole('button', { name: 'Continue' }).click();
    await expect(page.getByText(/^Step 2 of 5:/)).toBeVisible();
    await expect.poll(() => page.evaluate(() => window.scrollY)).toBe(0);

    // Client step needs a name and phone before moving on.
    await bar.getByRole('button', { name: 'Continue' }).click();
    await expect(page.getByText(/^Step 2 of 5:/)).toBeVisible();
    await expect(page.getByText('Please enter a name')).toBeVisible();

    // Returning client: focusing the lookup lists recent clients; picking one fills the step.
    await page.locator('#client-search').focus();
    const option = page.getByRole('listbox', { name: 'Matching clients' }).getByRole('option').first();
    if (await option.count() || await option.waitFor({ timeout: 3000 }).then(() => true, () => false)) {
        await option.click();
        await expect(page.getByText(/last booking — check the details below/)).toBeVisible();
        await bar.getByRole('button', { name: 'Continue' }).click();
        await expect(page.getByText(/^Step 3 of 5:/)).toBeVisible();
    }
});

test('phone: swiping a booking row left reveals Call and WhatsApp; a tap closes it', async ({ browser }) => {
    const context = await browser.newContext({ viewport: { width: 390, height: 844 }, hasTouch: true, isMobile: true });
    const page = await context.newPage();
    await signIn(page);
    await page.goto('/admin/bookings', { waitUntil: 'networkidle' });
    const row = page.locator('table.data-table tbody tr[data-swipe]').first();
    if (!(await row.count())) { await context.close(); return; }

    const box = await row.boundingBox();
    const y = box.y + box.height / 2;
    const cdp = await context.newCDPSession(page);
    const touch = (type, x) => cdp.send('Input.dispatchTouchEvent', { type, touchPoints: type === 'touchEnd' ? [] : [{ x, y }] });
    await touch('touchStart', box.x + box.width - 30);
    for (let dx = 10; dx <= 200; dx += 15) await touch('touchMove', box.x + box.width - 30 - dx);
    await touch('touchEnd');

    await expect(row).toHaveClass(/is-swiped/);
    await expect(row.getByRole('link', { name: /^Call / })).toBeVisible();
    await expect(row.getByRole('link', { name: /^WhatsApp / })).toHaveAttribute('href', /^https:\/\/wa\.me\/\d+$/);

    // Tapping the open row closes it instead of opening the booking.
    await page.touchscreen.tap(box.x + 60, y);
    await expect(row).not.toHaveClass(/is-swiped/);
    await expect(page).toHaveURL(/\/admin\/bookings$/);
    await context.close();
});
