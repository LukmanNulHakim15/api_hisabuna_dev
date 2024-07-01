<?php

use App\Http\Controllers\AkutansiTingkatDua;
use App\Http\Controllers\AkutansiTingkatEmpat;
use App\Http\Controllers\AkutansiTingkatEnam;
use App\Http\Controllers\AkutansiTingkatLima;
use App\Http\Controllers\AkutansiTingkatSatu;
use App\Http\Controllers\AkutansiTingkatTiga;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\JurnalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//tingkatsatu
Route::get('/tingkatsatu',[AkutansiTingkatSatu::class, 'index'])->name('tingkatsatu');
Route::post('/saveTingkatSatu',[AkutansiTingkatSatu::class,'create'])->name('saveTingkatSatu');
Route::post('/idTingkatSatu',[AkutansiTingkatSatu::class,'getById'])->name('idTingkatSatu');
Route::post('/updateTingkatSatu',[AkutansiTingkatSatu::class,'update'])->name('updateTingkatSatu');
Route::post('/deleteTingkatSatu',[AkutansiTingkatSatu::class,'delete'])->name('deleteTingkatSatu');

//tingkatdua
Route::get('/tingkatDua',[AkutansiTingkatDua::class, 'index'])->name('tingkatDua');
Route::post('/saveTingkatDua',[AkutansiTingkatDua::class,'create'])->name('saveTingkatDua');
Route::post('/idTingkatDua',[AkutansiTingkatDua::class,'getById'])->name('idTingkatDua');
Route::post('/updateTingkatDua',[AkutansiTingkatDua::class,'update'])->name('updateTingkatDua');
Route::post('/deleteTingkatDua',[AkutansiTingkatDua::class,'delete'])->name('deleteTingkatDua');

//tingkattiga
Route::get('/tingkatTiga',[AkutansiTingkatTiga::class, 'index'])->name('tingkatTiga');
Route::post('/saveTingkatTiga',[AkutansiTingkatTiga::class,'create'])->name('saveTingkatTiga');
Route::post('/idTingkatTiga',[AkutansiTingkatTiga::class,'getById'])->name('idTingkatTiga');
Route::post('/updateTingkatTiga',[AkutansiTingkatTiga::class,'update'])->name('updateTingkatTiga');
Route::post('/deleteTingkatTiga',[AkutansiTingkatTiga::class,'delete'])->name('deleteTingkatTiga');

//tingkatempat
Route::get('/tingkatEmpat',[AkutansiTingkatEmpat::class, 'index'])->name('tingkatEmpat');
Route::post('/saveTingkatEmpat',[AkutansiTingkatEmpat::class,'create'])->name('saveTingkatEmpat');
Route::post('/idTingkatEmpat',[AkutansiTingkatEmpat::class,'getById'])->name('idTingkatEmpat');
Route::post('/updateTingkatEmpat',[AkutansiTingkatEmpat::class,'update'])->name('updateTingkatEmpat');
Route::post('/deleteTingkatEmpat',[AkutansiTingkatEmpat::class,'delete'])->name('deleteTingkatEmpat');

//tingkatlima
Route::get('/tingkatLima',[AkutansiTingkatLima::class, 'index'])->name('tingkatLima');
Route::post('/saveTingkatLima',[AkutansiTingkatLima::class,'create'])->name('saveTingkatLima');
Route::post('/idTingkatLima',[AkutansiTingkatLima::class,'getById'])->name('idTingkatLima');
Route::post('/updateTingkatLima',[AkutansiTingkatLima::class,'update'])->name('updateTingkatLima');
Route::post('/deleteTingkatLima',[AkutansiTingkatLima::class,'delete'])->name('deleteTingkatLima');

//tingkatenam
Route::get('/tingkatEnam',[AkutansiTingkatEnam::class, 'index'])->name('tingkatEnam');
Route::post('/saveTingkatEnam',[AkutansiTingkatEnam::class,'create'])->name('saveTingkatEnam');
Route::post('/idTingkatEnam',[AkutansiTingkatEnam::class,'getById'])->name('idTingkatEnam');
Route::post('/updateTingkatEnam',[AkutansiTingkatEnam::class,'update'])->name('updateTingkatEnam');
Route::post('/deleteTingkatEnam',[AkutansiTingkatEnam::class,'delete'])->name('deleteTingkatEnam');

//coa
// Route::get('/coa',[CoaController::class,'index'])->name('coa');
// Route::post('/coa',[CoaController::class,'create'])->name('coa');
// Route::put('/coa/{id}',[CoaController::class,'update'])->name('coa.update');
Route::resource('coa', CoaController::class);
Route::get('/filterLevel1_3',[CoaController::class,'filterLevel1_3'])->name('/filterLevel1_3');
Route::get('/filterLevel4_5',[CoaController::class,'filterLevel4_5'])->name('/filterLevel4_5');

//jurnal
Route::resource('jurnal', JurnalController::class);
Route::post('/importJurnal', [JurnalController::class, 'import'])->name('importJurnal');
Route::get('/exportJurnal', [JurnalController::class, 'export'])->name('exportJurnal');

// Route untuk proses import Excel
Route::post('/import', [CoaController::class, 'import'])->name('import');
// Route untuk melakukan export data ke Excel
Route::get('/export', [CoaController::class, 'export'])->name('export');
