<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registrationData = $request->all();
        $validate = Validator::make($registrationData,[
            'name'=>'required|max:60',
            'email'=>'required|email:rfc,dns',
            'nomorIdentitas' => 'required|max:10',
            'username' => 'required|max:20',
            'password'=>'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
        $registrationData['password'] = bcrypt($request->password);
        $user = User::create($registrationData);
        $user->sendApiEmailVerificationNotification();
        return response([
            'message' => 'Register Succes',
            'user' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        if(!Auth::attempt($loginData))
            return response(['message' => 'Invalid Credentials'], 401);

        $user = Auth::user();
        if ($user->email_verified_at == NULL) {
            return response([
                'message' => 'Please Verify Your Email'
            ], 401); //Return error jika belum verifikasi email
        }
        $token = $user->createToken('Authentication Token')->accessToken;

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);
    }
}