<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $req)
    {
        $req->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        try {
            $user = User::create([
                'name'     => $req->name,
                'email'    => $req->email,
                'password' => Hash::make($req->password),
            ]);

            return response([
                'message' => 'Register Success',
            ], 201);
        } catch (Exception $e) {
            return response([
                'Message' => 'invalid registration'
            ], 401);
        }
    }

    public function login(Request $req)
    {
        $credentials = $req->only('email', 'password');

        if (!(Auth::attempt($credentials))) {
            return response(['Message' => 'invaild email or password'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('Token')->plainTextToken;
        $cookie = cookie('JWT', $token, 60 * 24);

        return response([
            'Message' => 'Login success',
            'data'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'token' => $token
            ]
        ], 201)->withCookie($cookie);
    }

    public function logout()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete();
        }
        
        return response()->json([
            'message' => 'loggout '
        ])->withCookie(Cookie::forget('JWT'));
    }
}
