<?php

namespace App\Http\Controllers;

use App\Models\AkutansiTingkatEnam as ModelsAkutansiTingkatEnam;
use Illuminate\Http\Request;

class AkutansiTingkatEnam extends Controller
{
    public function index()
    {
        return response()->json([
            'status'    => 200,
            'message'   => 'list akutansi tingkat enam',
            'data'      => ModelsAkutansiTingkatEnam::whereNull('is_deleted')->get(),
        ]);
    }

    public function create(Request $request)
    {
        //validasi
        $request->validate([
            'tingkat_lima_id'    => 'required',
            'name'               => 'required|string'
        ]);
        $name                 = $request->name;
        $tingkat_lima_id      = $request->tingkat_lima_id;

        $data = [
            'tingkat_lima_id'    => $tingkat_lima_id,
            'name'               => $name
        ];

        $save = ModelsAkutansiTingkatEnam::create($data);
        if($save) {
            return response()->json([
                'status'    => 200,
                'message'   => 'Success create data',
                'data'      => [
                    'name'            => $name,
                    'tingkat_lima'   => $tingkat_lima_id,
                ]
            ]);
        }

    }
    public function getById(Request $request)
    {
        $id = $request->id;
        if(!$id){
            return response()->json([
                'status'=> 404,
                'message'=> 'id tidak ditemukan'
            ]);
        }
        return response()->json([
            'status'    => 200,
            'message'   => 'list akutansi tingkat enam',
            'data'      => ModelsAkutansiTingkatEnam::find($id)
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        if(!$id){
            return response()->json([
                'status'=> 404,
                'message'=> 'id tidak ditemukan'
            ]);
        }
        $request->validate([
            'name'               => 'required|string',
            'tingkat_lima_id'    => 'required'
        ]);
        $name                 =  $request->name;
        $tingkat_lima_id     = $request->tingkat_lima_id;
        
        $data = [
            'name'              => $name,
            'tingkat_lima_id'  => $tingkat_lima_id,
            'updated_at'        => date('Y-m-d H:i:s')
        ];
       
        $akn = ModelsAkutansiTingkatEnam::find($id);
        $update = $akn->update($data);
        if($update) {
            return response()->json([
                'status'    => 200,
                'message'   => 'Success update data',
                'data'      => [
                    'name'                => $name,
                    'tingkat_lima_id'    => $tingkat_lima_id
                ]
            ]);
        }

    }

    public function delete(Request $request)
    {
        $id = $request->id;
        if(!$id){
            return response()->json([
                'status'=> 404,
                'message'=> 'id tidak ditemukan'
            ]);
        }
       
        $data = [
            'is_deleted'    => 1,
            'deleted_at'    => date('Y-m-d H:i:s')
        ];
        $akn = ModelsAkutansiTingkatEnam::find($id);
        $update = $akn->update($data);
        if($update) {
            return response()->json([
                'status'    => 200,
                'message'   => 'Success delete data',
                'data'      => [
                    'name' => $akn->name
                ]
            ]);
        }

    }
}
