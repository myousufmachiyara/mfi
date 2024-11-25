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
		@php die(session()); @endphp
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
									<a class="nav-link nav-link-rep" data-bs-target="#HR_Sale" href="#HR_Sale" data-bs-toggle="tab">HR Sale</a>
								</li>
								<li class="nav-item">
									<a class="nav-link nav-link-rep" data-bs-target="#GS" href="#GS" data-bs-toggle="tab">Garder Sale</a>
								</li>
								<li class="nav-item">
									<a class="nav-link nav-link-rep" data-bs-target="#DS" href="#DS" data-bs-toggle="tab">Door Sale</a>
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
								<div id="BNF" class="tab-pane">
									<div class="row form-group pb-3">
										<!-- Category Sale -->
										<div class="col-12 col-md-4 mb-2">
											<section class="card">
												<header class="card-header">
													<div class="card-actions">
														<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
													</div>

													<h2 class="card-title">Category Sales Chart</h2>
												</header>
												<div class="card-body">
													<canvas id="catSalesChart"></canvas>
												</div>
											</section>
										</div>

										<!-- Half Year Sales -->
										<div class="col-12 col-md-4 mb-2">
											<section class="card">
												<header class="card-header">
													<div class="card-actions">
														<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
													</div>

													<h2 class="card-title">Half Year Sale</h2>
												</header>
												<div class="card-body">
													<canvas id="halfYearSale"></canvas>
												</div>
											</section>
										</div>

										<!-- Half Year Sales 2 -->
										<div class="col-12 col-md-4 mb-2">
											<section class="card">
												<header class="card-header">
													<div class="card-actions">
														<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
													</div>

													<h2 class="card-title">Half Year Sale 2</h2>
												</header>
												<div class="card-body">
													<canvas id="halfYearSale2"></canvas>
												</div>
											</section>
										</div>

										<!-- Top 5 Customers -->
										<div class="col-12 col-md-4 mb-2">
											<section class="card">
												<header class="card-header">
													<div class="card-actions">
														<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
													</div>

													<h2 class="card-title">Customers Of The Month</h2>
												</header>
												<div class="card-body">
													<canvas id="top5Customers"></canvas>
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

		const catSalesChart = document.getElementById('catSalesChart');
		new Chart(catSalesChart, {
			type: 'bar',
			data: {
			labels: ['Pipe', 'Garder', 'TR', 'ABC', 'XYZ', 'Orange'],
			datasets: [{
				label: 'No. Of Tons',
				data: [20, 11, 50, 12, 100, 38],
				borderWidth: 1
			}]
			},
			options: {
				scales: {
					y: {
						beginAtZero: false
					}
				}
			}
		});

		const halfYearSale = document.getElementById('halfYearSale');
		const Utils = {
			months: function(options) {
				const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
				return months.slice(0, options.count);  // Returns first 'count' months
			}
		};
		const labels = Utils.months({count: 6});

		new Chart(halfYearSale, {
			type: 'line',
			data: {
				labels: labels,
				datasets: [{
					label: 'My First Dataset',
					data: [65, 59, 80, 81, 56, 55],
				}]
			},
			options: {
				scales: {
					y: {
						beginAtZero: false
					}
				}
			}
		}); 
		
		const halfYearSale2 = document.getElementById('halfYearSale2');
		const NUMBER_CFG = {count: 6, min: -100, max: 100};

		const data = {
			labels: labels,
			datasets: [
				{
				label: 'Dataset 1',
				data: [65, 59, 80, 81, 56, 55],
				borderColor: '#FF0000',
				backgroundColor:' #FF0000'
				},
				{
				label: 'Dataset 2',
				data: [21, 34, 53, 88, 71, 86],
				borderColor: '#0000FF',
				backgroundColor: '#0000FF',
				}
			]
		};
		new Chart(halfYearSale2, {
			type: 'line',
			data: data,
			options: {
				responsive: true,
				plugins: {
				legend: {
					position: 'top',
				},
				title: {
					display: true,
					text: 'Chart.js Line Chart'
				}
				}
			},
		}); 

		const top5Customers = document.getElementById('top5Customers');

		new Chart(top5Customers, {
			type: 'doughnut',
			data: {
				labels: ['Red','Blue','Yellow'],
				datasets: [{
					label: 'My First Dataset',
					data: [300, 50, 100],
					backgroundColor: [
						'rgb(255, 99, 132)',
						'rgb(54, 162, 235)',
						'rgb(255, 205, 86)'
					],
					hoverOffset: 4
				}]
			},
		});

	</script>
											
</html>