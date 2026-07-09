<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                /** @var User $user */
                $user = Auth::guard($guard)->user();

                // Safety check
                if (!$user instanceof User) {
                    return redirect('/login');
                }

                // Redirect based on role
                if ($user->isAdmin()) {
                    return redirect()->route('admin.dashboard');
                }

                if ($user->isEndUser()) {
                    return redirect()->route('enduser.dashboard');
                }

                if ($user->isPresentativeStaff()) {
                    return redirect()->route('enduser.dashboard');
                }

                // fallback (if role not matched)
                return redirect('/login');
            }
        }

        return $next($request);
    }
}
