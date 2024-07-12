<?php

use Illuminate\Support\Facades\Route;

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
Route::post('/item/new-item/validate', [App\Http\Controllers\ItemsController::class, 'validation'])->name('validate-item');
Route::get('/item/detail', [App\Http\Controllers\ItemsController::class, 'getItemDetails'])->name('get-item-details');

//items 2
Route::get('/item2/all-items', [App\Http\Controllers\Item2Controller::class, 'index'])->name('all-items-2');
Route::get('/item2/new-item', [App\Http\Controllers\Item2Controller::class, 'create'])->name('create-item-2'); 
Route::post('/item2/new-item/create', [App\Http\Controllers\Item2Controller::class, 'store'])->name('store-item-2');
Route::post('/item2/new-item/update', [App\Http\Controllers\Item2Controller::class, 'update'])->name('update-item-2');
Route::post('/item2/new-item/delete', [App\Http\Controllers\Item2Controller::class, 'destroy'])->name('delete-item-2');
Route::post('/item2/new-item/validate', [App\Http\Controllers\Item2Controller::class, 'validation'])->name('validate-item-2');
Route::get('/item2/detail', [App\Http\Controllers\Item2Controller::class, 'getItemDetails'])->name('get-item-details-2');

//COA
Route::get('/coa/all-acc', [App\Http\Controllers\COAController::class, 'index'])->name('all-acc');
Route::post('/coa/acc/create', [App\Http\Controllers\COAController::class, 'store'])->name('store-acc');
Route::post('/coa/acc/update', [App\Http\Controllers\COAController::class, 'update'])->name('update-acc');
Route::post('/coa/acc/delete', [App\Http\Controllers\COAController::class, 'destroy'])->name('delete-acc');
Route::get('/coa/acc/activate/{id}', [App\Http\Controllers\COAController::class, 'activate'])->name('activate-acc');
Route::post('/coa/acc/validate', [App\Http\Controllers\COAController::class, 'validation'])->name('validate-acc');
Route::get('/coa/acc/detail', [App\Http\Controllers\COAController::class, 'getAccountDetails'])->name('get-acc-details');
Route::get('/coa/acc/attachements', [App\Http\Controllers\COAController::class, 'getAttachements'])->name('get-acc-att');
Route::get('/coa/acc/print', [App\Http\Controllers\COAController::class, 'print'])->name('print-acc');
Route::get('/coa/acc/download/{id}', [App\Http\Controllers\COAController::class, 'downloadAtt'])->name('coa-att-download');
Route::get('/coa/acc/view/{id}', [App\Http\Controllers\COAController::class, 'view'])->name('coa-att-view');
Route::delete('/coa/acc/deleteAtt/{id}', [App\Http\Controllers\COAController::class, 'deleteAtt'])->name('coa-att-delete');
Route::post('/coa/acc/downloadAll', [App\Http\Controllers\COAController::class, 'downloadAllAtt'])->name('coa-att-download-all');

// COA Groups
Route::get('/coa/all-coa-groups', [App\Http\Controllers\COAGroupsController::class, 'index'])->name('all-acc-groups');
Route::post('/coa/coa-groups/create', [App\Http\Controllers\COAGroupsController::class, 'store'])->name('store-acc-groups');
Route::post('/coa/coa-groups/update', [App\Http\Controllers\COAGroupsController::class, 'update'])->name('update-acc-groups');
Route::post('/coa/coa-groups/delete', [App\Http\Controllers\COAGroupsController::class, 'destroy'])->name('delete-acc-groups');
Route::get('/coa/coa-groups/detail', [App\Http\Controllers\COAGroupsController::class, 'getDetails'])->name('get-acc-groups-details');

// COA Sub Heads
Route::get('/coa/all-coa-sub-heads', [App\Http\Controllers\COASubHeadsController::class, 'index'])->name('all-acc-sub-heads-groups');
Route::post('/coa/coa-sub-heads/create', [App\Http\Controllers\COASubHeadsController::class, 'store'])->name('store-acc-sub-heads-groups');
Route::post('/coa/coa-sub-heads/update', [App\Http\Controllers\COASubHeadsController::class, 'update'])->name('update-acc-sub-heads-groups');
Route::post('/coa/coa-sub-heads/delete', [App\Http\Controllers\COASubHeadsController::class, 'destroy'])->name('delete-acc-sub-heads-groups');
Route::get('/coa/coa-sub-heads/detail', [App\Http\Controllers\COASubHeadsController::class, 'getCOASubHeadDetails'])->name('get-acc-sub-heads-groups-details');

// Journal Voucher 1
Route::get('/vouchers/all-jv1', [App\Http\Controllers\JV1Controller::class, 'index'])->name('all-jv1');
Route::post('/vouchers/jv1/create', [App\Http\Controllers\JV1Controller::class, 'store'])->name('store-jv1');
Route::post('/vouchers/jv1/update', [App\Http\Controllers\JV1Controller::class, 'update'])->name('update-jv1');
Route::post('/vouchers/jv1/delete', [App\Http\Controllers\JV1Controller::class, 'destroy'])->name('delete-jv1');
Route::get('/vouchers/jv1/attachements', [App\Http\Controllers\JV1Controller::class, 'getAttachements'])->name('get-jv1-att');
Route::get('/vouchers/jv1/detail', [App\Http\Controllers\JV1Controller::class, 'getJVDetails'])->name('get-jv1-details');
Route::get('/vouchers/jv1/print/{id}', [App\Http\Controllers\JV1Controller::class, 'print'])->name('print-jv1');
Route::get('/vouchers/jv1/download/{id}', [App\Http\Controllers\JV1Controller::class, 'downloadAtt'])->name('jv1-att-download');
Route::get('/vouchers/jv1/view/{id}', [App\Http\Controllers\JV1Controller::class, 'view'])->name('jv1-att-view');
Route::delete('/vouchers/jv1/deleteAttachment/{id}', [App\Http\Controllers\JV1Controller::class, 'deleteAtt'])->name('jv1-att-delete');

// Journal Voucher 2
Route::get('/vouchers/all-jv2', [App\Http\Controllers\JV2Controller::class, 'index'])->name('all-jv2');
Route::get('/vouchers/jv2/new', [App\Http\Controllers\JV2Controller::class, 'create'])->name('new-jv2');
Route::post('/vouchers/jv2/create', [App\Http\Controllers\JV2Controller::class, 'store'])->name('store-jv2');
Route::get('/vouchers/jv2/edit/{id}', [App\Http\Controllers\JV2Controller::class, 'edit'])->name('edit-jv2');
Route::post('/vouchers/jv2/update', [App\Http\Controllers\JV2Controller::class, 'update'])->name('update-jv2');
Route::post('/vouchers/jv2/delete', [App\Http\Controllers\JV2Controller::class, 'destroy'])->name('delete-jv2');
Route::get('/vouchers/jv2/print/{id}', [App\Http\Controllers\JV2Controller::class, 'print'])->name('print-jv2');
Route::get('/vouchers/jv2/attachements', [App\Http\Controllers\JV2Controller::class, 'getAttachements'])->name('get-jv2-att');
Route::get('/vouchers/jv2/download/{id}', [App\Http\Controllers\JV2Controller::class, 'downloadAtt'])->name('jv2-att-download');
Route::get('/vouchers/jv2/view/{id}', [App\Http\Controllers\JV2Controller::class, 'view'])->name('jv2-att-view');
Route::delete('/vouchers/jv2/deleteAttachment/{id}', [App\Http\Controllers\JV2Controller::class, 'deleteAtt'])->name('jv2-att-delete');

//purchase 1
Route::get('/purchase1/all-purchases', [App\Http\Controllers\PurchaseController::class, 'index'])->name('all-purchases1');
Route::get('/purchase1/new', [App\Http\Controllers\PurchaseController::class, 'create'])->name('new-purchases1');
Route::post('/purchase1/create', [App\Http\Controllers\PurchaseController::class, 'store'])->name('store-purchases1');
Route::get('/purchase1/edit/{id}', [App\Http\Controllers\PurchaseController::class, 'edit'])->name('edit-purchases1');
Route::post('/purchase1/update', [App\Http\Controllers\PurchaseController::class, 'update'])->name('update-purchases1');
Route::post('/purchase1/delete', [App\Http\Controllers\PurchaseController::class, 'destroy'])->name('delete-purchases1');
Route::get('/purchase1/view/{id}', [App\Http\Controllers\PurchaseController::class, 'view'])->name('show-purchases1');
Route::get('/purchase1/attachements', [App\Http\Controllers\PurchaseController::class, 'getAttachements'])->name('get-purc1-att');
Route::get('/purchase1/download/{id}', [App\Http\Controllers\PurchaseController::class, 'downloadAtt'])->name('purc1-att-download');
Route::delete('/purchase1/deleteAttachment/{id}', [App\Http\Controllers\PurchaseController::class, 'deleteAtt'])->name('purc1-att-delete');
Route::get('/purchase1/generatePDF/{id}', [App\Http\Controllers\PurchaseController::class, 'generatePDF'])->name('print-purc1-invoice');

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