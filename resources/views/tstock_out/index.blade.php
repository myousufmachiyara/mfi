@extends('../layouts.header')
	<body>
		<section class="body">
			@extends('../layouts.menu')
			<div class="inner-wrapper">
				<section role="main" class="content-body">
					@extends('../layouts.pageheader')
                    <div class="row">
                        <div class="col">
                            <section class="card">
                                <header class="card-header" style="display: flex;justify-content: space-between;">
                                    <h2 class="card-title">All Pipe Stock 
                                        out
                                    </h2>
                                    <form class="text-end" action="{{ route('create-tstock-out-invoice') }}" method="GET">
                                        <button type="submit" class="btn btn-primary mt-2"> <i class="fas fa-plus"></i> New Stock Out</button>
                                    </form>
                                </header>
                                <div class="card-body">
                                    <div class="modal-wrapper">
                                        <table class="table table-bordered table-striped mb-0" id="datatable-default">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Date</th>
                                                    <th>Account Name</th>
                                                    <th>Name Of Person</th>
                                                    <th>Remarks</th>
                                                    <th>Sale_Inv #</th>
                                                    <th>Gate Pass#</th>
                                                    <th>Total Qty</th>
                                                    <th>Total Weight</th>
                                                    <th>Transporter</th>
                                                    <th>Status</th>
                                                    <th>Att.</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($tstock_out as $key => $row)
                                                <tr>
                                                    <td>{{$row->prefix}}{{$row->Sal_inv_no}}</td>
                                                    <td>{{ \Carbon\Carbon::parse($row->sa_date)->format('d-m-y') }}</td>
                                                    <td><strong>{{$row->ac_name}}</strong></td>
                                                    <td>{{$row->cash_pur_name}}</td>
                                                    <td>{{$row->Sales_remarks}}</td>
                                                    <td>{{$row->pur_inv}}</td>
                                                    <td>{{$row->mill_gate}}</td>
                                                    <td>{{$row->qty_sum}}</td>
                                                    <td>{{$row->weight_sum}}</td>
                                                    <td>{{$row->transporter}}</td>
                                                    @if($row->pur_inv!=null) 
                                                    <td> <i class="fas fa-circle" style="color:green;font-size:10px"></i> Closed </td>
                                                @else
                                                    <td> <i class="fas fa-circle" style="color:red;font-size:10px"></i> Not Close </td>
                                                @endif
                                                    <td><a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="getAttachements({{$row->Sal_inv_no}})" href="#attModal">View</a></td>
                                                    <td class="actions">
                                                        <a href="{{ route('print-sale-invoice', $row->Sal_inv_no) }}" class="text-danger">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                        <span class="separator"> | </span>
                                                        <a href="{{ route('show-sale-invoice',$row->Sal_inv_no) }}" class="">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <span class="separator"> | </span>
                                                        <a href="{{ route('edit-tstock-out-invoice',$row->Sal_inv_no) }}" class="">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </a>
                                                        <span class="separator"> | </span>
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="setId({{$row->Sal_inv_no}})" href="#deleteModal">
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
            <form method="post" action="{{ route('delete-tstock-out-invoice') }}" enctype="multipart/form-data">
                @csrf
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">Delete Pipe Stock Out</h2>
                    </header>
                    <div class="card-body">
                        <div class="modal-wrapper">
                            <div class="modal-icon">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <div class="modal-text">
                                <p class="mb-0">Are you sure that you want to delete this Pipe Stock Out?</p>
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
            </form>
        </div>

        <div id="attModal" class="zoom-anim-dialog modal-block modal-block-danger mfp-hide">
            <section class="card">
                <header class="card-header">
                    <h2 class="card-title">All Attachements</h2>
                </header>
                <div class="card-body">
                    <div class="modal-wrapper">

                        <table class="table table-bordered table-striped mb-0" >
                            <thead>
                                <tr>
                                    <th>Attachement Path</th>
                                    <th>Download</th>
                                    <th>View</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody id="tstockout_attachements">

                            </tbody>
                        </table>
                    </div>
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
        @extends('../layouts.footerlinks')
	</body>
</html>
<script>
    function setId(id){
        $('#deleteID').val(id);
    }

    function getAttachements(id){
        var table = document.getElementById('tstockout_attachements');
        while (table.rows.length > 0) {
            table.deleteRow(0);
        }

        $.ajax({
            type: "GET",
            url: "/tstock_out/attachements",
            data: {id:id},
            success: function(result){
                $.each(result, function(k,v){
                    var html="<tr>";
                    html+= "<td>"+v['att_path']+"</td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 mr-2 me-1 text-danger' href='/tstock_out/download/"+v['att_id']+"'><i class='fas fa-download'></i></a></td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 me-1 text-primary' href='/tstock_out/view/"+v['att_id']+"' target='_blank'><i class='fas fa-eye'></i></a></td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 me-1 text-primary' href='#' onclick='deleteFile("+v['att_id']+")'><i class='fas fa-trash'></i></a></td>"
                    html+="</tr>";
                    $('#tstockout_attachements').append(html);
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

        fetch('/tstock_out/deleteAttachment/' + fileId, {
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