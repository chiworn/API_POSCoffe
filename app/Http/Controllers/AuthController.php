<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(Request $req){
        $name = $req->name;
        $email = $req->email;
        $password = $req->password;

        $password = Hash::make($password);

        try{
            DB::table('users')->insert([
                "name" => $name,
                "email" => $email,
                "password" => $password


            ]);

            return response([
                'message'=> 'Register Success',
            ],status:201);
        }catch(Exception $e){
            return response([
                'Message' => 'invalid '
            ],status:401);
        }

    }
    public function login(Request $req){
        $credentail = $req->only('email','password');

        if(!(Auth::attempt($credentail))){
            return response(['Message' => 'invaild email or password'],status:401);
        }
        $user = Auth::user();
        $token = $user->createToken('Token')->plainTextToken;
        $cookie = cookie('JWT',$token,60*24);

        return response([
            'Message' => 'login sucess',
            'Message' => 'Login success',
            'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'token' => $token
        ]
        ],status:201)->withCookie($cookie);

    }
    public function logout(){
        return response()->json([
            'message' => 'loggout '
        ])->withCookie(Cookie::forget('JWT'));
    }
}
