<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
class LoggedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(session('user_id')) {
            $userId = session('user_id');
            $user = User::find($userId);
            if($user){
                return $next($request);
            }
        }
        return response()->redirectTo('/auth');
    }
}
