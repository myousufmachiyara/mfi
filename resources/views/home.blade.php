@include('layouts.header')
	<body>
		<section class="body">
			<div class="inner-wrapper">
				@include('layouts.leftmenu')
				<section role="main" class="content-body">
					@include('layouts.homepageheader')
					<!-- start: page -->
					<div class="row cust-pad">
						<!-- summaries -->

						<div class="col-12 col-md-4 mb-2">
							<section class="card card-featured-left card-featured-secondary mb-2">
								<div class="card-body">
									<div class="widget-summary">
										<div class="widget-summary-col widget-summary-col-icon">
											<div class="summary-icon bg-secondary">
												<i class="fas fa-university"></i> <!-- PDC Icon -->
											</div>
										</div>
										<div class="row">
											<div class="summary col-6">
												<strong class="amount">PDC</strong>
												<div class="info">
													<h4 class="amount m-0 text-danger"><strong>14,890.30</strong>
														<span class="title text-end text-dark">PKR</span>
													</h4>
												</div>
											</div>
											<div class="summary col-6">
												<strong class="amount">Bank</strong>
												<div class="info">
													<h4 class="amount m-0 text-danger"><strong>14,890.30</strong>
														<span class="title text-end text-dark">PKR</span>
													</h4>
												</div>
											</div>
											<div class="summary col-6">
												<strong class="amount">Cash</strong>
												<div class="info">
													<h4 class="amount m-0 text-danger"><strong>14,890.30</strong>
														<span class="title text-end text-dark">PKR</span>
													</h4>
												</div>
											</div>
											<div class="summary col-6">
												<strong class="amount">Foreign Currency
												</strong>
												<div class="info">
													<h4 class="amount m-0 text-danger"><strong>14,890.30</strong>
														<span class="title text-end text-dark">PKR</span>
													</h4>
												</div>
											</div>
										</div>
										<div>
											<div class="summary-footer">
												<a class="text-muted text-uppercase" href="#">(View Details)</a>
											</div>
										</div>
									</div>
								</div>
							</section>
						</div>
					
						<div class="col-12 col-md-4 mb-2">
							<section class="card card-featured-left card-featured-secondary mb-2">
								<div class="card-body">
									<div class="widget-summary">
										<div class="widget-summary-col widget-summary-col-icon">
											<div class="summary-icon bg-secondary">
												<i class="fas fa-university"></i> <!-- PDC Icon -->
											</div>
										</div>
										<div class="row">
											<div class="summary col-6">
												<h4 class="title">Total Payables</h4>
												<div class="info">
													<strong class="amount">14,890.30</strong>
													<span class="title text-end">PKR</p>
												</div>
											</div>
											<div class="summary col-6">
												<h4 class="title">Total Receivables</h4>
												<div class="info">
													<strong class="amount">14,890.30</strong>
													<span class="title text-end">PKR</p>
												</div>
											</div>
											<div class="summary col-6">
												<h4 class="title">Total Balance</h4>
												<div class="info">
													<strong class="amount">14,890.30</strong>
													<span class="title text-end">PKR</p>
												</div>
											</div>
											
										</div>
										<div>
											<div class="summary-footer">
												<a class="text-muted text-uppercase" href="#">(View Details)</a>
											</div>
										</div>
									</div>
								</div>
							</section>
						</div>
					
						<div class="col-12 col-md-4 mb-2">
							<section class="card card-featured-left card-featured-quaternary">
								<div class="card-body">
									<div class="widget-summary">
										<div class="widget-summary-col widget-summary-col-icon">
											<div class="summary-icon bg-quaternary">
												<i class="fas fa-user"></i>
											</div>
										</div>
										<div class="widget-summary-col">
											<div class="summary">
												<h4 class="title">Login Users</h4>
												<div class="info">
													<strong class="amount">3765</strong>
												</div>
											</div>
											<div class="summary-footer">
												<a class="text-muted text-uppercase" href="#">(View All)</a>
											</div>
										</div>
									</div>
								</div>
							</section>
						</div>
						<div class="col-12 col-md-3 mb-2">
							<section class="card card-featured-left card-featured-tertiary mb-2">
								<div class="card-body">
									<div class="widget-summary">
										<div class="widget-summary-col widget-summary-col-icon">
											<div class="summary-icon bg-tertiary">
												<i class="fas fa-wallet"></i> <!-- Cash Icon -->
											</div>
										</div>
										<div class="widget-summary-col">
											<div class="summary">
												<strong class="amount">Long Term Loan
												</strong>
												<div class="info">
													<h4 class="amount m-0 text-danger"><strong>14,890.30</strong>
														<span class="title text-end text-dark">PKR</span>
													</h4>
												</div>
											</div>
											<div class="summary">
												<strong class="amount">Short Term Loan
												</strong>
												<div class="info">
													<h4 class="amount m-0 text-danger"><strong>14,890.30</strong>
														<span class="title text-end text-dark">PKR</span>
													</h4>
												</div>
											</div>
											<div class="summary-footer">
												<a class="text-muted text-uppercase" href="#">(withdraw)</a>
											</div>
										</div>
									</div>
								</div>
							</section>
						</div>

						<div class="col-12 col-md-3 mb-2">
							<section class="card card-featured-left card-featured-tertiary mb-2">
								<div class="card-body">
									<div class="widget-summary">
										<div class="widget-summary-col widget-summary-col-icon">
											<div class="summary-icon bg-tertiary">
												<i class="fas fa-wallet"></i> <!-- Cash Icon -->
											</div>
										</div>
										<div class="widget-summary-col">
											<div class="summary">
												<strong class="amount">Last Month Purchase
												</strong>
												<div class="info">
													<h4 class="amount m-0 text-danger mt-2 mb-3"><strong>14,890.30</strong>
														<span class="title text-end text-dark">PKR</span>
													</h4>
													<h4 class="amount m-0 text-danger"><strong>14,890.30</strong>
														<span class="title text-end text-dark">M-Ton</span>
													</h4>
												</div>
											</div>
											<div class="summary-footer">
												<a class="text-muted text-uppercase" href="#">(withdraw)</a>
											</div>
										</div>
									</div>
								</div>
							</section>
						</div>

						<div class="col-12 col-md-3 mb-2">
							<section class="card card-featured-left card-featured-tertiary mb-2">
								<div class="card-body">
									<div class="widget-summary">
										<div class="widget-summary-col widget-summary-col-icon">
											<div class="summary-icon bg-tertiary">
												<i class="fas fa-wallet"></i> <!-- Cash Icon -->
											</div>
										</div>
										<div class="widget-summary-col">
											<div class="summary">
												<strong class="amount">Last Month Sale
												</strong>
												<div class="info">
													<h4 class="amount m-0 text-danger"><strong>14,890.30</strong>
														<span class="title text-end text-dark">PKR</span>
													</h4>
													<h4 class="amount m-0 text-danger"><strong>14,890.30</strong>
														<span class="title text-end text-dark">M-Ton</span>
													</h4>
												</div>
											</div>
											<div class="summary-footer">
												<a class="text-muted text-uppercase" href="#">(withdraw)</a>
											</div>
										</div>
									</div>
								</div>
							</section>
						</div>

						<div class="col-12 col-md-3 mb-2">
							<section class="card card-featured-left card-featured-primary">
								<div class="card-body">
									<div class="widget-summary">
										<div class="widget-summary-col widget-summary-col-icon">
											<div class="summary-icon bg-primary">
												<i class="fas fa-cash-register"></i>
											</div>
										</div>
										<div class="widget-summary-col">
											<div class="summary">
												<h4 class="amount mb-2">Last Month Cash Flow</h4>
												<div class="info">
													<span>CASH IN:</span> <strong class="amount">3765</strong>
												</div>
												<div class="info">
													<span>CASH OUT:</span> <strong class="amount">3765</strong>
												</div>
											</div>
											<div class="summary-footer">
												<a class="text-muted text-uppercase" href="#">(report)</a>
											</div>
										</div>
									</div>
								</div>
							</section>
						</div>

						<!-- summaries ends here -->
						<div class="tabs">
							<ul class="nav nav-tabs">
								<li class="nav-item">
									<a class="nav-link nav-link-rep" data-bs-target="#sales" href="#sales" data-bs-toggle="tab">Sales</a>
								</li>
								<li class="nav-item">
									<a class="nav-link nav-link-rep" data-bs-target="#pur" href="#pur" data-bs-toggle="tab">Purchases</a>
								</li>
								<li class="nav-item">
									<a class="nav-link nav-link-rep" data-bs-target="#vouchers" href="#vouchers" data-bs-toggle="tab">Vouchers</a>
								</li>
								<li class="nav-item">
									<a class="nav-link nav-link-rep" data-bs-target="#complains" href="#complains" data-bs-toggle="tab">Complains</a>
								</li>
								<li class="nav-item">
									<a class="nav-link nav-link-rep" data-bs-target="#items" href="#items" data-bs-toggle="tab">Items</a>
								</li>
								<li class="nav-item">
									<a class="nav-link nav-link-rep" data-bs-target="#ret" href="#ret" data-bs-toggle="tab">Returns</a>
								</li>
								<li class="nav-item">
									<a class="nav-link nav-link-rep" data-bs-target="#acc" href="#acc" data-bs-toggle="tab">Accounts</a>
								</li>
							</ul>
							<div class="tab-content">
								<div id="sales" class="tab-pane">
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