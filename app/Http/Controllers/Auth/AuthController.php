<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __invoke(AuthRequest $request)
    {
        $data = $request->validated();
        $email = '';
        if(isset($data['email'])) {
            $email = $data['email'];
        }

        $password = '';
        if(isset($data['password'])) {
            $password = $data['password'];
        }

        if($email !== '' && $password !== '') {
            $credentials = [
                'email' => $email,
                'password' => $password
            ];


            if(Auth::attempt($credentials)) {
                $user = Auth::getUser();
                session(['user_id' => $user->id]);
                return response()->redirectTo('/');
            }
        }

        return redirect('/auth?hasError=true');
    }
}
