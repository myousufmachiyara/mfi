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
Route::get('/sales/all-invoices', [App\Http\Controllers\SalesController::class, 'index'])->name('all-saleinvoices');
Route::get('/sales/new-invoice', [App\Http\Controllers\SalesController::class, 'create'])->name('create-sale-invoice'); 
Route::get('/sales/edit-invoice/{id}', [App\Http\Controllers\SalesController::class, 'edit'])->name('edit-sale-invoice');
Route::post('/sales/saleinvoice/create', [App\Http\Controllers\SalesController::class, 'store'])->name('store-sale-invoice');
Route::post('/sales/saleinvoice/update/{id}', [App\Http\Controllers\SalesController::class, 'update'])->name('update-sale-invoice');
Route::post('/sales/saleinvoice/delete', [App\Http\Controllers\SalesController::class, 'destroy'])->name('delete-sale-invoice');
Route::get('/sales/saleinvoice/view/{id}', [App\Http\Controllers\SalesController::class, 'show'])->name('show-sale-invoice');
Route::get('/sales/saleinvoice/generatePDF', [App\Http\Controllers\SalesController::class, 'generatePDF'])->name('print-sale-invoice');
Route::get('/sales/saleinvoice/downloadPDF', [App\Http\Controllers\SalesController::class, 'downloadPDF'])->name('download-sale-invoice');

//items
Route::get('/item/detail', [App\Http\Controllers\ItemsController::class, 'getItemDetails'])->name('get-item-details');

//COA
Route::get('/coa/detail', [App\Http\Controllers\COAController::class, 'getAccountDetails'])->name('get-acc-details');