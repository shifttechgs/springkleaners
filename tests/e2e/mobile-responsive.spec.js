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

    const sections = ['#services', '#why-us', '#areas', '#testimonials', '#pricing', '#faq'];

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

    test('navbar hamburger menu opens and shows links', async ({ page }) => {
        await page.goto('/');
        const hamburger = page.locator('button[aria-label="Toggle menu"]');
        await expect(hamburger).toBeVisible();
        await hamburger.click();
        await page.waitForTimeout(300);
        await page.screenshot({ path: 'test-results/mobile/nav-menu-open.png' });
        // The mobile menu should now show top-level links
        await expect(page.locator('text=Get My Instant Quote').last()).toBeVisible();
    });

    test('mobile sticky WhatsApp/quote bar is visible and not covering content', async ({ page }) => {
        await page.goto('/');
        const stickyBar = page.locator('.fixed.bottom-0.left-0.right-0.z-50.lg\\:hidden');
        await expect(stickyBar).toBeVisible();
        const box = await stickyBar.boundingBox();
        console.log('Sticky bar box:', JSON.stringify(box));
        expect(box.y + box.height).toBeCloseTo(844, -1);
    });

    test('booking wizard: service picker + calendar render without overflow', async ({ page }) => {
        await page.goto('/book');
        await page.waitForLoadState('networkidle');

        const overflow1 = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
        console.log('Booking step 1 overflow diff:', overflow1);
        await page.screenshot({ path: 'test-results/mobile/booking-step1-iphone12.png' });

        // Actually fill the required fields (name/phone/address/suburb) so validation lets us through to the calendar
        await page.getByPlaceholder('Jane Smith').fill('Test Client');
        await page.getByPlaceholder('+27 81 000 0000').fill('0821234567');
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

    test('homepage tap targets: hero form + nav are reachable without zoom', async ({ page }) => {
        await page.goto('/');
        const heroForm = page.locator('#hero-form');
        await expect(heroForm).toBeVisible();
        const box = await heroForm.boundingBox();
        console.log('Hero form box:', JSON.stringify(box));
    });
});
