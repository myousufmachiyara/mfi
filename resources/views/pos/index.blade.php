@include('../layouts.header')
	<body>
		<section class="body">
		    @include('../layouts.pageheader')
            <div class="inner-wrapper cust-pad">
                <section role="main" class="content-body" style="margin:0px">
                <div class="row">
                    <div class="col-12 mb-3">								
                        <section class="card">
                            <header class="card-header" style="display: flex;justify-content: space-between;">
                                <h2 class="card-title">New Invoice</h2>
                                <div class="card-actions">
                                    <button type="button" class="btn btn-primary" onclick="addNewRow_btn()"> <i class="fas fa-plus"></i> Add New Item </button>
                                </div>
                            </header>
                            <div class="card-body">
                                <div class="row" style="margin-right:0px;">
                                    <div class="col-lg-3 row" >
                                        <div class="card card-modern card-modern-alt-padding col-6 mt-0">
                                            <div class="card-body bg-light">
                                                <div class="image-frame mb-2">
                                                    <div class="image-frame-wrapper">
                                                        <img src="/assets/img/empty-300x240.jpg" class="img-fluid" alt="Product Short Name">
                                                    </div>
                                                </div>
                                                <h4 class="text-4 line-height-2 mt-0 mb-2"><a href="ecommerce-products-form.html" class="ecommerce-sidebar-link text-color-dark text-color-hover-primary text-decoration-none">Product Name</a></h4>
                                                <div class="product-price">
                                                    <div class="regular-price on-sale">$59.00</div>
                                                    <div class="sale-price">$49.00</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card card-modern card-modern-alt-padding col-6 mt-0">
                                            <div class="card-body bg-light">
                                                <div class="image-frame mb-2">
                                                    <div class="image-frame-wrapper">
                                                        <img src="/assets/img/empty-300x240.jpg" class="img-fluid" alt="Product Short Name">
                                                    </div>
                                                </div>
                                                <h4 class="text-4 line-height-2 mt-0 mb-2"><a href="ecommerce-products-form.html" class="ecommerce-sidebar-link text-color-dark text-color-hover-primary text-decoration-none">Product Name</a></h4>
                                                <div class="product-price">
                                                    <div class="regular-price on-sale">$59.00</div>
                                                    <div class="sale-price">$49.00</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card card-modern card-modern-alt-padding col-6 mt-0">
                                            <div class="card-body bg-light">
                                                <div class="image-frame mb-2">
                                                    <div class="image-frame-wrapper">
                                                        <img src="/assets/img/empty-300x240.jpg" class="img-fluid" alt="Product Short Name">
                                                    </div>
                                                </div>
                                                <h4 class="text-4 line-height-2 mt-0 mb-2"><a href="ecommerce-products-form.html" class="ecommerce-sidebar-link text-color-dark text-color-hover-primary text-decoration-none">Product Name</a></h4>
                                                <div class="product-price">
                                                    <div class="regular-price on-sale">$59.00</div>
                                                    <div class="sale-price">$49.00</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card card-modern card-modern-alt-padding col-6 mt-0">
                                            <div class="card-body bg-light">
                                                <div class="image-frame mb-2">
                                                    <div class="image-frame-wrapper">
                                                        <img src="/assets/img/empty-300x240.jpg" class="img-fluid" alt="Product Short Name">
                                                    </div>
                                                </div>
                                                <h4 class="text-4 line-height-2 mt-0 mb-2"><a href="ecommerce-products-form.html" class="ecommerce-sidebar-link text-color-dark text-color-hover-primary text-decoration-none">Product Name</a></h4>
                                                <div class="product-price">
                                                    <div class="regular-price on-sale">$59.00</div>
                                                    <div class="sale-price">$49.00</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="card-body" style="overflow-x:auto;min-height:250px;max-height:450px;overflow-y:auto">
                                            <table class="table table-bordered table-striped mb-0" id="myTable" >
                                                <thead>
                                                    <tr>
                                                        <th width="15%">Item Code<span style="color: red;"><strong>*</strong></span></th>
                                                        <th>Item Name<span style="color: red;"><strong>*</strong></span></th>
                                                        <th width="15%">Qty<span style="color: red;"><strong>*</strong></span></th>
                                                        <th width="15%">Price<span style="color: red;"><strong>*</strong></span></th>
                                                        <th width="15%">Amount</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="saleInvoiceTable">
                                                    <tr>
                                                        <td>
                                                            <input type="number" id="item_code1" name="item_code[]" placeholder="Code" class="form-control" required onchange="getItemDetails(1,1)">
                                                        </td>
                                                        <td>
                                                            <select class="form-control select2-js" id="item_name1" onchange="getItemDetails(1,2)" name="item_name[]" required>
                                                            <option selected>Select Item</option>
                                                               
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="number" id="item_qty1" name="item_qty[]" onchange="rowTotal(0)" placeholder="Qty" value="0" step="any" required class="form-control">
                                                        </td>
                                                        <td>
                                                            <input type="number" id="price1" name="item_price[]" onchange="rowTotal(1)" placeholder="Price" value="0" step="any" required class="form-control">
                                                        </td>
                                                        <td>
                                                            <input type="number" id="amount1" name="item_amount[]" placeholder="Amount" class="form-control" value="0" step="any" required disabled>
                                                        </td>
                                                        <td>
                                                            <button type="button" onclick="removeRow(this)" class="btn btn-danger" tabindex="1"><i class="fas fa-times"></i></button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
									    </div>
                                    </div>
                                </div>
                            </div>

                            <footer class="card-footer">
                            </footer>
                            
                        </section>
                    </div>
                </div>
                </section>
            </div>
        </section>
        @include('../layouts.footerlinks')
	</body>
</html>