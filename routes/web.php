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
Route::get('sales/all-invoices', [App\Http\Controllers\SalesController::class, 'index'])->name('all-saleinvoices');
Route::get('sales/new-invoice', [App\Http\Controllers\SalesController::class, 'create'])->name('create-sale-invoice'); 
Route::post('sales/saleinvoice/create', [App\Http\Controllers\SalesController::class, 'store'])->name('store-sale-invoice');
Route::get('sales/saleinvoice/print', [App\Http\Controllers\SalesController::class, 'printInvoice'])->name('print-sale-invoice');