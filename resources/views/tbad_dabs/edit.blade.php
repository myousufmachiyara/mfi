@extends('../layouts.header')
<body>
    <section class="body">
        @extends('../layouts.menu')
        <div class="inner-wrapper">
            <section role="main" class="content-body">
                @extends('../layouts.pageheader')
                <form method="post" id="myForm" action="{{ route('update-tbad-dabs') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
                    @csrf
                    <div class="row">
                        <div class="col-12 mb-3">
                            <section class="card">
                                <header class="card-header">
                                    <h2 class="card-title">Edit Pipe Bad Debts</h2>
                                </header>
                                <div class="card-body">
                                    <div class="row form-group mb-2">
                                        <div class="col-sm-12 col-md-2 mb-2">
                                            <label class="col-form-label">ID</label>
                                            <input type="text" placeholder="ID" class="form-control" disabled value="{{$tbad_dabs->bad_dabs_id}}">
                                            <input type="hidden" name="bad_dabs_id" placeholder="bad_dabs_id" class="form-control" value="{{$tbad_dabs->bad_dabs_id}}">
                                            <input type="hidden" id="itemCount" name="items" class="form-control">
                                        </div>
                                        <div class="col-sm-12 col-md-2 mb-2">
                                            <label class="col-form-label">Date</label>
                                            <input type="date" name="date" value="{{$tbad_dabs->date}}" class="form-control">
                                        </div>
                                        <div class="col-sm-12 col-md-8 mb-2">
                                            <label class="col-form-label">Reason</label>
                                            <textarea rows="2" cols="50" name="reason" id="reason" placeholder="Reason" class="form-control cust-textarea">{{$tbad_dabs->reason}}</textarea>
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
                                    <h2 class="card-title">Edit Pipe Bad Dabs Details</h2>
                                </header>
                                <div class="card-body" style="overflow-x:auto;min-height:450px;max-height:450px;overflow-y:auto">
                                    <table class="table table-bordered table-striped mb-0" id="myTable">
                                        <thead>
                                            <tr>
                                                <th width="10%">Item Code</th>
                                                <th width="30%">Item Name</th>
                                                <th width="20%">Remarks</th>
                                                <th width="15%">Qty Add</th>
                                                <th width="15%">Qty Less</th>
                                                <th width="10%"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbad_dabsTable">
                                            @foreach($tbad_dabs_items as $key1 => $tbad_dabs_item)
                                            <tr>
                                                <td>
                                                    <input type="number" id="item_code{{$key1}}" name="item_code[]" placeholder="Code" onchange="getItemDetails('{{$key1}}','1')" class="form-control" value="{{$tbad_dabs_item->item_cod}}" required>
                                                </td>
                                                <td>
                                                    <select class="form-control" id="item_name{{$key1}}" onchange="getItemDetails('{{$key1}}','2')" name="item_name2[]" required>
                                                        <option>Select Item</option>
                                                        @foreach($items as $key2 => $row)
                                                        <option value="{{$row->it_cod}}" {{ $row->it_cod == $tbad_dabs_item->item_cod ? 'selected' : '' }}>{{$row->item_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" id="remarks{{$key1}}" name="remarks[]" placeholder="Remarks" class="form-control" value="{{$tbad_dabs_item->remarks}}">
                                                </td>
                                                <td>
                                                    <input type="number" id="qtyadd{{$key1}}" name="qty_add[]" placeholder="Qty Add" class="form-control" step="any" value="{{$tbad_dabs_item->pc_add}}" required>
                                                </td>
                                                <td>
                                                    <input type="number" id="qtyless{{$key1}}" name="qty_less[]" placeholder="Qty Less" class="form-control" step="any" value="{{$tbad_dabs_item->pc_less}}" required>
                                                </td>
                                                <td>
                                                    <button type="button" onclick="removeRow(this)" class="btn btn-danger" tabindex="1"><i class="fas fa-times"></i></button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <footer class="card-footer">
                                    <div class="row mb-3" style="float:right; margin-right: 10%;">
                                        <div class="col-sm-2 col-md-6 pb-sm-3 pb-md-0">
                                             <label class="col-form-label">Total Add</label>
                                             <input type="number" id="total_add_show" step="any" placeholder="Total Add" class="form-control" disabled value=@php echo $total_add @endphp>
                                        </div>

                                        <div class="col-sm-6 col-md-6 pb-sm-3 pb-md-0">
                                            <label class="col-form-label">Total Less</label>
                                            <input type="number" id="total_less_show" step="any" placeholder="Total Less" class="form-control" disabled value=@php echo $total_less @endphp>
                                            <input type="hidden" id="total_less" name="total_less" step="any" placeholder="Total Less" class="form-control" value=@php echo $total_less @endphp>
                                        </div>

                                    </div>
                                </footer> 
                                
                                <footer class="card-footer">
                                    <div class="row form-group mb-2">
                                        <div class="text-end">
                                            <button type="button" class="btn btn-warning mt-2"  onclick="window.location='{{ route('all-tbad-dabs') }}'"> <i class="fas fa-trash"></i> Discard Changes</button>
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


////// ComboBox script start here /////
document.addEventListener('DOMContentLoaded', function() {
    const selectElementPattern = 'select[id^="item_name"]'; // Match all IDs that start with "item_name"
    let isTabPressed = false;

    // Detect if Tab key is pressed
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Tab') {
            isTabPressed = true;
        }
    });

    document.addEventListener('keyup', function(event) {
        if (event.key === 'Tab') {
            isTabPressed = false;
        }
    });

    // Apply the functionality to all item_name elements
    $(document).on('focus', selectElementPattern, function() {
        if (!isTabPressed && typeof $(this).select2 === 'function') {
            $(this).select2('open');
        }
    });

    $(document).on('select2:open', selectElementPattern, function() {
        setTimeout(function() {
            const searchField = document.querySelector('.select2-search__field');
            if (searchField) {
                searchField.focus();
            }
        }, 100);
    });
});
////// ComboBox script end here /////






var itemCount=0, index;
var totaladd=0, totalless=0;

var table = document.getElementById("tbad_dabsTable");
var rowCount = table.rows.length;

itemCount = rowCount;	
document.getElementById("itemCount").value = itemCount;

index = rowCount+1;

for (var j=0;j<rowCount; j++){
    less = table.rows[j].cells[4].querySelector('input').value;
    totalless = totalless + Number(less);

    add = table.rows[j].cells[3].querySelector('input').value;
    totaladd = totaladd + Number(add);
}
$('#total_less_show').val(totalless);
$('#total_add_show').val(totaladd);

$(document).ready(function() {
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
});

function removeRow(button) {
    var tableRows = $("#tbad_dabsTable tr").length;
    if(tableRows>1){
        var row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);
        index--;
        var itemCount = Number($('#itemCount').val());
        itemCount = itemCount-1;
        $('#itemCount').val(itemCount);
    }  
    tableTotal();
}

function addNewRow(){
    var lastRow =  $('#myTable tr:last');
    latestValue = lastRow[0].cells[1].querySelector('select').value;

    if(latestValue != "Select Item"){
        var table = document.getElementById('myTable').getElementsByTagName('tbody')[0];
        var newRow = table.insertRow(table.rows.length);

        var cell1 = newRow.insertCell(0);
        var cell2 = newRow.insertCell(1);
        var cell3 = newRow.insertCell(2);
        var cell4 = newRow.insertCell(3);
        var cell5 = newRow.insertCell(4);
        var cell6 = newRow.insertCell(5);

        cell1.innerHTML = '<input type="text" id="item_code'+index+'" name="item_code[]" placeholder="Code" onchange="getItemDetails('+index+','+1+')" class="form-control">';
        cell2.innerHTML = '<select class="form-control" id="item_name'+index+'" onchange="getItemDetails('+index+','+2+')" name="item_name">'+
                            '<option>Select Item</option>'+
                            @foreach($items as $key => $row)	
                                '<option value="{{$row->it_cod}}">{{$row->item_name}}</option>'+
                            @endforeach
                          '</select>';
        cell3.innerHTML = '<input type="text" id="remarks'+index+'" name="remarks[]" placeholder="Remarks" class="form-control">';
        cell4.innerHTML = '<input type="number" id="qtyadd'+index+'" name="qty_add[]" placeholder="Qty Add" value="0" onchange="tableTotal()" class="form-control">';
        cell5.innerHTML = '<input type="number" id="qtyless'+index+'" name="qty_less[]" placeholder="Qty Less" value="0" onchange="tableTotal()" class="form-control">';
        cell6.innerHTML = '<button type="button" onclick="removeRow(this)" class="btn btn-danger" tabindex="1"><i class="fas fa-times"></i></button>';

        var itemCount = Number($('#itemCount').val());
        itemCount = itemCount+1;
        $('#itemCount').val(itemCount);
        index++;
    }
}

function tableTotal(){
    totaladd=0, totalless=0;
    for (var j=0;j<index-1; j++){
        less = table.rows[j].cells[4].querySelector('input').value;
        totalless = totalless + Number(less);

        add = table.rows[j].cells[3].querySelector('input').value;
        totaladd = totaladd + Number(add);
    }
    $('#total_less_show').val(totalless);
    $('#total_add_show').val(totaladd);
}

function getItemDetails(row_no,option){
		var itemId;
		if(option==1){
			itemId = document.getElementById("item_code"+row_no).value;
		}
		else if(option==2){
			itemId = document.getElementById("item_name"+row_no).value;
		}
		$.ajax({
			type: "GET",
			url: "/item2/detail",
			data: {id:itemId},
			success: function(result){
                $('#item_code' + row_no).val(result[0]['it_cod']);
                $('#item_name' + row_no).val(result[0]['it_cod']);
                $('#remarks' + row_no).val(result[0]['item_remark']);
                
				addNewRow();
			},
			error: function(){
				alert("error");
			}
		});
		
	}

</script>
