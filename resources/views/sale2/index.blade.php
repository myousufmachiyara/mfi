@include('../layouts.header')	
    <body>
		<section class="body">
            @include('layouts.homepageheader')
			<div class="inner-wrapper cust-pad">
				@include('layouts.leftmenu')
				<section role="main" class="content-body">
                    <div class="row">
                        <div class="col">
                            <section class="card">
                                <header class="card-header" style="display: flex;justify-content: space-between;">
                                    <h2 class="card-title">All Sale Pipe</h2>
                                    <form class="text-end" action="{{ route('new-sales2') }}" method="GET">
                                        <button type="submit" class="btn btn-primary"> <i class="fas fa-plus"></i> New Invoice</button>
                                    </form>
                                </header>
                                <div class="card-body">
                                    <div>
                                        <div class="col-md-5" style="display:flex;">
                                            <select class="form-control" style="margin-right:10px" id="columnSelect">
                                                <option selected disabled>Search by</option>
                                                <option value="0">by Code</option>
                                                <option value="2">by Date</option>
                                                <option value="3">Account Name</option>
                                                <option value="4">Bill#</option>
                                                <option value="5">Company Name</option>
                                                <option value="6">Person Name</option>
                                                <option value="7">Remarks</option>
                                                <option value="8">Purchase Inv #</option>
                                                <option value="9">Weight (Kg)</option>
                                                <option value="10">Bill Amount</option>
                                                <option value="11">Convance Charges</option>
                                                <option value="12">Labour Charges Amount</option>
                                                <option value="13">Discount</option>
                                                <option value="14">Net Amount</option>
                                                <option value="15">Status</option>
                                            </select>
                                            <input type="text" class="form-control" id="columnSearch" placeholder="Search By Column"/>

                                        </div>
                                    </div>
                                    <div class="modal-wrapper table-scroll">
                                        <table class="table table-bordered table-striped mb-0" id="cust-datatable-default">
                                            <thead>
                                                <tr>
                                                    <th style="display:none">Inv #</th>
                                                    <th>Code</th>
                                                    <th>Date</th>
                                                    <th>Account Name</th>
                                                    <th>Bill #</th>
                                                    <th>Comapny Name</th>
                                                    <th>Person Name</th>
                                                    <th>Remarks</th>
                                                    <th>Purchase Inv #</th>
                                                    <th>Weight (kg)</th>
                                                    <th>Bill Amount</th>
                                                    <th>Convance Charges</th>
                                                    <th>Labour Charges</th>
                                                    <th>Discount</th>
                                                    <th>Net Amount</th>
                                                    <th>Status</th>
                                                    <th>Att.</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pur2 as $key => $row)
                                                <tr>
                                                    <td style="display:none">{{$row->Sal_inv_no}}</td>
                                                    <td>{{$row->prefix}}{{$row->Sal_inv_no}}</td>
                                                    <td>{{ \Carbon\Carbon::parse($row->sa_date)->format('d-m-y') }}</td>
                                                    <td><strong>{{$row->acc_name}}</strong></td>
                                                    <td>{{$row->pur_ord_no}}</td>
                                                    <td>{{$row->comp_account}}</td>
                                                    <td>{{$row->Cash_name}}</td>
                                                    <td>{{$row->Sales_Remarks}}</td>
                                                    <td>{{$row->pur_against}}</td>
                                                    <td>{{$row->weight_sum}}</td>
                                                    <td>{{$row->total_bill}}</td>
                                                    <td>{{$row->ConvanceCharges}}</td>
                                                    <td>{{$row->LaborCharges}}</td>
                                                    <td>{{$row->Bill_discount}}</td>
                                                    @php ($net_amount=$row->total_bill+$row->ConvanceCharges+$row->LaborCharges-$row->Bill_discount)
                                
                                                    @if(substr(strval($row->net_amount), strpos(strval($row->net_amount), '.') + 1)>0) 
                                                    <td><strong style="font-size:15px">{{ rtrim(rtrim(number_format($net_amount), '0'), '.') }}</strong></td>
                                                    @else
                                                        <td><strong style="font-size:15px">{{ number_format(intval($net_amount))}}</strong></td>
                                                    @endif

                                                    @if($row->pur_ord_no!=null) 
                                                        <td> <i class="fas fa-circle" style="color:green;font-size:10px"></i> Closed </td>
                                                    @else
                                                        <td> <i class="fas fa-circle" style="color:red;font-size:10px"></i> Not Close </td>
                                                    @endif
                                                    <td style="vertical-align: middle;">
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal text-dark" onclick="getAttachements({{$row->Sal_inv_no}})" href="#attModal"><i class="fa fa-eye"> </i></a>
                                                        <span class="separator"> | </span>
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal text-danger" onclick="setAttId({{$row->auto_lager}})" href="#addAttModal"> <i class="fas fa-paperclip"> </i></a>
                                                    </td>
                                                    <td class="actions">
                                                        <a href="{{ route('show-sales2',$row->Sal_inv_no) }}" class=""><i class="fas fa-eye"></i></a>
                                                        <span class="separator"> | </span>
                                                        <a href="{{ route('edit-sales2',$row->Sal_inv_no) }}" class=""><i class="fas fa-pencil-alt"></i></a>
                                                        <span class="separator"> | </span>
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="setId({{$row->Sal_inv_no}})" href="#deleteModal"><i class="far fa-trash-alt" style="color:red"></i></a>
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
            <form method="post" action="{{ route('delete-sales2') }}" enctype="multipart/form-data">
                @csrf
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">Delete Sale Invoice</h2>
                    </header>
                    <div class="card-body">
                        <div class="modal-wrapper">
                            <div class="modal-icon">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <div class="modal-text">
                                <p class="mb-0">Are you sure that you want to delete this Sale Invoice?</p>
                                <input name="delete_purc2" id="deleteID" hidden>
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
                            <tbody id="pur2_attachements">

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

        <div id="addAttModal" class="zoom-anim-dialog modal-block modal-block-danger mfp-hide">
            <form method="post" action="{{ route('sale2-att-add') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
                @csrf  
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">Upload Attachements</h2>
                    </header>
                    <div class="card-body">
                        <div class="modal-wrapper">
                            <div class="col-lg-12 mb-2">
                                <input type="file" class="form-control" name="addAtt[]" multiple accept="application/pdf, image/png, image/jpeg">
                                <input type="hidden" class="form-control" name="att_id" id="att_id">
                            </div>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="sumit" class="btn btn-danger">Upload</button>
                                <button class="btn btn-default modal-dismiss">Cancel</button>
                            </div>
                        </div>
                    </footer>
                </section>
            </form>
        </div>
        @include('../layouts.footerlinks')
	</body>
</html>
<script>

    $(document).ready(function(){
        var table = $('#cust-datatable-default').DataTable();

        $('#columnSelect').on('change', function () {
            // Clear the previous search
            table.search('').columns().search('').draw(); // Reset global and column-specific filters
        });
        $('#columnSearch').on('keyup change', function () {
            var columnIndex = $('#columnSelect').val(); // Get selected column index
            table.column(columnIndex).search(this.value).draw(); // Apply search and redraw
        });
    });
    
    function setId(id){
        $('#deleteID').val(id);
    }
    
    function setAttId(id){
        $('#att_id').val(id);
    }
    function getAttachements(id){

        var table = document.getElementById('pur2_attachements');
        while (table.rows.length > 0) {
            table.deleteRow(0);
        }

        $.ajax({
            type: "GET",
            url: "/sales2/attachements",
            data: {id:id},
            success: function(result){
                $.each(result, function(k,v){
                    var html="<tr>";
                    html+= "<td>"+v['att_path']+"</td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 mr-2 me-1 text-danger' href='/sales2/download/"+v['att_id']+"'><i class='fas fa-download'></i></a></td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 me-1 text-primary' href='/sales2/view/"+v['att_id']+"' target='_blank'><i class='fas fa-eye'></i></a></td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 me-1 text-primary' href='#' onclick='deleteFile("+v['att_id']+")'><i class='fas fa-trash'></i></a></td>"
                    html+="</tr>";
                    $('#pur2_attachements').append(html);
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

        fetch('/purchase2/deleteAttachment/' + fileId, {
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