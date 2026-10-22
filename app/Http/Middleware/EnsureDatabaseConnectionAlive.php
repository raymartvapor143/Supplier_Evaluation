<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class EnsureDatabaseConnectionAlive
{
    /**
     * Handle an incoming request.
     *
     * Ensures the database connection is alive before routing the request.
     * In persistent environments (Swoole, Octane, CLI workers, long-lived server processes),
     * idle connections can time out or be terminated by MySQL (SQLSTATE[HY000]: 2006 MySQL server has gone away).
     * This middleware checks connection health and automatically purges and reconnects if dropped.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->ensureConnectionAlive();

        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate(Request $request, Response $response): void
    {
        // When running under Swoole / persistent worker environments,
        // disconnect connections to prevent idle socket timeouts between requests
        if (
            extension_loaded('swoole') ||
            extension_loaded('openswoole') ||
            defined('SWOOLE_VERSION') ||
            isset($_SERVER['SWOOLE_CPU_NUM'])
        ) {
            try {
                DB::disconnect();
            } catch (Throwable $e) {
                // Ignore disconnect exceptions during termination
            }
        }
    }

    /**
     * Test database connection with a lightweight query and reconnect if connection is lost.
     */
    protected function ensureConnectionAlive(): void
    {
        try {
            // Check if connection is currently active by executing a lightweight SELECT 1
            DB::connection()->getPdo()->query('SELECT 1');
        } catch (Throwable $e) {
            try {
                DB::purge();
                DB::reconnect();
                DB::connection()->getPdo()->query('SELECT 1');
            } catch (Throwable $reconnectException) {
                Log::error('MySQL reconnect attempt failed: ' . $reconnectException->getMessage());
            }
        }
    }
}
