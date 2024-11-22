<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __invoke(Request $request) {
        $hasErr = false;
        if($request->query('hasError')) {
            $hasErr = true;
        }
        return view('auth', ['hasErr' => $hasErr]);
    }
}
