<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminLoginMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {

        if (!Auth::guard('admin')->check()) {
            return redirect()->route("admin.getlogin");
        } else {
            $user = Auth::guard('admin')->user();

            if ($user->roles == 0) {
                 Auth::guard('admin')->logout();
                return redirect()->route('site.home');
            }
        }
        return $next($request);
    }

}
