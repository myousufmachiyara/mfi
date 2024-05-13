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
							<div class="col-12 mb-3">
								<section class="card">
									<header class="card-header">
										<h2 class="card-title">Item Entry</h2>
									</header>

									<div class="card-body" style="overflow-x:auto;min-height:450px;max-height:450px;overflow-y:auto">
										<table class="table table-bordered table-striped mb-0" id="myTable" >
											<thead>
												<tr>
													<!-- <th width="4%">Code</th> -->
													<th width="">Group</th>
													<th width="">Name</th>
													<th width="">Remarks</th>
													<th width="">P.Price</th>
													<th width="">PR.Date</th>
													<th width="">S.Price</th>
													<th width="">SR.Date</th>
													<th width="">Qty</th>
													<th width="">Date</th>
													<th width="">S.Level</th>
													<th width="">L.Price</th>
													<th width="">Action</th>
												</tr>
											</thead>
											<tbody id="ItemsTable">
												<tr>
													<!-- <td>
														<input type="number" class="form-control" disabled>
													</td> -->
													<td>
														<input type="hidden" id="itemCount" name="items" value="1" placeholder="Code" class="form-control">
														<select class="form-control" name ="item_group[]" onchange="addNewRow(1)" required>
															<option selected>Select Group</option>
															@foreach($item_groups as $key => $row)	
																<option value="{{$row->item_group_cod}}">{{$row->group_name}}</option>
															@endforeach
														</select>
													</td>
													<td>
														<input type="text" class="form-control" name="item_name[]" required>
													</td>
													<td>
														<input type="text" class="form-control" name="item_remarks[]" required>
													</td>
													<td>
														<input type="number" class="form-control" name="item_pur_cost[]" required>
													</td>
													<td>
														<input type="date" class="form-control" name="purchase_rate_date[]" required>
													</td>
													<td>
														<input type="number" class="form-control" name="item_s_price[]" required>
													</td>
													<td>
														<input type="date" class="form-control" name="sale_rate_date[]" required>
													</td>

													<td>
														<input type="number" class="form-control" name="item_stock[]" required>
													</td>
													<td>
														<input type="date" class="form-control" name="item_date[]" required>
													</td>
													<td>
														<input type="number" class="form-control" name="item_stock_level[]" required>
													</td>
													<td>
														<input type="number" class="form-control" name="item_l_price[]" required>
													</td>
													<td>
														<button type="button" onclick="removeRow(this)" class="btn btn-danger" tabindex="1"><i class="fas fa-times"></i></button>
														<button type="button" onclick="addNewRow(1)" class="btn btn-primary" tabindex="1"><i class="fas fa-plus"></i></button>
													</td>
												</tr>
											</tbody>
										</table>
									</div>

									<footer class="card-footer">
										<div class="row form-group mb-2">
											<div class="text-end">
												<button type="submit" class="btn btn-primary mt-2"> <i class="fas fa-save"></i> Add Items</button>
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
		latestValue=lastRow[0].cells[1].querySelector('select').value;

		if(latestValue!="Select Group"){
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
			var cell9 = newRow.insertCell(8);
			var cell10 = newRow.insertCell(9);
			var cell11 = newRow.insertCell(10);

			cell1.innerHTML  = '<input type="text" class="form-control" disabled>';
			cell2.innerHTML  = '<select class="form-control" onclick="addNewRow('+index+')" name ="item_group[]">'+
									'<option>Select Group</option>'+
									@foreach($item_groups as $key => $row)	
										'<option value="{{$row->item_group_cod}}">{{$row->group_name}}</option>'+
									@endforeach
								'</select>';
			cell3.innerHTML  = '<input type="text" class="form-control" name="item_name[]">';
			cell4.innerHTML  = '<input type="text"   class="form-control" name="item_remarks[]">';
			cell5.innerHTML  = '<input type="number" class="form-control" name="item_s_price[]">';
			cell6.innerHTML  = '<input type="number" class="form-control" name="item_pur_cost[]">';
			cell7.innerHTML  = '<input type="number" class="form-control" name="item_stock[]">';
			cell8.innerHTML  = '<input type="date" class="form-control" name="item_date[]">';
			cell9.innerHTML  = '<input type="number" class="form-control" name="item_stock_level[]">';
			cell10.innerHTML = '<input type="number" class="form-control" name="item_l_price[]">';
			cell11.innerHTML = '<button type="button" onclick="removeRow(this)" class="btn btn-danger" tabindex="1"><i class="fas fa-times"></i></button> <button type="button" onclick="addNewRow('+index+')" class="btn btn-primary" tabindex="1"><i class="fas fa-plus"></i></button>';

			index++;

			itemCount = Number($('#itemCount').val());
			itemCount = itemCount+1;
			$('#itemCount').val(itemCount);
		}
	}

</script>