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
                                <header class="card-header">
                                    <div class="card-actions">

                                    </div>

                                    <h2 class="card-title">Sale Invoices</h2>
                                </header>
                                <div class="card-body">
                                	<table class="table table-bordered table-striped mb-0" id="datatable-default">
                                        <thead>
                                            <tr>
                                                <th>Invoice No.</th>
                                                <th>Bill No.</th>
                                                <th>Date</th>
                                                <th>Chart Of Account</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sales as $key => $row)
                                            <tr>
                                                <td>{{$row->Sal_inv_no}}</td>
                                                <td>{{$row->pur_ord_no}}</td>
                                                <td>{{$row->sa_date}}</td>
                                                <td>{{$row->account_name}}</td>
                                                <td> <i class="fas fa-circle" style="color:green;font-size:10px"></i> Finalized </td>
                                                <td class="actions">
                                                    <a href="" class=""><i class="fas fa-pencil-alt"></i></a>
                                                    <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="setId({{$row->Sal_inv_no}})" href="#modalAnim"><i class="far fa-trash-alt" style="color:red"></i></a>
                                                    <a href="{{ route('print-sale-invoice') }}" class=""><i class="fas fa-print" style="color:green"></i></a>
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
        <div id="modalAnim" class="zoom-anim-dialog modal-block modal-block-danger mfp-hide">
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
        @extends('../layouts.footerlinks')
	</body>
</html>
<script>
    function setId(id){
        $('#deleteID').val(id);
    }
</script>