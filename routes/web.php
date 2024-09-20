<?php

use Illuminate\Support\Facades\Route;


Route::get('/', [App\Http\Controllers\UsersController::class, 'loginScreen'])->name('login');
Route::post('/login', [App\Http\Controllers\UsersController::class, 'login'])->name('userlogin');
Route::get('/logout', [App\Http\Controllers\UsersController::class, 'logout'])->middleware('auth');

// Route::middleware(['checkPermission'])->group(function () {
Route::middleware(['auth'])->group(function () {

    //home
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    //users
    Route::get('/users/all-users', [App\Http\Controllers\UsersController::class, 'index'])->name('all-users');
    Route::post('/users/create', [App\Http\Controllers\UsersController::class, 'createUser'])->name('new-user');

    // user roles
    Route::get('/user-role/all-roles', [App\Http\Controllers\UserRoleController::class, 'index'])->name('all-roles');
    Route::get('/user-role/new-role', [App\Http\Controllers\UserRoleController::class, 'create'])->name('new-role');
    Route::post('/user-role/create-role', [App\Http\Controllers\UserRoleController::class, 'store'])->name('create-role');


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
    Route::get('/vouchers/jv2/pendingInvoice/{id}', [App\Http\Controllers\JV2Controller::class, 'pendingInvoice'])->name('jv2-pend-invoices');
    Route::get('/vouchers/jv2/purpendingInvoice/{id}', [App\Http\Controllers\JV2Controller::class, 'purpendingInvoice'])->name('jv2-pur-pend-invoices');

    //purchase 1
    Route::get('/purchase1/all-purchases', [App\Http\Controllers\PurchaseController::class, 'index'])->name('all-purchases1');
    Route::get('/purchase1/new', [App\Http\Controllers\PurchaseController::class, 'create'])->name('new-purchases1');
    Route::post('/purchase1/create', [App\Http\Controllers\PurchaseController::class, 'store'])->name('store-purchases1');
    Route::get('/purchase1/edit/{id}', [App\Http\Controllers\PurchaseController::class, 'edit'])->name('edit-purchases1');
    Route::post('/purchase1/update', [App\Http\Controllers\PurchaseController::class, 'update'])->name('update-purchases1');
    Route::post('/purchase1/delete', [App\Http\Controllers\PurchaseController::class, 'destroy'])->name('delete-purchases1');
    Route::get('/purchase1/show/{id}', [App\Http\Controllers\PurchaseController::class, 'show'])->name('show-purchases1');
    Route::get('/purchase1/view/{id}', [App\Http\Controllers\PurchaseController::class, 'view'])->name('show-purchases1-att');
    Route::get('/purchase1/attachements', [App\Http\Controllers\PurchaseController::class, 'getAttachements'])->name('get-purc1-att');
    Route::get('/purchase1/download/{id}', [App\Http\Controllers\PurchaseController::class, 'downloadAtt'])->name('purc1-att-download');
    Route::delete('/purchase1/deleteAttachment/{id}', [App\Http\Controllers\PurchaseController::class, 'deleteAtt'])->name('purc1-att-delete');
    Route::get('/purchase1/generatePDF/{id}', [App\Http\Controllers\PurchaseController::class, 'generatePDF'])->name('print-purc1-invoice');

    //purchase 2
    Route::get('/purchase2/all-purchases', [App\Http\Controllers\Purchase2Controller::class, 'index'])->name('all-purchases2');
    Route::get('/purchase2/new', [App\Http\Controllers\Purchase2Controller::class, 'create'])->name('new-purchases2');
    Route::post('/purchase2/create', [App\Http\Controllers\Purchase2Controller::class, 'store'])->name('store-purchases2');
    Route::get('/purchase2/edit/{id}', [App\Http\Controllers\Purchase2Controller::class, 'edit'])->name('edit-purchases2');
    Route::post('/purchase2/update', [App\Http\Controllers\Purchase2Controller::class, 'update'])->name('update-purchases2');
    Route::post('/purchase2/delete', [App\Http\Controllers\Purchase2Controller::class, 'destroy'])->name('delete-purchases2');
    Route::get('/purchase2/show/{id}', [App\Http\Controllers\Purchase2Controller::class, 'show'])->name('show-purchases2');
    Route::get('/purchase2/view/{id}', [App\Http\Controllers\Purchase2Controller::class, 'view'])->name('show-purchases2-att');
    Route::get('/purchase2/attachements', [App\Http\Controllers\Purchase2Controller::class, 'getAttachements'])->name('get-purc2-att');
    Route::get('/purchase2/download/{id}', [App\Http\Controllers\Purchase2Controller::class, 'downloadAtt'])->name('purc2-att-download');
    Route::delete('/purchase2/deleteAttachment/{id}', [App\Http\Controllers\Purchase2Controller::class, 'deleteAtt'])->name('purc2-att-delete');
    Route::get('/purchase2/generatePDF/{id}', [App\Http\Controllers\Purchase2Controller::class, 'generatePDF'])->name('print-purc2-invoice');
    Route::get('/purchase2/getunclosed/', [App\Http\Controllers\Purchase2Controller::class, 'getunclosed'])->name('get-unclosed-purc2-invoice');
    Route::get('/purchase2/getItems/{id}', [App\Http\Controllers\Purchase2Controller::class, 'getItems'])->name('get-purc2-items');

    //sales
    Route::get('/sales/all-invoices', [App\Http\Controllers\SalesController::class, 'index'])->name('all-saleinvoices');
    Route::get('/sales/new-invoice', [App\Http\Controllers\SalesController::class, 'create'])->name('create-sale-invoice'); 
    Route::get('/sales/edit-invoice/{id}', [App\Http\Controllers\SalesController::class, 'edit'])->name('edit-sale-invoice');
    Route::post('/sales/saleinvoice/create', [App\Http\Controllers\SalesController::class, 'store'])->name('store-sale-invoice');
    Route::post('/sales/saleinvoice/update', [App\Http\Controllers\SalesController::class, 'update'])->name('update-sale-invoice');
    Route::post('/sales/saleinvoice/delete', [App\Http\Controllers\SalesController::class, 'destroy'])->name('delete-sale-invoice');
    Route::get('/sales/saleinvoice/view/{id}', [App\Http\Controllers\SalesController::class, 'showNew'])->name('show-sale-invoice');
    Route::get('/sales/saleinvoice/generatePDF/{id}', [App\Http\Controllers\SalesController::class, 'generatePDF'])->name('print-sale-invoice');
    Route::get('/sales/saleinvoice/downloadPDF/{id}', [App\Http\Controllers\SalesController::class, 'downloadPDF'])->name('download-sale-invoice');
    Route::get('/sales/attachements', [App\Http\Controllers\SalesController::class, 'getAttachements'])->name('get-sale1-att');
    Route::get('/sales/download/{id}', [App\Http\Controllers\SalesController::class, 'downloadAtt'])->name('sale1-att-download');
    Route::delete('/sales/deleteAttachment/{id}', [App\Http\Controllers\SalesController::class, 'deleteAtt'])->name('sale1-att-delete');
    Route::get('/sales/view/{id}', [App\Http\Controllers\SalesController::class, 'view'])->name('show-sale1-att');

    //sales 2
    Route::get('/sales2/all-invoices', [App\Http\Controllers\Sales2Controller::class, 'index'])->name('all-sale2invoices');
    Route::get('/sales2/new', [App\Http\Controllers\Sales2Controller::class, 'create'])->name('new-sales2');
    Route::post('/sales2/create', [App\Http\Controllers\Sales2Controller::class, 'store'])->name('store-sales2');
    Route::get('/sales2/edit/{id}', [App\Http\Controllers\Sales2Controller::class, 'edit'])->name('edit-sales2');
    Route::post('/sales2/update', [App\Http\Controllers\Sales2Controller::class, 'update'])->name('update-sales2');
    Route::post('/sales2/delete', [App\Http\Controllers\Sales2Controller::class, 'destroy'])->name('delete-sales2');
    Route::get('/sales2/show/{id}', [App\Http\Controllers\Sales2Controller::class, 'show'])->name('show-sales2');
    Route::get('/sales2/view/{id}', [App\Http\Controllers\Sales2Controller::class, 'view'])->name('show-sales2-att');
    Route::get('/sales2/attachements', [App\Http\Controllers\Sales2Controller::class, 'getAttachements'])->name('get-sales2-att');
    Route::get('/sales2/download/{id}', [App\Http\Controllers\Sales2Controller::class, 'downloadAtt'])->name('sales2-att-download');
    Route::delete('/sales2/deleteAttachment/{id}', [App\Http\Controllers\Sales2Controller::class, 'deleteAtt'])->name('sales2-att-delete');
    Route::get('/sales2/generatePDF/', [App\Http\Controllers\Sales2Controller::class, 'generatePDF'])->name('print-sales2-invoice');
    Route::get('/sales2/getunclosed/', [App\Http\Controllers\Sales2Controller::class, 'getunclosed'])->name('get-unclosed-sales2-invoice');
    Route::get('/sales2/getItems/{id}', [App\Http\Controllers\Sales2Controller::class, 'getItems'])->name('get-sales2-items');

    //tbad bads
    Route::get('/tbad_dabs/all-tbad-dabs', [App\Http\Controllers\TBadDabsController::class, 'index'])->name('all-tbad-dabs');
    Route::get('/tbad_dabs/new-tbad-dabs', [App\Http\Controllers\TBadDabsController::class, 'create'])->name('create-tbad-dabs'); 
    Route::post('/tbad_dabs/create', [App\Http\Controllers\TBadDabsController::class, 'store'])->name('store-tbad-dabs-entry');
    Route::get('/tbad_dabs/edit/{id}', [App\Http\Controllers\TBadDabsController::class, 'edit'])->name('edit-tbad-dabs-entry');
    Route::post('/tbad_dabs/delete', [App\Http\Controllers\TBadDabsController::class, 'destroy'])->name('delete-tbad-dabs');
    Route::post('/tbad_dabs/update', [App\Http\Controllers\TBadDabsController::class, 'update'])->name('update-tbad-dabs');

    //tstock in
    Route::get('/tstock_in/all-tstock_in', [App\Http\Controllers\TStockInController::class, 'index'])->name('all-tstock-in');
    Route::get('/tstock_in/new-tstock_in', [App\Http\Controllers\TStockInController::class, 'create'])->name('create-tstock-in-invoice'); 
    Route::get('/tstock_in/edit-invoice/{id}', [App\Http\Controllers\TStockInController::class, 'edit'])->name('edit-tstock-in-invoice');
    Route::post('/tstock_in/tstock_in_invoice/create', [App\Http\Controllers\TStockInController::class, 'store'])->name('store-tstock-in-invoice');
    Route::post('/tstock_in/tstock_in_invoice/update', [App\Http\Controllers\TStockInController::class, 'update'])->name('update-tstock-in-invoice');
    Route::post('/tstock_in/stockin/delete', [App\Http\Controllers\TStockInController::class, 'destroy'])->name('delete-tstock-in-invoice');
    Route::get('/tstock_in/attachements', [App\Http\Controllers\TStockInController::class, 'getAttachements'])->name('get-tstock-in-att');
    Route::get('/tstock_in/download/{id}', [App\Http\Controllers\TStockInController::class, 'downloadAtt'])->name('tstock-in-att-download');
    Route::delete('/tstock_in/deleteAttachment/{id}', [App\Http\Controllers\TStockInController::class, 'deleteAtt'])->name('sale1-tstock-in-delete');
    Route::get('/tstock_in/view/{id}', [App\Http\Controllers\TStockInController::class, 'view'])->name('show-tstock-in-att'); 
    // Route::get('/tstock_in/saleinvoice/view/{id}', [App\Http\Controllers\SalesController::class, 'show'])->name('show-sale-invoice');
    // Route::get('/tstock_in/saleinvoice/generatePDF/{id}', [App\Http\Controllers\SalesController::class, 'generatePDF'])->name('print-sale-invoice');
    // Route::get('/tstock_in/saleinvoice/downloadPDF/{id}', [App\Http\Controllers\SalesController::class, 'downloadPDF'])->name('download-sale-invoice');


    //tstock out
    Route::get('/tstock_out/all-tstock_out', [App\Http\Controllers\TStockOutController::class, 'index'])->name('all-tstock-out');
    Route::get('/tstock_out/new-tstock_out', [App\Http\Controllers\TStockOutController::class, 'create'])->name('create-tstock-out-invoice'); 
    Route::get('/tstock_out/edit-invoice/{id}', [App\Http\Controllers\TStockOutController::class, 'edit'])->name('edit-tstock-out-invoice');
    Route::post('/tstock_out/tstock_out_invoice/create', [App\Http\Controllers\TStockOutController::class, 'store'])->name('store-tstock-out-invoice');
    Route::post('/tstock_out/tstock_out_invoice/update', [App\Http\Controllers\TStockOutController::class, 'update'])->name('update-tstock-out-invoice');
    Route::post('/tstock_out/stockout/delete', [App\Http\Controllers\TStockOutController::class, 'destroy'])->name('delete-tstock-out-invoice');
    Route::get('/tstock_out/attachements', [App\Http\Controllers\TStockOutController::class, 'getAttachements'])->name('get-tstock-out-att');
    Route::get('/tstock_out/download/{id}', [App\Http\Controllers\TStockOutController::class, 'downloadAtt'])->name('tstock-out-att-download');
    Route::delete('/tstock_out/deleteAttachment/{id}', [App\Http\Controllers\TStockOutController::class, 'deleteAtt'])->name('tstock-out-att-delete');
    Route::get('/tstock_out/view/{id}', [App\Http\Controllers\TStockOutController::class, 'view'])->name('show-tstock-out-att');

    // Route::get('/tstock_out/saleinvoice/view/{id}', [App\Http\Controllers\TStockOutController::class, 'show'])->name('show-sale-invoice');
    // Route::get('/tstock_out/saleinvoice/generatePDF/{id}', [App\Http\Controllers\TStockOutController::class, 'generatePDF'])->name('print-sale-invoice');
    // Route::get('/tstock_out/saleinvoice/downloadPDF/{id}', [App\Http\Controllers\TStockOutController::class, 'downloadPDF'])->name('download-sale-invoice');
    Route::get('/tstock_out/getunclosed/', [App\Http\Controllers\TStockOutController::class, 'getunclosed'])->name('get-unclosed-tstock-out-invoice');
    Route::get('/tstock_out/getItems/{id}', [App\Http\Controllers\TStockOutController::class, 'getItems'])->name('get-tstock-out-items');

    //bad bads
    Route::get('/bad_dabs/all-bad-dabs', [App\Http\Controllers\BadDabsController::class, 'index'])->name('all-bad-dabs');
    Route::get('/bad_dabs/new-bad-dabs', [App\Http\Controllers\BadDabsController::class, 'create'])->name('create-bad-dabs'); 
    Route::post('/bad_dabs/create', [App\Http\Controllers\BadDabsController::class, 'store'])->name('store-bad-dabs-entry');
    Route::get('/bad_dabs/edit/{id}', [App\Http\Controllers\BadDabsController::class, 'edit'])->name('edit-bad-dabs-entry');
    Route::post('/bad_dabs/delete', [App\Http\Controllers\BadDabsController::class, 'destroy'])->name('delete-bad-dabs');
    Route::post('/bad_dabs/update', [App\Http\Controllers\BadDabsController::class, 'update'])->name('update-bad-dabs');


    //stock in
    Route::get('/stock_in/all-stock_in', [App\Http\Controllers\StockInController::class, 'index'])->name('all-stock-in');
    Route::get('/stock_in/new-stock_in', [App\Http\Controllers\StockInController::class, 'create'])->name('create-stock-in-invoice'); 
    Route::get('/stock_in/edit-invoice/{id}', [App\Http\Controllers\StockInController::class, 'edit'])->name('edit-stock-in-invoice');
    Route::post('/stock_in/stock_in_invoice/create', [App\Http\Controllers\StockInController::class, 'store'])->name('store-stock-in-invoice');
    Route::post('/stock_in/stock_in_invoice/update', [App\Http\Controllers\StockInController::class, 'update'])->name('update-stock-in-invoice');
    Route::post('/stock_in/stockin/delete', [App\Http\Controllers\StockInController::class, 'destroy'])->name('delete-stock-in-invoice');
    Route::get('/stock_in/attachements', [App\Http\Controllers\StockInController::class, 'getAttachements'])->name('get-stock-in-att');
    Route::get('/stock_in/download/{id}', [App\Http\Controllers\StockInController::class, 'downloadAtt'])->name('stock-in-att-download');
    Route::delete('/stock_in/deleteAttachment/{id}', [App\Http\Controllers\StockInController::class, 'deleteAtt'])->name('stock-in-delete-att');
    Route::get('/stock_in/viewAtt/{id}', [App\Http\Controllers\StockInController::class, 'view'])->name('show-stock-in-att'); 
    Route::get('/stock_in/view/{id}', [App\Http\Controllers\StockInController::class, 'show'])->name('show-stock-in-invoice');
    // Route::get('/stock_in/generatePDF/{id}', [App\Http\Controllers\StockInController::class, 'generatePDF'])->name('print-stock-in-invoice');
    // Route::get('/stock_in/downloadPDF/{id}', [App\Http\Controllers\StockInController::class, 'downloadPDF'])->name('download-stock-in-invoice');


    //stock out
    Route::get('/stock_out/all-stock_out', [App\Http\Controllers\StockOutController::class, 'index'])->name('all-stock-out');
    Route::get('/stock_out/new-stock_out', [App\Http\Controllers\StockOutController::class, 'create'])->name('create-stock-out-invoice'); 
    Route::get('/stock_out/edit-invoice/{id}', [App\Http\Controllers\StockOutController::class, 'edit'])->name('edit-stock-out-invoice');
    Route::post('/stock_out/stock_out_invoice/create', [App\Http\Controllers\StockOutController::class, 'store'])->name('store-stock-out-invoice');
    Route::post('/stock_out/stock_out_invoice/update', [App\Http\Controllers\StockOutController::class, 'update'])->name('update-stock-out-invoice');
    Route::post('/stock_out/stockout/delete', [App\Http\Controllers\StockOutController::class, 'destroy'])->name('delete-stock-out-invoice');
    Route::get('/stock_out/attachements', [App\Http\Controllers\StockOutController::class, 'getAttachements'])->name('get-stock-out-att');
    Route::get('/stock_out/download/{id}', [App\Http\Controllers\StockOutController::class, 'downloadAtt'])->name('stock-out-att-download');
    Route::delete('/stock_out/deleteAttachment/{id}', [App\Http\Controllers\StockOutController::class, 'deleteAtt'])->name('stock-out-att-delete');
    Route::get('/stock_out/view/{id}', [App\Http\Controllers\StockOutController::class, 'view'])->name('show-stock-out-att');
    // Route::get('/stock_out/saleinvoice/view/{id}', [App\Http\Controllers\StockOutController::class, 'show'])->name('show-sale-invoice');
    // Route::get('/stock_out/saleinvoice/generatePDF/{id}', [App\Http\Controllers\StockOutController::class, 'generatePDF'])->name('print-sale-invoice');
    // Route::get('/stock_out/saleinvoice/downloadPDF/{id}', [App\Http\Controllers\StockOutController::class, 'downloadPDF'])->name('download-sale-invoice');
    // Route::get('/stock_out/saleinvoice/downloadPDF/{id}', [App\Http\Controllers\StockOutController::class, 'downloadPDF'])->name('download-sale-invoice');



    //complains
    Route::get('/complains/all-complains', [App\Http\Controllers\ComplainsController::class, 'index'])->name('all-complains');
    Route::post('/complains/create', [App\Http\Controllers\ComplainsController::class, 'store'])->name('store-complains');
    Route::post('/complains/delete', [App\Http\Controllers\ComplainsController::class, 'destroy'])->name('delete-complains');
    Route::post('/complains/update', [App\Http\Controllers\ComplainsController::class, 'update'])->name('update-complains');
    Route::get('/complains/detail', [App\Http\Controllers\ComplainsController::class, 'getcomplainsDetails'])->name('get-complains-details');
    Route::get('/complains/attachements', [App\Http\Controllers\ComplainsController::class, 'getAttachements'])->name('get-complains-att');
    Route::post('/complains/downloadAll', [App\Http\Controllers\ComplainsController::class, 'downloadAllAtt'])->name('complains-att-download-all');
    Route::get('/complains/print/{id}', [App\Http\Controllers\ComplainsController::class, 'print'])->name('print-complains');
    Route::get('/complains/download/{id}', [App\Http\Controllers\ComplainsController::class, 'downloadAtt'])->name('complains-att-download');
    Route::get('/complains/view/{id}', [App\Http\Controllers\ComplainsController::class, 'view'])->name('complains-att-view');
    Route::delete('/complains/deleteAttachment/{id}', [App\Http\Controllers\ComplainsController::class, 'deleteAtt'])->name('complains-att-delete');



    //tquotation
    Route::get('/tquotation/all-tquotation', [App\Http\Controllers\TQuotationController::class, 'index'])->name('all-tquotation');
    Route::get('/tquotation/new', [App\Http\Controllers\TQuotationController::class, 'create'])->name('new-tquotation');
    Route::post('/tquotation/create', [App\Http\Controllers\TQuotationController::class, 'store'])->name('store-tquotation');
    Route::get('/tquotation/edit/{id}', [App\Http\Controllers\TQuotationController::class, 'edit'])->name('edit-tquotation');
    Route::post('/tquotation/update', [App\Http\Controllers\TQuotationController::class, 'update'])->name('update-tquotation');
    Route::post('/tquotation/delete', [App\Http\Controllers\TQuotationController::class, 'destroy'])->name('delete-tquotation');
    Route::get('/tquotation/show/{id}', [App\Http\Controllers\TQuotationController::class, 'show'])->name('show-tquotation');
    Route::get('/tquotation/view/{id}', [App\Http\Controllers\TQuotationController::class, 'view'])->name('show-tquotation-att');
    Route::get('/tquotation/attachements', [App\Http\Controllers\TQuotationController::class, 'getAttachements'])->name('get-tquotation-att');
    Route::get('/tquotation/download/{id}', [App\Http\Controllers\TQuotationController::class, 'downloadAtt'])->name('tquotation-att-download');
    Route::delete('/tquotation/deleteAttachment/{id}', [App\Http\Controllers\TQuotationController::class, 'deleteAtt'])->name('tquotation-att-delete');
    Route::get('/tquotation/generatePDF/{id}', [App\Http\Controllers\TQuotationController::class, 'generatePDF'])->name('print-tquotation-invoice');
    Route::get('/tquotation/getunclosed/', [App\Http\Controllers\TQuotationController::class, 'getunclosed'])->name('get-unclosed-tquotation-invoice');
    Route::get('/tquotation/getItems/{id}', [App\Http\Controllers\TQuotationController::class, 'getItems'])->name('get-quot2-items');
    Route::get('/tquotation/getavailablestock/{id}', [App\Http\Controllers\TQuotationController::class, 'getavailablestock'])->name('qout-item-stock-bal');



    //quotation
    Route::get('/quotation/all-quotation', [App\Http\Controllers\QuotationController::class, 'index'])->name('all-quotation');
    Route::get('/quotation/new-quotation', [App\Http\Controllers\QuotationController::class, 'create'])->name('create-quotation-invoice'); 
    Route::get('/quotation/edit-invoice/{id}', [App\Http\Controllers\QuotationController::class, 'edit'])->name('edit-quotation-invoice');
    Route::post('/quotation/quotationinvoice/create', [App\Http\Controllers\QuotationController::class, 'store'])->name('store-quotation-invoice');
    Route::post('/quotation/quotationinvoice/update', [App\Http\Controllers\QuotationController::class, 'update'])->name('update-quotation-invoice');
    Route::post('/quotation/quotationinvoice/delete', [App\Http\Controllers\QuotationController::class, 'destroy'])->name('delete-quotation-invoice');
    Route::get('/quotation/quotationinvoice/view/{id}', [App\Http\Controllers\QuotationController::class, 'showNew'])->name('show-quotation-invoice');
    Route::get('/quotation/quotationinvoice/generatePDF/{id}', [App\Http\Controllers\QuotationController::class, 'generatePDF'])->name('print-quotation-invoice');
    Route::get('/quotation/quotationinvoice/downloadPDF/{id}', [App\Http\Controllers\QuotationController::class, 'downloadPDF'])->name('download-quotation-invoice');
    Route::get('/quotation/attachements', [App\Http\Controllers\QuotationController::class, 'getAttachements'])->name('get-quotation-att');
    Route::get('/quotation/download/{id}', [App\Http\Controllers\QuotationController::class, 'downloadAtt'])->name('quotation-att-download');
    Route::delete('/quotation/deleteAttachment/{id}', [App\Http\Controllers\QuotationController::class, 'deleteAtt'])->name('quotation-att-delete');
    Route::get('/quotation/view/{id}', [App\Http\Controllers\QuotationController::class, 'view'])->name('show-quotation-att');


    //tpo
    Route::get('/tpo/all-tpo', [App\Http\Controllers\TpoController::class, 'index'])->name('all-tpo');
    Route::get('/tpo/new', [App\Http\Controllers\TpoController::class, 'create'])->name('new-tpo');
    Route::post('/tpo/create', [App\Http\Controllers\TpoController::class, 'store'])->name('store-tpo');
    Route::get('/tpo/edit/{id}', [App\Http\Controllers\TpoController::class, 'edit'])->name('edit-tpo');
    Route::post('/tpo/update', [App\Http\Controllers\TpoController::class, 'update'])->name('update-tpo');
    Route::post('/tpo/delete', [App\Http\Controllers\TpoController::class, 'destroy'])->name('delete-tpo');
    Route::get('/tpo/show/{id}', [App\Http\Controllers\TpoController::class, 'show'])->name('show-tpo');
    Route::get('/tpo/view/{id}', [App\Http\Controllers\TpoController::class, 'view'])->name('show-tpo-att');
    Route::get('/tpo/attachements', [App\Http\Controllers\TpoController::class, 'getAttachements'])->name('get-tpo-att');
    Route::get('/tpo/download/{id}', [App\Http\Controllers\TpoController::class, 'downloadAtt'])->name('tpo-att-download');
    Route::delete('/tpo/deleteAttachment/{id}', [App\Http\Controllers\TpoController::class, 'deleteAtt'])->name('tpo-att-delete');
    Route::get('/tpo/generatePDF/{id}', [App\Http\Controllers\TpoController::class, 'generatePDF'])->name('print-tpo-invoice');
    Route::get('/tpo/getunclosed/', [App\Http\Controllers\TpoController::class, 'getunclosed'])->name('get-unclosed-tpo-invoice');
    Route::get('/tpo/getItems/{id}', [App\Http\Controllers\TpoController::class, 'getItems'])->name('get-tpo-items');
    Route::get('/tpo/getavailablestock/{id}', [App\Http\Controllers\TpoController::class, 'getavailablestock'])->name('tpo-item-stock-bal');


    //po
    Route::get('/po/all-po1', [App\Http\Controllers\PoController::class, 'index'])->name('all-po');
    Route::get('/po/new', [App\Http\Controllers\PoController::class, 'create'])->name('new-po');
    Route::post('/po/create', [App\Http\Controllers\PoController::class, 'store'])->name('store-po');
    Route::get('/po/edit/{id}', [App\Http\Controllers\PoController::class, 'edit'])->name('edit-po');
    Route::post('/po/update', [App\Http\Controllers\PoController::class, 'update'])->name('update-po');
    Route::post('/po/delete', [App\Http\Controllers\PoController::class, 'destroy'])->name('delete-po');
    Route::get('/po/show/{id}', [App\Http\Controllers\PoController::class, 'show'])->name('show-po');
    Route::get('/po/view/{id}', [App\Http\Controllers\PoController::class, 'view'])->name('show-po-att');
    Route::get('/po/attachements', [App\Http\Controllers\PoController::class, 'getAttachements'])->name('get-po-att');
    Route::get('/po/download/{id}', [App\Http\Controllers\PoController::class, 'downloadAtt'])->name('po-att-download');
    Route::delete('/po/deleteAttachment/{id}', [App\Http\Controllers\PoController::class, 'deleteAtt'])->name('po-att-delete');
    Route::get('/po/generatePDF/{id}', [App\Http\Controllers\PoController::class, 'generatePDF'])->name('print-po-invoice');


});
