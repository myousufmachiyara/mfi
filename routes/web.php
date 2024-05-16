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
Route::get('/item2/all-items', [App\Http\Controllers\Item2Controller::class, 'index'])->name('all-items-2');
Route::get('/item2/new-item', [App\Http\Controllers\Item2Controller::class, 'create'])->name('create-item-2'); 
Route::post('/item2/new-item/create', [App\Http\Controllers\Item2Controller::class, 'store'])->name('store-item-2');
Route::post('/item2/new-item/update', [App\Http\Controllers\Item2Controller::class, 'update'])->name('update-item-2');
Route::post('/item2/new-item/delete', [App\Http\Controllers\Item2Controller::class, 'destroy'])->name('delete-item-2');
Route::get('/item2/detail', [App\Http\Controllers\Item2Controller::class, 'getItemDetails'])->name('get-item-details-2');

//COA
Route::get('/coa/all-acc', [App\Http\Controllers\COAController::class, 'index'])->name('all-acc');
Route::post('/coa/acc/create', [App\Http\Controllers\COAController::class, 'store'])->name('store-acc');
Route::post('/coa/acc/update', [App\Http\Controllers\COAController::class, 'update'])->name('update-acc');
Route::post('/coa/acc/delete', [App\Http\Controllers\COAController::class, 'destroy'])->name('delete-acc');
Route::get('/coa/acc/detail', [App\Http\Controllers\COAController::class, 'getAccountDetails'])->name('get-acc-details');

// COA Groups
Route::get('/coa/all-coa-groups', [App\Http\Controllers\COAGroupsController::class, 'index'])->name('all-acc-groups');
Route::post('/coa/coa-groups/create', [App\Http\Controllers\COAGroupsController::class, 'store'])->name('store-acc-groups');
Route::post('/coa/coa-groups/update', [App\Http\Controllers\COAGroupsController::class, 'update'])->name('update-acc-groups');
Route::post('/coa/coa-groups/delete', [App\Http\Controllers\COAGroupsController::class, 'destroy'])->name('delete-acc-groups');
Route::get('/coa/coa-groups/detail', [App\Http\Controllers\COAGroupsController::class, 'getAccountDetails'])->name('get-acc-groups-details');

// COA Sub Heads
Route::get('/coa/all-coa-sub-heads', [App\Http\Controllers\COASubHeadsController::class, 'index'])->name('all-acc-sub-heads-groups');
Route::post('/coa/coa-sub-heads/create', [App\Http\Controllers\COASubHeadsController::class, 'store'])->name('store-acc-sub-heads-groups');
Route::post('/coa/coa-sub-heads/update', [App\Http\Controllers\COASubHeadsController::class, 'update'])->name('update-acc-sub-heads-groups');
Route::post('/coa/coa-sub-heads/delete', [App\Http\Controllers\COASubHeadsController::class, 'destroy'])->name('delete-acc-sub-heads-groups');
Route::get('/coa/coa-sub-heads/detail', [App\Http\Controllers\COASubHeadsController::class, 'getAccountDetails'])->name('get-acc-sub-heads-groups-details');

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
