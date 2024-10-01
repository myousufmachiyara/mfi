@include('../layouts.header')
	<body>
		<section class="body">
        @include('../layouts.pageheader')
            <div class="inner-wrapper">
				<section role="main" class="content-body">
                    <div class="row">
                        <div class="col">
                            <section class="card" style="display: flex;justify-content: space-between;">
                                <header class="card-header" style="display: flex;justify-content: space-between;">
                                    <h2 class="card-title">All Purchase Orders Pipe/Garder</h2>
                                    <form class="text-end" action="{{ route('new-tpo') }}" method="GET">
                                        <button type="submit" class="btn btn-primary mt-2"> <i class="fas fa-plus"></i> New PO</button>
                                    </form>
                                </header>
                                <div class="card-body">
                                    <div class="modal-wrapper">
                                        <table class="table table-bordered table-striped mb-0" id="datatable-default">
                                            <thead>
                                                <tr>
                                                    <th style="display:none">PO #</th>
                                                    <th>Code</th>
                                                    <th>Date</th>
                                                    <th>Company Name</th>
                                                    <th>Person Name</th>
                                                    <th>Remarks</th>
                                                    <th>PurInv #</th>
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
                                                @foreach ($tpo2 as $key => $row)
                                                <tr>
                                                    <td style="display:none">{{$row->Sale_inv_no}}</td>
                                                    <td>{{$row->prefix}}{{$row->Sale_inv_no}}</td>
                                                    <td>{{ \Carbon\Carbon::parse($row->sa_date)->format('d-m-y') }}</td>
                                                    <td>{{$row->acc_name}}</td>
                                                    <td>{{$row->Cash_pur_name}}</td>
                                                    <td class="limited-text">{{$row->Sales_Remarks}}</td>
                                                    <td>{{$row->sales_against}}</td>
                                                    <td>{{$row->weight_sum}}</td>
                                                    <td>{{$row->total_bill}}</td>
                                                    <td>{{$row->ConvanceCharges}}</td>
                                                    <td>{{$row->LaborCharges}}</td>
                                                    <td>{{$row->Bill_discount}}</td>
                                                    @php ($net_amount=$row->total_bill+$row->ConvanceCharges+$row->ConvanceCharges-$row->Bill_discount)
                                                    @if(substr(strval($row->net_amount), strpos(strval($row->net_amount), '.') + 1)>0) 
                                                        <td><strong style="font-size:15px">{{ rtrim(rtrim(number_format($net_amount), '0'), '.') }}</strong></td>
                                                    @else
                                                        <td><strong style="font-size:15px">{{ number_format(intval($net_amount))}}</strong></td>
                                                    @endif
                                                    @if($row->sales_against!=null) 
                                                        <td> <i class="fas fa-circle" style="color:green;font-size:10px"></i> Closed </td>
                                                    @else
                                                        <td> <i class="fas fa-circle" style="color:red;font-size:10px"></i> Not Close </td>
                                                    @endif
                                                    <td><a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="getAttachements({{$row->Sale_inv_no}})" href="#attModal">View</a></td>
                                                    <td class="actions">
                                                        <a href="{{ route('print-purc2-invoice', $row->Sale_inv_no) }}" class="text-danger"> <i class="fas fa-print"></i></a>
                                                        <a href="{{ route('show-purchases2',$row->Sale_inv_no) }}" class=""><i class="fas fa-eye"></i></a>
                                                        <a href="{{ route('edit-tpo',$row->Sale_inv_no) }}" class=""><i class="fas fa-pencil-alt"></i></a>
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="setId({{$row->Sale_inv_no}})" href="#deleteModal"><i class="far fa-trash-alt" style="color:red"></i></a>
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
            <form method="post" action="{{ route('delete-tpo') }}" enctype="multipart/form-data">
                @csrf
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">Delete Purchase Order</h2>
                    </header>
                    <div class="card-body">
                        <div class="modal-wrapper">
                            <div class="modal-icon">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <div class="modal-text">
                                <p class="mb-0">Are you sure that you want to delete this Purchase Order?</p>
                                <input name="delete_tpo2" id="deleteID" hidden>
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
                            <tbody id="tpo_attachements">

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

    function getAttachements(id){

        var table = document.getElementById('tpo_attachements');
        while (table.rows.length > 0) {
            table.deleteRow(0);
        }

        $.ajax({
            type: "GET",
            url: "/tpo/attachements",
            data: {id:id},
            success: function(result){
                $.each(result, function(k,v){
                    var html="<tr>";
                    html+= "<td>"+v['att_path']+"</td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 mr-2 me-1 text-danger' href='/tpo/download/"+v['att_id']+"'><i class='fas fa-download'></i></a></td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 me-1 text-primary' href='/tpo/view/"+v['att_id']+"' target='_blank'><i class='fas fa-eye'></i></a></td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 me-1 text-primary' href='#' onclick='deleteFile("+v['att_id']+")'><i class='fas fa-trash'></i></a></td>"
                    html+="</tr>";
                    $('#tpo_attachements').append(html);
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

        fetch('/qtpo/deleteAttachment/' + fileId, {
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