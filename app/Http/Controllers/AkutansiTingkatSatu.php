<?php

namespace App\Http\Controllers;

use App\Models\AkutansiTingkatSatu as ModelsAkutansiTingkatSatu;
use Illuminate\Http\Request;

class AkutansiTingkatSatu extends Controller
{
    public function index()
    {
        return response()->json([
            'status'    => 200,
            'message'   => 'list akutansi tingkat satu',
            'data'      => ModelsAkutansiTingkatSatu::whereNull('is_deleted')->get(),
        ]);
    }

    public function create(Request $request)
    {
        //validasi
        $request->validate([
            'name'  => 'required|string'
        ]);
        $name = $request->name;
       
        $data = [
            'name'      => $name
        ];

        $save = ModelsAkutansiTingkatSatu::create($data);
        if($save) {
            return response()->json([
                'status'    => 200,
                'message'   => 'Success create data',
                'data'      => [
                    'name' => $name
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
            'message'   => 'list akutansi tingkat satu',
            'data'      => ModelsAkutansiTingkatSatu::find($id)
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
            'name'  => 'required|string'
        ]);
        $name = $request->name;
       
        $data = [
            'name'      => $name,
            'updated_at'=> date('Y-m-d H:i:s')
        ];
        $akn = ModelsAkutansiTingkatSatu::find($id);
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
        $akn = ModelsAkutansiTingkatSatu::find($id);
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
