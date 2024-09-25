@include('../layouts.header')
    <style>
        .select2-container{
            width: 100% !important;
        }
    </style>	
    <body>
		<section class="body">
            @include('../layouts.pageheader')
            <div class="inner-wrapper">
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
                                    <div class="modal-wrapper">
                                        <table class="table table-bordered table-striped mb-0" id="datatable-default">
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
                                                    <td>{{$row->acc_name}}</td>
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
                                                    @php ($net_amount=$row->total_bill+$row->ConvanceCharges+$row->ConvanceCharges-$row->Bill_discount)
                                                    <td><strong style="font-size:15px">{{ round($net_amount)}}</strong></td>
                                                    <!-- @if(substr(strval($row->net_amount), strpos(strval($row->net_amount), '.') + 1)>0) 
                                                        <td><strong style="font-size:15px">{{ rtrim(rtrim(number_format($net_amount), '0'), '.') }}</strong></td>
                                                    @else
                                                        <td><strong style="font-size:15px">{{ number_format(intval($net_amount))}}</strong></td>
                                                    @endif -->
                                                    @if($row->pur_ord_no!=null) 
                                                        <td> <i class="fas fa-circle" style="color:green;font-size:10px"></i> Closed </td>
                                                    @else
                                                        <td> <i class="fas fa-circle" style="color:red;font-size:10px"></i> Not Close </td>
                                                    @endif
                                                    <td><a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="getAttachements({{$row->Sal_inv_no}})" href="#attModal">View</a></td>
                                                    <td class="actions">
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal text-danger" onclick="setPrintId({{$row->Sal_inv_no}})" href="#printModal"> <i class="fas fa-print"></i></a>
                                                        <a href="{{ route('show-sales2',$row->Sal_inv_no) }}" class=""><i class="fas fa-eye"></i></a>
                                                        <a href="{{ route('edit-sales2',$row->Sal_inv_no) }}" class=""><i class="fas fa-pencil-alt"></i></a>
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

        <div id="printModal" class="zoom-anim-dialog modal-block modal-block-danger mfp-hide" style="max-width: 350px;">
            <form method="get" action="{{ route('print-sales2-invoice') }}" enctype="multipart/form-data">
                @csrf
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">Select Print Format</h2>
                    </header>
                    <div class="card-body">
                        <div class="modal-wrapper">
                            <select data-plugin-selecttwo class="form-control select2-js" autofocus name="print_type" required>
                                <option value="" disabled selected>Select Print Format</option>
                                <option value="1" >Show All</option>
                                <option value="2" >Exclude Item Length</option>
                                <option value="3" >Only Quantity & Price</option>
                            </select>
                            <input type="hidden" name="print_sale2" id="printID" >
                        </div>
                    </div>
                    <footer class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-danger">Print</button>
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
        @include('../layouts.footerlinks')
	</body>
</html>
<script>

    
    function setId(id){
        $('#deleteID').val(id);
    }
    function setPrintId(id){
        $('#printID').val(id);
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
                console.log(result);
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