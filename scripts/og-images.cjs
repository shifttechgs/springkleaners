// Link-preview images (Open Graph) for WhatsApp, Facebook, LinkedIn, X and others: 1200x630 JPG cards made from
// our real team photos, the logo and the site fonts. Rendered in Chrome so the type matches the site.
//
//   npm run og-images
//
// Writes public/images/og/default.jpg (every page) and public/images/og/service-{slug}.jpg (each service page,
// using that page's own photo from config/service_pages.php). Run again after changing a service photo or label.
// Needs PHP on the PATH (to read the config) and Chrome installed.
const { chromium } = require('@playwright/test');
const { execFileSync } = require('node:child_process');
const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
// Files are served to the page from public/ through an internal address (Chrome won't load local files into a
// generated page, and fonts need a real origin).
const ORIGIN = 'http://og.local/';
const pub = (p) => ORIGIN + p;
const TYPES = { '.woff2': 'font/woff2', '.png': 'image/png', '.avif': 'image/avif', '.jpg': 'image/jpeg', '.webp': 'image/webp' };
const out = path.join(root, 'public', 'images', 'og');
fs.mkdirSync(out, { recursive: true });

const services = JSON.parse(execFileSync('php', ['-r', `
    $c = include ${JSON.stringify(path.join(root, 'config', 'service_pages.php'))};
    $o = [];
    foreach ($c as $slug => $s) { if (!empty($s['hero_image'])) $o[] = ['slug' => $slug, 'label' => $s['label'] ?? $slug, 'img' => $s['hero_image']]; }
    echo json_encode($o);
`]).toString());

const esc = (s) => String(s).replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));

// Flat navy panel + real photo, brand yellow as the only accent. No gradients or glows (brand rule).
function card({ img, eyebrow, title, titleSize }) {
    return `<!doctype html><html><head><meta charset="utf-8"><style>
        @font-face { font-family: Geist; src: url(${pub('fonts/geist-600.woff2')}) format('woff2'); font-weight: 600; }
        @font-face { font-family: Jakarta; src: url(${pub('fonts/plus-jakarta-sans-latin.woff2')}) format('woff2'); font-weight: 200 800; }
        * { margin: 0; box-sizing: border-box; }
        body { width: 1200px; height: 630px; display: grid; grid-template-columns: 560px 1fr; background: #081d3a; font-family: Jakarta, sans-serif; }
        .panel { display: flex; flex-direction: column; padding: 56px 56px 52px; color: #fff; }
        .logo { height: 86px; width: auto; align-self: flex-start; }
        .eyebrow { margin-top: auto; font-size: 22px; font-weight: 600; color: rgba(255,255,255,.82); display: flex; gap: 12px; align-items: center; }
        .eyebrow b { color: #f6e304; }
        h1 { margin-top: 18px; font-family: Geist, sans-serif; font-weight: 600; font-size: ${titleSize}px; line-height: 1.04; letter-spacing: -0.03em; }
        .rule { margin-top: 28px; width: 64px; height: 4px; background: #f6e304; }
        .foot { margin-top: 22px; font-size: 21px; color: rgba(255,255,255,.82); }
        .foot b { color: #fff; font-weight: 700; }
        .photo { width: 100%; height: 630px; object-fit: cover; display: block; }
    </style></head><body>
        <div class="panel">
            <img class="logo" src="${pub('images/logo-light.png')}">
            <p class="eyebrow">${eyebrow}</p>
            <h1>${title}</h1>
            <div class="rule"></div>
            <p class="foot">Instant price · weekend slots · <b>springkleaners.co.za</b></p>
        </div>
        <img class="photo" src="${pub(img.replace(/^\//, ''))}">
    </body></html>`;
}

(async () => {
    const browser = await chromium.launch({ channel: 'chrome' });
    const page = await browser.newPage({ viewport: { width: 1200, height: 630 } });
    let html = '';
    const publicDir = path.join(root, 'public');
    await page.route(ORIGIN + '**', (route) => {
        const rel = decodeURIComponent(new URL(route.request().url()).pathname).slice(1);
        if (rel === '') return route.fulfill({ contentType: 'text/html', body: html });
        const file = path.join(publicDir, rel);
        if (!file.startsWith(publicDir) || !fs.existsSync(file)) return route.fulfill({ status: 404, body: '' });
        return route.fulfill({ contentType: TYPES[path.extname(file)] || 'application/octet-stream', body: fs.readFileSync(file) });
    });
    const render = async (doc, file) => {
        html = doc;
        await page.goto(ORIGIN, { waitUntil: 'load' });
        await page.evaluate(() => document.fonts.ready);
        await page.screenshot({ path: path.join(out, file), type: 'jpeg', quality: 86 });
        console.log('wrote public/images/og/' + file);
    };

    await render(card({
        img: '/images/works/services/real-deep-cleaning.avif',
        eyebrow: '<b>★ 4.9</b> on Google · 500+ cleans done',
        title: "Cape Town's Northern Suburbs, cleaned properly.",
        titleSize: 58,
    }), 'default.jpg');

    for (const s of services) {
        await render(card({
            img: s.img,
            eyebrow: "Cape Town's Northern Suburbs",
            title: esc(s.label),
            titleSize: s.label.length > 24 ? 56 : 66,
        }), `service-${s.slug}.jpg`);
    }
    await browser.close();
})();
