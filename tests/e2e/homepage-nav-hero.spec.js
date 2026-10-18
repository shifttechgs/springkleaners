import { test, expect } from '@playwright/test';

test.describe('Homepage navbar + hero layout', () => {
    test.use({ viewport: { width: 1440, height: 800 } });

    test('desktop nav renders as a single row and its links work', async ({ page }) => {
        await page.goto('/');

        const navRow = page.locator('#mainNav .nav-bar > div').first();
        const rowBox = await navRow.boundingBox();
        // A wrapped/broken nav renders far taller than one 80px row.
        expect(rowBox.height).toBeLessThan(96);

        const faqLink = page.locator('#mainNav a', { hasText: 'FAQ' }).first();
        await expect(faqLink).toBeVisible();
        await faqLink.click();
        await expect(page).toHaveURL(/\/faq$/);
    });

    test('hero quote form fits on a common laptop screen without scrolling', async ({ page }) => {
        await page.goto('/');

        const box = await page.locator('#hero-form').boundingBox();
        const viewport = page.viewportSize();

        expect(box.y).toBeGreaterThanOrEqual(80); // clear of the navbar
        expect(box.y + box.height).toBeLessThanOrEqual(viewport.height);
    });
});
