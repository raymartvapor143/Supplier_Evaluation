<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
public function handle(Request $request, Closure $next, ...$roles): Response
{
    if (!auth()->check()) {
        return redirect()->route('auth.login');
    }

    /** @var User $user */
    $user = auth()->user();

    if (!$user instanceof User) {
        abort(403, 'Unauthorized');
    }

    // ✅ Handle account status FIRST
    switch ($user->status) {
        case 'freeze':
            auth()->logout(); // فور safety: kick user out
            return redirect()->route('auth.login')
                ->with('error', 'Your account has been temporarily suspended.');

        case 'inactive':
            auth()->logout();
            return redirect()->route('auth.login')
                ->with('error', 'Your account is pending approval.');

        case 'rejected':
            auth()->logout();
            return redirect()->route('auth.login')
                ->with('error', 'Your account has been rejected.');

        case 'active':
            // continue
            break;

        default:
            auth()->logout();
            abort(403, 'Invalid account status.');
    }

    // ✅ Role check AFTER status is valid
    if (!empty($roles) && !in_array($user->role, $roles)) {
        abort(403, 'Unauthorized');
    }

    return $next($request);
}
}
