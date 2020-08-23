<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterPost;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  RegisterPost  $data
     * @return User
     */
    public function create(RegisterPost $req)
    {
        $user = User::create([
            'name' => $req->input('data.name'),
            'email' => $req->input('data.email'),
            'password' => Hash::make($req->input('data.password')),
        ]);

        $user->sendEmailVerificationNotification();

        return response()->json([
            'data' => $user
        ]);
    }
}
