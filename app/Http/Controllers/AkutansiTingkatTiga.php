<?php

namespace App\Http\Controllers;

use App\Models\AkutansiTingkatTiga as ModelsAkutansiTingkatTiga;
use Illuminate\Http\Request;

class AkutansiTingkatTiga extends Controller
{
    public function index()
    {
        return response()->json([
            'status'    => 200,
            'message'   => 'list akutansi tingkat tiga',
            'data'      => ModelsAkutansiTingkatTiga::whereNull('is_deleted')->get(),
        ]);
    }

    public function create(Request $request)
    {
        //validasi
        $request->validate([
            'tingkat_dua_id'   => 'required',
            'name'              => 'required|string'
        ]);
        $name               = $request->name;
        $tingkat_dua_id     = $request->tingkat_dua_id;

        $data = [
            'tingkat_dua_id'   => $tingkat_dua_id,
            'name'              => $name
        ];

        $save = ModelsAkutansiTingkatTiga::create($data);
        if($save) {
            return response()->json([
                'status'    => 200,
                'message'   => 'Success create data',
                'data'      => [
                    'name'          => $name,
                    'tingkat_dua'   => $tingkat_dua_id,
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
            'message'   => 'list akutansi tingkat tiga',
            'data'      => ModelsAkutansiTingkatTiga::find($id)
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
            'name'              => 'required|string',
            'tingkat_dua_id'    => 'required'
        ]);
        $name               = $request->name;
        $tingkat_dua_id    = $request->tingkat_dua_id;
        
        $data = [
            'name'              => $name,
            'tingkat_dua_id'    => $tingkat_dua_id,
            'updated_at'        => date('Y-m-d H:i:s')
        ];
       
        $akn = ModelsAkutansiTingkatTiga::find($id);
        $update = $akn->update($data);
        if($update) {
            return response()->json([
                'status'    => 200,
                'message'   => 'Success update data',
                'data'      => [
                    'name'              => $name,
                    'tingkat_dua_id'    => $tingkat_dua_id
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
        $akn = ModelsAkutansiTingkatTiga::find($id);
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
