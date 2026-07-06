<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Hosting Pods terminates TLS at a reverse proxy in front of the app
        // container and forwards plain HTTP internally — without trusting
        // its X-Forwarded-* headers, Laravel thinks every request is HTTP
        // and generates insecure (http://) absolute URLs, e.g. in form
        // actions like the admin login page.
        $middleware->trustProxies(at: '*', headers: Request::HEADER_X_FORWARDED_FOR
            | Request::HEADER_X_FORWARDED_HOST
            | Request::HEADER_X_FORWARDED_PORT
            | Request::HEADER_X_FORWARDED_PROTO
            | Request::HEADER_X_FORWARDED_AWS_ELB);

        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
        ]);
        // Called by CI (GitHub Actions), not a browser session — no CSRF token to send.
        // Protected instead by a constant-time secret-token check in DeployController.
        $middleware->validateCsrfTokens(except: [
            'deploy/migrate',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
