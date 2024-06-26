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

//item groups
Route::get('/item-groups/all-groups', [App\Http\Controllers\ItemGroupsController::class, 'index'])->name('all-item-groups');
Route::post('/item-groups/groups/create', [App\Http\Controllers\ItemGroupsController::class, 'store'])->name('store-item-group');
Route::post('/item-groups/groups/delete', [App\Http\Controllers\ItemGroupsController::class, 'destroy'])->name('delete-item-group');
Route::post('/item-groups/groups/update', [App\Http\Controllers\ItemGroupsController::class, 'update'])->name('update-item-group');
Route::get('/item-group/detail', [App\Http\Controllers\ItemGroupsController::class, 'getGroupDetails'])->name('get-item-group-details');

//items
Route::get('/items/all-items', [App\Http\Controllers\ItemsController::class, 'index'])->name('all-items');
Route::get('/items/new-item', [App\Http\Controllers\ItemsController::class, 'create'])->name('create-item'); 
Route::post('/items/new-item/create', [App\Http\Controllers\ItemsController::class, 'store'])->name('store-item');
Route::post('/item/new-item/update', [App\Http\Controllers\ItemsController::class, 'update'])->name('update-item');
Route::post('/item/new-item/delete', [App\Http\Controllers\ItemsController::class, 'destroy'])->name('delete-item');
Route::get('/item/detail', [App\Http\Controllers\ItemsController::class, 'getItemDetails'])->name('get-item-details');

//items 2


//sales
Route::get('/sales/all-invoices', [App\Http\Controllers\SalesController::class, 'index'])->name('all-saleinvoices');
Route::get('/sales/new-invoice', [App\Http\Controllers\SalesController::class, 'create'])->name('create-sale-invoice'); 
Route::get('/sales/edit-invoice/{id}', [App\Http\Controllers\SalesController::class, 'edit'])->name('edit-sale-invoice');
Route::post('/sales/saleinvoice/create', [App\Http\Controllers\SalesController::class, 'store'])->name('store-sale-invoice');
Route::post('/sales/saleinvoice/update/{id}', [App\Http\Controllers\SalesController::class, 'update'])->name('update-sale-invoice');
Route::post('/sales/saleinvoice/delete', [App\Http\Controllers\SalesController::class, 'destroy'])->name('delete-sale-invoice');
Route::get('/sales/saleinvoice/view/{id}', [App\Http\Controllers\SalesController::class, 'show'])->name('show-sale-invoice');
Route::get('/sales/saleinvoice/generatePDF/{id}', [App\Http\Controllers\SalesController::class, 'generatePDF'])->name('print-sale-invoice');
Route::get('/sales/saleinvoice/downloadPDF/{id}', [App\Http\Controllers\SalesController::class, 'downloadPDF'])->name('download-sale-invoice');





//COA
Route::get('/coa/detail', [App\Http\Controllers\COAController::class, 'getAccountDetails'])->name('get-acc-details');