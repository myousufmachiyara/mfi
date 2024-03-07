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
                                                <th>Party Name</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td> 1001 </td>
                                                <td> 12/12/2024 </td>
                                                <td> Account 1 Name </td>
                                                <td> Party 1 </td>
                                                <td> <i class="fas fa-circle" style="color:green;font-size:10px"></i> Finalized </td>
                                                <td class="actions">
                                                    <a href="" class=""><i class="fas fa-pencil-alt"></i></a>
                                                    <a href="" class=""><i class="far fa-trash-alt" style="color:red"></i></a>
                                                    <a href="" class=""><i class="fas fa-print" style="color:green"></i></a>
												</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td> 1002 </td>
                                                <td> 12/13/2024 </td>
                                                <td> Account 2 Name </td>
                                                <td> Party 2 </td>
                                                <td> <i class="fas fa-circle" style="color:red;font-size:10px"></i> Not Final </td>
                                                <td class="actions">
                                                    <a href="" class=""><i class="fas fa-pencil-alt"></i></a>
                                                    <a href="" class=""><i class="far fa-trash-alt" style="color:red"></i></a>
                                                    <a href="" class=""><i class="fas fa-print" style="color:green"></i></a>
												</td>
                                            </tr>
                                        </tbody>
									</table>
                                </div>
                            </section>
                        </div>
                    </div>
                </section>		
			</div>
		</section>
        @extends('../layouts.footerlinks')
	</body>
</html>