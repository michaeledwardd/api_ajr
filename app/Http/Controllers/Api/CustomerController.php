<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerController extends Controller
{
    //Method untuk menampilkan semua data product (READ)
    public function index(){
        $customers = Customer::all(); //Mengambil semua data Customer

        if(count($customers) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $customers
            ], 200);
        } //Return data semua Customer dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data Customer kosong
    }

    //Method untuk menampilkan 1 data Customer (SEARCH)
    public function show($id_customer){
        $customers = Customer::find($id_customer); //Mencari data Customer berdasarkan id

        if(!is_null($customers)){
            return response([
                'message' => 'Retrieve Customer Success',
                'data' => $customers
            ], 200);
        } //Return data semua Customer dalam bentuk JSON

        return response([
            'message' => 'Customer Not Found',
            'data' => null
        ], 400); //Return message data Customer kosong
    }

    //Method untuk menambah 1 data Customer baru (CREATE)
    public function store(Request $request){
        $storeData = $request->all(); //Mengambil semua input dari API Client
        $validate = Validator::make($storeData, [
            'nama_customer' => 'required|regex:/^[\pL\s\-]+$/u',
            'alamat_customer' => 'required',
            'tgl_lahir' => 'required|date_format:Y-m-d',
            'jenis_kelamin' => 'required|regex:/^[\pL\s\-]+$/u',
            'email_customer' => 'required|unique:Customer|email:rfc,dns',
            'no_telp' => 'required',
            'upload_berkas' => 'required|max:1024|mimes:jpg,png,jpeg|image',
            'status_berkas',
            'nomor_kartupengenal' => 'required|numeric',
            'no_sim' => 'numeric',
            'asal_customer' => 'required',
            'password',
            'usia_customer' => 'required|numeric'
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $count = DB::table('customer')->count() +1;
        $id_generate = sprintf("%03d", $count);
        $datenow = Carbon::now()->format('ymd');
        if($request->upload_berkas == null){
            $request->status_berkas = sprintf("not-verified");
        }

        $uploadBerkas = $request->upload_berkas->store('img_ktp',['disk'=>'public']);

        $Customer = Customer::create([
            'id_customer'=>'CUS'.$datenow.'-'.$id_generate,
            'nama_customer'=>$request->nama_customer,
            'alamat_customer'=>$request->alamat_customer,
            'tgl_lahir'=>$request->tgl_lahir,
            'jenis_kelamin'=>$request->jenis_kelamin,
            'email_customer'=>$request->email_customer,
            'no_telp'=>$request->no_telp,
            'upload_berkas'=>$uploadBerkas,
            'status_berkas'=>$request->status_berkas,
            'nomor_kartupengenal'=>$request->nomor_kartupengenal,
            'no_sim'=>$request->no_sim,
            'asal_customer'=>$request->asal_customer,
            'password'=>bcrypt($request->tgl_lahir),
            'usia_customer'=>$request->usia_customer,
        ]);

        return response([
            'message' => 'Add Customer Success',
            'data' => $Customer
        ], 200); //Return message data Customer baru dalam bentuk JSON
    }

    //Method untuk menghapus 1 data product (DELETE)
    public function destroy($id_customer){
        $Customer = Customer::find($id_customer); //Mencari data product berdasarkan id

        if(is_null($Customer)){
            return response([
                'message' => 'Customer Not Found',
                'date' => null
            ], 404);
        } //Return message saat data Customer tidak ditemukan

        if($Customer->delete()){
            return response([
                'message' => 'Delete Customer Success',
                'data' => $Customer
            ], 200);
        } //Return message saat berhasil menghapus data Customer

        return response([
            'message' => 'Delete Customer Failed',
            'data' => null,
        ], 400);
    }

    //Method untuk mengubah 1 data Customer (UPDATE)
    public function update(Request $request, $id_customer){
        $Customer = Customer::find($id_customer); //Mencari data Customer berdasarkan id

        if(is_null($Customer)){
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ], 404);
        } //Return message saat data Customer tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_customer' => 'required|regex:/^[\pL\s\-]+$/u',
            'alamat_customer'=> 'required',
            'tgl_lahir' => 'required|date_format:Y-m-d',
            'jenis_kelamin' => 'required|regex:/^[\pL\s\-]+$/u',
            'email_customer' => 'required|email:rfc,dns',
            'no_telp' => 'required',
            'upload_berkas' => 'required|max:1024|mimes:jpg,png,jpeg|image',
            'status_berkas' => 'required',
            'nomor_kartupengenal' => 'required|numeric',
            'no_sim' => 'numeric',
            'asal_customer' => 'required',
            'password',
            'usia_customer' => 'required|numeric'
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $Customer->nama_customer = $updateData['nama_customer']; 
        $Customer->alamat_customer = $updateData['alamat_customer'];
        $Customer->tgl_lahir = $updateData['tgl_lahir']; 
        $Customer->jenis_kelamin = $updateData['jenis_kelamin'];
        $Customer->email_customer = $updateData['email_customer'];
        $Customer->no_telp = $updateData['no_telp'];
        if(isset($request->upload_berkas))
        {
            $uploadBerkas = $request->upload_berkas->store('img_ktp',['disk'=>'public']);
            $Customer->upload_berkas = $uploadBerkas;
        }
        $Customer->status_berkas = $updateData['status_berkas'];
        $Customer->nomor_kartupengenal = $updateData['nomor_kartupengenal'];
        $Customer->no_sim = $updateData['no_sim'];
        $Customer->asal_customer = $updateData['asal_customer'];
        $Customer->password = bcrypt($updateData['password']);
        $Customer->usia_customer = $updateData['usia_customer'];

        if($Customer->save()){
            return response([
                'message' => 'Update Customer Success',
                'data' => $Customer
            ], 200);
        } //Return data Customer yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update Customer Failed',
            'data' => null
        ], 400);
    }
}
