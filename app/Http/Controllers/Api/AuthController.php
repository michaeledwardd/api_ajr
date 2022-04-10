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
    // public function register(Request $request)
    // {
    //     $registrationData = $request->all();
    //     $validate = Validator::make($registrationData,[
    //         'name'=>'required|max:60',
    //         'email'=>'required|email:rfc,dns',
    //         'nomorIdentitas' => 'required|max:10',
    //         'username' => 'required|max:20',
    //         'password'=>'required'
    //     ]);

    //     if($validate->fails())
    //         return response(['message' => $validate->errors()], 400);
        
    //     $registrationData['password'] = bcrypt($request->password);
    //     $user = User::create($registrationData);
    //     $user->sendApiEmailVerificationNotification();
    //     return response([
    //         'message' => 'Register Succes',
    //         'user' => $user
    //     ], 200);
    // }

    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]);

        if(Customer::where('email_customer','=',$loginData['email'])->first())
        {
            $loginCustomer = Customer::where('email_customer','=',$loginData['email'])->get();

            foreach($loginCustomer as $value){
                $temp = $value['password'];
            }
            if(Hash::check($loginData['password'], $temp)){
                $customer = Customer::where('password',$loginData['email'])->first();
            }
            return response([
                'message' => 'authenticated',
                'data' => $loginCustomer
            ]);
        }
        else if(Driver::where('email_driver','=',$loginData['email'])->first())
        {

        }
        else if(Pegawai::where('email','=',$loginData['email'])->first())
        {

        }

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
    }
}