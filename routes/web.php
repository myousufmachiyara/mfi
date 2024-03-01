<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/laravel-dashboard', function () {
    return view('template');
});


//sales
Route::get('sales/saleinvoice', [App\Http\Controllers\SalesController::class, 'index'])->name('saleinvoice');
Route::get('sales/saleinvoiceprint', [App\Http\Controllers\SalesController::class, 'printInvoice'])->name('saleinvoiceprint');