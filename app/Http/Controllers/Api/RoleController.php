<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Role;

class RoleController extends Controller
{
    //Method untuk menampilkan semua data role (READ)
    public function index(){
        $role = Role::all(); //Mengambil semua data role

        if(count($role) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $role
            ], 200);
        } //Return data semua role dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data role kosong
    }

    //Method untuk menampilkan 1 data role (SEARCH)
    public function show($id_role){
        $role = Role::find($id_role); //Mencari data role berdasarkan id

        if(!is_null($role)){
            return response([
                'message' => 'Retrieve role Success',
                'data' => $role
            ], 200);
        } //Return data semua role dalam bentuk JSON

        return response([
            'message' => 'role Not Found',
            'data' => null
        ], 400); //Return message data role kosong
    }

    //Method untuk menambah 1 data role baru (CREATE)
    public function store(Request $request){
        $storeData = $request->all(); //Mengambil semua input dari API Client
        $validate = Validator::make($storeData, [
            'nama_role' => 'required|unique:role|regex:/^[\pL\s\-]+$/u',
            'peranan' => 'required|regex:/^[\pL\s\-]+$/u'
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $Role = Role::create($storeData);

        return response([
            'message' => 'Add role Success',
            'data' => $Role
        ], 200); //Return message data role baru dalam bentuk JSON
    }

    //Method untuk menghapus 1 data role (DELETE)
    public function destroy($id_role){
        $role = role::find($id_role); //Mencari data role berdasarkan id

        if(is_null($role)){
            return response([
                'message' => 'Role Not Found',
                'date' => null
            ], 404);
        } //Return message saat data role tidak ditemukan

        if($role->delete()){
            return response([
                'message' => 'Delete Role Success',
                'data' => $role
            ], 200);
        } //Return message saat berhasil menghapus data role

        return response([
            'message' => 'Delete Role Failed',
            'data' => null,
        ], 400);
    }

    //Method untuk mengubah 1 data role (UPDATE)
    public function update(Request $request, $id_role){
        $Role = Role::find($id_role); //Mencari data role berdasarkan id

        if(is_null($Role)){
            return response([
                'message' => 'Role Not Found',
                'data' => null
            ], 404);
        } //Return message saat data role tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_role' => 'required|regex:/^[\pL\s\-]+$/u',
            'peranan' => 'required'
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $Role->nama_role = $updateData['nama_role']; 
        $Role->peranan = $updateData['peranan'];

        if($Role->save()){
            return response([
                'message' => 'Update role Success',
                'data' => $Role
            ], 200);
        } //Return data role yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update role Failed',
            'data' => null
        ], 400);
    }
}
