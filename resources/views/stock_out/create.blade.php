@extends('../layouts.header')
	<body>
		<section class="body">
			@extends('../layouts.menu')
			<div class="inner-wrapper">
				<section role="main" class="content-body">
					@extends('../layouts.pageheader')
					<form method="post" id="myForm" action="{{ route('store-stock-out-invoice') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
						@csrf
						<div class="row">
							<div class="col-12 mb-3">								
								<section class="card">
									<header class="card-header">
										<h2 class="card-title">New Stock Out Door</h2>
									</header>

									<div class="card-body">
										<div class="row form-group mb-2">
											<div class="col-sm-12 col-md-2 mb-2">
												<label class="col-form-label" >New ID</label>
												<input type="text" name="invoice_no" placeholder="(NEW ID)" class="form-control" disabled>
												<input type="hidden" id="itemCount" name="items" value="1" class="form-control" >
												<input type="hidden" id="printInvoice" name="printInvoice" value="0" class="form-control" >
											</div>

											<div class="col-sm-12 col-md-2 mb-2">
												<label class="col-form-label" >Date</label>
												<input type="date" name="date" required value="<?php echo date('Y-m-d'); ?>" class="form-control">
											</div>

											<div class="col-sm-12 col-md-3">
												<label class="col-form-label">Account Name</label>
												<select data-plugin-selecttwo class="form-control" id="coa_name" name="account_name" required>
													<option value="" disabled selected>Select Account</option>
													@foreach($coa as $key => $row)	
														<option value="{{$row->ac_code}}">{{$row->ac_name}}</option>
													@endforeach
												</select>
											</div>
                                            <div class="col-sm-12 col-md-2">
												<label class="col-form-label" >Name of Person</label>
												<input type="text" name="cash_pur_name" placeholder="Name of Person" class="form-control">
											</div>

                                            <div class="col-sm-12 col-md-1">
												<label class="col-form-label" >Gate Pass#</label>
												<input type="text" name="mill_gate" placeholder="Gate Passe#" class="form-control">
											</div>
											
											<div class="col-sm-12 col-md-2">
												<label class="col-form-label" >Sale Inv#</label>
												<input type="text" name="pur_inv" placeholder="Sale Inv#" class="form-control" disabled>
											</div>
											
											<div class="col-sm-12 col-md-4">
												<label class="col-form-label">File Attached</label>
												<input type="file" class="form-control" name="att[]" multiple accept=".zip, appliation/zip, application/pdf, image/png, image/jpeg">
											</div>

											<div class="col-sm-12 col-md-8 mb-2">
												<label class="col-form-label">Remarks</label>
												<textarea rows="2" cols="50" name="remarks" id="remarks" placeholder="Remarks" class="form-control"></textarea>
											</div>
									  </div>
									</div>
								</section>
							</div>

							<div class="col-12 mb-3">
								<section class="card">
                                    <header class="card-header">
										<div class="card-actions">
											<button type="button" class="btn btn-primary" onclick="addNewRow()"> <i class="fas fa-plus"></i> Add New Row </button>
										</div>
										<h2 class="card-title">Stock Out Details</h2>
									</header>
									<div class="card-body" style="overflow-x:auto;min-height:450px;max-height:450px;overflow-y:auto">
										<table class="table table-bordered table-striped mb-0" id="myTable" >
											<thead>
												<tr>
													<th width="10%">Item Code</th>
													<th width="20%">Item Name</th>
													<th width="20%">Remarks</th>
													<th width="15%">Qty</th>
													<th width="10%">Weight</th>
													<th width="10%"></th>
												</tr>
											</thead>
										    <tbody id="stock_outTable">
											 <tr>
                                                <td>
                                                    <input type="number" id="item_code1" name="item_code[]" placeholder="Code" class="form-control" required onchange="getItemDetails(1,1)">
                                                </td>
                                                <td>
                                                    <select data-plugin-selecttwo class="form-control" id="item_name1" onchange="getItemDetails(1,2)" name="item_name[]" required>
                                                        <option selected>Select Item</option>
                                                        @foreach($items as $key => $row)
                                                            <option value="{{ $row->it_cod }}">{{ $row->item_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" id="remarks1" name="item_remarks[]" placeholder="Remarks" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="number" id="qty1" name="qty[]" placeholder="Qty" value="0" step="any" required class="form-control">
                                                   
                                                </td>
                                                <td>
                                                    <input type="number" id="weight1" name="weight[]" placeholder="Weight"  value="0" step="any" required class="form-control">
                                                </td>
                                                <td>
                                                    <button type="button" onclick="removeRow(this)" class="btn btn-danger" tabindex="1"><i class="fas fa-times"></i></button>
                                                </td>
                                             </tr>
                                            </tbody>
                                    </table>
                                </div>
                                <footer class="card-footer">
                                    <<div class="row mb-3" style="float:right; margin-right: 10%;">
                                        <div class="col-sm-2 col-md-6 pb-sm-3 pb-md-0">
                                            <label class="col-form-label">Total Qty</label>
                                            <input type="number" id="total_qty" placeholder="Total Qty" class="form-control" step="any" disabled>
                                        </div>
                                        
                                        <div class="col-sm-6 col-md-6 pb-sm-3 pb-md-0">
                                            <label class="col-form-label">Total Weight</label>
                                            <input type="number" id="total_weight" placeholder="Total weight" class="form-control" step="any" disabled>
                                        </div>
                                        
                                    </div>
                                </footer>
                                
                                <footer class="card-footer">
                                    <div class="row form-group mb-2">
                                        <div class="text-end">
                                            <button type="button" class="btn btn-danger mt-2" onclick="window.location='{{ route('all-stock-out') }}'"> <i class="fas fa-trash"></i> Discard Entry</button>
                                            <button type="submit" class="btn btn-primary mt-2"> <i class="fas fa-save"></i> Add Entry</button>
                                        </div>
                                    </div>
                                </footer>
                            </section>
                        </div>
                    </div>
                </form>
                <div id="deleteModal" class="zoom-anim-dialog modal-block modal-block-danger mfp-hide">
                    <section class="card">
                        <header class="card-header">
                            <h2 class="card-title">Delete Entry</h2>
                        </header>
                        <div class="card-body">
                            <div class="modal-wrapper">
                                <div class="modal-icon">
                                    <i class="fas fa-question-circle"></i>
                                </div>
                                <div class="modal-text">
                                    <p class="mb-0">Are you sure that you want to delete this Entry?</p>
                                    <input name="invoice_id" id="deleteID" hidden>
                                </div>
                            </div>
                        </div>
                        <footer class="card-footer">
                            <div class="row">
                                <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                    <button class="btn btn-default modal-dismiss">Cancel</button>
                                </div>
                            </div>
                        </footer>
                    </section>
                </div>
            </section>
        </div>
    </section>
    @extends('../layouts.footerlinks')
</body>
</html>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

    var index = 2;

    $(document).ready(function() {
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
    });

    function removeRow(button) {
        var tableRows = $("#stock_outTable tr").length;
        if (tableRows > 1) {
            $(button).closest('tr').remove();
            index--;
            $('#itemCount').val(Number($('#itemCount').val()) - 1);
            tableTotal();
        }
    }

    function addNewRow() {
        var lastRow = $('#myTable tr:last');
        var latestValue = lastRow.find('select').val();

        if (latestValue !== "Select Item") {
            var table = $('#myTable').find('tbody');
            var newRow = $('<tr>');

            newRow.append('<td><input type="number" id="item_code'+index+'" name="item_code[]" placeholder="Code" class="form-control" required onchange="getItemDetails(' + index + ', 1)"></td>');
            newRow.append('<td><select data-plugin-selecttwo class="form-control" id="item_name'+index+'" name="item_name[]" onchange="getItemDetails(' + index + ', 2)"><option>Select Item</option>@foreach($items as $key => $row)<option value="{{ $row->it_cod }}">{{ $row->item_name }}</option>@endforeach</select></td>');
            newRow.append('<td><input type="text" id="remarks'+index+'" name="item_remarks[]" placeholder="Remarks" class="form-control"></td>');
            newRow.append('<td><input type="number" id="qty'+index+'" name="qty[]" placeholder="Qty" value="0" step="any" required class="form-control" ">');
            newRow.append('<td><input type="number" id="weight'+index+'" name="weight[]" placeholder="Weight" value="0" step="any" required class="form-control"></td>')</td>');
            newRow.append('<td><button type="button" onclick="removeRow(this)" class="btn btn-danger"><i class="fas fa-times"></i></button></td>');

            table.append(newRow);
            index++;
            $('#itemCount').val(Number($('#itemCount').val()) + 1);
            $('#myTable select[data-plugin-selecttwo]').select2();

        }
    }

    function getItemDetails(row_no, option) {
        var itemId = option === 1 ? $("#item_code" + row_no).val() : $("#item_name" + row_no).val();

        $.ajax({
            type: "GET",
            url: "/item/detail",
            data: {id: itemId},
            success: function(result) {
                if (result.length > 0) {
                    $('#item_code'+row_no).val(result[0]['it_cod']);
                    $('#item_name'+row_no).val(result[0]['it_cod']);
                    $('#remarks'+row_no).val(result[0]['item_remark']);

                    addNewRow();
                }
            },
            error: function() {
                alert("Error retrieving item details.");
            }
        });
    }


    
    function tableTotal() {
        var totalqty = 0;
        var totalweight = 0;
        $('#stock_outTable tr').each(function() {
            totalqty += Number($(this).find('input[name="qty[]"]').val());
            totalweight += Number($(this).find('input[name="weight[]"]').val());
        });

        $('#total_qty').val(totalqty.toFixed(0));
        $('#total_weight').val(totalweight.toFixed(0));
    }
</script>