@extends('../layouts.header')
	<body>
		<section class="body">
			@extends('../layouts.menu')
			<div class="inner-wrapper">
				<section role="main" class="content-body">
					@extends('../layouts.pageheader')
					<form method="post" action="{{ route('store-item') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
						@csrf
						<div class="row">
							<div class="col-2 mb-3">								
								<section class="card">
									<header class="card-header">
										<h2 class="card-title">Journal Voucher 2</h2>
									</header>

									<div class="card-body">
										<div class="row form-group mb-2">
											<div class="col-sm-12 col-md-12 mb-2">
												<label class="col-form-label" >RC. #</label>
												<input type="text" name="invoice_no" placeholder="Invoice No." class="form-control" disabled>
											</div>

											<div class="col-sm-12 col-md-12 mb-2">
												<label class="col-form-label" >Date</label>
												<input type="date" name="date" required value="<?php echo date('Y-m-d'); ?>" class="form-control">
											</div>
											<div class="col-12 mb-3">
												<label class="col-form-label">Narration</label>
												<textarea rows="4" cols="50" name="remarks" id="narration" placeholder="Narration" class="form-control"></textarea>
											</div>
									  </div>
									</div>
								</section>
							</div>
							<div class="col-10 mb-3">
								<section class="card">
									<!-- <header class="card-header">
										<h2 class="card-title">Journal Voucher 2 Details</h2>
									</header> -->
									<div class="card-body" style="overflow-x:auto;min-height:450px;max-height:450px;overflow-y:auto">
										<table class="table table-bordered table-striped mb-0" id="myTable" >
											<thead>
												<tr>
													<!-- <th width="4%">Code</th> -->
													<th width="">Account Name</th>
													<th width="">Remarks</th>
													<th width="">Bank Name</th>
													<th width="9%">Instr. #</th>
													<th width="10%">Chq Date</th>
													<th width="10%">Debit</th>
													<th width="10%">Credit</th>
													<th width=""></th>
												</tr>
											</thead>
											<tbody id="ItemsTable">
												<tr>
													<td>
														<input type="hidden" id="itemCount" name="items" value="1" placeholder="Code" class="form-control">
														<select class="form-control" name ="account[]" onchange="addNewRow(1)" required>
															<option value="0" disabled selected>Select Account</option>
															@foreach($acc as $key => $row)	
																<option value="{{$row->ac_code}}">{{$row->ac_name}}</option>
															@endforeach
														</select>
													</td>
													<td>
														<input type="text" class="form-control" name="remarks[]" onchange="validateItemName(this)" required>
													</td>
													<td>
														<input type="text" class="form-control" name="bank_name[]">
													</td>
													<td>
														<input type="number" class="form-control" name="instrumentnumber[]" required value="0" step=".00001">
													</td>
                                                    <td>
														<input type="date" class="form-control" style="max-width: 124px" name="date[]" size=5 required value="<?php echo date('Y-m-d'); ?>" >
                                                    </td>
													<td>
														<input type="number" class="form-control" name="debit[]" required value="0" step=".00001">
													</td>

													<td>
														<input type="number" class="form-control" name="credit[]" required value="0" step=".00001">
													</td>
													<td style="vertical-align: middle;">
														<button type="button" onclick="removeRow(this)" class="btn btn-danger" tabindex="1"><i class="fas fa-times"></i></button>
													</td>
												</tr>
											</tbody>
										</table>
									</div>

									<footer class="card-footer">
										<div class="row form-group mb-2">
											<div class="text-end">
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
	});

    function removeRow(button) {
		var tableRows = $("#ItemsTable tr").length;
		if(tableRows>1){
			var row = button.parentNode.parentNode;
			row.parentNode.removeChild(row);
			index--;	
			itemCount = Number($('#itemCount').val());
			itemCount = itemCount-1;
			$('#itemCount').val(itemCount);
		}   
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

		if(latestValue!="0"){
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

			cell1.innerHTML  = '<select class="form-control" onclick="addNewRow('+index+')" name ="account[]" required>'+
									'<option value="0" disabled selected>Select Group</option>'+
									@foreach($acc as $key => $row)	
                                        '<option value="{{$row->ac_code}}">{{$row->ac_name}}</option>'+
                                    @endforeach
								'</select>';
			cell2.innerHTML  = '<input type="text" class="form-control" name="remarks[]" onchange="validateItemName(this)" required>';
			cell3.innerHTML  = '<input type="text"   class="form-control" name="bank_name[]" required>';
			cell4.innerHTML  = '<input type="number" class="form-control" name="instrumentnumber[]" required value="0" step=".00001">';
			cell5.innerHTML  = '<input type="date" class="form-control" style="max-width: 124px" name="date[]" required value="<?php echo date('Y-m-d'); ?>" >';
			cell6.innerHTML  = '<input type="number" class="form-control" name="debit[]" required value="0" step=".00001">';
			cell7.innerHTML  = '<input type="number" class="form-control" name="credit[]" required value="0" step=".00001">';
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
</script>