<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacientController;
use App\Http\Controllers\AdministradorController;


Route::get('/', function () {
        return view('menu');
 })->name("menu"); 

Route::resource('pacient', PacientController::class);
Route::resource('admin', AdministradorController::class);
