<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Hash;
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
            'email' => 'required|email',
            'password' => 'required'
        ],
        [
            'email.email' => 'Kesalahan format input email',
        ]);

        if(is_null($request->email) || is_null($request->password)){
            return response(['message' => 'Inputan tidak boleh kosong'], 400);
        }

        $customer = null;
        $driver = null;
        $pegawai = null;

        //get token with random string//
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        if(Customer::where('email_customer','=',$loginData['email'])->first())
        {
            $loginCustomer = Customer::where('email_customer','=',$loginData['email'])->first();

            if(Hash::check($loginData['password'], $loginCustomer['password'])){
                $customer = Customer::where('email_customer',$loginData['email'])->first();
            }
            else{
                return response([
                    'message' => 'email atau password salah',
                    'data' => $customer
                ], 404);
            }
            $token = bcrypt($randomString);
            return response([
                'message' => 'berhasil login sebagai customer',
                'data' => $customer, 
                'token'=> $token
            ]);
        }
        else if(Driver::where('email_driver','=',$loginData['email'])->first())
        {
            $loginDriver = Driver::where('email_driver','=',$loginData['email'])->first();

            if(Hash::check($loginData['password'], $loginDriver['password'])){
                $driver = Driver::where('email_driver',$loginData['email'])->first();
            }
            else{
                return response([
                    'message' => 'email atau password salah',
                ]);
            }
            $token = bcrypt($randomString);
            return response([
                'message' => 'berhasil login sebagai driver',
                'data' => $driver,
                'token' => $token
            ]);
        }
        else if(Pegawai::where('email','=',$loginData['email'])->first())
        {
            $loginPegawai = Pegawai::where('email','=',$loginData['email'])->first();

            if(Hash::check($loginData['password'], $loginPegawai['password'])){
                $pegawai = Pegawai::where('email',$loginData['email'])->first();
            }
            else{
                return response([
                    'message' => 'email atau password salah',
                    'data' => $pegawai
                ], 400);
            }
            $token = bcrypt($randomString);
            return response([
                'message' => 'berhasil login sebagai pegawai',
                'data' => $pegawai,
                'token' => $token
            ]);
        }

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
    }
}