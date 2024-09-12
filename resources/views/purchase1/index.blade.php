@include('../layouts.header')
	<body>
        <style>
            #searchloader {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            border: 16px solid #f3f3f3; /* Light grey */
            border-top: 16px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        </style>
		<section class="body">
            @include('layouts.pageheader')
			<div class="inner-wrapper">
				<section role="main" class="content-body">                    
                    <div class="row">
                        <div class="col">
                            <section class="card">
                                <header class="card-header" style="display: flex;justify-content: space-between;">
                                    <h2 class="card-title">All Purchase Invoices</h2>
                                    <form class="text-end" action="{{ route('new-purchases1') }}" method="GET">
                                        <button type="submit" class="btn btn-primary mt-2"> <i class="fas fa-plus"></i> New Purchase Invoice</button>
                                    </form>
                                </header>
                               
                                <div class="card-body">
                                    <div class="row" style="justify-content:end">
                                        <div class="col-md-5" style="display:flex;">
                                            <select class="form-control" style="margin-right:10px" id="searchColId">
                                                <option value="default">Search All</option>
                                                <option value="0">by Code</option>
                                                <option value="2">by Date</option>
                                                <option value="3">by Account</option>
                                                <option value="4">by Person Name</option>
                                                <option value="5">by Remarks</option>
                                                <option value="6">by Sale Inv #</option>
                                                <option value="7">by Weight</option>
                                                <option value="8">by Bill Amount</option>
                                                <option value="12">by Net Amount</option>
                                            </select>
                                            <input class="form-control" placeholder="Search By..." onkeyup="searchTable()" id="searchInput" style="margin-right:10px">
                                            <!-- <button class="btn btn-danger" style="width:12em"> <i class="fas fa-filter"> &nbsp;</i> Filter </button> -->
                                        </div>
                                    </div>
                                    <div id="searchloader"></div>

                                    <div class="modal-wrapper">
                                        <table class="table table-bordered table-striped mb-0" id="searchableTable">
                                            <thead>
                                                <tr>
                                                    <th style="display:none">Inv #</th>
                                                    <th>Code</th>
                                                    <th>Date</th>
                                                    <th>Account Name</th>
                                                    <th>Person Name</th>
                                                    <th>Remarks</th>
                                                    <th>SaleInv #</th>
                                                    <th>Weight (kg)</th>
                                                    <th>Bill Amount</th>
                                                    <th>Convance Charges</th>
                                                    <th>Labour Charges</th>
                                                    <th>Discount</th>
                                                    <th>Net Amount</th>
                                                    <th>Att.</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pur1 as $key => $row)
                                                <tr>
                                                    <td style="display:none">{{$row->pur_id}}</td>
                                                    <td>{{$row->prefix}}{{$row->pur_id}}</td>
                                                    <td>{{ \Carbon\Carbon::parse($row->pur_date)->format('d-m-y') }}</td>
                                                    <td><strong>{{$row->ac_name}}</strong></td>
                                                    <td>{{$row->cash_saler_name}}</td>
                                                    <td>{{$row->pur_remarks}}</td>
                                                    <td>{{$row->sale_against}}</td>
                                                    <td>{{$row->weight_sum}}</td>
                                                    <td>{{$row->total_bill}}</td>
                                                    <td>{{$row->pur_convance_char}}</td>
                                                    <td>{{$row->pur_labor_char}}</td>
                                                    <td>{{$row->pur_discount}}</td>
                                                    @php ($net_amount=$row->total_bill+$row->pur_convance_char+$row->pur_labor_char-$row->pur_discount)
                                                    @if(substr(strval($row->net_amount), strpos(strval($row->net_amount), '.') + 1)>0) 
                                                        <td><strong style="font-size:15px">{{ rtrim(rtrim(number_format($net_amount), '0'), '.') }}</strong></td>
                                                    @else
                                                        <td><strong style="font-size:15px">{{ number_format(intval($net_amount))}}</strong></td>
                                                    @endif
                                                    <td><a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="getAttachements({{$row->pur_id}})" href="#attModal">View</a></td>
                                                    <td class="actions">
                                                        <!-- <a href="{{ route('print-purc1-invoice', $row->pur_id) }}" class="text-danger">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                        <span class="separator"> | </span> -->
                                                        <a href="{{ route('show-purchases1',$row->pur_id) }}" class="">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <span class="separator"> | </span>
                                                        <a href="{{ route('edit-purchases1',$row->pur_id) }}" class="">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </a>
                                                        <span class="separator"> | </span>
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="setId({{$row->pur_id}})" href="#deleteModal">
                                                            <i class="far fa-trash-alt" style="color:red"></i>
                                                        </a>
                                                    </td>

                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </section>		
			</div>
		</section>

        <div id="deleteModal" class="zoom-anim-dialog modal-block modal-block-danger mfp-hide">
            <form method="post" action="{{ route('delete-purchases1') }}" enctype="multipart/form-data">
                @csrf
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">Delete Purchase Invoice</h2>
                    </header>
                    <div class="card-body">
                        <div class="modal-wrapper">
                            <div class="modal-icon">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <div class="modal-text">
                                <p class="mb-0">Are you sure that you want to delete this Purchase Invoice?</p>
                                <input name="delete_purc1" id="deleteID" hidden>
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
            </form>
        </div>

        <div id="attModal" class="zoom-anim-dialog modal-block modal-block-danger mfp-hide">
            <section class="card">
                <header class="card-header">
                    <h2 class="card-title">All Attachements</h2>
                </header>
                <div class="card-body">

                        <table class="table table-bordered table-striped mb-0" id="datatable-default">
                            <thead>
                                <tr>
                                    <th>Attachement Path</th>
                                    <th>Download</th>
                                    <th>View</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody id="pur1_attachements">

                            </tbody>
                        </table>
                </div>
                <footer class="card-footer">
                    <div class="row">
                        <div class="col-md-12 text-end">
                            <button class="btn btn-default modal-dismiss">Cancel</button>
                        </div>
                    </div>
                </footer>
            </section>
        </div>
        @include('../layouts.footerlinks')
	</body>
</html>

<script>
    function searchTable() {

        document.getElementById('searchloader').style.display = 'block';

        // Get the input value
        const input = document.getElementById('searchInput').value.toUpperCase();
        const colId = $('#searchColId').val();

        // Get the table and rows
        const table = document.getElementById('searchableTable');
        const rows = table.getElementsByTagName('tr');
        setTimeout(function() { 
            if(colId=="default"){
                
                // Loop through all rows
                for (let i = 0; i < rows.length; i++) {
                    const cells = rows[i].getElementsByTagName('td'); // Get all cells in the current row
                    let found = false;
                    
                    // Loop through each cell in the row
                    for (let j = 0; j < cells.length; j++) {
                        const cellText = cells[j].textContent || cells[j].innerText;
                        
                        // Check if the cell text matches the input value
                        if (cellText.toUpperCase().indexOf(input) > -1) {
                            found = true;
                            break; // No need to check other cells in this row if a match is found
                        }
                    }
                    
                    // Show or hide the row based on whether a match was found
                    if (found) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }   

            else {
                for (let i = 1; i < rows.length; i++) {
                    const cells = rows[i].getElementsByTagName('td');
                    if (cells.length > 2) { // Ensure there are enough cells in the row
                        const columnText = cells[colId].textContent || cells[colId].innerText; // 2 for the third column
                        // Check if the column text matches the input value
                        if (columnText.toUpperCase().indexOf(input) > -1) {
                            rows[i].style.display = '';
                        } else {
                            rows[i].style.display = 'none';
                        }
                    }
                }
            }
            document.getElementById('loader').style.display = 'none';
        }, 200);  
    }
</script>

<script>
    function setId(id){
        $('#deleteID').val(id);
    }

    function getAttachements(id){

        var table = document.getElementById('pur1_attachements');
        while (table.rows.length > 0) {
            table.deleteRow(0);
        }

        $.ajax({
            type: "GET",
            url: "/purchase1/attachements",
            data: {id:id},
            success: function(result){
                console.log(result);
                $.each(result, function(k,v){
                    var html="<tr>";
                    html+= "<td>"+v['att_path']+"</td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 mr-2 me-1 text-danger' href='/purchase1/download/"+v['att_id']+"'><i class='fas fa-download'></i></a></td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 me-1 text-primary' href='/purchase1/view/"+v['att_id']+"' target='_blank'><i class='fas fa-eye'></i></a></td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 me-1 text-primary' href='#' onclick='deleteFile("+v['att_id']+")'><i class='fas fa-trash'></i></a></td>"
                    html+="</tr>";
                    $('#pur1_attachements').append(html);
                });
            },
            error: function(){
                alert("error");
            }
        });
    }

    function deleteFile(fileId) {
        if (!confirm('Are you sure you want to delete this file?')) {
            return;
        }

        fetch('/purchase1/deleteAttachment/' + fileId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                alert('File deleted successfully.');
                // Optionally, remove the element or reload the page
                location.reload();
            } else {
                return response.json().then(data => {
                    throw new Error(data.message || 'An error occurred.');
                });
            }
        })
        .catch(error => {
            alert(error.message);
        });
    }

</script>