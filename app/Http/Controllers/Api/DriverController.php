<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Driver;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'nama_driver'=> 'required|max:255',
            'alamat' => 'required'
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $count= DB::table('drivers')->count() +1;
        $driver = Driver::create([
            'id_driver'=>'DRI-'.$count,
            'nama_driver'=>$request->nama_driver,
            'alamat' => $request->alamat,
        ]);
     
        return response([
            'message' => 'Add driver Success',
            'data' => $driver,
        ],200);
    }
}
