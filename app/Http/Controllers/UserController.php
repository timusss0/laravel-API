<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $data = User::orderBy('name','asc')->get();
        return response()->json([
            'status' => true,
            'message' => 'Data ditemukan',
            'data' => $data
        ],200);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'age' => 'required|integer|min:0'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Log::error('Validation failed', $validator->errors()->toArray());
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }
        

        $dataUser = new User;
        $dataUser->name = $request->name;
        $dataUser->email = $request->email;
        $dataUser->age = $request->age;

        $post = $dataUser->save();

        return response()->json([
            'status' => true,
            'message' => 'Data User berhasil disimpan',
            'data' => $dataUser
        ],201);
    }


    public function show(string $id)
    {
        $data=User::find($id);      
        if($data){
            return response()->json([
                'status'=>true,
                'message'=>'Data ditemukan',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>'Data tidak ditemukan',
                'data'=>null
            ],404);
        }
    }

    public function update(Request $request, string $id)
    {

        $dataUser = User::find($id);
        if(empty($dataUser)){
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ],404);
        }
        $rules = [
            'name' => 'required|string|max:255',
            'email' =>'required|string|email|max:255|unique:users,email',
            'age' => 'required|integer|min:0'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ],400);
        }

    $dataUser->name = $request->name;
    $dataUser->email = $request->email;
    $dataUser->age = $request->age;

    $dataUser->save(); 

    return response()->json([
        'status' => true,
        'message' => 'Sukses berhasil update data',
        'data' => $dataUser
    ], 200);
}

}