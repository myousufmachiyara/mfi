@extends('../layouts.header')
	<body>
		<section class="body">
			@extends('../layouts.menu')

			<div class="inner-wrapper">
				<section role="main" class="content-body">
					@extends('../layouts.pageheader')

					<!-- start: page -->
					<div class="row">
						<div class="col-lg-12">
							<form>
								<section class="card">
									<header class="card-header">
										<div class="card-actions">
											<button type="button" class="mb-1 me-1 btn btn-warning"><i class="fas fa-sync" ></i> Refresh</button>											
											<button type="button" class="mb-1 me-1 btn btn-danger"><i class="fas fa-print"></i> Print</button>											
										</div>
										<h2 class="card-title">Party Details</h2>
									</header>

									<div class="card-body">
										<div class="row form-group mb-3">
											<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
												<input type="text" name="firstName" placeholder="Invoice No." class="form-control" disabled>
											</div>

											<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
												<input type="date" name="firstName" placeholder="Date" class="form-control">
											</div>

											<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
												<input type="text" name="firstName" placeholder="Bill No." class="form-control" disabled>
											</div>

											<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
												<select class="form-control mb-3">
													<option selected>Status</option>
													<option>Bill Not Final</option>
													<option>Finalized</option>
												</select>												
											</div>

										</div>
										<div class="row">
											<div class="col-lg-7">
												<select class="form-control mb-3">
													<option selected>Select Chart Of Account</option>
													<option>COA 1</option>
													<option>COA 2</option>
												</select>											
											</div>

											<div class="col-lg-1">
												<button type="button" class="mb-1 me-1 btn btn-primary"><i class="fas fa-plus"></i> New</button>											
											</div>
											<div class="col-lg-4">
												<input type="text" name="nop" placeholder="Name Of Person" class="form-control" disabled>
											</div>
										</div>
										<div class="row form-group mb-3">
											<div class="col-lg-6 col-md-3 pb-sm-3 pb-md-0">
												<input type="text" name="address" placeholder="Address" class="form-control" disabled>
											</div>

											<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
												<input type="text" name="cash-pur_phone" placeholder="Cash - Pur_phone" class="form-control" disabled>
											</div>

											<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
												<input type="text" name="remarks" placeholder="Remarks" class="form-control" disabled>
											</div>
										</div>
									</div>
								</section>

								<section class="card">
									<header class="card-header">
										<div class="card-actions">
											<button id="addToTable" class="btn btn-primary"> <i class="fas fa-plus"></i> Add Item</button>
										</div>

										<h2 class="card-title">Item Details</h2>
									</header>
									<div class="card-body">
										<table class="table table-bordered table-striped mb-0" id="datatable-editable">
											<thead>
												<tr>
													<th>Item Code</th>
													<th>Qty</th>
													<th>Item Name</th>
													<th>Remarks</th>
													<th>Weight(kgs)</th>
													<th>Price</th>
													<th>Amount</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
									</div>
								</section>

								<section class="card">
									<header class="card-header">
										<div class="card-actions">
											<h3 class="font-weight-bold text-color-dark mt-0 mb-0 text-5">Net Amount</h3>
											<span class="d-flex align-items-center justify-content-lg-end">
													<strong class="text-color-dark text-4">PKR 1000.00</strong>
											</span>
										</div>
										<h2 class="card-title">Invoice Summary Details</h2>
									</header>
									<div class="card-body">
										<div class="row form-group mb-3">
											<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
												<input type="text" name="totalAmount" placeholder="Total Amount" class="form-control" disabled>
											</div>

											<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
												<input type="date" name="firstName" placeholder="Total Weight" class="form-control">
											</div>

											<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
												<input type="text" name="firstName" placeholder="GST" class="form-control">
											</div>

											<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
												<input type="text" name="firstName" placeholder="Convance Charges" class="form-control">
											</div>

											<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
												<input type="text" name="firstName" placeholder="Labour Charges" class="form-control">
											</div>

											<div class="col-sm-2 col-md-2 pb-sm-3 pb-md-0">
												<input type="text" name="firstName" placeholder="Bill Discount" class="form-control">
											</div>

										</div>
									</div>
									<footer class="card-footer text-end">
										<button class="btn btn-primary">Create Invoice</button>
									</footer>
								</section>
							</form>
						</div>
					</div>
					<!-- end: page -->
				</section>
			</div>
		</section>
		<div id="dialog" class="modal-block mfp-hide">
			<section class="card">
				<header class="card-header">
					<h2 class="card-title">Are you sure?</h2>
				</header>
				<div class="card-body">
					<div class="modal-wrapper">
						<div class="modal-text">
							<p>Are you sure that you want to delete this row?</p>
						</div>
					</div>
				</div>
				<footer class="card-footer">
					<div class="row">
						<div class="col-md-12 text-end">
							<button id="dialogConfirm" class="btn btn-primary">Confirm</button>
							<button id="dialogCancel" class="btn btn-default">Cancel</button>
						</div>
					</div>
				</footer>
			</section>
		</div>
        @extends('../layouts.footerlinks')
	</body>
</html>