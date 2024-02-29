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
										<h2 class="card-title">Item Details</h2>
									</header>
									<div class="card-body">

									</div>
									<footer class="card-footer text-end">
										<button class="btn btn-primary">Add Item</button>
									</footer>
								</section>

								<section class="card">
									<header class="card-header">
										<h2 class="card-title">Invoice Summary Details</h2>
									</header>
									<div class="card-body">

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
        @extends('../layouts.footerlinks')
	</body>
</html>