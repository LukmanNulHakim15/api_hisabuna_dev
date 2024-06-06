<?php

namespace App\Http\Controllers;

use App\Models\AkutansiTingkatDua as ModelsAkutansiTingkatDua;
use Illuminate\Http\Request;

class AkutansiTingkatDua extends Controller
{
    public function index()
    {
        return response()->json([
            'status'    => 200,
            'message'   => 'list akutansi tingkat dua',
            'data'      => ModelsAkutansiTingkatDua::whereNull('is_deleted')->get(),
        ]);
    }

    public function create(Request $request)
    {
        //validasi
        $request->validate([
            'tingkat_satu_id'   => 'required',
            'name'              => 'required|string'
        ]);
        $name = $request->name;
        $tingkat_satu_id    = $request->tingkat_satu_id;

        $data = [
            'tingkat_satu_id'   => $tingkat_satu_id,
            'name'              => $name
        ];

        $save = ModelsAkutansiTingkatDua::create($data);
        if($save) {
            return response()->json([
                'status'    => 200,
                'message'   => 'Success create data',
                'data'      => [
                    'name'          => $name,
                    'tingkat_satu'  => $tingkat_satu_id
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
            'message'   => 'list akutansi tingkat dua',
            'data'      => ModelsAkutansiTingkatDua::find($id)
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
            'tingkat_satu_id'   => 'required'
        ]);
        $name               = $request->name;
        $tingkat_satu_id    = $request->tingkat_satu_id;
        
        $data = [
            'name'              => $name,
            'tingkat_satu_id'   => $tingkat_satu_id,
            'updated_at'=> date('Y-m-d H:i:s')
        ];
        $akn = ModelsAkutansiTingkatDua::find($id);
        $update = $akn->update($data);
        if($update) {
            return response()->json([
                'status'    => 200,
                'message'   => 'Success update data',
                'data'      => [
                    'name' => $name
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
        $akn = ModelsAkutansiTingkatDua::find($id);
        $update = $akn->update($data);
        if($update) {
            return response()->json([
                'status'    => 200,
                'message'   => 'Success update data',
                'data'      => [
                    'name' => $akn->name
                ]
            ]);
        }

    }
}
