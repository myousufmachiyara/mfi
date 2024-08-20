@extends('../layouts.header')
	<body>
		<section class="body">
			@extends('../layouts.menu')
			<div class="inner-wrapper">
				<section role="main" class="content-body">
					@extends('../layouts.pageheader')
					<form method="post" id="myForm" action="{{ route('store-tstock-in-invoice') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
						@csrf
						<div class="row">
							<div class="col-12 mb-3">								
								<section class="card">
									<header class="card-header">
										<h2 class="card-title">New Stock In Pipe</h2>
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

											<div class="col-sm-12 col-md-4">
												<label class="col-form-label">Account Name</label>
												<select class="form-control" id="coa_name" name="account_name" required>
													<option value="" disabled selected>Select Account</option>
													@foreach($coa as $key => $row)	
														<option value="{{$row->ac_code}}">{{$row->ac_name}}</option>
													@endforeach
												</select>
											</div>
											
											<div class="col-sm-12 col-md-2">
												<label class="col-form-label" >Purchase Inv#</label>
												<input type="text" name="pur_inv" placeholder="Purchase Inv#" class="form-control">
											</div>
											<div class="col-sm-12 col-md-2">
												<label class="col-form-label" >Mill Inv/Gate#</label>
												<input type="text" name="mill_gate" placeholder="Mill Inv/Gate#" class="form-control">
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
										    <tbody id="tstock_inTable">
											 <tr>
                                                <td>
                                                    <input type="number" id="item_code1" name="item_code[]" placeholder="Code" class="form-control" required onchange="getItemDetails(1,1)">
                                                </td>
                                                <td>
                                                    <select class="form-control" id="item_name1" onchange="getItemDetails(1,2)" name="item_name[]" required>
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
                                                    <input type="number" id="qty" name="qty[]" onchange="tableTotal()" placeholder="Qty" value="0" step="any" required class="form-control">
                                                    <input type="number" class="form-control" name="weight_per_piece[]" id="weight_per_piece1" onchange="CalculateRowWeight(1)" value="0" step="any" required>
                                                </td>
                                                <td>
                                                    <input type="number" id="weight" name="weight[]" onchange="tableTotal()" placeholder="Weight" value="0" step="any"  class="form-control">
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
                                            <input type="number" id="total_qty_show" placeholder="Total Qty" class="form-control" step="any" disabled>
                                            <input type="hidden" id="totalqty" name="totalqty" step="any" placeholder="Total Qty" class="form-control">
                                        </div>
                                        
                                        <div class="col-sm-6 col-md-6 pb-sm-3 pb-md-0">
                                            <label class="col-form-label">Total Weight</label>
                                            <input type="number" id="total_weight_show" placeholder="Total weight" class="form-control" step="any" disabled>
                                            <input type="hidden" id="totalweight" name="totalweight" step="any" placeholder="Total Weight" class="form-control">
                                        </div>
                                        
                                    </div>
                                </footer>
                                
                                <footer class="card-footer">
                                    <div class="row form-group mb-2">
                                        <div class="text-end">
                                            <button type="button" class="btn btn-danger mt-2" onclick="window.location='{{ route('all-tstock-in') }}'"> <i class="fas fa-trash"></i> Discard Entry</button>
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
    var tableRows = $("#tstock_inTable tr").length;
    if (tableRows > 1) {
        $(button).closest('tr').remove();
        index--;
        $('#itemCount').val(Number($('#itemCount').val()) - 1);
        tableTotal();
    }
}

var lastRow =  $('#myTable tr:last');
		latestValue=lastRow[0].cells[1].querySelector('select').value;
		if(latestValue!=""){
			var table = document.getElementById('myTable').getElementsByTagName('tbody')[0];
			var newRow = table.insertRow(table.rows.length);

			var cell1 = newRow.insertCell(0);
			var cell2 = newRow.insertCell(1);
			var cell3 = newRow.insertCell(2);
			var cell4 = newRow.insertCell(3);
			var cell5 = newRow.insertCell(4);
			var cell6 = newRow.insertCell(5);


			cell1.innerHTML  = '<input type="text" class="form-control" name="item_cod[]" id="item_cod'+index+'" onchange="getItemDetails('+index+','+1+')" required>';
			cell2.innerHTML  = '<select class="form-control" id="item_name'+index+'" autofocus onchange="getItemDetails('+index+','+2+')" name="item_name[]" required>'+
									'<option value="" disabled selected>Select Account</option>'+
									'@foreach($items as $key => $row)'+	
                                        '<option value="{{$row->it_cod}}">{{$row->item_name}}</option>'+
                                    '@endforeach'+
								'</select>';
			cell3.innerHTML  = '<input type="text" class="form-control" id="remarks'+index+'" name="remarks[]">';
			cell4.innerHTML  = '<input type="text" class="form-control" onchange="rowTotal('+index+')" id="pur2_qty2'+index+'" value="0" name="pur2_qty2[]" step="any" required><input type="hidden" class="form-control" id="weight_per_piece'+index+'" name="weight_per_piece[]" onchange="CalculateRowWeight('+index+')" value="0" step="any" required>';
			cell5.innerHTML  = '<input type="number" id="amount'+index+'" class="form-control"  value="0" step="any" disabled>';
			cell6.innerHTML = '<button type="button" onclick="removeRow(this)" class="btn btn-danger" tabindex="1"><i class="fas fa-times"></i></button>';

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
			url: "/item2/detail",
			data: {id:itemId},
			success: function(result){
				$('#item_cod'+row_no).val(result[0]['it_cod']);
				$('#item_name'+row_no).val(result[0]['it_cod']);
				$('#remarks'+row_no).val(result[0]['item_remark']);
				$('#weight_per_piece'+row_no).val(result[0]['weight']);
				$('#weight_per_piece'+row_no+'').trigger('change')
				addNewRow();
			},
			error: function(){
				alert("error");
			}
		});
	}

function tableTotal() {
    var totalqty = 0;
    var totalweight = 0;
    $('#tbad_dabsTable tr').each(function() {
        totalqty += Number($(this).find('input[name="qty[]"]').val());
        totalweight += Number($(this).find('input[name="weight[]"]').val());
    });

    $('#total_qty_show').val(totalqty);
    $('#totalqty').val(totalqty);
    $('#total_weight_show').val(totalweight);
    $('#totalweight').val(totalweight);
}
</script>