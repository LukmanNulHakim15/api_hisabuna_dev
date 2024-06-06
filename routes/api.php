<?php

use App\Http\Controllers\AkutansiTingkatDua;
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

