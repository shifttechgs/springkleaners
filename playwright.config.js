import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
    testDir: './tests/e2e',
    fullyParallel: false,
    workers: 1,
    reporter: [['list']],
    use: {
        baseURL: 'http://127.0.0.1:8123',
        trace: 'retain-on-failure',
        screenshot: 'only-on-failure',
        actionTimeout: 10_000,
    },
    projects: [
        {
            name: 'chromium',
            use: { ...devices['Desktop Chrome'] },
        },
    ],
    webServer: {
        command: 'php artisan serve --port=8123',
        url: 'http://127.0.0.1:8123',
        reuseExistingServer: true,
        timeout: 30_000,
    },
});
