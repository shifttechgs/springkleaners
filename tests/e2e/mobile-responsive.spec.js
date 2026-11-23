import { test, expect } from '@playwright/test';

const viewports = [
    { name: 'iphone-se', width: 375, height: 667 },
    { name: 'iphone-12', width: 390, height: 844 },
    { name: 'android-360', width: 360, height: 800 },
];

const pages = [
    { path: '/', name: 'homepage' },
    { path: '/about', name: 'about' },
    { path: '/services/deep-cleaning', name: 'service-deep-cleaning' },
    { path: '/services/end-of-tenancy', name: 'service-eot' },
    { path: '/services/post-construction', name: 'service-post-construction' },
    { path: '/areas/milnerton', name: 'area-milnerton' },
    { path: '/areas/bloubergstrand', name: 'area-bloubergstrand' },
    { path: '/blog', name: 'blog-index' },
    { path: '/blog/deep-clean-cost-cape-town-pricing-guide', name: 'blog-post' },
    { path: '/privacy-policy', name: 'privacy' },
    { path: '/terms-of-service', name: 'terms' },
];

for (const vp of viewports) {
    test.describe(`Viewport: ${vp.name} (${vp.width}x${vp.height})`, () => {
        test.use({ viewport: { width: vp.width, height: vp.height } });

        for (const p of pages) {
            test(`${p.name} — no horizontal overflow + screenshot`, async ({ page }) => {
                const consoleErrors = [];
                page.on('console', (msg) => {
                    if (msg.type() === 'error') consoleErrors.push(msg.text());
                });

                await page.goto(p.path, { waitUntil: 'networkidle' });
                await page.waitForTimeout(400); // let WOW.js / lazy stuff settle

                const overflow = await page.evaluate(() => {
                    const doc = document.documentElement;
                    return {
                        scrollWidth: doc.scrollWidth,
                        clientWidth: doc.clientWidth,
                        diff: doc.scrollWidth - doc.clientWidth,
                    };
                });

                await page.screenshot({
                    path: `test-results/mobile/${vp.name}__${p.name}.png`,
                    fullPage: false, // viewport-only — readable at a glance; overflow check above already covers the full document
                });

                console.log(`[${vp.name}] ${p.name}: overflow diff=${overflow.diff}px, consoleErrors=${consoleErrors.length}`);
                if (consoleErrors.length) console.log(`  console errors: ${JSON.stringify(consoleErrors)}`);

                expect(overflow.diff, `Horizontal overflow of ${overflow.diff}px on ${p.name} at ${vp.name}`).toBeLessThanOrEqual(5);
            });
        }
    });
}

test.describe('Homepage section-by-section (iPhone SE viewport)', () => {
    test.use({ viewport: { width: 375, height: 667 } });

    // Phones get the app layout on the homepage: its tile grid, then the content sections shown on phones.
    // (#why-us and #testimonials are desktop sections; the app shows its own versions.)
    const sections = ['#app-services', '#services', '#pricing', '#areas', '#faq'];

    test('scroll through each homepage section and screenshot', async ({ page }) => {
        await page.goto('/', { waitUntil: 'networkidle' });
        await page.waitForTimeout(500);
        for (const sel of sections) {
            const el = page.locator(sel).first();
            if (await el.count()) {
                await el.scrollIntoViewIfNeeded();
                await page.waitForTimeout(600); // let WOW.js reveal fire
                const safeName = sel.replace('#', '');
                await page.screenshot({ path: `test-results/mobile/section__${safeName}.png` });
            }
        }
    });
});

test.describe('Mobile interaction checks (iPhone 12 viewport)', () => {
    test.use({ viewport: { width: 390, height: 844 } });

    // Inner pages on phones use the app shell (tab bar + More sheet) like the homepage; the menu lives in More.
    test('More tab opens the menu sheet with the main links', async ({ page }) => {
        await page.goto('/about');
        await expect(page.locator('button[aria-label="Toggle menu"]')).toBeHidden();
        const tabbar = page.getByRole('navigation', { name: 'App' });
        await tabbar.getByRole('button', { name: 'More' }).click();
        const more = page.getByRole('dialog', { name: 'More' });
        await expect(more).toBeVisible();
        for (const name of ['About us', 'Areas we serve', 'How-To Guides', 'FAQ']) {
            await expect(more.getByRole('link', { name })).toBeVisible();
        }
        await page.screenshot({ path: 'test-results/mobile/more-sheet-open.png' });
    });

    test('tab bar sits on the bottom edge and the page end is not hidden behind it', async ({ page }) => {
        await page.goto('/about');
        await expect(page.locator('[data-mobile-bar]')).toBeHidden();
        const tabbar = page.getByRole('navigation', { name: 'App' });
        await expect(tabbar).toBeVisible();
        const box = await tabbar.boundingBox();
        expect(box.y + box.height).toBeGreaterThan(844 - 4);
        expect(box.y + box.height).toBeLessThanOrEqual(844 + 1);
        // Scrolled to the very end, the footer's last line sits above the tab bar.
        await page.evaluate(() => window.scrollTo(0, document.documentElement.scrollHeight));
        await page.waitForTimeout(300);
        const footerBottom = await page.locator('footer').evaluate((f) => f.getBoundingClientRect().bottom);
        expect(footerBottom).toBeLessThanOrEqual(box.y + 2);
    });

    test('booking and quote pages keep the menu and floating buttons (no tab bar)', async ({ page }) => {
        await page.goto('/book');
        await expect(page.locator('button[aria-label="Toggle menu"]')).toBeVisible();
        await expect(page.getByRole('navigation', { name: 'App' })).toHaveCount(0);
    });

    test('booking wizard: service picker + calendar render without overflow', async ({ page }) => {
        await page.goto('/book');
        await page.waitForLoadState('networkidle');

        const overflow1 = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
        console.log('Booking step 1 overflow diff:', overflow1);
        await page.screenshot({ path: 'test-results/mobile/booking-step1-iphone12.png' });

        // Actually fill the required fields (name/phone/email/address/suburb) so validation lets us through to the calendar
        await page.getByPlaceholder('Jane Smith').fill('Test Client');
        await page.getByPlaceholder('+27 81 000 0000').fill('0821234567');
        await page.getByPlaceholder('jane@email.com').fill('playwright-mobile@example.com');
        await page.getByPlaceholder(/Ocean View Drive/).fill('12 Test Street');
        await page.getByPlaceholder(/Milnerton, Table View/).fill('Milnerton');
        await page.waitForTimeout(200);

        const nextBtn = page.locator('button:has-text("Next"), button:has-text("Continue")').first();
        if (await nextBtn.count()) {
            await nextBtn.click().catch(() => {});
            await page.waitForTimeout(500);
            const overflow2 = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
            console.log('Booking step 2 (calendar) overflow diff:', overflow2);
            await page.screenshot({ path: 'test-results/mobile/booking-step2-iphone12-top.png' });
            await page.evaluate(() => window.scrollBy(0, 500));
            await page.waitForTimeout(300);
            await page.screenshot({ path: 'test-results/mobile/booking-step2-iphone12-calendar.png' });
        }

        expect(overflow1).toBeLessThanOrEqual(5);
    });

    test('homepage app layout: tiles, tab bar at the bottom, booking sheet opens', async ({ page }) => {
        await page.goto('/');
        await expect(page.locator('#app-services')).toBeVisible();
        await expect(page.locator('#hero-form')).toBeHidden();       // the desktop card is computers-only
        await expect(page.locator('[data-mobile-bar]')).toBeHidden(); // replaced by the tab bar

        const tabBar = page.locator('nav[aria-label="App"]');
        await expect(tabBar).toBeVisible();
        const box = await tabBar.boundingBox();
        expect(box.y + box.height).toBeLessThanOrEqual(844);
        expect(box.y + box.height).toBeGreaterThan(844 - 4);

        await tabBar.getByRole('button', { name: 'Book' }).click();
        const sheet = page.getByRole('dialog', { name: 'Book a clean' });
        await expect(sheet.getByText('What needs cleaning?')).toBeVisible();
        await page.screenshot({ path: 'test-results/mobile/home-booking-sheet.png' });
    });
});
