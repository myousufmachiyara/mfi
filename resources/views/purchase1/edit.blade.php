@extends('../layouts.header')
	<body>
		<section class="body">
			@extends('../layouts.menu')
			<div class="inner-wrapper">
				<section role="main" class="content-body">
					@extends('../layouts.pageheader')
					<form method="post" action="{{ route('update-jv2') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';" id="updateForm">
						@csrf
						<div class="row">
							<div class="col-12 mb-3">								
								<section class="card">
									<header class="card-header">
										<h2 class="card-title">Edit Purchase Invoice</h2>
									</header>

									<div class="card-body">
										<div class="row form-group mb-2">
											<div class="col-sm-12 col-md-2 mb-2">
												<label class="col-form-label" >Invoice #</label>
												<input type="text" placeholder="Invoice #" class="form-control" value="{{$pur->pur_id}}" disabled>
												<input type="hidden" id="itemCount" name="items" value="1" class="form-control">
											</div>
											<div class="col-sm-12 col-md-2 mb-2">
												<label class="col-form-label" >Date</label>
												<input type="date" name="pur_date" value="{{$pur->pur_date}}"  class="form-control">
											</div>
											<div class="col-sm-12 col-md-2 mb-2">
												<label class="col-form-label" >Bill No.</label>
												<input type="number" placeholder="Bill No." name="pur_bill_no" value="{{$pur->pur_bill_no}}" class="form-control">
											</div>
											<div class="col-sm-12 col-md-2 mb-2">
												<label class="col-form-label" >Sale Inv.</label>
												<input type="text" placeholder="Sale Inv." name="pur_sale_inv" value="{{$pur->pur_sale_inv}}" class="form-control">
											</div>
											<div class="col-sm-12 col-md-4 mb-3">
												<label class="col-form-label">Attachements</label>
												<input type="file" class="form-control" name="att[]" multiple accept=".zip, appliation/zip, application/pdf, image/png, image/jpeg">
											</div>
											<div class="col-sm-12 col-md-3 mb-3">
												<td>
													<label class="col-form-label">Account Name</label>
													<select data-plugin-selectTwo class="form-control" autofocus name="ac_cod" required>
														<option value="" disabled selected>Select Account</option>
														@foreach($acc as $key => $row)	
															<option value="{{$row->ac_code}}" {{ $pur->ac_cod == $row->ac_code ? 'selected' : '' }}>{{$row->ac_name}}</option>
														@endforeach
													</select>
												</td>
											</div>
											<div class="col-sm-3 col-md-3 mb-2">
												<label class="col-form-label" >Name of Person</label>
												<input type="text" placeholder="Name of Person" name="cash_saler_name" value="{{$pur->cash_saler_name}}" class="form-control">
											</div>
											<div class="col-sm-3 col-md-6 mb-2">
												<label class="col-form-label" >Address</label>
												<input type="text" placeholder="Address" name="cash_saler_address" value="{{$pur->cash_saler_address}}" class="form-control">
											</div>
											<div class="col-12 mb-2">
												<label class="col-form-label">Purchase Remarks</label>
												<textarea rows="4" cols="50" name="pur_remarks" id="pur_remarks" value="{{$pur->pur_remarks}}" placeholder="Remarks" class="form-control"></textarea>
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
										<h2 class="card-title">Invoice Details</h2>
									</header>
									<div class="card-body" style="overflow-x:auto;min-height:450px;max-height:450px;overflow-y:auto">
										<table class="table table-bordered table-striped mb-0" id="myTable" >
											<thead>
												<tr>
													<th width="10%">Item Code</th>
													<th width="10%">Qty.</th>
													<th width="20%">Item Name</th>
													<th width="20%">Remarks</th>
													<th width="15%">Weight (kgs)</th>
													<th width="10%">Price</th>
													<th width="10%">Amount</th>
													<th width="10%"></th>
												</tr>
											</thead>
											<tbody id="Purchase1Table">
                                            	@foreach ($pur_items as $jv_key => $pur_items)
												<tr>
													<td>
														<input type="text" class="form-control" name="item_cod[]" id="item_cod1" onchange="getItemDetails(1,1)">
													</td>	
													<td>
														<input type="text" class="form-control" name="pur_qty2[]" value="0">
													</td>
													<td>
														<select class="form-control" autofocus id="item_name1" name="item_name[]" onchange="getItemDetails(1,2)" required>
															<option value="" selected disabled>Select Item</option>
															@foreach($items as $key => $row)	
																<option value="{{$row->it_cod}}">{{$row->item_name}}</option>
															@endforeach
														</select>													
													</td>
													<td>
														<input type="text" class="form-control" id="remarks1" name="remarks[]">
													</td>
                                                    <td>
														<input type="number" class="form-control" name="pur_qty[]" id="pur_qty1" onchange="rowTotal(1)" value="0">
                                                    </td>
													<td>
														<input type="number" class="form-control" name="pur_price[]" id="pur_price1" onchange="rowTotal(1)" value="0">
													</td>
													<td>
														<input type="number" class="form-control" name="amount[]" id="amount1" onchange="tableTotal()" value="0">
													</td>
													<td style="vertical-align: middle;">
														<button type="button" onclick="removeRow(this)" class="btn btn-danger" tabindex="1"><i class="fas fa-times"></i></button>
													</td>
												</tr>
												@endforeach
											</tbody>
										</table>
									</div>

									<footer class="card-footer" >
										<div class="row">
											<div class="row form-group mb-3">
												<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
													<label class="col-form-label">Total Amount</label>
													<input type="text" id="totalAmount" name="totalAmount" placeholder="Total Amount" class="form-control" disabled>
													<input type="hidden" id="total_amount_show" name="total_amount" placeholder="Total Weight" class="form-control">
												</div>

												<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
													<label class="col-form-label">Total Weight</label>
													<input type="text" id="total_weight" placeholder="Total Weight" class="form-control" disabled>
													<input type="hidden" id="total_weight_show" name="total_weight" placeholder="Total Weight" class="form-control">

												</div>

												<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
													<label class="col-form-label">Total Quantity</label>
													<input type="text" id="total_quantity" name="total_quantity" placeholder="Total Quantity" class="form-control" disabled>
												</div>

												<!-- <div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
													<label class="col-form-label">GST</label>
													<input type="text" id="gst" name="gst_pur" onchange="netTotal()" placeholder="GST" class="form-control">
												</div> -->

												<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
													<label class="col-form-label">Convance Charges</label>
													<input type="text" id="convance_charges" onchange="netTotal()" name="pur_convance_char" placeholder="Convance Charges" class="form-control">
												</div>

												<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
													<label class="col-form-label">Labour Charges</label>
													<input type="text" id="labour_charges"  onchange="netTotal()" name="pur_labor_char" placeholder="Labour Charges" class="form-control">
												</div>
												<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
													<label class="col-form-label">Bill Discount</label>
													<input type="text" id="bill_discount"  onchange="netTotal()" name="bill_discount" placeholder="Bill Discount" class="form-control">
												</div>
												<div class="col-sm-2 col-md-12 pb-sm-3 pb-md-0">
													<h3 class="font-weight-bold mt-3 mb-0 text-5 text-end text-primary">Net Amount</h3>
													<span class="d-flex align-items-center justify-content-lg-end">
														<strong class="text-4 text-primary">PKR <span id="netTotal" class="text-4 text-danger">0.00 </span></strong>
													</span>
													<input type="hidden" id="net_amount" name="net_amount" placeholder="Total Weight" class="form-control">
												</div>
											</div>
										</div>
									</footer>
									<footer class="card-footer">
										<div class="row form-group mb-2">
											<div class="text-end">
												<button type="button" class="btn btn-danger mt-2"  onclick="window.location='{{ route('all-purchases1') }}'"> <i class="fas fa-trash"></i> Discard Changes</button>
												<button type="submit" class="btn btn-primary mt-2"> <i class="fas fa-save"></i> Update Invoice</button>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

	var itemCount;

	$(document).ready(function() {
		$(window).keydown(function(event){
			if(event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		});

		$('#updateForm').on('submit', function(e){
            e.preventDefault();
			var total_credit=$('#total_credit').val();
			var total_debit=$('#total_debit').val();
			if(total_debit==total_credit){
				var form = document.getElementById('updateForm');
				form.submit();
			}
			else{
				alert("Total Debit & Credit Must be Equal")
			}

		});

		var totalDebit=0, totalCredit=0, debit=0, credit=0;
		var table = document.getElementById("Purchase1Table"); // Get the table element
        var rowCount = table.rows.length; // Get the total number of rows

		var itemCount = rowCount;
		$('#itemCount').val(itemCount);

		for (var j=0;j<rowCount; j++){

			debit = table.rows[j].cells[5].querySelector('input').value; // Get the value of the input field in the specified cell
			totalDebit = totalDebit + Number(debit);

			credit = table.rows[j].cells[6].querySelector('input').value; // Get the value of the input field in the specified cell
			totalCredit = totalCredit + Number(credit);
		}
		$('#total_credit').val(totalCredit);
		$('#total_debit').val(totalDebit);

	});

    function removeRow(button) {
		var tableRows = $("#Purchase1Table tr").length;
		if(tableRows>1){
			var row = button.parentNode.parentNode;
			row.parentNode.removeChild(row);
			itemCount = Number($('#itemCount').val());
			itemCount = itemCount-1;
			$('#itemCount').val(itemCount);
		}   
		totalDebit();
		totalCredit();
    }

    document.getElementById('removeRowBtn').addEventListener('click', function() {
        var table = document.getElementById('myTable').getElementsByTagName('tbody')[0];
        if (table.rows.length > 0) {
            table.deleteRow(table.rows.length - 1);
        } else {
            alert("No rows to delete!");
        }
    });

	function addNewRow(){
		var lastRow =  $('#myTable tr:last');
		latestValue=lastRow[0].cells[2].querySelector('select').value;
		if(latestValue!=""){
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

			cell1.innerHTML  = '<input type="text" class="form-control" name="item_cod[]" id="item_cod'+index+'" onchange="getItemDetails('+index+','+1+')">';
			cell2.innerHTML  = '<input type="text" class="form-control" name="pur_qty2[]" step=".00001" value="0">';
			cell3.innerHTML  = '<select class="form-control" id="item_name'+index+'" autofocus onchange="getItemDetails('+index+','+2+')" name ="item_name[]" required>'+
									'<option value="" disabled selected>Select Account</option>'+
									'@foreach($items as $key => $row)'+	
                                        '<option value="{{$row->it_cod}}">{{$row->item_name}}</option>'+
                                    '@endforeach'+
								'</select>';
			cell4.innerHTML  = '<input type="text" class="form-control" id="remarks'+index+'" name="remarks[]">';
			cell5.innerHTML  = '<input type="number" id="pur_qty'+index+'" class="form-control" name="pur_qty[]" onchange="rowTotal('+index+')" value="0" step=".00001">';
			cell6.innerHTML  = '<input type="number" id="pur_price'+index+'" class="form-control" name="pur_price[]"  value="0" onchange="rowTotal('+index+')" step=".00001">';
			cell7.innerHTML  = '<input type="number" id="amount'+index+'" class="form-control" name="amount[]"  value="0" onchange="tableTotal()" step=".00001">';
			cell8.innerHTML = '<button type="button" onclick="removeRow(this)" class="btn btn-danger" tabindex="1"><i class="fas fa-times"></i></button>';
			index++;

			itemCount = Number($('#itemCount').val());
			itemCount = itemCount+1;
			$('#itemCount').val(itemCount);
		}
	}

	function getItemDetails(row_no,option){
		var itemId;
		if(option==1){
			itemId = document.getElementById("item_cod"+row_no).value;
		}
		else if(option==2){
			itemId = document.getElementById("item_name"+row_no).value;
		}
		$.ajax({
			type: "GET",
			url: "/item/detail",
			data: {id:itemId},
			success: function(result){
				$('#item_cod'+row_no).val(result[0]['it_cod']);
				$('#item_name'+row_no).val(result[0]['it_cod']);
				$('#remarks'+row_no).val(result[0]['item_remark']);
				$('#pur_price'+row_no).val(result[0]['OPP_qty_cost']);
				addNewRow();
			},
			error: function(){
				alert("error");
			}
		});
		
	}

	function validateItemName(inputElement)
	{
		var item_name = inputElement.value;

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

        $.ajax({
            type: 'POST',
			url: '/item/new-item/validate',
            data: {'item_name': item_name},
            success: function(response){
				console.log(response)
            },
            error: function(response){
                var errors = response.responseJSON.errors;
                var errorMessage = 'Item Already Exists';
                alert(errorMessage);
            }
        });
    }

	function totalDebit(){
		var totalDebit=0;
		var debit=0;
		var table = document.getElementById("Purchase1Table"); // Get the table element
        var rowCount = table.rows.length; // Get the total number of rows

		for (var j=0;j<rowCount; j++){
			debit = table.rows[j].cells[5].querySelector('input').value; // Get the value of the input field in the specified cell
			totalDebit = totalDebit + Number(debit);
		}
		$('#total_debit').val(totalDebit);

	}

	function totalCredit(){
		var totalCredit=0;
		var credit=0;
		var table = document.getElementById("Purchase1Table"); // Get the table element
        var rowCount = table.rows.length; // Get the total number of rows

		for (var i=0;i<rowCount; i++){
			credit = table.rows[i].cells[6].querySelector('input').value; // Get the value of the input field in the specified cell
			totalCredit = totalCredit + Number(credit);
		}
		$('#total_credit').val(totalCredit);

	}

	function goBack() {
		window.history.back();
	}

</script>