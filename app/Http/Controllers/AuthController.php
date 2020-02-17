<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api')
            ->only('logout');
    }

    public function register(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|between:6,25|confirmed',
            'password_confirmation' => 'same:password',
        ]);

        $user = new User($request->all());
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'success' => true
        ]);
    }

    public function login(Request $request) {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|between:6,25'
        ]);

        $user = User::whereEmail($request->email)->first();

        if($user && Hash::check($request->password, $user->password)) {
            $user->api_token = Str::random(60);
            $user->save();

            return response()->json([
                'success' => true,
                'user' => $user->info()
            ]);
        }

        return response()->json([
            'errors' => [
                'message' => 'Your email or password were incorrect.',
            ]
        ], 422);
    }

    public function logout(Request $request) {
        $user = $request->user();
        $user->api_token = null;
        $user->save();

        return response()->json([
            'success' => true
        ]);
    }

}