import { test, expect } from '@playwright/test';
import { execFileSync } from 'node:child_process';

// Every public page (the same set sitemap.xml lists, plus the booking flow and admin sign-in) at common phone
// widths and a tablet width. Customers mostly arrive on their phones, so nothing may push the page sideways
// or be cut off at the screen edge. One test per page, so a failure names the page.

const widths = [360, 390, 768];

// Read from the same config files the sitemap uses (the test list is built before the dev server starts).
const paths = JSON.parse(execFileSync('php', ['-r', `
    $blog = require 'config/blog.php';
    $services = require 'config/service_pages.php';
    $areas = require 'config/locations.php';
    $paths = ['/', '/book', '/book/deep-cleaning', '/about', '/privacy-policy', '/terms-of-service', '/blog', '/faq', '/areas',
              '/admin/login', '/admin/forgot-password'];
    foreach ($blog['posts'] as $post) $paths[] = '/blog/' . $post['slug'];
    foreach (array_keys($services) as $slug) $paths[] = '/services/' . $slug;
    foreach (array_keys($areas) as $slug) $paths[] = '/areas/' . $slug;
    echo json_encode($paths);
`], { encoding: 'utf8' }));

// Scroll the whole page so lazy images and scroll-reveal content are laid out before measuring.
async function settle(page) {
    await page.evaluate(async () => {
        for (let y = 0; y < document.body.scrollHeight; y += 800) {
            window.scrollTo(0, y);
            await new Promise((r) => setTimeout(r, 30));
        }
        window.scrollTo(0, 0);
    });
    await page.waitForTimeout(200);
}

// Visible text and controls that stick out past the screen edge. Decorative (aria-hidden) elements and
// content inside a horizontal scroller (which the visitor can swipe) are allowed.
function cutOffElements(width) {
    const decorative = (e) => e.closest('[aria-hidden="true"]');
    const inScroller = (e) => {
        for (let a = e.parentElement; a && a !== document.body; a = a.parentElement) {
            const o = getComputedStyle(a).overflowX;
            if (o === 'auto' || o === 'scroll') return true;
        }
        return false;
    };
    const shown = (e) => {
        for (let a = e; a; a = a.parentElement) {
            const s = getComputedStyle(a);
            if (s.display === 'none' || s.visibility === 'hidden' || s.opacity === '0') return false;
        }
        return true;
    };
    return [...document.querySelectorAll('h1,h2,h3,h4,p,a,button,label,input,select,textarea,li,td,th,img')]
        .filter((e) => {
            const r = e.getBoundingClientRect();
            if (r.width < 2 || r.height < 2) return false;
            if (r.right <= width + 1 && r.left >= -1) return false;
            if (getComputedStyle(e).position === 'fixed') return false;
            return !decorative(e) && !inScroller(e) && shown(e);
        })
        .slice(0, 5)
        .map((e) => `${e.tagName.toLowerCase()} "${(e.textContent || e.alt || '').trim().slice(0, 40)}"`);
}

test.describe('Every public page fits phone and tablet screens', () => {
    for (const path of paths) {
        test(path, async ({ browser }) => {
            const failures = [];
            for (const width of widths) {
                const context = await browser.newContext({ viewport: { width, height: 800 }, isMobile: width < 768, hasTouch: width < 768 });
                const page = await context.newPage();
                const res = await page.goto(path, { waitUntil: 'load' });
                await settle(page);
                const overflow = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
                const cut = await page.evaluate(cutOffElements, width);
                if (res.status() >= 400) failures.push(`${width}px: HTTP ${res.status()}`);
                if (overflow > 1) failures.push(`${width}px: page is ${overflow}px wider than the screen`);
                if (cut.length) failures.push(`${width}px: cut off at the edge: ${cut.join(', ')}`);
                await context.close();
            }
            expect(failures, failures.join('\n')).toEqual([]);
        });
    }
});

test('hero slide controls fit on small phones for every slide label', async ({ browser }) => {
    for (const width of [320, 360, 390, 400, 414]) {
        const context = await browser.newContext({ viewport: { width, height: 800 }, isMobile: true, hasTouch: true });
        const page = await context.newPage();
        await page.goto('/', { waitUntil: 'load' });
        for (const label of ['Deep Cleaning', 'Move-in Ready', 'Post Construction']) {
            const right = await page.evaluate((text) => {
                document.querySelector('[data-hero-label]').textContent = text;
                return document.querySelector('[data-hero-pause]').getBoundingClientRect().right;
            }, label);
            expect(right, `pause button cut off at ${width}px with "${label}"`).toBeLessThanOrEqual(width);
        }
        await context.close();
    }
});
