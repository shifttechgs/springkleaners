import { test, expect } from '@playwright/test';

test.describe('Homepage navbar + hero layout', () => {
    test.use({ viewport: { width: 1440, height: 800 } });

    test('desktop nav renders as a single row and Blog link is clickable', async ({ page }) => {
        await page.goto('/');

        const navRow = page.locator('#mainNav .h-16');
        const rowBox = await navRow.boundingBox();
        // A wrapped/broken nav renders far taller than one 64px row.
        expect(rowBox.height).toBeLessThan(80);

        const blogLink = page.locator('#mainNav a', { hasText: 'Blog' }).first();
        await expect(blogLink).toBeVisible();
        await blogLink.click();
        await expect(page).toHaveURL(/\/blog$/);
    });

    test('hero stats bar is visible without scrolling on a common laptop viewport', async ({ page }) => {
        await page.goto('/');

        const statsBar = page.locator('#hero-form .mt-4').first();
        const box = await statsBar.boundingBox();
        const viewport = page.viewportSize();

        expect(box.y + box.height).toBeLessThanOrEqual(viewport.height);
    });
});
