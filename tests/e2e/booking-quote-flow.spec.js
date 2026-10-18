import { test, expect } from '@playwright/test';

// Full user journey: public booking wizard -> admin quote pricing -> client acceptance.
// Admin credentials: E2E_ADMIN_EMAIL / E2E_ADMIN_PASSWORD if set, otherwise the account from
// `php artisan db:seed --class=AdminUserSeeder`.
const ADMIN_EMAIL = process.env.E2E_ADMIN_EMAIL || 'shifttechgs1@gmail.com';
const ADMIN_PASSWORD = process.env.E2E_ADMIN_PASSWORD || 'cq2m-QJR0989N/Ki';

const clientName = `Playwright E2E ${Date.now()}`;

test.describe('Booking to accepted quote', () => {
    test.setTimeout(120_000);

    test('client books, admin quotes it, client accepts', async ({ page, browser }) => {
        // ── Step 1: Details ──
        await page.goto('/book?service=deep-cleaning');
        await expect(page.getByRole('heading', { name: 'Your details' })).toBeVisible();

        await page.getByRole('button', { name: 'House', exact: true }).click();
        await page.locator('label:has-text("Bedrooms") + select').selectOption('3');
        await page.locator('label:has-text("Bathrooms") + select').selectOption('2');
        await page.getByRole('button', { name: '3–6 months ago' }).click();
        await page.getByRole('button', { name: 'Tile', exact: true }).click();
        await page.getByText('Do you have any pets?').locator('..').getByRole('button').click();
        await page.getByPlaceholder('Any areas to focus on or avoid...').fill('Playwright test booking — please ring doorbell twice.');

        await page.getByPlaceholder('Jane Smith').fill(clientName);
        await page.getByPlaceholder('+27 81 000 0000').fill('082 555 1234');
        await page.getByPlaceholder('jane@email.com').fill('playwright-test@example.com');
        await page.getByPlaceholder('e.g. 12 Ocean View Drive').fill('99 Automation Avenue');

        const suburbInput = page.getByPlaceholder('e.g. Milnerton, Table View...');
        await suburbInput.fill('Milnerton');
        await suburbInput.press('Enter');

        // Interior windows add-on
        await page.locator('.addon-card').filter({ hasText: 'Interior windows' }).click();

        await page.locator('div[x-show="step === 1"]').getByRole('button', { name: 'Continue' }).click();

        // ── Step 2: Schedule ──
        await expect(page.getByRole('heading', { name: 'Choose your date & time' })).toBeVisible();

        // Take the wizard's own "next available date" and the first free time, so repeat runs never
        // land on a slot or day that earlier runs already booked.
        await page.getByRole('button', { name: 'Select this date' }).click();
        await page.locator('.time-slot:not(.time-taken)').first().click();

        await page.locator('div[x-show="step === 2"]').getByRole('button', { name: 'Continue' }).click();

        // ── Step 3: Review & submit ──
        await expect(page.getByRole('heading', { name: 'Review your booking' })).toBeVisible();

        await page.getByRole('button', { name: 'Get My Instant Quote' }).click();
        await expect(page.getByRole('heading', { name: 'Booking request sent!' })).toBeVisible({ timeout: 15_000 });

        // ── Admin: log in ──
        await page.goto('/admin/login');
        await page.locator('input[name="email"]').fill(ADMIN_EMAIL);
        await page.locator('input[name="password"]').fill(ADMIN_PASSWORD);
        await page.getByRole('button', { name: 'Sign In' }).click();
        await expect(page.getByRole('heading', { name: 'Dashboard' })).toBeVisible();

        // ── Admin: find the booking and open it ──
        await page.goto('/admin/bookings');
        await page.getByPlaceholder('Search name or phone...').fill(clientName);
        await page.getByRole('button', { name: 'Filter' }).click();
        await page.getByRole('link', { name: 'View' }).click();
        await expect(page.getByRole('heading', { name: clientName, exact: true })).toBeVisible();

        // Confirm the full wizard payload actually persisted, not just the old 7 fields.
        // Note: the stored value uses a plain hyphen ("3-6 months ago") even though the
        // wizard button label renders with an en dash ("3–6 months ago").
        await expect(page.getByText('3-6 months ago')).toBeVisible();
        await expect(page.getByText('Playwright test booking')).toBeVisible();
        await expect(page.getByText('Interior windows')).toBeVisible();

        // ── Admin: send a quote ──
        const quotedPriceInput = page.locator('form[action*="send-quote"] input[type="number"][name="quoted_price"]');
        await quotedPriceInput.fill('1850');
        await page.getByRole('button', { name: 'Set Price & Send Quote' }).click();
        await expect(page.getByText('Quote generated')).toBeVisible();

        // The quote link is what the "Copy Link" button puts on the clipboard.
        const copyHandler = await page.getByRole('button', { name: 'Copy Link' }).getAttribute('@click');
        const quoteUrl = copyHandler.match(/writeText\('([^']+)'\)/)[1];
        expect(quoteUrl).toContain('/quote/');

        // ── Client: open the quote link in a fresh, unauthenticated context ──
        const clientContext = await browser.newContext();
        const clientPage = await clientContext.newPage();
        await clientPage.goto(quoteUrl);

        await expect(clientPage.getByRole('heading', { name: new RegExp(`Hi ${clientName}`) })).toBeVisible();
        // Appears twice by design: the header summary and the itemised total line.
        await expect(clientPage.getByText('R1,850.00').first()).toBeVisible();

        await clientPage.getByRole('button', { name: 'Accept Quote' }).click();
        await expect(clientPage.getByRole('heading', { name: 'Quote accepted!' })).toBeVisible();

        // Replay protection: a second visit should not show the Accept/Decline buttons again.
        await clientPage.goto(quoteUrl);
        await expect(clientPage.getByRole('heading', { name: 'Quote accepted!' })).toBeVisible();
        await expect(clientPage.getByRole('button', { name: 'Accept Quote' })).toHaveCount(0);

        await clientContext.close();

        // ── Admin: confirm the status flipped ──
        await page.reload();
        await expect(page.locator('select[name="status"]')).toHaveValue('accepted');
    });
});
