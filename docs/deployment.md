# Deployment — Hosting Pods (cPanel)

Replaces the Render deployment (see [`todo.md`](./todo.md) for why: auto-deploy silently stopped working there, and production drifted weeks out of date without anyone noticing). This uses GitHub Actions to build the app and push it over FTPS on every push to `main` — works whether or not the hosting plan turns out to have SSH access.

## How it works

1. Push to `main` → GitHub Actions checks out the code, runs `composer install --no-dev` and `npm run build`.
2. The built app is uploaded over FTPS to the server (`SamKirkland/FTP-Deploy-Action`) — only changed files are transferred.
3. A final step calls `POST /deploy/migrate` on the live site with a secret token, which runs `php artisan migrate --force` and clears config/view/cache/route caches. This exists **specifically because there's no SSH to run `artisan` directly** — if that changes later, swap this step for a direct SSH command and the endpoint can stay dormant (it 403s without the token, so it's safe to leave in place either way).

## One-time setup (I can't do this part — needs your Hosting Pods/cPanel access)

### 1. Point the domain at `public/`, not the app root

Laravel needs the webroot to be the `public/` folder, not the project root. In cPanel's **Domains** section, set `springkleaners.co.za`'s document root to `/home/<cpanel-user>/springkleaners/public` (upload the whole app to `/home/<cpanel-user>/springkleaners`, one level above the actual web-served folder). If Hosting Pods' interface doesn't allow a custom document root path, the fallback is symlinking `public_html` to that `public/` folder instead — check which your plan supports.

### 2. PHP version

Set PHP to **8.3** (or 8.2 minimum) in cPanel's **MultiPHP Manager** for the domain.

### 3. Database

Create a MySQL database + user in cPanel, matching what's already used locally (the DB was switched to MySQL specifically in prep for this move). Note the database name, username, and password — they go in the server's `.env` (step 5).

### 4. Required directories exist with correct permissions

The app needs these writable by PHP (usually already correct if uploaded via FTP as the cPanel user, but confirm):
```
storage/framework/cache
storage/framework/sessions
storage/framework/views
storage/logs
bootstrap/cache
```

### 5. Server `.env` file

**This is never uploaded by CI** (excluded from the FTP sync on purpose — CI must never overwrite the server's own secrets). Create `/home/<cpanel-user>/springkleaners/.env` manually once, based on `.env.example`, with real production values:

- `APP_ENV=production`, `APP_DEBUG=false`
- `APP_URL=https://springkleaners.co.za`
- `APP_KEY=` — generate with `php artisan key:generate --show` (run once locally or via cPanel's Terminal/Cron-triggered PHP if no SSH — ask if you need help generating this without shell access)
- `DB_*` — the database credentials from step 3
- `DEPLOY_TOKEN=` — a long random value, e.g. generate locally with `php artisan tinker --execute="echo Str::random(48);"`. **This same value also needs to be added as the `DEPLOY_TOKEN` GitHub Secret** (step 6) — they must match exactly.

### 6. GitHub Secrets

In the GitHub repo → **Settings → Secrets and variables → Actions**, add:

| Secret | Value |
|---|---|
| `FTP_HOST` | Hosting Pods FTP hostname (from cPanel → FTP Accounts) |
| `FTP_USERNAME` | FTP username |
| `FTP_PASSWORD` | FTP password |
| `FTP_REMOTE_DIR` | e.g. `/springkleaners/` — the app root, **not** `public_html` (see step 1) |
| `DEPLOY_URL` | `https://springkleaners.co.za/deploy/migrate` |
| `DEPLOY_TOKEN` | Same value as the server's `.env` `DEPLOY_TOKEN` (step 5) |

### 7. First deploy will be different from every deploy after it

The very first push needs the server's `.env`, `vendor/` (or let CI's `composer install` populate it — either works, but the app can't boot at all until `.env` + `APP_KEY` exist), and the database migrated once manually or via the `/deploy/migrate` endpoint after the first successful file upload. After that, every push to `main` is fully automatic.

## Verifying a deploy actually landed

Learned the hard way with Render — don't assume auto-deploy is working just because you didn't see an error. After pushing, check:

```
curl -I https://springkleaners.co.za/about   # should be 200, not 404
curl -s https://springkleaners.co.za/ | grep -c "cdn.tailwindcss.com"   # should be 0
```

If either looks wrong, check the GitHub Actions run (repo → **Actions** tab) for a red ❌ before assuming it's a hosting-side issue.

## If SSH turns out to be available after all

Switch the "Deploy over FTPS" step to an `rsync`-over-SSH or `appleboy/ssh-action` step, and change the final step to run `php artisan migrate --force && php artisan optimize:clear` directly over SSH instead of hitting `/deploy/migrate`. Simpler, and removes the need for the token-guarded endpoint entirely (though there's no harm leaving it in place unused).
