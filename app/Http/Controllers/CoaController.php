<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CoaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'status' => '200',
            'message'=> 'List Coa',
            'data'   => Coa::whereNull('is_deleted')->get(),
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'nomor_akun'    => 'required|regex:/^[0-9\-]+$/',
            'nama_akun'     => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => '400',
                'message'=> 'Validation error',
                ]);
        }

        $nomor_akun     = $request->nomor_akun;
        $nomor_akun_tanpa_tanda_hubung = str_replace('-', '', $nomor_akun);
        $jumlah_nomor_akun = strlen($nomor_akun_tanpa_tanda_hubung);
        if($jumlah_nomor_akun == "6") {
            $level      = $jumlah_nomor_akun - 1;
        }else if($jumlah_nomor_akun > "6"){
            $level      = $jumlah_nomor_akun - 2;
        }else{
            $level      = $jumlah_nomor_akun + 0;
        }
        

        $nama_akun      = $request->nama_akun;
        if($level == "1") {
            $golongan = "Aset";
        }else if($level == "2") {
            $golongan = "Leabilitas";
        }else if($level == "3") {
            $golongan = "Ekuitas";
        }else if($level == "4") {
            $golongan = "Pendapatan";
        }else if($level == "5") {
            $golongan = "Beban";
        }else if($level == "6") {
            $golongan = "Beban Umum";
        }

        $saldo_normal   = "Debit";
        
        $data = [
            'nomor_akun'    => $nomor_akun,
            'nama_akun'     => $nama_akun,
            'level'         => $level,
            'saldo_normal'  => $saldo_normal,
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        $save = Coa::create($data);
        if($save) {
            $save->update(['updated_at' => null]);
            return response()->json([
                'status'    => 200,
                'message'   => 'Success create data',
            ]);
        }else{
            return response()->json([
                'status'    => 400,
                'message'   => 'Failed create data',
            ]);
        }


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coa $coa) : JsonResponse
    {
        $input = $request->all();
        
        // Validasi input
        $validator = Validator::make($input, [
            'nomor_akun' => 'required|regex:/^[0-9\-]+$/',
            'nama_akun'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 400,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 400);
        }

        $nomor_akun = $coa->nomor_akun = $input['nomor_akun'];
        $nomor_akun_tanpa_tanda_hubung = str_replace('-', '', $nomor_akun);
        $jumlah_nomor_akun = strlen($nomor_akun_tanpa_tanda_hubung);

        if ($jumlah_nomor_akun == 6) {
            $level = $jumlah_nomor_akun - 1;
        } elseif ($jumlah_nomor_akun > 6) {
            $level = $jumlah_nomor_akun - 2;
        } else {
            $level = $jumlah_nomor_akun;
        }

        $nama_akun = $input['nama_akun'];

        switch ($level) {
            case 1:
                $golongan = "Aset";
                break;
            case 2:
                $golongan = "Liabilitas";
                break;
            case 3:
                $golongan = "Ekuitas";
                break;
            case 4:
                $golongan = "Pendapatan";
                break;
            case 5:
                $golongan = "Beban";
                break;
            default:
                $golongan = "Beban Umum";
        }

        $saldo_normal = "Debit";

        $data = [
            'nomor_akun'   => $nomor_akun,
            'nama_akun'    => $nama_akun,
            'level'        => $level,
            'golongan'     => $golongan,
            'saldo_normal' => $saldo_normal,
            'updated_at'   => now(),
        ];

        $update = $coa->update($data);

        if ($update) {
            return response()->json([
                'status'  => 200,
                'message' => 'Success update data',
            ]);
        } else {
            return response()->json([
                'status'  => 400,
                'message' => 'Failed to update data',
            ]);
        }
    }

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $coa = Coa::find($id);

        // Cek apakah data ditemukan
        if (!$coa) {
            return response()->json([
                'status'  => 404,
                'message' => 'Data not found',
            ], 404);
        }

        $data = [
            'is_deleted'    => 1,
            'deleted_at'    => now(),
        ];

        $update = $coa->update($data);

        if ($update) {
            return response()->json([
                'status'  => 200,
                'message' => 'Success delete data',
            ]);
        } else {
            return response()->json([
                'status'  => 400,
                'message' => 'Failed delete data',
            ]);
        }
    }

    public function filterLevel1_3()
    {
        $query = DB::table('coas')
            ->select('id', 'nomor_akun', 'nama_akun', 'level', 'saldo_normal')
            ->whereNull('deleted_at')
            ->whereBetween('level', [1, 3])
            ->get();

            if ($query->count() == 0) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Success filter data',
                    'data'    => $query
                ]);
            } else {
                return response()->json([
                    'status'  => 400,
                    'message' => 'Success filter data',
                    'data'    => $query
                ]);
            }
    }

    public function filterLevel4_5()
    {
        $query = DB::table('coas')
            ->select('id', 'nomor_akun', 'nama_akun', 'level', 'saldo_normal')
            ->whereNull('deleted_at')
            ->whereBetween('level', [4, 5])
            ->get();

            if ($query->count() == 0) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Success filter data',
                    'data'    => $query
                ]);
            } else {
                return response()->json([
                    'status'  => 400,
                    'message' => 'Success filter data',
                    'data'    => $query
                ]);
            }
    }
}
