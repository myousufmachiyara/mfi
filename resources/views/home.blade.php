@include('layouts.header')
	<style>
		h3{
			margin-top: 7px;
			margin-bottom: 10px;
		}

		.icon-container {
			background-size: auto; /* Adjust the size of the icon to fit within the div */
			background-repeat: no-repeat; /* Ensure the icon doesn't repeat */
			background-position: right bottom; /* Align the icon to the center-right */
		}
		/* Initially hide the masked data */
		.masked-data {
			display: none;
		}

		/* When the switch is toggled ON, show the masked data and hide the actual data */
		.switch-off .actual-data {
			display: none;
		}

		.switch-off .masked-data {
			display: block;
		}
	</style>
	<body>
		<section class="body">
			<div class="inner-wrapper">
				@include('layouts.leftmenu')
				<section role="main" class="content-body">
					@include('layouts.homepageheader')
					<!-- start: page -->
					<div class="row home-cust-pad">
						@if(session('user_role')==1 || session('user_role')==2)
							<div style="display: flex;justify-content: space-between;">
								<h2 class="text-dark"><strong>Good Morning! Have a Nice Day</strong></h2>
								<div class="form-check form-switch">
									<input class="form-check-input" type="checkbox" id="ShowDatatoggleSwitch" onchange="handleToggleSwitch(this)" style="margin-top:30px">
								</div>
							</div>

							<div class="col-12 col-md-3 mb-2">
								<section class="card card-featured-left card-featured-primary">
									<div class="card-body icon-container data-container" style="background-image: url('/assets/img/cheque-icon.png'); ">
										<h3 class="amount text-dark"><strong>Post Dated Cheques</strong></h3>
										@if(isset($pdc) && isset($pdc->Total_Balance))
											<h2 class="amount m-0 text-primary actual-data">
												<strong data-value="{{ $pdc->Total_Balance }}">0</strong>
												<span class="title text-end text-dark h6"> PKR</span>
											</h2>
											<h2 class="amount m-0 text-primary masked-data"><strong>******</strong></h2>
										@else
											<h2 class="amount m-0 text-primary actual-data">
												<strong data-value="0">0</strong>
												<span class="title text-end text-dark h6"> PKR</span>
											</h2>
											<h2 class="amount m-0 text-primary masked-data"><strong>******</strong></h2>
										@endif

										<div class="summary-footer">
											<a class="text-primary text-uppercase" href="#">View Details</a>
										</div>
									</div>
								</section>

								<section class="card card-featured-left card-featured-primary mt-3">
									<div class="card-body icon-container data-container" style="background-image: url('/assets/img/rec-icon.png'); ">
										<h3 class="amount text-dark"><strong>Total Receivables</strong></h3>
										@if(isset($receivables) && isset($receivables->total_balance))
											<h2 class="amount m-0 text-primary actual-data">
												<strong data-value="{{ $receivables->total_balance }}">0</strong>
												<span class="title text-end text-dark h6"> PKR</span>
											</h2>
											<h2 class="amount m-0 text-primary masked-data"><strong>******</strong></h2>
										@else
											<h2 class="amount m-0 text-primary actual-data">
												<strong data-value="0">0</strong>
												<span class="title text-end text-dark h6"> PKR</span>
											</h2>
											<h2 class="amount m-0 text-primary masked-data"><strong>******</strong></h2>
										@endif
										<div class="summary-footer">
											<a class="text-primary text-uppercase" href="#">View Details</a>
										</div>
									</div>
								</section>

								<section class="card card-featured-left card-featured-primary mt-3">
									<div class="card-body icon-container data-container" style="background-image: url('/assets/img/last-month-sale-icon.png'); ">
										<h3 class="amount text-dark"><strong>Last Month Sale</strong></h3>
										@if(isset($last_month_sale) && isset($last_month_sale->total_dr_amt) && isset($last_month_sale->total_weight))
											<h2 class="amount m-0 text-primary actual-data">
												<strong data-value="{{ $last_month_sale->total_dr_amt }}">0</strong>
												<span class="title text-end text-dark h6"> PKR</span></h2>
											<h2 class="amount m-0 text-primary masked-data"><strong>******</strong></h2>

											<h2 class="amount m-0 text-primary actual-data">
												<strong data-value="{{ $last_month_sale->total_weight }}">0</strong>
												<span class="title text-end text-dark h6"> M-Ton</span></h2>
											<h2 class="amount m-0 text-primary masked-data"><strong>******</strong></h2>
										@else
											<h2 class="amount m-0 text-primary actual-data">
												<strong data-value="0">0</strong>
												<span class="title text-end text-dark h6"> PKR</span></h2>
											<h2 class="amount m-0 text-primary masked-data">
												<strong>******</strong></h2>

											<h2 class="amount m-0 text-primary actual-data">
												<strong data-value="0">0</strong>
												<span class="title text-end text-dark h6"> M-Ton</span></h2>
											<h2 class="amount m-0 text-primary masked-data">
												<strong>******</strong>
											</h2>
										@endif

										<div class="summary-footer">
											<a class="text-primary text-uppercase" href="#">View Details</a>
										</div>
									</div>
								</section>

							</div>

							<div class="col-12 col-md-3 mb-2">
								<section class="card card-featured-left card-featured-danger">
									<div class="card-body icon-container data-container" style="background-image: url('/assets/img/bank-icon.png'); ">
										<h3 class="amount text-dark"><strong>Bank</strong></h3>
										@if(isset($banks) && isset($banks->Total_Balance))
											<h2 class="amount m-0 text-danger actual-data">
												<strong data-value="{{ $banks->Total_Balance }}">0</strong>
												<span class="title text-end text-dark h6"> PKR</span>
											</h2>
											<h2 class="amount m-0 text-danger masked-data"><strong>******</strong></h2>
										@else
											<h2 class="amount m-0 text-danger actual-data">
												<strong data-value="0">0</strong>
												<span class="title text-end text-dark h6"> PKR</span>
											</h2>
											<h2 class="amount m-0 text-danger masked-data"><strong>******</strong></h2>
										@endif

										<div class="summary-footer">
											<a class="text-primary text-uppercase" href="#">View Details</a>
										</div>
									</div>
								</section>

								<section class="card card-featured-left card-featured-danger mt-3">
									<div class="card-body icon-container data-container" style="background-image: url('/assets/img/pay-icon.png'); ">
										<h3 class="amount text-dark"><strong>Total Payables</strong></h3>
										@if(isset($payables) && isset($payables->total_balance))
											<h2 class="amount m-0 text-danger actual-data">
												<strong data-value="{{ $payables->total_balance }}">0</strong>
												<span class="title text-end text-dark h6"> PKR</span>
											</h2>
											<h2 class="amount m-0 text-danger masked-data"><strong>******</strong></h2>
										@else
											<h2 class="amount m-0 text-danger actual-data">
												<strong data-value="0">0</strong>
												<span class="title text-end text-dark h6"> PKR</span>
											</h2>
											<h2 class="amount m-0 text-danger masked-data"><strong>******</strong></h2>
										@endif
										<div class="summary-footer">
											<a class="text-primary text-uppercase" href="#">View Details</a>
										</div>
									</div>
								</section>

								<section class="card card-featured-left card-featured-danger mt-3">
									<div class="card-body icon-container data-container" style="background-image: url('/assets/img/last-month-pur-icon.png'); ">
										<h3 class="amount text-dark"><strong>Last Month Purchase</strong></h3>
										@if(isset($last_month_purchase) && isset($last_month_purchase->total_cr_amt) && isset($last_month_purchase->total_weight))
										<h2 class="amount m-0 text-danger actual-data">
											<strong data-value="{{ $last_month_purchase->total_cr_amt }}">0</strong>
											<span class="title text-end text-dark h6"> PKR</span></h2>
										<h2 class="amount m-0 text-danger masked-data"><strong>******</strong></h2>

										<h2 class="amount m-0 text-danger actual-data">
											<strong data-value="{{ $last_month_purchase->total_weight }}">0</strong>
											<span class="title text-end text-dark h6"> M-Ton</span></h2>
										<h2 class="amount m-0 text-danger masked-data"><strong>******</strong></h2>
										@else
											<h2 class="amount m-0 text-danger actual-data">
												<strong data-value="0">0</strong>
												<span class="title text-end text-dark h6"> PKR</span></h2>
											<h2 class="amount m-0 text-danger masked-data">
												<strong>******</strong></h2>

											<h2 class="amount m-0 text-danger actual-data">
												<strong data-value="0">0</strong>
												<span class="title text-end text-dark h6"> M-Ton</span></h2>
											<h2 class="amount m-0 text-danger masked-data">
												<strong>******</strong>
											</h2>
										@endif

										<div class="summary-footer">
											<a class="text-primary text-uppercase" href="#">View Details</a>
										</div>
									</div>
								</section>
							</div>

							<div class="col-12 col-md-2 mb-2">
								<section class="card card-featured-left card-featured-success">
									<div class="card-body icon-container data-container" style="background-image: url('/assets/img/cash-icon.png'); ">
										<h3 class="amount text-dark"><strong>Cash</strong></h3>
										@if(isset($cash) && isset($cash->Total_Balance))
										<h2 class="amount m-0 text-success actual-data">
											<strong data-value="{{ $cash->Total_Balance }}">0</strong>
											<span class="title text-end text-dark h6"> PKR</span>
										</h2>
										<h2 class="amount m-0 text-success masked-data"><strong>******</strong></h2>
										@else
											<h2 class="amount m-0 text-success actual-data">
												<strong data-value="0">0</strong>
												<span class="title text-end text-dark h6"> PKR</span>
											</h2>
											<h2 class="amount m-0 text-success masked-data"><strong>******</strong></h2>
										@endif
										<div class="summary-footer">
											<a class="text-primary text-uppercase" href="#">View Details</a>
										</div>
									</div>
								</section>
								
								<section class="card card-featured-left card-featured-success mb-2 mt-3">
									<div class="card-body icon-container data-container" style="background-image: url('/assets/img/long-term-loan-icon.png'); ">
										<h3 class="amount text-dark"><strong>Long Term Loan</strong></h3>

										@if(isset($long_term_loan) && isset($long_term_loan->total_balance))
										<h2 class="amount m-0 text-success actual-data">
											<strong data-value="{{ $long_term_loan->total_balance }}">0</strong>
											<span class="title text-end text-dark h6"> PKR</span>
										</h2>
										<h2 class="amount m-0 text-success masked-data"><strong>******</strong></h2>
										@else
											<h2 class="amount m-0 text-success actual-data">
												<strong data-value="0">0</strong>
												<span class="title text-end text-dark h6"> PKR</span>
											</h2>
											<h2 class="amount m-0 text-success masked-data"><strong>******</strong></h2>
										@endif

										<div class="summary-footer">
											<a class="text-primary text-uppercase" href="#">View Details</a>
										</div>
									</div>
								</section>

								<section class="card card-featured-left card-featured-success mt-3">
									<div class="card-body icon-container data-container" style="background-image: url('/assets/img/cash-in-icon.png'); ">
										<h3 class="amount text-dark"><strong>Last Month Cash In</strong></h3>
										@if(isset($long_term_loan) && isset($long_term_loan->total_balance))
										<h2 class="amount m-0 text-success actual-data">
											<strong data-value="{{ $long_term_loan->total_balance }}">0</strong>
											<span class="title text-end text-dark h6"> PKR</span>
										</h2>
										<h2 class="amount m-0 text-success masked-data"><strong>******</strong></h2>
										@else
											<h2 class="amount m-0 text-success actual-data">
												<strong data-value="0">0</strong>
												<span class="title text-end text-dark h6"> PKR</span>
											</h2>
											<h2 class="amount m-0 text-success masked-data"><strong>******</strong></h2>
										@endif
										<div class="summary-footer">
											<a class="text-primary text-uppercase" href="#">View Details</a>
										</div>
									</div>
								</section>
							</div>

							<div class="col-12 col-md-2 mb-2">
								<section class="card card-featured-left card-featured-tertiary mb-2">
									<div class="card-body icon-container data-container" style="background-image: url('/assets/img/fc-icon.png'); ">
										<h3 class="amount text-dark"><strong>Foreign Currency</strong></h3>

										@if(isset($foreign) && isset($foreign->Total_Balance))
										<h2 class="amount m-0 text-tertiary actual-data">
											<strong data-value="{{ $foreign->Total_Balance }}">0</strong>
											<span class="title text-end text-dark h6"> PKR</span>
										</h2>
										<h2 class="amount m-0 text-tertiary masked-data"><strong>******</strong></h2>
										@else
											<h2 class="amount m-0 text-tertiary actual-data">
												<strong data-value="0">0</strong>
												<span class="title text-end text-dark h6"> PKR</span>
											</h2>
											<h2 class="amount m-0 text-tertiary masked-data"><strong>******</strong></h2>
										@endif

										<div class="summary-footer">
											<a class="text-primary text-uppercase" href="#">View Details</a>
										</div>
									</div>
								</section>

								<section class="card card-featured-left card-featured-tertiary mt-3">
									<div class="card-body icon-container data-container" style="background-image: url('/assets/img/short-term-loan-icon.png'); ">
										<h3 class="amount text-dark"><strong>Short Term Loan</strong></h3>
										@if(isset($short_term_loan) && isset($short_term_loan->total_balance))
										<h2 class="amount m-0 text-tertiary actual-data">
											<strong data-value="{{ $short_term_loan->total_balance }}">0</strong>
											<span class="title text-end text-dark h6"> PKR</span>
										</h2>
										<h2 class="amount m-0 text-tertiary masked-data"><strong>******</strong></h2>
										@else
											<h2 class="amount m-0 text-tertiary actual-data">
												<strong data-value="0">0</strong>
												<span class="title text-end text-dark h6"> PKR</span>
											</h2>
											<h2 class="amount m-0 text-tertiary masked-data"><strong>******</strong></h2>
										@endif
										<div class="summary-footer">
											<a class="text-primary text-uppercase" href="#">View Details</a>
										</div>
									</div>
								</section>

								<section class="card card-featured-left card-featured-tertiary mt-3">
									<div class="card-body icon-container data-container" style="background-image: url('/assets/img/cash-out-icon.png'); ">
										<h3 class="amount text-dark"><strong>Last Month Cash Out</strong></h3>
										@if(isset($short_term_loan) && isset($short_term_loan->total_balance))
										<h2 class="amount m-0 text-tertiary actual-data">
											<strong data-value="{{ $short_term_loan->total_balance }}">0</strong>
											<span class="title text-end text-dark h6"> PKR</span>
										</h2>
										<h2 class="amount m-0 text-tertiary masked-data"><strong>******</strong></h2>
										@else
											<h2 class="amount m-0 text-tertiary actual-data">
												<strong data-value="0">0</strong>
												<span class="title text-end text-dark h6"> PKR</span>
											</h2>
											<h2 class="amount m-0 text-tertiary masked-data"><strong>******</strong></h2>
										@endif
										<div class="summary-footer">
											<a class="text-primary text-uppercase" href="#">View Details</a>
										</div>
									</div>
								</section>
							</div>

							<div class="col-12 col-md-2 mb-2">
								<section class="card card-featured-left card-featured-success">
									<div class="card-body icon-container data-container" style="background-image: url('/assets/img/all-user-icon.png');background-position: top right;background-size: 44%;">
										<h3 class="amount text-dark"><strong>Active Users</strong></h3>

										<table class="table table-responsive-md table-striped mb-0">
											<thead>
												<tr>
													<th>Name</th>
													<th>Role</th>
												</tr>
											</thead>
											<tbody>
												@foreach ($login_users as $key => $row)
												<tr>
													<td>{{$row->user_name}}</td>
													<td>{{$row->user_role}}</td>
												</tr>
												@endforeach
											</tbody>
										</table>
										<div class="summary-footer text-end mt-1">
											<a class="text-primary text-uppercase" href="{{ route('all-users')}}">View All</a>
										</div>
									</div>
								</section>
							</div>	
						@endif
					
						<!-- summaries ends here -->
						<div class="tabs mt-3">
							<ul class="nav nav-tabs">
								<li class="nav-item">
									<a class="nav-link nav-link-rep" data-bs-target="#BNF" href="#BNF" data-bs-toggle="tab">Bills Not Final</a>
								</li>
								<li class="nav-item">
									<a class="nav-link nav-link-rep" data-bs-target="#BNR" href="#BNR" data-bs-toggle="tab">Bills Not Recieved </a>
								</li>
								<li class="nav-item">
									<a class="nav-link nav-link-rep" data-bs-target="#PC" href="#PC" data-bs-toggle="tab">Pending Complains</a>
								</li>
								<li class="nav-item">
									<a class="nav-link nav-link-dashboard-tab" data-bs-target="#HR" href="#HR" data-bs-toggle="tab">HR</a>
								</li>
								<li class="nav-item">
									<a class="nav-link nav-link-rep" data-bs-target="#GS" href="#GS" data-bs-toggle="tab">Garder Sale</a>
								</li>
								<li class="nav-item">
									<a class="nav-link nav-link-rep" data-bs-target="#AS" href="#AS" data-bs-toggle="tab">All Sale</a>
								</li>
								<li class="nav-item">
									<a class="nav-link nav-link-rep" data-bs-target="#IIL_Sale" href="#IIL_Sale" data-bs-toggle="tab">IIL Sale</a>
								</li>
								<li class="nav-item">
									<a class="nav-link nav-link-rep" data-bs-target="#annual" href="#annual" data-bs-toggle="tab">Annual</a>
								</li>
								<li class="nav-item">
									<a class="nav-link nav-link-rep" data-bs-target="#UV" href="#UV" data-bs-toggle="tab">Unadjusted Vouchers</a>
								</li>
								<li class="nav-item">
									<a class="nav-link nav-link-rep" data-bs-target="#overDues" href="#overDues" data-bs-toggle="tab">Overdues</a>
								</li>
								<li class="nav-item">
									<a class="nav-link nav-link-rep" data-bs-target="#overDays" href="#overDays" data-bs-toggle="tab">Over Days</a>
								</li>
							</ul>
							<div class="tab-content">
								<div id="HR" class="tab-pane">
									<div class="mb-3 text-end">
										<div class="form-group" style="display: inline-block">
											<label class="col-form-label">Select Month</label>
											<input type="month" class="form-control" id="filterHR" value="{{ date('Y-m') }}" onchange="getTabData()">
										</div>
										<a class="btn btn-primary" style="padding: 0.5rem 0.6rem;" onclick="getTabData()"><i class="fa fa-filter"></i></a>
									</div>
									<div class="row form-group pb-3">
										<!-- Category Sale -->
										<div class="col-12 col-md-5 mb-3">
											<section class="card">
												<header class="card-header">
													<div class="card-actions">
														<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
													</div>

													<h2 class="card-title">MILL WISE HR PIPE PURCHASE</h2>
												</header>
												<div class="card-body">
													<canvas id="top5CustomerPerformance" style="height: 353px;width: 600px;"></canvas>
												</div>
											</section>
										</div>

										<div class="col-12 col-md-3 mb-3">
											<section class="card">
												<header class="card-header">
													<div class="card-actions">
														<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
													</div>

													<h2 class="card-title">Monthly Tonage</h2>
												</header>
												<div class="card-body">
													<canvas id="MonthlyTonage"></canvas>
												</div>
											</section>
										</div>

										<div class="col-12 col-md-4 mb-3">
											<section class="card">
												<header class="card-header">
													<div class="card-actions">
														<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
													</div>

													<h2 class="card-title">Monthly Tonage Of Customer</h2>
												</header>
												<div class="card-body">
													<div class="form-group">
														<select data-plugin-selecttwo class="form-control select2-js" id="hr_monthly_tonage_of_coa" name="account_name" required onchange="getMonthlyTonageOfCustomer()">
															<option value="" disabled selected>Select Account</option>
															@foreach($coa as $key => $row)	
																<option value="{{$row->ac_code}}">{{$row->ac_name}}</option>
															@endforeach
														</select>
														<a class="btn btn-primary" style="padding: 0.5rem 0.6rem;" onclick="getMonthlyTonageOfCustomer()"><i class="fa fa-filter"></i></a>
													</div>
													<table class="table table-responsive-md table-striped mb-0">
														<thead>
															<tr>
																<th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Company Name</font></font></th>
																<th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;text-align:center">Tonage</font></font></th>
															</tr>
														</thead>
														<tbody id="HRMonthlyTonageOfCust">
															
														</tbody>
													</table>
												</div>
											</section>
										</div>

										<div class="col-12 col-md-3 mb-3">
											<section class="card">
												<header class="card-header">
													<div class="card-actions">
														<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
													</div>

													<h2 class="card-title">Steelex Sale Summary</h2>
												</header>
												<div class="card-body">
													
													<table class="table table-responsive-md table-striped mb-0">
														<thead>
															<tr>
																<th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Company Name</font></font></th>
																<th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;text-align:center">Tonage</font></font></th>
															</tr>
														</thead>
														<tbody id="SteelexSaleTable">
															
														</tbody>
													</table>
												</div>
											</section>
										</div>

										<div class="col-12 col-md-3 mb-3">
											<section class="card">
												<header class="card-header">
													<div class="card-actions">
														<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
													</div>

													<h2 class="card-title">S.P.M Sale Summary</h2>
												</header>
												<div class="card-body">
													
													<table class="table table-responsive-md table-striped mb-0">
														<thead>
															<tr>
																<th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Company Name</font></font></th>
																<th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;text-align:center">Tonage</font></font></th>
															</tr>
														</thead>
														<tbody id="SPMSaleTable">
															
														</tbody>
													</table>
												</div>
											</section>
										</div>

										<div class="col-12 col-md-3 mb-3">
											<section class="card">
												<header class="card-header">
													<div class="card-actions">
														<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
													</div>

													<h2 class="card-title">Mehboob Sale Summary</h2>
												</header>
												<div class="card-body">
													
													<table class="table table-responsive-md table-striped mb-0">
														<thead>
															<tr>
																<th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Company Name</font></font></th>
																<th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;text-align:center">Tonage</font></font></th>
															</tr>
														</thead>
														<tbody id="MehboobSaleTable">
															
														</tbody>
													</table>
												</div>
											</section>
										</div>

										<div class="col-12 col-md-3 mb-3">
											<section class="card">
												<header class="card-header">
													<div class="card-actions">
														<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
													</div>

													<h2 class="card-title">Godown Sale Summary</h2>
												</header>
												<div class="card-body">
													
													<table class="table table-responsive-md table-striped mb-0">
														<thead>
															<tr>
																<th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Company Name</font></font></th>
																<th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;text-align:center">Tonage</font></font></th>
															</tr>
														</thead>
														<tbody id="GodownSaleTable">
															
														</tbody>
													</table>
												</div>
											</section>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
					<!-- end: page -->
				</section>
			</div>
		</section>
        @include('layouts.footerlinks')
	</body>
	<script>

		$(document).ready(function() {
			var toggleSwitch = document.getElementById('ShowDatatoggleSwitch');
            toggleSwitch.checked = true; // Set to "on" by default
            handleToggleSwitch(toggleSwitch); // Trigger the function
		});	

		function handleToggleSwitch(switchElement) {
			var dataContainers = document.querySelectorAll('.data-container');
			dataContainers.forEach(function(dataContainer) {
				if (!switchElement.checked) {
					dataContainer.classList.remove('switch-off');
					const elements = document.querySelectorAll(".actual-data strong");
					elements.forEach(element => {
						const totalBalance = parseFloat(element.dataset.value || 0); // Get value from data-value attribute or default to 0
						const duration = 2000; // Animation duration in milliseconds
						const frameRate = 60; // Frames per second
						const totalFrames = Math.round(duration / (1000 / frameRate));
						let frame = 0;
						
						if (totalBalance !== 0) {
							const counter = setInterval(() => {
								frame++;
								const progress = frame / totalFrames;
								const currentValue = Math.floor(progress * totalBalance);
								element.textContent = currentValue.toLocaleString();

								if (frame === totalFrames) {
									clearInterval(counter);
									element.textContent = totalBalance.toLocaleString();
								}
							}, 1000 / frameRate);
						} else {
							element.textContent = "0"; // Set to 0 if no value
						}
					});
				} else {
					dataContainer.classList.add('switch-off');
				}
			});
		}

		// Graph Chart for MILL WISE HR PIPE PURCHASE Started
		const dash_pur_2_summary_monthly_companywise = @json($dash_pur_2_summary_monthly_companywise);
		const mills = ['187', '170', '133'];
		const colors = [
			'rgba(220, 53, 69, 1)',
			'rgba(0, 136, 204, 1)',
			'rgba(25, 135, 84, 1)',
			'rgba(43, 170, 177, 1)',
			'rgba(219, 150, 81, 1)',
		];
		const { datasets, chartLabels } = generateChartDatasets(dash_pur_2_summary_monthly_companywise, mills, colors);

		function generateChartDatasets(data, mills, colors) {
			// Group data by 'dat' field
			const groupedData = data.reduce((acc, item) => {
				if (!acc[item.dat]) acc[item.dat] = [];
				acc[item.dat].push(item);
				return acc;
			}, {});

			// Get unique 'dat' values for chart labels
			const chartLabels = Object.keys(groupedData);

			// Initialize datasets
			const datasets = [];

			// Loop through each mill and create datasets
			mills.forEach((mill, index) => {
				const dataForMill = chartLabels.map(dat => {
					const millData = groupedData[dat]?.find(item => item.mill_code.toString() === mill);
					return millData ? millData.total_weight : 0;
				});

				// Get the mill name
				const millName = chartLabels
					.map(dat => groupedData[dat]?.find(item => item.mill_code.toString() === mill)?.mill_name)
					.find(name => name) || `Mill ${mill}`; // Default if not found

				// Add dataset for the mill
				datasets.push({
					label: millName,
					data: dataForMill,
					backgroundColor: colors[index] || 'rgba(0, 0, 0, 0.5)', // Default color if not enough colors provided
					stack: `Stack ${index}`,
				});
			});

			// Create dataset for "Others" (mills not in the mills array)
			const othersData = chartLabels.map(dat => {
				return groupedData[dat]?.reduce((acc, item) => {
					if (!mills.includes(item.mill_code.toString())) acc += item.total_weight;
					return acc;
				}, 0) || 0; // Default to 0 if no matching items
			});

			datasets.push({
				label: 'Others',
				data: othersData,
				backgroundColor: 'rgba(200, 200, 200, 1)', // Default color for "Others"
				stack: 'Stack Others',
			});

			return { datasets, chartLabels };
		}
		// Graph Chart for MILL WISE HR PIPE PURCHASE Ended 

		// donut graph for Monthly Tonage Started 
		const MonthlyTonage = document.getElementById('MonthlyTonage');
		let monthlyTonageChart; // Declare a global variable to hold the chart instance
		let top5CustomerPerformanceChart;

		function groupByMillCode(mills, data) {
			const result = {
				labels: [], // To hold the labels for the chart
				data: [], // To hold the total_weight for each mill
				backgroundColor: [] // To hold the colors for the chart
			};

			// Initialize groups for each mill in mills array
			mills.forEach(mill => {
				result[mill] = { weight: 0, name: "", backgroundColor: "" };
			});

			// Add a group for "Others"
			result['Others'] = { weight: 0, name: "Others" };

			// Iterate through the data to group by mill_code and calculate total_weight
			data.forEach(item => {
				const millCode = item.mill_code.toString();
				const millName = mills.includes(millCode) ? item.mill_name : 'Others';

				// Aggregate the total_weight based on the mill_code or group it under "Others"
				if (millName === 'Others') {
					result['Others'].weight += item.total_weight;
					result['Others'].backgroundColor = 'rgba(200, 200, 200, 1)';

				} else {
					result[millCode].weight += item.total_weight;
					result[millCode].name = item.mill_name;
					if(item.mill_name=="STEELEX"){
						result[millCode].backgroundColor = 'rgba(220, 53, 69, 1)';
					}
					else if(item.mill_name=="S.P.M"){
						result[millCode].backgroundColor = 'rgba(0, 136, 204, 1)';
					}
					else if(item.mill_name=="MEHBOOB PIPE"){
						result[millCode].backgroundColor = 'rgba(25, 135, 84, 1)';
					}
				}
			});
			// Prepare the final chart data
			for (const key in result) {
				if (result[key].weight > 0) {
					result.labels.push(result[key].name);
					result.data.push(result[key].weight);
					result.backgroundColor.push(result[key].backgroundColor);
				}
			}

			return result;
		}

		// donut graph for Monthly Tonage Ended 

		// get Monthly Tonage Of Customer Started
		function getMonthlyTonageOfCustomer(){
			var month = document.getElementById('filterHR').value;
			var acc_name = document.getElementById('hr_monthly_tonage_of_coa').value;
			var table = document.getElementById('HRMonthlyTonageOfCust');

			while (table.rows.length > 0) {
                table.deleteRow(0);
            }

			$.ajax({
				type: "GET",
				url: '/dashboard-tabs/hr/monthlyTonageOfCustomer',
				data: {
					month: month,
					acc_name:acc_name,
				},
				beforeSend: function () {
                    $('#HRMonthlyTonageOfCust').html('<tr><td colspan="2" class="text-center">Loading Data Please Wait...</td></tr>');
                },
				success: function(result) {
					var rows = '';

					$.each(result, function (index, value) {
						rows += `<tr>
							<td>${value['company_name'] ? value['company_name'] : ''}</td>
                            <td>${value['weight'] ? value['weight'] : ''}</td>
 						</tr>`;
					});
					$('#HRMonthlyTonageOfCust').html(rows);
				},
				error: function() {
					alert("Error loading HR Monthly Tonage Of Customer Data");
				}
			});
		}

		// get Monthly Tonage Of Customer Ended

		// on tab changes flow
		document.querySelectorAll('.nav-link-dashboard-tab').forEach(tabLink => {
            tabLink.addEventListener('click', function() {
                tabId = this.getAttribute('data-bs-target');
                tabChanged(tabId);
            });
        });

		function tabChanged(tabId) {
			if(tabId=="#HR"){
				var table = document.getElementById('SteelexSaleTable');
				while (table.rows.length > 0) {
					table.deleteRow(0);
				}

				var table = document.getElementById('SPMSaleTable');
				while (table.rows.length > 0) {
					table.deleteRow(0);
				}

				var table = document.getElementById('MehboobSaleTable');
				while (table.rows.length > 0) {
					table.deleteRow(0);
				}

				var table = document.getElementById('GodownSaleTable');
				while (table.rows.length > 0) {
					table.deleteRow(0);
				}

				if (top5CustomerPerformanceChart) {
					top5CustomerPerformanceChart.destroy();
				}

				const top5CustomerPerformance = document.getElementById('top5CustomerPerformance');

				top5CustomerPerformance.width = 600; // Set desired width
				top5CustomerPerformance.height = 353; // Set desired height

				// Create the new chart
				top5CustomerPerformanceChart = new Chart(top5CustomerPerformance, {
					type: 'bar',
					data: {
						labels: chartLabels, // 'dat' values as labels
						datasets: datasets,  // Dynamic datasets based on groupedData
					},
				});

				var month = document.getElementById('filterHR').value;

				$.ajax({
					type: "GET",
					url: '/dashboard-tabs/hr',
					data: {
						month: month,
					},
					success: function(result) {
						const groupedData = groupByMillCode(mills, result['dash_pur_2_summary_monthly_companywise']);

						if (monthlyTonageChart) {
							monthlyTonageChart.destroy();
						}

						const chartData = {
							labels: groupedData.labels, // Set the labels directly here
							datasets: [
								{
									data: groupedData.data, // Extract total_weight values for each mill
									backgroundColor: groupedData.backgroundColor, // Assign background colors
								}
							]
						};
						
						// Create the doughnut chart
						monthlyTonageChart = new Chart(MonthlyTonage, {
							type: 'doughnut',
							data: chartData,
						});

						var rows = '';

						$.each(result['steelex'], function (index, value) {
							rows += `<tr>
								<td>${value['ac_name'] ? value['ac_name'] : ''}</td>
								<td>${value['weight'] ? value['weight'] : ''}</td>
							</tr>`;
						});
						$('#SteelexSaleTable').html(rows);

						var rows = '';

						$.each(result['spm'], function (index, value) {
							rows += `<tr>
								<td>${value['ac_name'] ? value['ac_name'] : ''}</td>
								<td>${value['weight'] ? value['weight'] : ''}</td>
							</tr>`;
						});
						$('#SPMSaleTable').html(rows);

						var rows = '';

						$.each(result['mehboob'], function (index, value) {
							rows += `<tr>
								<td>${value['ac_name'] ? value['ac_name'] : ''}</td>
								<td>${value['weight'] ? value['weight'] : ''}</td>
							</tr>`;
						});
						$('#MehboobSaleTable').html(rows);

						var rows = '';

						$.each(result['godown'], function (index, value) {
							rows += `<tr>
								<td>${value['ac_name'] ? value['ac_name'] : ''}</td>
								<td>${value['weight'] ? value['weight'] : ''}</td>
							</tr>`;
						});
						$('#GodownSaleTable').html(rows);

					},
					error: function() {
						alert("Error loading HR data");
					}
				});
        	}
		}

		function getTabData() {
            const activeTabLink = document.querySelector('.nav-link-dashboard-tab.active');
            if (activeTabLink) {
                activeTabLink.click();
            }
        }
	</script>									
</html>