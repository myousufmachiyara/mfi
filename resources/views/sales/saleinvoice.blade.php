@extends('../layouts.header')
	<body>
		<section class="body">
			@extends('../layouts.menu')

			<div class="inner-wrapper">
				<section role="main" class="content-body">
					@extends('../layouts.pageheader')

					<!-- start: page -->
					<div class="row">
						<div class="col-lg-12">
							<!-- <form> -->
								<section class="card">
									<header class="card-header">
										<div class="card-actions">
											<button type="button" class="mb-1 me-1 btn btn-warning"><i class="fas fa-sync" ></i> Refresh</button>											
											<button type="button" class="mb-1 me-1 btn btn-danger"><i class="fas fa-print"></i> Print</button>											
										</div>
										<h2 class="card-title">Party Details</h2>
									</header>

									<div class="card-body">
										<div class="row form-group mb-3">
											<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
												<input type="text" name="firstName" placeholder="Invoice No." class="form-control" disabled>
											</div>

											<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
												<input type="date" name="firstName" placeholder="Date" class="form-control">
											</div>

											<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
												<input type="text" name="firstName" placeholder="Bill No." class="form-control" disabled>
											</div>

											<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
												<select class="form-control mb-3">
													<option selected>Status</option>
													<option>Bill Not Final</option>
													<option>Finalized</option>
												</select>												
											</div>

										</div>
										<div class="row">
											<div class="col-lg-7">
												<select class="form-control mb-3">
													<option selected>Select Chart Of Account</option>
													<option>COA 1</option>
													<option>COA 2</option>
												</select>											
											</div>

											<div class="col-lg-1">
												<button type="button" class="mb-1 me-1 btn btn-primary"><i class="fas fa-plus"></i> New</button>											
											</div>
											<div class="col-lg-4">
												<input type="text" name="nop" placeholder="Name Of Person" class="form-control" disabled>
											</div>
										</div>
										<div class="row form-group mb-3">
											<div class="col-lg-6 col-md-3 pb-sm-3 pb-md-0">
												<input type="text" name="address" placeholder="Address" class="form-control" disabled>
											</div>

											<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
												<input type="text" name="cash-pur_phone" placeholder="Cash - Pur_phone" class="form-control" disabled>
											</div>

											<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
												<input type="text" name="remarks" placeholder="Remarks" class="form-control" disabled>
											</div>
										</div>
									</div>
								</section>

								<section class="card">
									<header class="card-header">
										<div class="card-actions">
											<button id="addRowBtn" class="btn btn-primary addRowBtn"> <i class="fas fa-plus"></i> Add Item</button>
										</div>

										<h2 class="card-title">Item Details</h2>
									</header>
									<div class="card-body" style="overflow-x:auto;">
										<table class="table table-bordered table-striped mb-0" id="myTable" >
											<thead>
												<tr>
													<th width="5%">Item Code</th>
													<th width="5%">Qty</th>
													<th width="5%">Item Name</th>
													<th width="5%">Remarks</th>
													<th width="5%">Weight(kgs)</th>
													<th width="5%">Price</th>
													<th width="5%">Amount</th>
													<th width="5%">Action</th>
												</tr>
											</thead>
											<tbody id="saleInvoiceTable">
												
											</tbody>
										</table>
									</div>
									<!-- <footer class="card-footer">
						
									</footer> -->
								</section>

								<section class="card">
									<header class="card-header">
										<div class="card-actions">
											<h3 class="font-weight-bold text-color-dark mt-0 mb-0 text-5">Net Amount</h3>
											<span class="d-flex align-items-center justify-content-lg-end" id="netTotal">
													<strong class="text-color-dark text-4" >PKR 0.00</strong>
											</span>
										</div>
										<h2 class="card-title">Invoice Summary Details</h2>
									</header>
									<div class="card-body">
										<div class="row form-group mb-3">
											<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
												<input type="text" id="totalAmount" name="totalAmount" placeholder="Total Amount" class="form-control" disabled>
											</div>

											<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
												<input type="text" id="total_weight" name="total_weight" placeholder="Total Weight" class="form-control" disabled>
											</div>

											<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
												<input type="text" id="gst" name="gst" onchange="netTotal()" placeholder="GST" class="form-control">
											</div>

											<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
												<input type="text" id="convance_charges" onchange="netTotal()" name="convance_charges" placeholder="Convance Charges" class="form-control">
											</div>

											<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
												<input type="text" id="labour_charges"  onchange="netTotal()" name="labour_charges" placeholder="Labour Charges" class="form-control">
											</div>

											<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
												<input type="text" id="bill_discount"  onchange="netTotal()" name="bill_discount" placeholder="Bill Discount" class="form-control">
											</div>
										</div>
										<div class="row mb-3">
											<div class="col-sm-2 col-md-4 pb-sm-3 pb-md-0">
												<input type="file" class="form-control">
											</div>
										</div>
									</div>
									<footer class="card-footer text-end">
										<button type="submit" class="btn btn-primary">Create Invoice</button>
									</footer>
								</section>
							<!-- </form> -->
						</div>
					</div>
					<!-- end: page -->
				</section>
			</div>
		</section>
		<div id="dialog" class="modal-block mfp-hide">
			<section class="card">
				<header class="card-header">
					<h2 class="card-title">Are you sure?</h2>
				</header>
				<div class="card-body">
					<div class="modal-wrapper">
						<div class="modal-text">
							<p>Are you sure that you want to delete this row?</p>
						</div>
					</div>
				</div>
				<footer class="card-footer">
					<div class="row">
						<div class="col-md-12 text-end">
							<button id="dialogConfirm" class="btn btn-primary">Confirm</button>
							<button id="dialogCancel" class="btn btn-default">Cancel</button>
						</div>
					</div>
				</footer>
			</section>
		</div>
        @extends('../layouts.footerlinks')
	</body>
</html>
<script>
	var index=0;
    document.getElementById('addRowBtn').addEventListener('click', function() {
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

        cell1.innerHTML = '<input type="text" id="item_code'+index+'" name="item_code" placeholder="Item Code" class="form-control"><input type="number" name="row_no" value="'+index+'" class="form-control" hidden>';
        cell2.innerHTML = '<input type="number" id="item_qty'+index+'" name="item_qty" onchange="rowTotal('+index+')" placeholder="Quantity" class="form-control">';
		cell3.innerHTML = '<select class="form-control mb-3" id="item_code'+index+'" name="item_name"><option>Select Item</option><option>Item 1</option><option>Item 2</option></select>';
        cell4.innerHTML = '<input type="text" id="remarks'+index+'" name="remarks" placeholder="Remarks" class="form-control">';
        cell5.innerHTML = '<input type="number" id="weight'+index+'" name="weight" onchange="rowTotal('+index+')" placeholder="Weight (kgs)" class="form-control">';
        cell6.innerHTML = '<input type="number" id="price'+index+'" name="price" onchange="rowTotal('+index+')" placeholder="Price" class="form-control">';
        cell7.innerHTML = '<input type="number" id="amount'+index+'" name="amount" onchange="rowTotal('+index+')" placeholder="Amount" class="form-control" disabled>';
        cell8.innerHTML = '<button onclick="removeRow(this)" class="btn btn-danger"><i class="fas fa-times"></i></button>';

		index++;
    });

    function removeRow(button) {
        var row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);
		index--;
    }

    document.getElementById('removeRowBtn').addEventListener('click', function() {
        var table = document.getElementById('myTable').getElementsByTagName('tbody')[0];
        if (table.rows.length > 0) {
            table.deleteRow(table.rows.length - 1);
        } else {
            alert("No rows to delete!");
        }
    });

	function rowTotal(index){
		var qty = $('#item_qty'+index+'').val();
		var price = $('#price'+index+'').val();
		var amount = qty * price;
		$('#amount'+index+'').val(amount);
		tableTotal();
	}

	function tableTotal(){
		var total=0;
		var weight=0;
		var tableRows = $("#saleInvoiceTable tr").length;
		for (var i = 0; i < tableRows; i++) {
			total = total + Number($('#amount'+i+'').val());
			weight = weight + Number($('#weight'+i+'').val());
        }
		$('#totalAmount').val(total);
		$('#total_weight').val(weight);
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
		document.getElementById("netTotal").innerHTML = '<strong class="text-color-dark text-4" >PKR '+netTotal+'.00</strong>';
	}

</script>