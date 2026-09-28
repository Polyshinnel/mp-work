<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrintLabelBasicAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $expectedUsername = (string) config('print_labels.basic_auth.username', '');
        $expectedPassword = (string) config('print_labels.basic_auth.password', '');
        $username = (string) $request->getUser();
        $password = (string) $request->getPassword();

        if ($expectedUsername === '' || $expectedPassword === '' ||
            !hash_equals($expectedUsername, $username) ||
            !hash_equals($expectedPassword, $password)) {
            return response()->json(['message' => 'Unauthorized'], 401, [
                'WWW-Authenticate' => 'Basic realm="Generated labels"',
            ]);
        }

        return $next($request);
    }
}
