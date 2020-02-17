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


    /**
    * Function register new user
    * @return json
    */
    public function register(Request $request) {
        // validate request data
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|between:6,25|confirmed',
            'password_confirmation' => 'same:password',
        ]);

        // create new user
        $user = new User($request->all());
        $user->password = bcrypt($request->password);
        $user->save();

        // return success
        return response()->json([
            'success' => true
        ]);
    }


    /**
    * Function sign in user
    * @return json
    */
    public function login(Request $request) {
        // validate request data
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|between:6,25'
        ]);
        
        // search user by email
        $user = User::whereEmail($request->email)->first();
        
        // check password
        if($user && Hash::check($request->password, $user->password)) {
            
            // create api_token 
            $user->api_token = Str::random(60);
            $user->save();

            // return success
            return response()->json([
                'success' => true,
                'user' => $user->info()
            ]);
        }

        // return error
        return response()->json([
            'errors' => [
                'message' => 'Your email or password were incorrect.',
            ]
        ], 422);
    }


    /**
    * Function sign out user
    * @return json
    */
    public function logout(Request $request) {

        // remove api_token
        $user = $request->user();
        $user->api_token = null;
        $user->save();

        // return success
        return response()->json([
            'success' => true
        ]);
    }

}