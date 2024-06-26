<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\JurnalDetail;
use App\Models\Saldo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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
    //validasi jurnal pertama harus jv karna modal yang harus di setor
    $validasi_jenis = Jurnal::all();
    $jenis = $request->jenis;
    if(count($validasi_jenis) == 0){
        if($jenis !== 'JV') {
            return response()->json([
                'status' => 400,
                'message' => 'Harus Jenis JV terlebih dahulu',
            ]);
        }
    }
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
        $coa_ids        = $request->input('coa_id');
        $debits         = $request->input('debit');
        $sum_debit      = array_sum($debits);
        $credits        = $request->input('credit');
        $sum_kredit     = array_sum($credits);
        
        if ($jenis == 'RV') {
            // jika field current sudah ada maka ambil dari db dengan rumus current_debit dari db+debit - kredit
            $query = Saldo::orderBy('id', 'desc')->first();
            if ($query) {
                $current_saldo_debit_terkini = $query->current_saldo_debit;
                $current_saldo_kredit_terkini = $query->current_saldo_kredit;
            } else {
                $current_saldo_debit_terkini = 0;
                $current_saldo_kredit_terkini = 0;
            }
            $current_saldo_debit = ($current_saldo_debit_terkini + $sum_debit) - $sum_kredit;
            $current_saldo_kredit = $current_saldo_kredit_terkini + 0;
        } else if ($jenis == 'PV') {
            // jika input awal current saldo langsung di ambil dari kredit
            // jika field current_kredit sudah ada maka ambil dari db dengan rumus current_kredit dari db+kredit + debit
            $query = Saldo::orderBy('id', 'desc')->first();
            if ($query) {
                $current_saldo_debit_terkini = $query->current_saldo_debit;
                $current_saldo_kredit_terkini = $query->current_saldo_kredit;
            } else {
                $current_saldo_debit_terkini = 0;
                $current_saldo_kredit_terkini = 0;
            }
            if (is_null($current_saldo_kredit_terkini) || $current_saldo_kredit_terkini == 0) {
                $current_saldo_debit = $current_saldo_debit_terkini + 0;
                $current_saldo_kredit = $sum_kredit;
            } else {
                $current_saldo_debit = $current_saldo_debit_terkini + 0;
                $current_saldo_kredit = ($current_saldo_kredit_terkini + $sum_kredit) + $sum_debit;
            }
        } else {
            $current_saldo_debit = $sum_debit;
            echo $current_saldo_debit;
            $current_saldo_kredit = 0;
        }

        $data_saldo = [
            'jurnal_id'             => $save->id,
            'current_saldo_debit'   => $current_saldo_debit,
            'current_saldo_kredit'  => $current_saldo_kredit

        ];

        $save_saldo = Saldo::create($data_saldo);
        if($save_saldo) {
              // Menyimpan data jurnal detail
            foreach ($debits as $key => $debit) {
                $detail_data = [
                    'jurnal_id' => $save->id,
                    'coa_id' => $coa_ids[$key],
                    'debit' => $debit,
                    'credit' => $credits[$key],
                    'ket_transaksi' => $keterangan
                ];

                $save_detail = JurnalDetail::create($detail_data);
                $save_detail->update(['updated_at' => null]);
            }
            return response()->json([
                'status' => 201,
                'message' => 'Success create data',
            ]);
        }

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

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx',
        ]);
    
        try {
            $id = $request->input('id');
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();
    
            if (count($data)) {
                foreach ($data as $key => $value) {
                    if ($key == 0) {
                        // Skip header row
                        continue;
                    }
    
                    // Validasi dan sanitasi data
                    if (isset($value[0], $value[1], $value[2])) {
                        $jurnalData = [
                            'jurnal_id'     => $id,
                            'coa_id'        => intval($value[0]),  // Pastikan bahwa ID adalah integer
                            'debit'         => floatval($value[1]), // Konversi ke float
                            'credit'        => floatval($value[2]),  // Konversi ke float
                            'ket_transaksi' => $value[3]
                        ];
    
                        // Masukkan data ke dalam tabel JurnalDetail
                        JurnalDetail::create($jurnalData);
                    }
                }
    
                return response()->json([
                    'status'  => 200,
                    'message' => 'Success import data',
                ]);
            } else {
                return response()->json([
                    'status'  => 400,
                    'message' => 'File is empty or invalid data format',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }
    

    public function export()
    {
        try {
          $jurnalDetail     = JurnalDetail::all();

          //Buat spreedsheet baru 
          $spreadsheet = new Spreadsheet();
          $sheet = $spreadsheet->getActiveSheet();

          // Set header untuk kolom
          $sheet->setCellValue('A1', 'Jurnal Id');
          $sheet->setCellValue('B1', 'Coa Id');
          $sheet->setCellValue('C1', 'debit');
          $sheet->setCellValue('D1', 'credit');

          $row = 2;
            foreach ($jurnalDetail as $jurnalDetails) {
                $sheet->setCellValue('A' . $row, $jurnalDetails->jurnal_id);
                $sheet->setCellValue('B' . $row, $jurnalDetails->coa_id);
                $sheet->setCellValue('C' . $row, $jurnalDetails->debit);
                $sheet->setCellValue('D' . $row, $jurnalDetails->credit);
                $row++;
            }

             // Menyimpan file Excel
             $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
             $filePath = storage_path('app/public/jurnalDetail.xlsx');
             $writer->save($filePath);
 
             return response()->download($filePath);
        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi
            return response()->json([
                'status'  => 500,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }
}
