<?php

use App\Http\Controllers\AkutansiTingkatDua;
use App\Http\Controllers\AkutansiTingkatEmpat;
use App\Http\Controllers\AkutansiTingkatLima;
use App\Http\Controllers\AkutansiTingkatSatu;
use App\Http\Controllers\AkutansiTingkatTiga;
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