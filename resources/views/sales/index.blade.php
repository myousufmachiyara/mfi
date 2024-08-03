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
                                    <h2 class="card-title">Sale Invoices</h2>
                                    <form class="text-end" action="{{ route('create-sale-invoice') }}" method="GET">
                                        <button type="submit" class="btn btn-primary mt-2"> <i class="fas fa-plus"></i> New Sale</button>
                                    </form>
                                </header>
                                <div class="card-body">
                                	<table class="table table-bordered table-striped mb-0" id="datatable-default">
                                        <thead>
                                            <tr>
                                                <th>Inv #</th>
                                                <th>Date</th>
                                                <th>Account</th>
                                                <th>Person Name</th>
                                                <th>Remarks</th>
                                                <th>Bill #</th>
                                                <th>Att.</th>
                                                <th>Weight (kg)</th>
                                                <th>Bill Amount</th>
                                                <th>Convance Charges</th>
                                                <th>Labour Charges</th>
                                                <th>Discount</th>
                                                <th>Net Amount</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sales as $key => $row)
                                            <tr>
                                                <td>{{$row->Sal_inv_no}}</td>
                                                <td>{{$row->sa_date}}</td>
                                                <td>{{$row->ac_name}}</td>
                                                <td>{{$row->Cash_pur_name}}</td>
                                                <td>{{$row->Sales_remarks}}</td>
                                                <td>{{$row->pur_ord_no}}</td>
                                                <td><a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="getAttachements({{$row->Sal_inv_no}})" href="#attModal">View</a></td>
                                                <td></td>
                                                <td>{{$row->sed_sal}}</td>
                                                <td>{{$row->ConvanceCharges}}</td>
                                                <td>{{$row->LaborCharges}}</td>
                                                <td>{{$row->Bill_discount}}</td>
                                                <td>{{$row->sed_sal + $row->ConvanceCharges + $row->LaborCharges - $row->Bill_discount}}</td>
                                                @if($row->bill_not==0)
                                                    <td> <i class="fas fa-circle" style="color:red;font-size:10px"></i> Not Final </td>
                                                @elseif($row->bill_not==1)
                                                    <td> <i class="fas fa-circle" style="color:green;font-size:10px"></i> Finalized </td>
                                                @endif
                                                <td class="actions">
                                                    <a href="{{ route('show-sale-invoice',$row->Sal_inv_no) }}" class=""><i class="fas fa-eye"></i></a>
                                                    <a href="{{ route('edit-sale-invoice',$row->Sal_inv_no) }}" class=""><i class="fas fa-pencil-alt"></i></a>
                                                    <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="setId({{$row->Sal_inv_no}})" href="#deleteModal"><i class="far fa-trash-alt" style="color:red"></i></a>
												</td>
                                            </tr>
                                        </tbody>
                                        @endforeach
									</table>
                                </div>
                            </section>
                        </div>
                    </div>
                </section>		
			</div>
		</section>
        <div id="deleteModal" class="zoom-anim-dialog modal-block modal-block-danger mfp-hide">
            <form method="post" action="{{ route('delete-sale-invoice') }}" enctype="multipart/form-data">
                @csrf
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">Delete Invoice</h2>
                    </header>
                    <div class="card-body">
                        <div class="modal-wrapper">
                            <div class="modal-icon">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <div class="modal-text">
                                <p class="mb-0">Are you sure that you want to delete this invoice?</p>
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

                        <table class="table table-bordered table-striped mb-0" id="datatable-default">
                            <thead>
                                <tr>
                                    <th>Attachement Path</th>
                                    <th>Download</th>
                                    <th>View</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody id="sale1_attachements">

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
        var table = document.getElementById('sale1_attachements');
        while (table.rows.length > 0) {
            table.deleteRow(0);
        }

        $.ajax({
            type: "GET",
            url: "/sales/attachements",
            data: {id:id},
            success: function(result){
                $.each(result, function(k,v){
                    var html="<tr>";
                    html+= "<td>"+v['att_path']+"</td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 mr-2 me-1 text-danger' href='/sales/download/"+v['att_id']+"'><i class='fas fa-download'></i></a></td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 me-1 text-primary' href='/sales/view/"+v['att_id']+"' target='_blank'><i class='fas fa-eye'></i></a></td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 me-1 text-primary' href='#' onclick='deleteFile("+v['att_id']+")'><i class='fas fa-trash'></i></a></td>"
                    html+="</tr>";
                    $('#sale1_attachements').append(html);
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

        fetch('/sales/deleteAttachment/' + fileId, {
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