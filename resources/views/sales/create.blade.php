@extends('../layouts.header')
	<body>
		<section class="body">
			@extends('../layouts.menu')
			<div class="inner-wrapper">
				<section role="main" class="content-body">
					@extends('../layouts.pageheader')
					<form method="post" action="{{ route('store-sale-invoice') }}" enctype="multipart/form-data">
						@csrf
						<div class="row">
							<div class="col-3 mb-3">								
								<section class="card">
									<header class="card-header">
										<div class="card-actions">
											<button type="button" class="mb-1 me-1 btn btn-primary"><i class="fas fa-plus" ></i> Add New</button>											
										</div>
										<h2 class="card-title">Party Details</h2>
									</header>

									<div class="card-body">
										<div class="row form-group mb-2">
											<div class="col-sm-12 col-md-6 mb-2">
												<label class="col-form-label" >Invoice no.</label>
												<input type="text" name="invoice_no" placeholder="Invoice No." class="form-control" disabled>
											</div>

											<div class="col-sm-12 col-md-6 mb-2">
												<label class="col-form-label" >Date</label>
												<input type="date" name="date" required value="<?php echo date('Y-m-d'); ?>" class="form-control">
											</div>

											<div class="col-sm-12 col-md-6">
												<label class="col-form-label" >Bill No.</label>
												<input type="text" name="bill_no" placeholder="Bill No." class="form-control">
											</div>

											<div class="col-sm-12 col-md-6">
												<label class="col-form-label">Status</label>
												<select class="form-control mb-3" name="bill_status">
													<option value="0">Bill Not Final</option>
													<option value="1">Finalized</option>
												</select>												
											</div>

											<div class="col-12 mb-3">
												<label class="col-form-label">Chart Of Account</label>
												<select class="form-control" id="coa_name" onchange="getCOADetails()" name="account_name" required>
													<option>Select Chart Of Account</option>
													@foreach($coa as $key => $row)	
														<option value="{{$row->ac_code}}">{{$row->ac_name}}</option>
													@endforeach
												</select>
											</div>

											<div class="col-12 mb-3">
												<label class="col-form-label">Name Of Person</label>
												<input type="text" name="nop" id="nop" placeholder="Name Of Person" class="form-control">
											</div>

											<div class="col-12 mb-3">
												<label class="col-form-label">Address</label>
												<input type="text" name="address" id="address" placeholder="Address" class="form-control">
											</div>

											<div class="col-12 mb-3">
												<label class="col-form-label">Cash Pur Phone</label>
												<input type="text" name="cash_pur_phone" id="cash_pur_phone" placeholder="Cash - Pur_phone" class="form-control">
											</div>

											<div class="col-12 mb-3">
												<label class="col-form-label">Remarks</label>
												<input type="text" name="remarks" id="remarks" placeholder="Remarks" class="form-control">
											</div>
									  </div>
									</div>
								</section>
							</div>

							<div class="col-9 mb-3">
								<section class="card">
									<div class="card-body" style="overflow-x:auto;min-height:450px;max-height:450px;overflow-y:auto">
										<table class="table table-bordered table-striped mb-0" id="myTable" >
											<thead>
												<tr>
													<th width="5%">Code</th>
													<th width="5%">Qty</th>
													<th width="5%">Name</th>
													<th width="5%">Remarks</th>
													<th width="5%">Weight(kgs)</th>
													<th width="5%">Price</th>
													<th width="5%">Amount</th>
													<th width="5%">Action</th>
												</tr>
											</thead>
											<tbody id="saleInvoiceTable">
												<tr>
													<td>
														<input type="number" id="item_code0" name="item_code[]" placeholder="Code" class="form-control" required>
														<input type="text" id="itemCount" name="items" value="0" placeholder="Code" class="form-control" hidden>
													</td>
													<td>
														<input type="number" id="item_qty0" name="item_qty[]" onchange="rowTotal(0)" placeholder="Qty" class="form-control">
													</td>
													<td>
														<select class="form-control" id="item_name0" onchange="addNewRow(0)" required name="item_name[]">
														<option selected>Select Item</option>
															@foreach($items as $key => $row)	
																<option value="{{$row->it_cod}}">{{$row->item_name}}</option>
															@endforeach
														</select>
													</td>
													<td>
														<input type="text" id="remarks0" name="item_remarks[]" placeholder="Remarks" class="form-control">
													</td>
													<td>
														<input type="number" id="weight0" name="item_weight[]" onchange="rowTotal(0)" placeholder="Weight (kgs)" class="form-control">
													</td>
													<td>
														<input type="number" id="price0" name="item_price[]" onchange="rowTotal(0)" placeholder="Price" class="form-control">
													</td>
													<td>
														<input type="number" id="amount0" name="item_amount[]" placeholder="Amount" class="form-control" disabled>
													</td>
													<td>
														<button onclick="removeRow(this)" class="btn btn-danger" tabindex="1"><i class="fas fa-times"></i></button>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<footer class="card-footer">
										<div class="row form-group mb-3">
											<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
										 	    <label class="col-form-label">Total Amount</label>
										 		<input type="text" id="totalAmount" name="totalAmount" placeholder="Total Amount" class="form-control" disabled>
											</div>

											<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
												<label class="col-form-label">Total Weight</label>
												<input type="text" id="total_weight" name="total_weight" placeholder="Total Weight" class="form-control" disabled>
											</div>

											<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
												<label class="col-form-label">Total Quantity</label>
												<input type="text" id="total_quantity" name="total_quantity" placeholder="Total Weight" class="form-control" disabled>
											</div>

											<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
												<label class="col-form-label">GST</label>
												<input type="text" id="gst" name="gst" onchange="netTotal()" placeholder="GST" class="form-control">
											</div>

											<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
												<label class="col-form-label">Convance</label>
												<input type="text" id="convance_charges" onchange="netTotal()" name="convance_charges" placeholder="Convance Charges" class="form-control">
											</div>

											<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
												<label class="col-form-label">Labour Charges</label>
												<input type="text" id="labour_charges"  onchange="netTotal()" name="labour_charges" placeholder="Labour Charges" class="form-control">
											</div>
										</div>
										<div class="row mb-3">
											<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
												<label class="col-form-label">Bill Discount</label>
												<input type="text" id="bill_discount"  onchange="netTotal()" name="bill_discount" placeholder="Bill Discount" class="form-control">
											</div>

											<div class="col-sm-2 col-md-4 pb-sm-3 pb-md-0">
												<label class="col-form-label">File Attached</label>
												<input type="file" class="form-control" name="att" accept="application/pdf, image/png, image/jpeg">
										 	</div>

											<div class="col-sm-2 col-md-6 pb-sm-3 pb-md-0">
												<h3 class="font-weight-bold mt-3 mb-0 text-5 text-end text-primary">Net Amount</h3>
												<span class="d-flex align-items-center justify-content-lg-end">
														<strong class="text-4 text-primary">PKR <span id="netTotal" class="text-4 text-danger">0.00 </span></strong>
												</span>
											</div>
										</div>
									</footer>
									<footer class="card-footer">
										<div class="row form-group mb-2">
											<div class="text-end">
												<button type="submit" class="btn btn-primary mt-2"> <i class="fas fa-save"></i> Save Invoice</button>
											</div>
										</div>
									</footer>
								</section>
							</div>
						</div>
					</form>
				</section>
			</div>
		</section>
        @extends('../layouts.footerlinks')
	</body>
</html>
<script>
	var index=1;

    function removeRow(button) {
        var row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);
		index--;
		var itemCount = Number($('#itemCount').val());
		itemCount = itemCount-1;
		$('#itemCount').val(itemCount);
		tableTotal()
    }

    document.getElementById('removeRowBtn').addEventListener('click', function() {
        var table = document.getElementById('myTable').getElementsByTagName('tbody')[0];
        if (table.rows.length > 0) {
            table.deleteRow(table.rows.length - 1);
        } else {
            alert("No rows to delete!");
        }
    });

	function addNewRow(id){

		getItemDetails(id);
        var table = document.getElementById('myTable').getElementsByTagName('tbody')[0];
        var newRow = table.insertRow(table.rows.length);

        var cell1 = newRow.insertCell(0);
        var cell2 = newRow.insertCell(1);
        var cell3 = newRow.insertCell(2);
        var cell4 = newRow.insertCell(3);
        var cell5 = newRow.insertCell(4);
        var cell6 = newRow.insertCell(5);
		var cell7 = newRow.insertCell(6);
        var cell8 = newRow.insertCell(7);

        cell1.innerHTML = '<input type="text" id="item_code'+index+'" name="item_code[]" placeholder="Code" class="form-control">';
        cell2.innerHTML = '<input type="number" id="item_qty'+index+'"  onchange="rowTotal('+index+')" name="item_qty[]" placeholder="Qty" class="form-control">';
		cell3.innerHTML = '<select class="form-control" id="item_name'+index+'" onchange="addNewRow('+index+')" name="item_name">'+
								'<option>Select Item</option>'+
								@foreach($items as $key => $row)	
									'<option value="{{$row->it_cod}}">{{$row->item_name}}</option>'+
								@endforeach
							'</select>';
        cell4.innerHTML = '<input type="text" id="remarks'+index+'" name="item_remarks[]" placeholder="Remarks" class="form-control">';
        cell5.innerHTML = '<input type="number" id="weight'+index+'" onchange="rowTotal('+index+')" name="item_weight[]" placeholder="Weight (kgs)" class="form-control">';
        cell6.innerHTML = '<input type="number" id="price'+index+'" onchange="rowTotal('+index+')" name="item_price[]" placeholder="Price" class="form-control">';
        cell7.innerHTML = '<input type="number" id="amount'+index+'" name="item_amount[]" placeholder="Amount" class="form-control" disabled>';
        cell8.innerHTML = '<button onclick="removeRow(this)" class="btn btn-danger" tabindex="1"><i class="fas fa-times"></i></button>';

		index++;

		var itemCount = Number($('#itemCount').val());
		itemCount = itemCount+1;
		$('#itemCount').val(itemCount);
	}

	function getItemDetails(row_no){
		var itemId = document.getElementById("item_name"+row_no).value;

		$.ajax({
			type: "GET",
			url: "/item/detail",
			data: {id:itemId},
			success: function(result){
				$('#item_code'+row_no).val(result[0]['it_cod']);
				$('#remarks'+row_no).val(result[0]['item_remark']);
				$('#price'+row_no).val(result[0]['sales_price']);
				$('#item_qty'+row_no).val(result[0]['opp_qty']);
			},
			error: function(){
				alert("error");
			}
		});
	}

	function getCOADetails(){
		var coaId = document.getElementById("coa_name").value;
		
		$.ajax({
			type: "GET",
			url: "/coa/detail",
			data: {id:coaId},
			success: function(result){
				$('#address').val(result[0]['address']);
				$('#cash_pur_phone').val(result[0]['phone_no']);
				$('#remarks').val(result[0]['remarks']);
			},
			error: function(){
				alert("error");
			}
		});
	}

	function rowTotal(index){
		var weight = $('#weight'+index+'').val();
		var price = $('#price'+index+'').val();
		var amount = weight * price;
		$('#amount'+index+'').val(amount);
		tableTotal();
	}

	function tableTotal(){
		var total=0;
		var weight=0;
		var quantity=0;
		var tableRows = $("#saleInvoiceTable tr").length;
		for (var i = 0; i < tableRows; i++) {
			total = total + Number($('#amount'+i+'').val());
			weight = weight + Number($('#weight'+i+'').val());
			quantity = quantity + Number($('#item_qty'+i+'').val());
        }
		$('#totalAmount').val(total);
		$('#total_weight').val(weight);
		$('#total_quantity').val(quantity);

		netTotal();
	}

	function netTotal(){
		var netTotal = 0;
		var total = Number($('#totalAmount').val());
		var gst = Number($('#gst').val());
		var convance_charges = Number($('#convance_charges').val());
		var labour_charges = Number($('#labour_charges').val());
		var bill_discount = Number($('#bill_discount').val());

		netTotal = total + gst + convance_charges + labour_charges - bill_discount;
		document.getElementById("netTotal").innerHTML = '<span class="text-4 text-danger">'+netTotal+'.00</span>';
	}
</script>