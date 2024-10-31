<?php

    use Illuminate\Support\Facades\Route;

    Route::get('/', [App\Http\Controllers\UsersController::class, 'loginScreen'])->name('login');
    Route::get('/login', [App\Http\Controllers\UsersController::class, 'loginScreen']);
    Route::post('/login', [App\Http\Controllers\UsersController::class, 'login'])->name('userlogin');

    Route::middleware(['checkPermission:view'])->group(function () {

        Route::get('/user/all-users', [App\Http\Controllers\UsersController::class, 'index'])->name('all-users');
        Route::get('/user-role/all-roles', [App\Http\Controllers\UserRoleController::class, 'index'])->name('all-roles');
        Route::get('/item-groups/all-groups', [App\Http\Controllers\ItemGroupsController::class, 'index'])->name('all-item-groups');
        Route::get('/items/all-items', [App\Http\Controllers\ItemsController::class, 'index'])->name('all-items');
        Route::get('/item2/all-items', [App\Http\Controllers\Item2Controller::class, 'index'])->name('all-items-2');
        Route::get('/coa/all-acc', [App\Http\Controllers\COAController::class, 'index'])->name('all-acc');
        Route::get('/coa-groups/all-coa-groups', [App\Http\Controllers\COAGroupsController::class, 'index'])->name('all-acc-groups');
        Route::get('/coa-sub-heads/all-coa-sub-heads', [App\Http\Controllers\COASubHeadsController::class, 'index'])->name('all-acc-sub-heads-groups');
        Route::get('/vouchers/all-jv1', [App\Http\Controllers\JV1Controller::class, 'index'])->name('all-jv1');
        Route::get('/vouchers2/all-jv2', [App\Http\Controllers\JV2Controller::class, 'index'])->name('all-jv2');
        Route::get('/purchase1/all-purchases', [App\Http\Controllers\PurchaseController::class, 'index'])->name('all-purchases1');
        Route::get('/purchase2/all-purchases', [App\Http\Controllers\Purchase2Controller::class, 'index'])->name('all-purchases2');
        Route::get('/sales/all-invoices', [App\Http\Controllers\SalesController::class, 'index'])->name('all-saleinvoices');
        Route::get('/sales2/all-invoices', [App\Http\Controllers\Sales2Controller::class, 'index'])->name('all-sale2invoices');
        Route::get('/tbad_dabs/all-tbad-dabs', [App\Http\Controllers\TBadDabsController::class, 'index'])->name('all-tbad-dabs');
        Route::get('/tstock_in/all-tstock_in', [App\Http\Controllers\TStockInController::class, 'index'])->name('all-tstock-in');
        Route::get('/tstock_out/all-tstock_out', [App\Http\Controllers\TStockOutController::class, 'index'])->name('all-tstock-out');
        Route::get('/bad_dabs/all-bad-dabs', [App\Http\Controllers\BadDabsController::class, 'index'])->name('all-bad-dabs');
        Route::get('/stock_in/all-stock_in', [App\Http\Controllers\StockInController::class, 'index'])->name('all-stock-in');
        Route::get('/stock_out/all-stock_out', [App\Http\Controllers\StockOutController::class, 'index'])->name('all-stock-out');
        Route::get('/complains/all-complains', [App\Http\Controllers\ComplainsController::class, 'index'])->name('all-complains');
        Route::get('/po/all-po1', [App\Http\Controllers\PoController::class, 'index'])->name('all-po');
        Route::get('/tpo/all-tpo', [App\Http\Controllers\TpoController::class, 'index'])->name('all-tpo');
        Route::get('/quotation/all-quotation', [App\Http\Controllers\QuotationController::class, 'index'])->name('all-quotation');
        Route::get('/tquotation/all-tquotation', [App\Http\Controllers\TQuotationController::class, 'index'])->name('all-tquotation');
        Route::get('/weight/all-weight', [App\Http\Controllers\WeightController::class, 'index'])->name('all-weight');
        Route::get('/po/show/{id}', [App\Http\Controllers\PoController::class, 'show'])->name('show-po');
        Route::get('/purchase2/show/{id}', [App\Http\Controllers\Purchase2Controller::class, 'show'])->name('show-purchases2');
        Route::get('/sales/saleinvoice/view/{id}', [App\Http\Controllers\SalesController::class, 'showNew'])->name('show-sale-invoice');
        Route::get('/sales2/show/{id}', [App\Http\Controllers\Sales2Controller::class, 'show'])->name('show-sales2');
        Route::get('/quotation/quotationinvoice/view/{id}', [App\Http\Controllers\QuotationController::class, 'showNew'])->name('show-quotation-invoice');
        Route::get('/tpo/show/{id}', [App\Http\Controllers\TpoController::class, 'show'])->name('show-tpo');
        Route::get('/stock_in/view/{id}', [App\Http\Controllers\StockInController::class, 'show'])->name('show-stock-in-invoice');
        Route::get('/tquotation/show/{id}', [App\Http\Controllers\TQuotationController::class, 'show'])->name('show-tquotation');
        Route::get('/weight/show/{id}', [App\Http\Controllers\WeightController::class, 'show'])->name('show-weight');
        Route::get('/purchase1/show/{id}', [App\Http\Controllers\PurchaseController::class, 'show'])->name('show-purchases1');
        Route::get('/vouchers/show/{id}', [App\Http\Controllers\JV1Controller::class, 'show'])->name('show-jv1');
        Route::get('/tbad_dabs/show/{id}', [App\Http\Controllers\TBadDabsController::class, 'show'])->name('show-tbad-dabs');
        Route::get('/tstock_in/tstock_in_invoice/view/{id}', [App\Http\Controllers\TStockInController::class, 'show'])->name('show-tstock-in-invoice');
        Route::get('/tstock_out/tstock_out/view/{id}', [App\Http\Controllers\TStockOutController::class, 'show'])->name('show-tstock-out-invoice');
        Route::get('/bad_dabs/show/{id}', [App\Http\Controllers\BadDabsController::class, 'show'])->name('show-bad-dabs');
        Route::get('/stock_out/stock_out_invoice/view/{id}', [App\Http\Controllers\StockOutController::class, 'show'])->name('show-stock-out-invoice');
        Route::get('/coa/download/{id}', [App\Http\Controllers\COAController::class, 'downloadAtt'])->name('coa-att-download');
        Route::get('/coa/view/{id}', [App\Http\Controllers\COAController::class, 'view'])->name('coa-att-view');
        Route::get('/vouchers/download/{id}', [App\Http\Controllers\JV1Controller::class, 'downloadAtt'])->name('jv1-att-download');
        Route::get('/vouchers/view/{id}', [App\Http\Controllers\JV1Controller::class, 'view'])->name('jv1-att-view');
        Route::get('/vouchers2/download/{id}', [App\Http\Controllers\JV2Controller::class, 'downloadAtt'])->name('jv2-att-download');
        Route::get('/vouchers2/view/{id}', [App\Http\Controllers\JV2Controller::class, 'view'])->name('jv2-att-view');
        Route::get('/purchase1/view/{id}', [App\Http\Controllers\PurchaseController::class, 'view'])->name('show-purchases1-att');
        Route::get('/purchase1/download/{id}', [App\Http\Controllers\PurchaseController::class, 'downloadAtt'])->name('purc1-att-download');
        Route::get('/purchase2/view/{id}', [App\Http\Controllers\Purchase2Controller::class, 'view'])->name('show-purchases2-att');
        Route::get('/purchase2/download/{id}', [App\Http\Controllers\Purchase2Controller::class, 'downloadAtt'])->name('purc2-att-download');
        Route::get('/sales2/view/{id}', [App\Http\Controllers\Sales2Controller::class, 'view'])->name('show-sales2-att');
        Route::get('/sales2/download/{id}', [App\Http\Controllers\Sales2Controller::class, 'downloadAtt'])->name('sales2-att-download');
        Route::get('/sales/download/{id}', [App\Http\Controllers\SalesController::class, 'downloadAtt'])->name('sale1-att-download');
        Route::get('/sales/view/{id}', [App\Http\Controllers\SalesController::class, 'view'])->name('show-sale1-att');
        Route::get('/po/view/{id}', [App\Http\Controllers\PoController::class, 'view'])->name('show-po-att');
        Route::get('/po/download/{id}', [App\Http\Controllers\PoController::class, 'downloadAtt'])->name('po-att-download');
        Route::get('/tpo/view/{id}', [App\Http\Controllers\TpoController::class, 'view'])->name('show-tpo-att');
        Route::get('/tpo/download/{id}', [App\Http\Controllers\TpoController::class, 'downloadAtt'])->name('tpo-att-download');
        Route::get('/quotation/download/{id}', [App\Http\Controllers\QuotationController::class, 'downloadAtt'])->name('quotation-att-download');
        Route::get('/quotation/view/{id}', [App\Http\Controllers\QuotationController::class, 'view'])->name('show-quotation-att');
        Route::get('/tstock_in/download/{id}', [App\Http\Controllers\TStockInController::class, 'downloadAtt'])->name('tstock-in-att-download');
        Route::get('/tstock_in/view/{id}', [App\Http\Controllers\TStockInController::class, 'view'])->name('show-tstock-in-att'); 
        Route::get('/tstock_out/download/{id}', [App\Http\Controllers\TStockOutController::class, 'downloadAtt'])->name('tstock-out-att-download');
        Route::get('/tstock_out/view/{id}', [App\Http\Controllers\TStockOutController::class, 'view'])->name('show-tstock-out-att');
        Route::get('/stock_in/download/{id}', [App\Http\Controllers\StockInController::class, 'downloadAtt'])->name('stock-in-att-download');
        Route::get('/stock_in/viewAtt/{id}', [App\Http\Controllers\StockInController::class, 'view'])->name('show-stock-in-att'); 
        Route::get('/stock_out/download/{id}', [App\Http\Controllers\StockOutController::class, 'downloadAtt'])->name('stock-out-att-download');
        Route::get('/stock_out/view/{id}', [App\Http\Controllers\StockOutController::class, 'view'])->name('show-stock-out-att');
        Route::get('/tquotation/view/{id}', [App\Http\Controllers\TQuotationController::class, 'view'])->name('show-tquotation-att');
        Route::get('/weight/view/{id}', [App\Http\Controllers\WeightController::class, 'view'])->name('show-weight-att');
        Route::get('/tquotation/download/{id}', [App\Http\Controllers\TQuotationController::class, 'downloadAtt'])->name('tquotation-att-download');
        Route::get('/weight/download/{id}', [App\Http\Controllers\WeightController::class, 'downloadAtt'])->name('weight-att-download');
        Route::post('/complains/downloadAll', [App\Http\Controllers\ComplainsController::class, 'downloadAllAtt'])->name('complains-att-download-all');
        Route::get('/complains/download/{id}', [App\Http\Controllers\ComplainsController::class, 'downloadAtt'])->name('complains-att-download');
        Route::get('/complains/view/{id}', [App\Http\Controllers\ComplainsController::class, 'view'])->name('complains-att-view');

    });

    Route::middleware(['checkPermission:add'])->group(function () {

        Route::post('/user/create', [App\Http\Controllers\UsersController::class, 'createUser'])->name('new-user');
        Route::post('/user/create/validate', [App\Http\Controllers\UsersController::class, 'createValidation'])->name('new-user-validation');
        Route::post('/user/device/create', [App\Http\Controllers\UsersController::class, 'addDevice'])->name('new-user-device');
        Route::get('/user-role/new', [App\Http\Controllers\UserRoleController::class, 'create'])->name('new-role');
        Route::post('/user-role/create', [App\Http\Controllers\UserRoleController::class, 'store'])->name('create-role');
        Route::post('/item-groups/create', [App\Http\Controllers\ItemGroupsController::class, 'store'])->name('store-item-group');
        Route::get('/items/new', [App\Http\Controllers\ItemsController::class, 'create'])->name('create-item'); 
        Route::post('/items/create', [App\Http\Controllers\ItemsController::class, 'store'])->name('store-item');
        Route::post('/coa/create', [App\Http\Controllers\COAController::class, 'store'])->name('store-acc');
        Route::post('/item2/create', [App\Http\Controllers\Item2Controller::class, 'store'])->name('store-item-2');
        Route::post('/vouchers/create', [App\Http\Controllers\JV1Controller::class, 'store'])->name('store-jv1');
        Route::post('/sales/create', [App\Http\Controllers\SalesController::class, 'store'])->name('store-sale-invoice');
        Route::get('/vouchers2/new', [App\Http\Controllers\JV2Controller::class, 'create'])->name('new-jv2');
        Route::post('/vouchers2/create', [App\Http\Controllers\JV2Controller::class, 'store'])->name('store-jv2');
        Route::get('/purchase2/new', [App\Http\Controllers\Purchase2Controller::class, 'create'])->name('new-purchases2');
        Route::post('/purchase2/create', [App\Http\Controllers\Purchase2Controller::class, 'store'])->name('store-purchases2');
        Route::get('/tbad_dabs/new', [App\Http\Controllers\TBadDabsController::class, 'create'])->name('create-tbad-dabs'); 
        Route::post('/tbad_dabs/create', [App\Http\Controllers\TBadDabsController::class, 'store'])->name('store-tbad-dabs-entry');
        Route::get('/po/new', [App\Http\Controllers\PoController::class, 'create'])->name('new-po');
        Route::post('/po/create', [App\Http\Controllers\PoController::class, 'store'])->name('store-po');
        Route::get('/tpo/new', [App\Http\Controllers\TpoController::class, 'create'])->name('new-tpo');
        Route::post('/tpo/create', [App\Http\Controllers\TpoController::class, 'store'])->name('store-tpo');
        Route::get('/quotation/new', [App\Http\Controllers\QuotationController::class, 'create'])->name('create-quotation-invoice'); 
        Route::post('/quotation/create', [App\Http\Controllers\QuotationController::class, 'store'])->name('store-quotation-invoice');
        Route::post('/coa-sub-heads/create', [App\Http\Controllers\COASubHeadsController::class, 'store'])->name('store-acc-sub-heads-groups');
        Route::post('/coa-groups/create', [App\Http\Controllers\COAGroupsController::class, 'store'])->name('store-acc-groups');
        Route::get('/sales/new', [App\Http\Controllers\SalesController::class, 'create'])->name('create-sale-invoice'); 
        Route::get('/stock_out/new', [App\Http\Controllers\StockOutController::class, 'create'])->name('create-stock-out-invoice'); 
        Route::post('/stock_out/create', [App\Http\Controllers\StockOutController::class, 'store'])->name('store-stock-out-invoice');
        Route::get('/item2/new', [App\Http\Controllers\Item2Controller::class, 'create'])->name('create-item-2'); 
        Route::get('/stock_in/new', [App\Http\Controllers\StockInController::class, 'create'])->name('create-stock-in-invoice'); 
        Route::get('/purchase1/new', [App\Http\Controllers\PurchaseController::class, 'create'])->name('new-purchases1');
        Route::post('/purchase1/create', [App\Http\Controllers\PurchaseController::class, 'store'])->name('store-purchases1');
        Route::get('/tquotation/new', [App\Http\Controllers\TQuotationController::class, 'create'])->name('new-tquotation');
        Route::post('/tquotation/create', [App\Http\Controllers\TQuotationController::class, 'store'])->name('store-tquotation');
        Route::get('/weight/new', [App\Http\Controllers\WeightController::class, 'create'])->name('new-weight');
        Route::post('/weight/create', [App\Http\Controllers\WeightController::class, 'store'])->name('store-weight');
        Route::post('/complains/create', [App\Http\Controllers\ComplainsController::class, 'store'])->name('store-complains');
        Route::post('/stock_in/create', [App\Http\Controllers\StockInController::class, 'store'])->name('store-stock-in-invoice');
        Route::get('/bad_dabs/new', [App\Http\Controllers\BadDabsController::class, 'create'])->name('create-bad-dabs'); 
        Route::post('/bad_dabs/create', [App\Http\Controllers\BadDabsController::class, 'store'])->name('store-bad-dabs-entry');
        Route::get('/sales2/new', [App\Http\Controllers\Sales2Controller::class, 'create'])->name('new-sales2');
        Route::post('/sales2/create', [App\Http\Controllers\Sales2Controller::class, 'store'])->name('store-sales2');
        Route::get('/tstock_out/new', [App\Http\Controllers\TStockOutController::class, 'create'])->name('create-tstock-out-invoice'); 
        Route::post('/tstock_out/create', [App\Http\Controllers\TStockOutController::class, 'store'])->name('store-tstock-out-invoice');
        Route::get('/tstock_in/new', [App\Http\Controllers\TStockInController::class, 'create'])->name('create-tstock-in-invoice'); 
        Route::post('/tstock_in/create', [App\Http\Controllers\TStockInController::class, 'store'])->name('store-tstock-in-invoice');
        Route::get('/vouchers2/pendingInvoice/{id}', [App\Http\Controllers\JV2Controller::class, 'pendingInvoice'])->name('jv2-pend-invoices');
        Route::get('/vouchers2/purpendingInvoice/{id}', [App\Http\Controllers\JV2Controller::class, 'purpendingInvoice'])->name('jv2-pur-pend-invoices');
    });

    Route::middleware(['checkPermission:edit'])->group(function () {

        Route::get('/user/details', [App\Http\Controllers\UsersController::class, 'getUserDetails'])->name('user-details');
        Route::post('/user/change-credentials', [App\Http\Controllers\UsersController::class, 'changeCredentials'])->name('change-user-credentials');
        Route::post('/user/add-device', [App\Http\Controllers\UsersController::class, 'addDevice'])->name('add-user-device');
        Route::get('/user/mac_address', [App\Http\Controllers\UsersController::class, 'getMacAdd'])->name('user-mac-address');
        Route::post('/user/update', [App\Http\Controllers\UsersController::class, 'updateUser'])->name('update-user');
        Route::post('/user/deactivate', [App\Http\Controllers\UsersController::class, 'deactivateUser'])->name('deactivate-user');
        Route::post('/user/activate', [App\Http\Controllers\UsersController::class, 'activateUser'])->name('activate-user');
        Route::get('/user-role/edit/{id}', [App\Http\Controllers\UserRoleController::class, 'edit'])->name('edit-role');
        Route::post('/user-role/update', [App\Http\Controllers\UserRoleController::class, 'update'])->name('update-role');
        Route::get('/po/edit/{id}', [App\Http\Controllers\PoController::class, 'edit'])->name('edit-po');
        Route::post('/po/update', [App\Http\Controllers\PoController::class, 'update'])->name('update-po');
        Route::get('/tpo/edit/{id}', [App\Http\Controllers\TpoController::class, 'edit'])->name('edit-tpo');
        Route::post('/tpo/update', [App\Http\Controllers\TpoController::class, 'update'])->name('update-tpo');
        Route::get('/quotation/edit/{id}', [App\Http\Controllers\QuotationController::class, 'edit'])->name('edit-quotation-invoice');
        Route::post('/quotation/update', [App\Http\Controllers\QuotationController::class, 'update'])->name('update-quotation-invoice');
        Route::get('/tquotation/edit/{id}', [App\Http\Controllers\TQuotationController::class, 'edit'])->name('edit-tquotation');
        Route::post('/tquotation/update', [App\Http\Controllers\TQuotationController::class, 'update'])->name('update-tquotation');
        Route::get('/weight/edit/{id}', [App\Http\Controllers\WeightController::class, 'edit'])->name('edit-weight');
        Route::post('/weight/update', [App\Http\Controllers\WeightController::class, 'update'])->name('update-weight');
        Route::post('/complains/update', [App\Http\Controllers\ComplainsController::class, 'update'])->name('update-complains');
        Route::post('/stock_out/update', [App\Http\Controllers\StockOutController::class, 'update'])->name('update-stock-out-invoice');
        Route::get('/tstock_out/edit/{id}', [App\Http\Controllers\TStockOutController::class, 'edit'])->name('edit-tstock-out-invoice');
        Route::get('/stock_out/edit/{id}', [App\Http\Controllers\StockOutController::class, 'edit'])->name('edit-stock-out-invoice');
        Route::post('/item-groups/update', [App\Http\Controllers\ItemGroupsController::class, 'update'])->name('update-item-group');
        Route::post('/tstock_out/update', [App\Http\Controllers\TStockOutController::class, 'update'])->name('update-tstock-out-invoice');
        Route::post('/items/update', [App\Http\Controllers\ItemsController::class, 'update'])->name('update-item');
        Route::post('/item2/update', [App\Http\Controllers\Item2Controller::class, 'update'])->name('update-item-2');
        Route::post('/coa/update', [App\Http\Controllers\COAController::class, 'update'])->name('update-acc');
        Route::post('/coa-groups/update', [App\Http\Controllers\COAGroupsController::class, 'update'])->name('update-acc-groups');
        Route::post('/coa-sub-heads/update', [App\Http\Controllers\COASubHeadsController::class, 'update'])->name('update-acc-sub-heads-groups');
        Route::post('/vouchers/update', [App\Http\Controllers\JV1Controller::class, 'update'])->name('update-jv1');
        Route::get('/vouchers2/edit/{id}', [App\Http\Controllers\JV2Controller::class, 'edit'])->name('edit-jv2');
        Route::post('/vouchers2/update', [App\Http\Controllers\JV2Controller::class, 'update'])->name('update-jv2');
        Route::get('/vouchers2/active_sales_ageing/{id}', [App\Http\Controllers\JV2Controller::class, 'activeSalesAgeing'])->name('active-sales-ageing');
        Route::get('/vouchers2/deactive_sales_ageing/{id}', [App\Http\Controllers\JV2Controller::class, 'deactiveSalesAgeing'])->name('deactive-sales-ageing');
        Route::get('/vouchers2/active_pur_ageing/{id}', [App\Http\Controllers\JV2Controller::class, 'activePurAgeing'])->name('active-pur-ageing');
        Route::get('/vouchers2/deactive_pur_ageing/{id}', [App\Http\Controllers\JV2Controller::class, 'deactivePurAgeing'])->name('deactive-pur-ageing');
        Route::get('/purchase1/edit/{id}', [App\Http\Controllers\PurchaseController::class, 'edit'])->name('edit-purchases1');
        Route::post('/purchase1/update', [App\Http\Controllers\PurchaseController::class, 'update'])->name('update-purchases1');
        Route::get('/purchase2/edit/{id}', [App\Http\Controllers\Purchase2Controller::class, 'edit'])->name('edit-purchases2');
        Route::post('/purchase2/update', [App\Http\Controllers\Purchase2Controller::class, 'update'])->name('update-purchases2');
        Route::post('/stock_in/update', [App\Http\Controllers\StockInController::class, 'update'])->name('update-stock-in-invoice');
        Route::post('/bad_dabs/update', [App\Http\Controllers\BadDabsController::class, 'update'])->name('update-bad-dabs');
        Route::get('/stock_in/{id}', [App\Http\Controllers\StockInController::class, 'edit'])->name('edit-stock-in-invoice');
        Route::get('/bad_dabs/edit/{id}', [App\Http\Controllers\BadDabsController::class, 'edit'])->name('edit-bad-dabs-entry');
        Route::get('/sales/edit/{id}', [App\Http\Controllers\SalesController::class, 'edit'])->name('edit-sale-invoice');
        Route::post('/sales/update', [App\Http\Controllers\SalesController::class, 'update'])->name('update-sale-invoice');
        Route::post('/tbad_dabs/update', [App\Http\Controllers\TBadDabsController::class, 'update'])->name('update-tbad-dabs');
        Route::get('/coa/activate/{id}', [App\Http\Controllers\COAController::class, 'activate'])->name('activate-acc');
        Route::get('/vouchers2/pendingInvoice/{id}', [App\Http\Controllers\JV2Controller::class, 'pendingInvoice'])->name('jv2-pend-invoices');
        Route::get('/vouchers2/purpendingInvoice/{id}', [App\Http\Controllers\JV2Controller::class, 'purpendingInvoice'])->name('jv2-pur-pend-invoices');
        Route::get('/sales2/edit/{id}', [App\Http\Controllers\Sales2Controller::class, 'edit'])->name('edit-sales2');
        Route::post('/sales2/update', [App\Http\Controllers\Sales2Controller::class, 'update'])->name('update-sales2');
        Route::get('/tbad_dabs/edit/{id}', [App\Http\Controllers\TBadDabsController::class, 'edit'])->name('edit-tbad-dabs-entry');
        Route::get('/tstock_in/edit/{id}', [App\Http\Controllers\TStockInController::class, 'edit'])->name('edit-tstock-in-invoice');
        Route::post('/tstock_in/tstock_in_invoice/update', [App\Http\Controllers\TStockInController::class, 'update'])->name('update-tstock-in-invoice');
    });

    Route::middleware(['checkPermission:delete'])->group(function () {
        Route::post('/item-groups/delete', [App\Http\Controllers\ItemGroupsController::class, 'destroy'])->name('delete-item-group');
        Route::post('/items/delete', [App\Http\Controllers\ItemsController::class, 'destroy'])->name('delete-item');
        Route::post('/item2/delete', [App\Http\Controllers\Item2Controller::class, 'destroy'])->name('delete-item-2');
        Route::post('/coa-groups/delete', [App\Http\Controllers\COAGroupsController::class, 'destroy'])->name('delete-acc-groups');
        Route::post('/vouchers/delete', [App\Http\Controllers\JV1Controller::class, 'destroy'])->name('delete-jv1');
        Route::post('/po/delete', [App\Http\Controllers\PoController::class, 'destroy'])->name('delete-po');
        Route::post('/tpo/delete', [App\Http\Controllers\TpoController::class, 'destroy'])->name('delete-tpo');
        Route::post('/quotation/delete', [App\Http\Controllers\QuotationController::class, 'destroy'])->name('delete-quotation-invoice');
        Route::post('/tquotation/delete', [App\Http\Controllers\TQuotationController::class, 'destroy'])->name('delete-tquotation');
        Route::post('/weight/delete', [App\Http\Controllers\WeightController::class, 'destroy'])->name('delete-weight');
        Route::post('/purchase1/delete', [App\Http\Controllers\PurchaseController::class, 'destroy'])->name('delete-purchases1');
        Route::post('/coa/delete', [App\Http\Controllers\COAController::class, 'destroy'])->name('delete-acc');
        Route::post('/coa-sub-heads/delete', [App\Http\Controllers\COASubHeadsController::class, 'destroy'])->name('delete-acc-sub-heads-groups');
        Route::post('/vouchers2/delete', [App\Http\Controllers\JV2Controller::class, 'destroy'])->name('delete-jv2');
        Route::post('/purchase2/delete', [App\Http\Controllers\Purchase2Controller::class, 'destroy'])->name('delete-purchases2');
        Route::post('/stock_out/delete', [App\Http\Controllers\StockOutController::class, 'destroy'])->name('delete-stock-out-invoice');
        Route::post('/complains/delete', [App\Http\Controllers\ComplainsController::class, 'destroy'])->name('delete-complains');
        Route::post('/stock_in/delete', [App\Http\Controllers\StockInController::class, 'destroy'])->name('delete-stock-in-invoice');
        Route::post('/bad_dabs/delete', [App\Http\Controllers\BadDabsController::class, 'destroy'])->name('delete-bad-dabs');
        Route::post('/sales/delete', [App\Http\Controllers\SalesController::class, 'destroy'])->name('delete-sale-invoice');
        Route::post('/tbad_dabs/delete', [App\Http\Controllers\TBadDabsController::class, 'destroy'])->name('delete-tbad-dabs');
        Route::post('/sales2/delete', [App\Http\Controllers\Sales2Controller::class, 'destroy'])->name('delete-sales2');
        Route::post('/tstock_in/delete', [App\Http\Controllers\TStockInController::class, 'destroy'])->name('delete-tstock-in-invoice');
        Route::post('/tstock_out/delete', [App\Http\Controllers\TStockOutController::class, 'destroy'])->name('delete-tstock-out-invoice');
    });

    Route::middleware(['checkPermission:att_add'])->group(function () {
        Route::post('/coa/addAtt/', [App\Http\Controllers\COAController::class, 'addAtt'])->name('coa-att-add');
        Route::post('/vouchers/addAtt/', [App\Http\Controllers\JV1Controller::class, 'addAtt'])->name('jv1-att-add');

    });

    Route::middleware(['checkPermission:att_delete'])->group(function () {
        Route::delete('/coa/deleteAtt/{id}', [App\Http\Controllers\COAController::class, 'deleteAtt'])->name('coa-att-delete');
        Route::delete('/vouchers/deleteAttachment/{id}', [App\Http\Controllers\JV1Controller::class, 'deleteAtt'])->name('jv1-att-delete');
        Route::delete('/purchase1/deleteAttachment/{id}', [App\Http\Controllers\PurchaseController::class, 'deleteAtt'])->name('purc1-att-delete');
        Route::delete('/complains/deleteAttachment/{id}', [App\Http\Controllers\ComplainsController::class, 'deleteAtt'])->name('complains-att-delete');
        Route::delete('/tquotation/deleteAttachment/{id}', [App\Http\Controllers\TQuotationController::class, 'deleteAtt'])->name('tquotation-att-delete');
        Route::delete('/weight/deleteAttachment/{id}', [App\Http\Controllers\WeightController::class, 'deleteAtt'])->name('weight-att-delete');
        Route::delete('/stock_out/deleteAttachment/{id}', [App\Http\Controllers\StockOutController::class, 'deleteAtt'])->name('stock-out-att-delete');
        Route::delete('/stock_in/deleteAttachment/{id}', [App\Http\Controllers\StockInController::class, 'deleteAtt'])->name('stock-in-delete-att');
        Route::delete('/tstock_out/deleteAttachment/{id}', [App\Http\Controllers\TStockOutController::class, 'deleteAtt'])->name('tstock-out-att-delete');
        Route::delete('/tstock_in/deleteAttachment/{id}', [App\Http\Controllers\TStockInController::class, 'deleteAtt'])->name('sale1-tstock-in-delete');
        Route::delete('/quotation/deleteAttachment/{id}', [App\Http\Controllers\QuotationController::class, 'deleteAtt'])->name('quotation-att-delete');
        Route::delete('/tpo/deleteAttachment/{id}', [App\Http\Controllers\TpoController::class, 'deleteAtt'])->name('tpo-att-delete');
        Route::delete('/po/deleteAttachment/{id}', [App\Http\Controllers\PoController::class, 'deleteAtt'])->name('po-att-delete');
        Route::delete('/sales/deleteAttachment/{id}', [App\Http\Controllers\SalesController::class, 'deleteAtt'])->name('sale1-att-delete');
        Route::delete('/sales2/deleteAttachment/{id}', [App\Http\Controllers\Sales2Controller::class, 'deleteAtt'])->name('sales2-att-delete');
        Route::delete('/purchase2/deleteAttachment/{id}', [App\Http\Controllers\Purchase2Controller::class, 'deleteAtt'])->name('purc2-att-delete');
        Route::delete('/vouchers2/deleteAttachment/{id}', [App\Http\Controllers\JV2Controller::class, 'deleteAtt'])->name('jv2-att-delete');
    });

    Route::middleware(['checkPermission:report'])->group(function () {
    });

    Route::middleware(['checkPermission:print'])->group(function (){

        Route::get('/coa/print', [App\Http\Controllers\COAController::class, 'print'])->name('print-acc');
        Route::get('/purchase2/generatePDF/{id}', [App\Http\Controllers\Purchase2Controller::class, 'generatePDF'])->name('print-purc2-invoice');
        Route::get('/purchase1/generatePDF/{id}', [App\Http\Controllers\PurchaseController::class, 'generatePDF'])->name('print-purc1-invoice');
        Route::get('/vouchers2/print/{id}', [App\Http\Controllers\JV2Controller::class, 'print'])->name('print-jv2');
        Route::get('/vouchers/print/{id}', [App\Http\Controllers\JV1Controller::class, 'print'])->name('print-jv1');
        Route::get('/complains/print/{id}', [App\Http\Controllers\ComplainsController::class, 'print'])->name('print-complains');
        Route::get('/weight/generatePDF/{id}', [App\Http\Controllers\WeightController::class, 'generatePDF'])->name('print-weight-invoice');
        Route::get('/tpo/generatePDF/', [App\Http\Controllers\TpoController::class, 'generatePDF'])->name('print-tpo-invoice');
        Route::get('/quotation/generatePDF/{id}', [App\Http\Controllers\QuotationController::class, 'generatePDF'])->name('print-quotation-invoice');
        Route::get('/quotation/downloadPDF/{id}', [App\Http\Controllers\QuotationController::class, 'downloadPDF'])->name('download-quotation-invoice');
        Route::get('/po/generatePDF/{id}', [App\Http\Controllers\PoController::class, 'generatePDF'])->name('print-po-invoice');
        Route::get('/sales2/generatePDF/', [App\Http\Controllers\Sales2Controller::class, 'generatePDF'])->name('print-sales2-invoice');
        Route::get('/sales/generatePDF/{id}', [App\Http\Controllers\SalesController::class, 'generatePDF'])->name('print-sale-invoice');
        Route::get('/sales/downloadPDF/{id}', [App\Http\Controllers\SalesController::class, 'downloadPDF'])->name('download-sale-invoice');
        Route::get('/tbad_dabs/tbad_dabs_invoice/generatePDF/{id}', [App\Http\Controllers\TBadDabsController::class, 'generatePDF'])->name('print-tbad-dabs-invoice');
        Route::get('/tstock_in/tstock_in_invoice/generatePDF/{id}', [App\Http\Controllers\TStockInController::class, 'generatePDF'])->name('print-tstock-in-invoice');
        Route::get('/tstock_out/tstock_out/generatePDF/{id}', [App\Http\Controllers\TStockOutController::class, 'generatePDF'])->name('print-tstock-out-invoice');
        Route::get('/bad_dabs/bad_dabs_invoice/generatePDF/{id}', [App\Http\Controllers\BadDabsController::class, 'generatePDF'])->name('print-bad-dabs-invoice');
        Route::get('/stock_in/generatePDF/{id}', [App\Http\Controllers\StockInController::class, 'generatePDF'])->name('print-stock-in-invoice');
        Route::get('/complains/generatePDF/{id}', [App\Http\Controllers\ComplainsController::class, 'generatePDF'])->name('print-complain');
        Route::get('/stock_out/stock_out_invoice/generatePDF/{id}', [App\Http\Controllers\StockOutController::class, 'generatePDF'])->name('print-stock-out-invoice');
        Route::get('/tquotation/generatePDF/', [App\Http\Controllers\TQuotationController::class, 'generatePDF'])->name('print-tqout-invoice');
    });

    // Route::middleware(['checkPermission:report'])->group(function (){

    // });

    Route::middleware(['auth'])->group(function () {
        Route::get('/logout', [App\Http\Controllers\UsersController::class, 'logout']);
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);
        Route::get('/validate-user-password', [App\Http\Controllers\UsersController::class, 'getUserPassword'])->name('validate-user-password');
        Route::post('/change-user-password', [App\Http\Controllers\UsersController::class, 'updateUserPassowrd'])->name('change-user-password');
        Route::get('/item-groups/detail', [App\Http\Controllers\ItemGroupsController::class, 'getGroupDetails'])->name('get-item-group-details');
        Route::get('/items/detail', [App\Http\Controllers\ItemsController::class, 'getItemDetails'])->name('get-item-details');
        Route::get('/item2/detail', [App\Http\Controllers\Item2Controller::class, 'getItemDetails'])->name('get-item-details-2');
        Route::get('/coa-groups/detail', [App\Http\Controllers\COAGroupsController::class, 'getDetails'])->name('get-acc-groups-details');
        Route::get('/coa-sub-heads/detail', [App\Http\Controllers\COASubHeadsController::class, 'getCOASubHeadDetails'])->name('get-acc-sub-heads-groups-details');
        Route::get('/vouchers/detail', [App\Http\Controllers\JV1Controller::class, 'getJVDetails'])->name('get-jv1-details');
        Route::get('/purchase2/getunclosed/', [App\Http\Controllers\Purchase2Controller::class, 'getunclosed'])->name('get-unclosed-purc2-invoice');
        Route::get('/purchase2/getItems/{id}', [App\Http\Controllers\Purchase2Controller::class, 'getItems'])->name('get-purc2-items');
        Route::get('/tpo/getunclosed/', [App\Http\Controllers\TpoController::class, 'getunclosed'])->name('get-unclosed-tpo-invoice');
        Route::get('/tpo/getItems/{id}', [App\Http\Controllers\TpoController::class, 'getItems'])->name('get-tpo-items');
        Route::get('/tpo/getavailablestock/{id}', [App\Http\Controllers\TpoController::class, 'getavailablestock'])->name('tpo-item-stock-bal');
        Route::get('/sales2/getunclosed/', [App\Http\Controllers\Sales2Controller::class, 'getunclosed'])->name('get-unclosed-sales2-invoice');
        Route::get('/sales2/getItems/{id}', [App\Http\Controllers\Sales2Controller::class, 'getItems'])->name('get-sales2-items');
        Route::get('/tstock_out/getunclosed/', [App\Http\Controllers\TStockOutController::class, 'getunclosed'])->name('get-unclosed-tstock-out-invoice');
        Route::get('/tstock_out/getItems/{id}', [App\Http\Controllers\TStockOutController::class, 'getItems'])->name('get-tstock-out-items');
        Route::get('/tquotation/getunclosed/', [App\Http\Controllers\TQuotationController::class, 'getunclosed'])->name('get-unclosed-tquotation-invoice');
        Route::get('/tquotation/getItems/{id}', [App\Http\Controllers\TQuotationController::class, 'getItems'])->name('get-quot2-items');
        Route::get('/tquotation/getavailablestock/{id}', [App\Http\Controllers\TQuotationController::class, 'getavailablestock'])->name('qout-item-stock-bal');
        Route::get('/weight/getunclosed/', [App\Http\Controllers\WeightController::class, 'getunclosed'])->name('get-unclosed-weight-invoice');
        Route::get('/weight/getItems/{id}', [App\Http\Controllers\WeightController::class, 'getItems'])->name('get-weight-items');
        Route::get('/weight/getavailablestock/{id}', [App\Http\Controllers\WeightController::class, 'getavailablestock'])->name('weightt-item-stock-bal');
        Route::get('/complains/detail', [App\Http\Controllers\ComplainsController::class, 'getcomplainsDetails'])->name('get-complains-details');
        Route::get('/coa/detail', [App\Http\Controllers\COAController::class, 'getAccountDetails'])->name('get-acc-details');
        Route::post('/items/new-item/validate', [App\Http\Controllers\ItemsController::class, 'validation'])->name('validate-item');
        Route::post('/item2/new-item/validate', [App\Http\Controllers\Item2Controller::class, 'validation'])->name('validate-item-2');
        Route::post('/coa/acc/validate', [App\Http\Controllers\COAController::class, 'validation'])->name('validate-acc');
        Route::get('/user/attachements', [App\Http\Controllers\UsersController::class, 'getAttachements'])->name('get-user-att');
        Route::get('/coa/attachements', [App\Http\Controllers\COAController::class, 'getAttachements'])->name('get-acc-att');
        Route::get('/vouchers/attachements', [App\Http\Controllers\JV1Controller::class, 'getAttachements'])->name('get-jv1-att');
        Route::get('/complains/attachements', [App\Http\Controllers\ComplainsController::class, 'getAttachements'])->name('get-complains-att');
        Route::get('/tquotation/attachements', [App\Http\Controllers\TQuotationController::class, 'getAttachements'])->name('get-tquotation-att');
        Route::get('/weight/attachements', [App\Http\Controllers\WeightController::class, 'getAttachements'])->name('get-weight-att');
        Route::get('/stock_out/attachements', [App\Http\Controllers\StockOutController::class, 'getAttachements'])->name('get-stock-out-att');
        Route::get('/stock_in/attachements', [App\Http\Controllers\StockInController::class, 'getAttachements'])->name('get-stock-in-att');
        Route::get('/tstock_out/attachements', [App\Http\Controllers\TStockOutController::class, 'getAttachements'])->name('get-tstock-out-att');
        Route::get('/tstock_in/attachements', [App\Http\Controllers\TStockInController::class, 'getAttachements'])->name('get-tstock-in-att');
        Route::get('/quotation/attachements', [App\Http\Controllers\QuotationController::class, 'getAttachements'])->name('get-quotation-att');
        Route::get('/tpo/attachements', [App\Http\Controllers\TpoController::class, 'getAttachements'])->name('get-tpo-att');
        Route::get('/po/attachements', [App\Http\Controllers\PoController::class, 'getAttachements'])->name('get-po-att');
        Route::get('/sales/attachements', [App\Http\Controllers\SalesController::class, 'getAttachements'])->name('get-sale1-att');
        Route::get('/sales2/attachements', [App\Http\Controllers\Sales2Controller::class, 'getAttachements'])->name('get-sales2-att');
        Route::get('/purchase2/attachements', [App\Http\Controllers\Purchase2Controller::class, 'getAttachements'])->name('get-purc2-att');
        Route::get('/vouchers2/attachements', [App\Http\Controllers\JV2Controller::class, 'getAttachements'])->name('get-jv2-att');
        Route::get('/purchase1/attachements', [App\Http\Controllers\PurchaseController::class, 'getAttachements'])->name('get-purc1-att');
        Route::get('/modules/all-modules', [App\Http\Controllers\ModulesController::class, 'index'])->name('all-modules');
        Route::post('/modules/add', [App\Http\Controllers\ModulesController::class, 'store'])->name('add-module');
        Route::post('/modules/update', [App\Http\Controllers\ModulesController::class, 'update'])->name('update-module');
        Route::get('/modules/details', [App\Http\Controllers\ModulesController::class, 'getModuleDetails'])->name('get-module-details');
        Route::get('/unauthorized', function () {
            return view('unauthorized');
        })->name('unauthorized');

        Route::post('/keep-alive', function () {
            session(['lastActivity' => time()]); // Update last activity time
            return response()->json(['status' => 'success']);
        });
    });

    // reporting
    Route::middleware(['auth'])->group(function () {

        // Main Routes
        Route::get('/rep-by-acc-name', [App\Http\Controllers\ReportingController::class, 'byAccountName'])->name('rep-by-acc-name');
        Route::get('/rep-godown-by-item-name', [App\Http\Controllers\ReportingController::class, 'byGodownItemName'])->name('rep-by-godown-item-name');
        Route::get('/rep-godown-by-group-name', [App\Http\Controllers\ReportingController::class, 'byGodownGroupName'])->name('rep-by-godown-group-name');
        Route::get('/rep-daily-register', [App\Http\Controllers\ReportingController::class, 'dailyRegister'])->name('rep-daily-register');

        //RPT by Acc Name Purchase 1
        Route::get('/rep-by-acc-name/pur1', [App\Http\Controllers\RptAccNamePur1Controller::class, 'purchase1'])->name('pur1-rep-by-acc-name');
        Route::get('/rep-by-acc-name/pur1/excel', [App\Http\Controllers\RptAccNamePur1Controller::class, 'purchase1Excel'])->name('pur1-rep-by-acc-name-excel');
        Route::get('/rep-by-acc-name/pur1/PDF', [App\Http\Controllers\RptAccNamePur1Controller::class, 'purchase1PDF'])->name('pur1-rep-by-acc-name-PDF');
        Route::get('/rep-by-acc-name/pur1/download', [App\Http\Controllers\RptAccNamePur1Controller::class, 'purchase1Download'])->name('pur1-rep-by-acc-name-download');

        //RPT by Acc Name Purchase 2
        Route::get('/rep-by-acc-name/pur2', [App\Http\Controllers\RptAccNamePur2Controller::class, 'purchase2'])->name('pur2-rep-by-acc-name');
        Route::get('/rep-by-acc-name/pur2/excel', [App\Http\Controllers\RptAccNamePur2Controller::class, 'purchase2Excel'])->name('pur2-rep-by-acc-name-excel');
        Route::get('/rep-by-acc-name/pur2/PDF', [App\Http\Controllers\RptAccNamePur2Controller::class, 'purchase2PDF'])->name('pur2-rep-by-acc-name-PDF');
        Route::get('/rep-by-acc-name/pur2/download', [App\Http\Controllers\RptAccNamePur2Controller::class, 'purchase2Download'])->name('pur2-rep-by-acc-name-download');

        //RPT by Acc Name Combine Purchases
        Route::get('/rep-by-acc-name/comb-pur', [App\Http\Controllers\RptAccNameCombPurController::class, 'combinePurchase'])->name('comb-pur-rep-by-acc-name');
        Route::get('/rep-by-acc-name/comb-pur/excel', [App\Http\Controllers\RptAccNameCombPurController::class, 'combinePurchaseExcel'])->name('comb-pur-rep-by-acc-name-excel');
        Route::get('/rep-by-acc-name/comb-pur/PDF', [App\Http\Controllers\RptAccNameCombPurController::class, 'combinePurchasePDF'])->name('comb-pur-rep-by-acc-name-PDF');
        Route::get('/rep-by-acc-name/comb-pur/download', [App\Http\Controllers\RptAccNameCombPurController::class, 'combinePurchaseDownload'])->name('comb-pur-rep-by-acc-name-download');

        //RPT by Acc Name Sale 1
        Route::get('/rep-by-acc-name/sale1', [App\Http\Controllers\RptAccNameSale1Controller::class, 'sale1'])->name('sale1-rep-by-acc-name');
        Route::get('/rep-by-acc-name/sale1/excel', [App\Http\Controllers\RptAccNameSale1Controller::class, 'sale1Excel'])->name('sale1-rep-by-acc-name-excel');
        Route::get('/rep-by-acc-name/sale1/PDF', [App\Http\Controllers\RptAccNameSale1Controller::class, 'sale1PDF'])->name('sale1-rep-by-acc-name-PDF');
        Route::get('/rep-by-acc-name/sale1/download', [App\Http\Controllers\RptAccNameSale1Controller::class, 'sale1Download'])->name('sale1-rep-by-acc-name-download');

        //RPT by Acc Name Sale 2
        Route::get('/rep-by-acc-name/sale2', [App\Http\Controllers\RptAccNameSale2Controller::class, 'sale2'])->name('sale2-rep-by-acc-name');
        Route::get('/rep-by-acc-name/sale2/excel', [App\Http\Controllers\RptAccNameSale2Controller::class, 'sale2Excel'])->name('sale2-rep-by-acc-name-excel');
        Route::get('/rep-by-acc-name/sale2/PDF', [App\Http\Controllers\RptAccNameSale2Controller::class, 'sale2PDF'])->name('sale2-rep-by-acc-name-PDF');
        Route::get('/rep-by-acc-name/sale2/download', [App\Http\Controllers\RptAccNameSale2Controller::class, 'sale2Download'])->name('sale2-rep-by-acc-name-download');

        //RPT by Acc Name Combine Sales
        Route::get('/rep-by-acc-name/comb-sale', [App\Http\Controllers\RptAccNameCombSaleController::class, 'combineSale'])->name('comb-sale-rep-by-acc-name');
        Route::get('/rep-by-acc-name/comb-sale/excel', [App\Http\Controllers\RptAccNameCombSaleController::class, 'combineSaleExcel'])->name('comb-sale-rep-by-acc-name-excel');
        Route::get('/rep-by-acc-name/comb-sale/PDF', [App\Http\Controllers\RptAccNameCombSaleController::class, 'combineSalePDF'])->name('comb-pur-sale-by-acc-name-PDF');
        Route::get('/rep-by-acc-name/comb-sale/download', [App\Http\Controllers\RptAccNameCombSaleController::class, 'combineSaleDownload'])->name('comb-sale-rep-by-acc-name-download');

        //RPT by Acc Name JV
        Route::get('/rep-by-acc-name/jv', [App\Http\Controllers\RptAccNameJVController::class, 'jv'])->name('jv-rep-by-acc-name');
        Route::get('/rep-by-acc-name/jv/excel', [App\Http\Controllers\RptAccNameJVController::class, 'jvExcel'])->name('jv-rep-by-acc-name-excel');
        Route::get('/rep-by-acc-name/jv/PDF', [App\Http\Controllers\RptAccNameJVController::class, 'jvPDF'])->name('jv-sale-by-acc-name-PDF');
        Route::get('/rep-by-acc-name/jv/download', [App\Http\Controllers\RptAccNameJVController::class, 'jvDownload'])->name('jv-rep-by-acc-name-download');

        //RPT Godown By Item Name si
        Route::get('/rep-godown-by-item-name/si', [App\Http\Controllers\RptGoDownItemNameController::class, 'tstockin'])->name('si-rep-godown-by-item-name');
        Route::get('/rep-godown-by-item-name/si/excel', [App\Http\Controllers\RptGoDownItemNameController::class, 'tstockinExcel'])->name('si-rep-godown-by-item-name-excel');;
        Route::get('/rep-godown-by-item-name/si/report', [App\Http\Controllers\RptGoDownItemNameController::class, 'tstockinReport'])->name('si-rep-godown-by-item-name-report');

        //RPT Godown By Item Name so
        Route::get('/rep-godown-by-item-name/so', [App\Http\Controllers\RptGoDownItemNameController::class, 'tstockout'])->name('so-rep-godown-by-item-name');
        Route::get('/rep-godown-by-item-name/so/report', [App\Http\Controllers\RptGoDownItemNameController::class, 'tstockoutReport'])->name('so-rep-godown-by-item-name-report');
        
        //RPT Godown By Item Name bal 
        Route::get('/rep-godown-by-item-name/bal', [App\Http\Controllers\RptGoDownItemNameController::class, 'tstockbal'])->name('bal-rep-godown-by-item-name');
        Route::get('/rep-godown-by-item-name/bal/report', [App\Http\Controllers\RptGoDownItemNameController::class, 'tstockbalReport'])->name('bal-rep-godown-by-item-name-report');
        
      
    });