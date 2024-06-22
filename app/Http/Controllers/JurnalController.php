<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\JurnalDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class JurnalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'status'        => 200,
            'message'       => 'List Jurnal',
            'data'          => Jurnal::whereNull('is_deleted')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
{
    $input = $request->all();
    
    // Melakukan validasi input
    $validator = Validator::make($input, [
        'no_transaksi' => 'required|integer',
        'jenis' => 'required|string',
        'keterangan' => 'required|string',
        'lampiran' => 'required|string',
        'coa_id' => 'required|array',
        'debit' => 'array',
        'credit' => 'array',
        'ket_transaksi' => 'required|array',
    ]);

    // Jika validasi gagal, kembalikan respon dengan error
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation Error.',
            'errors' => $validator->errors()
        ], 400);
    }

    // Mengambil data dari request
    $no_transaksi = $request->no_transaksi;
    $jenis = $request->jenis;
    $keterangan = $request->keterangan;
    $lampiran = $request->lampiran;

    // Menyimpan data jurnal
    $data = [
        'no_transaksi' => $no_transaksi,
        'jenis' => $jenis,
        'keterangan' => $keterangan,
        'lampiran' => $lampiran
    ];

    $save = Jurnal::create($data);

    if ($save) {
        // Mengupdate updated_at menjadi null
        $save->update(['updated_at' => null]);

        // Mengambil array input dari request
        $coa_ids = $request->input('coa_id');
        $debits = $request->input('debit');
        $credits = $request->input('credit');
        $ket_transaksis = $request->input('ket_transaksi');

        // Menyimpan data jurnal detail
        foreach ($debits as $key => $debit) {
            $detail_data = [
                'jurnal_id' => $save->id,
                'coa_id' => $coa_ids[$key],
                'debit' => $debit,
                'credit' => $credits[$key],
                'ket_transaksi' => $ket_transaksis[$key]
            ];

            $save_detail = JurnalDetail::create($detail_data);
            $save_detail->update(['updated_at' => null]);
        }

        return response()->json([
            'status' => 201,
            'message' => 'Success create data',
        ]);
    } else {
        return response()->json([
            'status' => 400,
            'message' => 'Failed create data',
        ]);
    }
}


    /**
     * Display the specified resource.
     */
    public function show(Jurnal $jurnal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jurnal $jurnal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jurnal $jurnal): JsonResponse
    {
        $input = $request->all();
        $id = $jurnal->id;
        // Melakukan validasi input
        $validator = Validator::make($input, [
            'no_transaksi' => 'required|integer',
            'jenis' => 'required|string',
            'keterangan' => 'required|string',
            'lampiran' => 'required|string',
            'coa_id' => 'required|array',
            'debit' => 'array',
            'credit' => 'array',
            'ket_transaksi' => 'required|array',
        ]);
    
        // Jika validasi gagal, kembalikan respon dengan error
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $validator->errors()
            ], 400);
        }
    
        // Mengambil data dari request
        $no_transaksi = $request->no_transaksi;
        $jenis = $request->jenis;
        $keterangan = $request->keterangan;
        $lampiran = $request->lampiran;
    
        // Menyimpan data jurnal
        $data = [
            'no_transaksi' => $no_transaksi,
            'jenis' => $jenis,
            'keterangan' => $keterangan,
            'lampiran' => $lampiran,
            'updated_at' => now(),
        ];
    
        $update = $jurnal->update($data);
    
        if ($update) {
    
            // Mengambil array input dari request
            $coa_ids = $request->input('coa_id');
            $debits = $request->input('debit');
            $credits = $request->input('credit');
            $ket_transaksis = $request->input('ket_transaksi');
    
            // Mengambil semua entri jurnal detail yang memiliki jurnal_id tertentu
            $jurnal_details = JurnalDetail::where('jurnal_id', $id)->get();

            // Pastikan jumlah elemen input sesuai dengan jumlah entri yang ditemukan
            if ($jurnal_details->count() !== count($coa_ids)) {
                return response()->json(['error' => 'Jumlah input tidak sesuai dengan jumlah entri yang ditemukan.'], 400);
            }

            // Menyimpan data jurnal detail berdasarkan input dari JSON
            foreach ($jurnal_details as $key => $jurnal_detail) {
                $detail_data = [
                    'coa_id' => $coa_ids[$key],
                    'debit' => $debits[$key],
                    'credit' => $credits[$key],
                    'ket_transaksi' => $ket_transaksis[$key],
                    'updated_at' => now(),
                ];

                // Mengupdate data jurnal detail
                $jurnal_detail->update($detail_data);
            }
    
            return response()->json([
                'status' => 201,
                'message' => 'Success update data',
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Failed update data',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jurnal = Jurnal::find($id);

        // Cek apakah data ditemukan
        if (!$jurnal) {
            return response()->json([
                'status'  => 'False',
                'message' => 'Data not found',
            ], 200);
        }

        $data = [
            'is_deleted'    => 1,
            'deleted_at'    => now(),
        ];

        $update = $jurnal->update($data);

        if ($update) {
            $jurnal_details = JurnalDetail::where('jurnal_id', $id)->get();
            foreach ($jurnal_details as $key => $jurnal_detail) {
                $data = [
                    'is_deleted'    => 1,
                    'deleted_at'    => now(),
                ];

                $jurnal_detail->update($data);
            }

            return response()->json([
                'status' => 201,
                'message' => 'Success delete data',
            ]);
            
        } else {
            return response()->json([
                'status'  => 400,
                'message' => 'Failed delete data',
            ]);
        }
    }
}
