<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class DeployController extends Controller
{
    /**
     * Run pending migrations and clear caches after a deploy.
     *
     * Exists for hosting environments with no SSH access, where a GitHub Actions
     * workflow can upload the built app over FTP/SFTP but has no shell to run
     * `artisan` commands directly. Guarded by a long random token (DEPLOY_TOKEN)
     * compared in constant time — not by CSRF, since this is called by CI, not a browser.
     */
    public function migrate(Request $request)
    {
        $expected = (string) config('app.deploy_token');
        $given = (string) $request->header('X-Deploy-Token', '');

        if ($expected === '' || ! hash_equals($expected, $given)) {
            Log::warning('Deploy endpoint hit with an invalid or missing token.', ['ip' => $request->ip()]);
            abort(403);
        }

        Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = Artisan::output();

        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');

        Log::info('Deploy endpoint ran migrations and cleared caches.', ['ip' => $request->ip()]);

        return response("Migrated and caches cleared.\n\n{$migrateOutput}")
            ->header('Content-Type', 'text/plain');
    }
}
