<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('home');
    }

    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Define route constraints for different user types
            $adminHrRoutes = [
                'admin*',
                'users*',
                'hirings*',
                'shortlist',
                'getDates',
                'notifications/get',
                'markAsRead',
            ];

            $guestSelectionBoardRoutes = [
                'applicants*',
                'BeiPDF*',
                'upload*',
                'initial-interview*',
                'view-applications*',
                'getDates',
                'BEIUpdate',
                'UploadBEI',
            ];

            $userRoutes = [
                'apply-process*',
                'apply*',
                'cancel-application*',
                'shortlistApplicants*',
                'upload-requirement*',
                'store-requirement*',
                'profile*',
                'notifications/getUser',
                'markAsRead',
            ];

            // Check user type constraints
            if (in_array($user->usertype, ['admin', 'hr'])) {
                foreach ($adminHrRoutes as $pattern) {
                    if ($request->is($pattern)) {
                        return $next($request);
                    }
                }
            }

            if (in_array($user->usertype, ['guest', 'selection board', 'hr', 'admin'])) {
                foreach ($guestSelectionBoardRoutes as $pattern) {
                    if ($request->is($pattern)) {
                        return $next($request);
                    }
                }
            }

            if ($user->usertype === 'user') {
                foreach ($userRoutes as $pattern) {
                    if ($request->is($pattern)) {
                        return $next($request);
                    }
                }
            }

            // Redirect if the user doesn't have the correct usertype for the route
            return redirect()->route('home')->with('error', 'You do not have access to this page.');
        }

        // If the user is not authenticated, use the default redirection
        return parent::handle($request, $next, ...$guards);
    }
}
