<?php

namespace App\Http\Controllers;

use App\Models\AkutansiTingkatEmpat as ModelsAkutansiTingkatEmpat;
use Illuminate\Http\Request;

class AkutansiTingkatEmpat extends Controller
{
    public function index()
    {
        return response()->json([
            'status'    => 200,
            'message'   => 'list akutansi tingkat empat',
            'data'      => ModelsAkutansiTingkatEmpat::whereNull('is_deleted')->get(),
        ]);
    }

    public function create(Request $request)
    {
        //validasi
        $request->validate([
            'tingkat_tiga_id'   => 'required',
            'name'              => 'required|string'
        ]);
        $name                = $request->name;
        $tingkat_tiga_id     = $request->tingkat_tiga_id;

        $data = [
            'tingkat_tiga_id'   => $tingkat_tiga_id,
            'name'              => $name
        ];

        $save = ModelsAkutansiTingkatEmpat::create($data);
        if($save) {
            return response()->json([
                'status'    => 200,
                'message'   => 'Success create data',
                'data'      => [
                    'name'           => $name,
                    'tingkat_tiga'   => $tingkat_tiga_id,
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
            'message'   => 'list akutansi tingkat empat',
            'data'      => ModelsAkutansiTingkatEmpat::find($id)
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
            'tingkat_tiga_id'    => 'required'
        ]);
        $name               =  $request->name;
        $tingkat_tiga_id     = $request->tingkat_tiga_id;
        
        $data = [
            'name'              => $name,
            'tingkat_tiga_id'    => $tingkat_tiga_id,
            'updated_at'        => date('Y-m-d H:i:s')
        ];
       
        $akn = ModelsAkutansiTingkatEmpat::find($id);
        $update = $akn->update($data);
        if($update) {
            return response()->json([
                'status'    => 200,
                'message'   => 'Success update data',
                'data'      => [
                    'name'              => $name,
                    'tingkat_tiga_id'    => $tingkat_tiga_id
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
        $akn = ModelsAkutansiTingkatEmpat::find($id);
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
