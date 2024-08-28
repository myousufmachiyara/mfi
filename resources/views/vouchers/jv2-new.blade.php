@extends('../layouts.header')
	<body>
		<section class="body">
			@extends('../layouts.menu')
			<div class="inner-wrapper">
				<section role="main" class="content-body">
					@extends('../layouts.pageheader')
					<form method="post" action="{{ route('store-jv2') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';" id="addForm">
						@csrf
						<div class="row">
							<div class="col-12 mb-3">								
								<section class="card">
									<header class="card-header">
										<h2 class="card-title">Journal Voucher 2</h2>
									</header>

									<div class="card-body">
										<div class="row form-group mb-2">
											<div class="col-sm-12 col-md-4 mb-2">
												<label class="col-form-label" >RC. #</label>
												<input type="text" placeholder="RC. #" class="form-control" disabled>
												<input type="hidden" id="itemCount" name="items" value="1" class="form-control">
												<input type="hidden" id="prevInvoices" name="prevInvoices" value="0" class="form-control">
											</div>

											<div class="col-sm-12 col-md-4 mb-2">
												<label class="col-form-label" >Date</label>
												<input type="date" name="jv_date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
											</div>

											<div class="col-4 mb-2">
												<label class="col-form-label">Select Customer To See Prev Invoices</label>
												<select data-plugin-selecttwo class="form-control" id="customer_name" autofocus name="customer_name" onchange="getPendingInvoices()" required>
													<option value="" disabled selected>Select Account</option>
													@foreach($acc as $key1 => $row1)	
														<option value="{{$row1->ac_code}}">{{$row1->ac_name}}</option>
													@endforeach
												</select>																			
											</div>

											<div class="col-12 mb-2">
												<table class="table table-bordered table-striped mb-0 mt-2">
													<thead>
														<tr>
															<th></th>
															<th width="">Inv #</th>
															<th width="">Bill</th>
															<th width="">Balance</th>
															<th width="">Amount</th>
														</tr>
													</thead>
													<tbody id="pendingInvoices">
														<tr>

														</tr>
													</tbody>
												</table>										
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
										<h2 class="card-title">JV2 Details</h2>
									</header>
									<div class="card-body" style="overflow-x:auto;min-height:450px;max-height:450px;overflow-y:auto">
										<table class="table table-bordered table-striped mb-0" id="myTable" >
											<thead>
												<tr>
													<!-- <th width="4%">Code</th> -->
													<th width="">Account Name</th>
													<th width="">Remarks</th>
													<th width="">Bank Name</th>
													<th width="">Instr. #</th>
													<th width="">Chq Date</th>
													<th width="">Debit</th>
													<th width="">Credit</th>
													<th width=""></th>
												</tr>
											</thead>
											<tbody id="JV2Table">
												<tr>
													<td>
														<select class="form-control" autofocus  name ="account_cod[]" onchange="addNewRow(1)" required>
															<option value="" disabled selected>Select Account</option>
															@foreach($acc as $key => $row)	
																<option value="{{$row->ac_code}}">{{$row->ac_name}}</option>
															@endforeach
														</select>
													</td>	
													<td>
														<input type="text" class="form-control" name="remarks[]">
													</td>
													<td>
														<input type="text" class="form-control" name="bank_name[]">
													</td>
													<td>
														<input type="text" class="form-control" name="instrumentnumber[]">
													</td>
                                                    <td>
														<input type="date" class="form-control" name="chq_date[]" size=5 value="<?php echo date('Y-m-d'); ?>" >
                                                    </td>
													<td>
														<input type="number" class="form-control" name="debit[]" onchange="totalDebit()" required value="0" step="any">
													</td>

													<td>
														<input type="number" class="form-control" name="credit[]" onchange="totalCredit()" required value="0" step="any">
													</td>
													<td style="vertical-align: middle;">
														<button type="button" onclick="removeRow(this)" class="btn btn-danger" tabindex="1"><i class="fas fa-times"></i></button>
													</td>
												</tr>
											</tbody>
										</table>
									</div>

									<footer class="card-footer" >
										<div class="row mb-3" >
											<div class="col-3 mb-2">
												<label class="col-form-label">Narration</label>
												<textarea rows="1" cols="50" name="narration" id="narration" placeholder="Narration" class="form-control" required></textarea>
											</div>
											<div class="col-3 mb-3">
												<label class="col-form-label">Attachements</label>
												<input type="file" class="form-control" name="att[]" multiple accept=".zip, appliation/zip, application/pdf, image/png, image/jpeg">
											</div>
											<div class="col-sm-2 col-md-3 pb-sm-3 pb-md-0" style="float:right">
												<label class="col-form-label">Total Debit</label>
												<input type="number" id="total_debit" name="total_debit" placeholder="Total Debit" class="form-control" disabled>
											</div>
											<div class="col-sm-6 col-md-3 pb-sm-3 pb-md-0" style="float:right">
												<label class="col-form-label">Total Credit</label>
												<input type="number" id="total_credit" name="total_credit" placeholder="Total Credit" class="form-control" disabled>
											</div>
										</div>
									</footer>
									<footer class="card-footer">
										<div class="row form-group mb-2">
											<div class="text-end">
												<button type="button" class="btn btn-danger mt-2"  onclick="window.location='{{ route('all-jv2') }}'"> <i class="fas fa-trash"></i> Discard Voucher</button>
												<button type="submit" class="btn btn-primary mt-2"> <i class="fas fa-save"></i> Add Voucher</button>
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

	var index=2;
	var itemCount = Number($('#itemCount').val());

	$(document).ready(function() {
		$(window).keydown(function(event){
			if(event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		});

		$('#addForm').on('submit', function(e){
            e.preventDefault();
			var total_credit=$('#total_credit').val();
			var total_debit=$('#total_debit').val();
			if(total_debit==total_credit){
				var form = document.getElementById('addForm');
				form.submit();
			}
			else{
				alert("Total Debit & Credit Must be Equal")
			}

		});	
	});

    function removeRow(button) {
		var tableRows = $("#JV2Table tr").length;
		if(tableRows>1){
			var row = button.parentNode.parentNode;
			row.parentNode.removeChild(row);
			index--;	
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

	function addNewRow(id){
		var lastRow =  $('#myTable tr:last');
		latestValue=lastRow[0].cells[0].querySelector('select').value;

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

			cell1.innerHTML  = '<select class="form-control" autofocus onclick="addNewRow('+index+')" name ="account_cod[]" required>'+
									'<option value="" disabled selected>Select Account</option>'+
									'@foreach($acc as $key => $row)'+
                                        '<option value="{{$row->ac_code}}">{{$row->ac_name}}</option>'+
                                    '@endforeach';
								'</select>';
			cell2.innerHTML  = '<input type="text" class="form-control" name="remarks[]" >';
			cell3.innerHTML  = '<input type="text" class="form-control" name="bank_name[]" >';
			cell4.innerHTML  = '<input type="text" class="form-control" name="instrumentnumber[]">';
			cell5.innerHTML  = '<input type="date" class="form-control" name="chq_date[]"  value="<?php echo date('Y-m-d'); ?>" >';
			cell6.innerHTML  = '<input type="number" class="form-control" name="debit[]"  required value="0" onchange="totalDebit()" step="any">';
			cell7.innerHTML  = '<input type="number" class="form-control" name="credit[]"  required value="0" onchange="totalCredit()" step="any">';
			cell8.innerHTML = '<button type="button" onclick="removeRow(this)" class="btn btn-danger" tabindex="1"><i class="fas fa-times"></i></button>';
			index++;

			itemCount = Number($('#itemCount').val());
			itemCount = itemCount+1;
			$('#itemCount').val(itemCount);
		}
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
		var table = document.getElementById("JV2Table"); // Get the table element
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
		var table = document.getElementById("JV2Table"); // Get the table element
        var rowCount = table.rows.length; // Get the total number of rows

		for (var i=0;i<rowCount; i++){
			credit = table.rows[i].cells[6].querySelector('input').value; // Get the value of the input field in the specified cell
			totalCredit = totalCredit + Number(credit);
		}
		$('#total_credit').val(totalCredit);
	}

	function getPendingInvoices(){
		var cust_id=$('#customer_name').val();
		$('#prevInvoices').val(1)
		
		var table = document.getElementById('pendingInvoices');
        while (table.rows.length > 0) {
            table.deleteRow(0);
        }

		$.ajax({
			type: "GET",
			url: "/vouchers/jv2/pendingInvoice/"+cust_id,
			success: function(result){
				$.each(result, function(k,v){
					var html="<tr>";
					html+= "<td style='vertical-align: middle;' width='2%'><input type='checkbox' name='selectedItems[]' value="+(k+1)+"></td>"
					html+= "<td width='8%''><input type='number' class='form-control' value="+v['Sal_inv_no']+" disabled><input type='hidden' name='invoice_nos[]' class='form-control' value="+v['Sal_inv_no']+"></td>"
					html+= "<td width='30%'><input type='number' class='form-control' value="+Math.round(v['b_amt'])+" disabled><input type='hidden' name='balance_amount[]' class='form-control' value="+Math.round(v['b_amt'])+"></td>"
					html+= "<td width='30%'><input type='number' class='form-control' value="+Math.round(v['bill_balance'])+" disabled><input type='hidden' name='bill_amount[]' class='form-control' value="+Math.round(v['bill_balance'])+"></td>"
					html+= "<td width='30%'><input type='number' name='rec_amount[]' class='form-control' value='0' step='any' ></td>"
					html+="</tr>";
					$('#pendingInvoices').append(html);
				});
			},
			error: function(){
				alert("error");
			}
		});
	}
</script>