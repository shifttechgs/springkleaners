import { test, expect } from '@playwright/test';

// Real-browser checks for the 2026-11 local SEO pass: new Northern Suburbs can be booked, area/service
// context carries into /book, and the area pages' nearby links and FAQ behave.

const NEW_SUBURBS = ['Durbanville', 'Bellville', 'Parow', 'Goodwood', 'Brackenfell', 'Plattekloof'];
const INVALID = "We don't currently service this suburb";

// Sections below the fold use WOW scroll reveals (hidden until scrolled to), so scroll like a visitor would.
async function scrollThrough(page) {
    await page.evaluate(async () => {
        for (let y = 0; y < document.body.scrollHeight; y += 400) {
            window.scrollTo(0, y);
            await new Promise((r) => setTimeout(r, 60));
        }
    });
}

test.describe('Booking accepts the newly served suburbs', () => {
    for (const suburb of NEW_SUBURBS) {
        test(`${suburb} is accepted when typed`, async ({ page }) => {
            await page.goto('/book?service=deep-cleaning');
            const input = page.getByPlaceholder('e.g. Milnerton, Table View...');
            await input.fill(suburb);
            await input.press('Enter');
            await expect(page.getByText(INVALID)).toBeHidden();
            await expect(input).toHaveValue(suburb);
        });
    }

    test('it can also be picked from the suggestions', async ({ page }) => {
        await page.goto('/book');
        const input = page.getByPlaceholder('e.g. Milnerton, Table View...');
        await input.pressSequentially('Durban');
        await page.locator('[x-text="s"]', { hasText: 'Durbanville' }).click();
        await expect(input).toHaveValue('Durbanville');
        await input.blur();
        await expect(page.getByText(INVALID)).toBeHidden();
    });

    test('an unserved suburb is still rejected', async ({ page }) => {
        await page.goto('/book');
        const input = page.getByPlaceholder('e.g. Milnerton, Table View...');
        await input.fill('Stellenbosch');
        await input.press('Enter');
        await expect(page.getByText(INVALID)).toBeVisible();
    });
});

test.describe('New area pages', () => {
    for (const suburb of NEW_SUBURBS) {
        const slug = suburb.toLowerCase();

        test(`/areas/${slug} renders with its title, H1 and booking button`, async ({ page }) => {
            const res = await page.goto(`/areas/${slug}`);
            expect(res.status()).toBe(200);
            await expect(page).toHaveTitle(`Cleaning Services in ${suburb}, Cape Town | SpringKleaners`);
            await expect(page.getByRole('heading', { level: 1 })).toHaveText(`Cleaning Services in ${suburb}, Cape Town`);

            // First match is the nav-bar button; it should carry the suburb just like the hero one.
            await page.getByRole('link', { name: 'Get My Instant Quote' }).first().click();
            await expect(page).toHaveURL(new RegExp(`/book\\?suburb=${suburb}`));
            await expect(page.getByPlaceholder('e.g. Milnerton, Table View...')).toHaveValue(suburb);
        });
    }

    test('nearby links go to genuinely neighbouring suburbs', async ({ page }) => {
        await page.goto('/areas/plattekloof');
        await scrollThrough(page);
        const nearby = page.locator('section', { has: page.getByRole('heading', { name: 'Nearby areas we also serve' }) });
        for (const name of ['Parow', 'Edgemead', 'Bothasig', 'Monte Vista']) {
            await expect(nearby.getByRole('link', { name, exact: true })).toBeVisible();
        }
        await nearby.getByRole('link', { name: 'Parow', exact: true }).click();
        await expect(page).toHaveURL(/\/areas\/parow$/);
    });

    test('the "which services can I book online" FAQ opens', async ({ page }) => {
        await page.goto('/areas/bellville');
        await scrollThrough(page);
        await page.getByRole('button', { name: 'Which cleaning services can I book online in Bellville?' }).click();
        await expect(page.getByText('You can see your price and book these online:')).toBeVisible();
    });

    test('/areas groups suburbs into three regions and footer links the new pages', async ({ page }) => {
        await page.goto('/areas');
        await scrollThrough(page);
        for (const cluster of ['Northern Suburbs', 'Milnerton & Blaauwberg', 'Atlantic Seaboard']) {
            await expect(page.getByRole('heading', { name: cluster, exact: true })).toBeVisible();
        }
        const footer = page.locator('footer');
        for (const suburb of NEW_SUBURBS) {
            await expect(footer.getByRole('link', { name: suburb, exact: true })).toHaveAttribute('href', new RegExp(`/areas/${suburb.toLowerCase()}$`));
        }
    });
});

test.describe('Phone: the tab bar Book sheet keeps the page context', () => {
    test.use({ viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true });

    // What the booking sheet's flow has selected (the app sheet, components/app-booking), and whether it's on screen.
    const sheetState = (page) => page.evaluate(() => {
        const flow = document.querySelector('[data-booking-flow] [x-data^="quoteFlow"]');
        const d = window.Alpine.$data(flow);
        const r = document.querySelector('.app-sheet').getBoundingClientRect();
        return { suburb: d.selectedSuburb, service: d.serviceKey, onScreen: r.top > 0 && r.bottom <= window.innerHeight };
    });

    test('on an area page it opens with the suburb chosen', async ({ page }) => {
        await page.goto('/areas/goodwood');
        await page.getByRole('navigation', { name: 'App' }).getByRole('button', { name: 'Book' }).tap();
        await expect(page.getByRole('dialog', { name: 'Book a clean' })).toBeVisible();
        await page.waitForTimeout(500);
        const s = await sheetState(page);
        expect(s.onScreen).toBe(true);
        expect(s.suburb).toBe('Goodwood');
    });

    test('on a bookable service page it opens on that service', async ({ page }) => {
        await page.goto('/services/end-of-tenancy');
        await page.getByRole('navigation', { name: 'App' }).getByRole('button', { name: 'Book' }).tap();
        await expect(page.getByRole('dialog', { name: 'Book a clean' })).toBeVisible();
        await page.waitForTimeout(500);
        const s = await sheetState(page);
        expect(s.onScreen).toBe(true);
        expect(s.service).toBe('end-of-tenancy');
    });
});

test('pricing guide CTA opens booking on the matching service', async ({ page }) => {
    await page.goto('/blog/end-of-tenancy-cleaning-cost-cape-town');
    await scrollThrough(page);
    // The in-article CTA box: its heading paragraph, then the box (two levels up) holding the button.
    // The guide's one help box (components/blog/cta): its button opens booking on the guide's service.
    await page.locator('.guide-help').getByRole('link', { name: 'See your price' }).click();
    await expect(page).toHaveURL(/\/book\?service=end-of-tenancy/);
    await expect(page.getByRole('heading', { level: 1 })).toHaveText('End-of-Tenancy Cleaning');
});

test('legacy /book/{service} link redirects permanently', async ({ request }) => {
    const res = await request.get('/book/deep-cleaning', { maxRedirects: 0 });
    expect(res.status()).toBe(301);
    expect(res.headers()['location']).toContain('/book?service=deep-cleaning');
});
