@include('layouts.header')

	<style>
		@keyframes slideIn {
			from {
				transform: translateY(-50px);
				opacity: 0;
			}
			to {
				transform: translateY(0);
				opacity: 1;
			}
		}
		.animated-amount {
			animation: slideIn 1s ease-out;
		}
		/* Main container for the seen users */
		.seen-users {
			display: flex;
			align-items: center;
			flex-wrap: wrap;  /* Allow wrapping for smaller screens */
		}

		/* Container for user icons */
		.user-icon-container {
			display: flex;
			justify-content: flex-start;
			position: relative;
		}

		/* Styling for each user icon */
		.user-icon {
			width: 50px;
			height: 50px;
			margin-right: -10px; /* Slight overlap */
			border-radius: 50%;
			background-size: cover;
			background-position: center;
			border: 2px solid #fff;
			transition: transform 0.3s ease;
			position: relative;
			display: flex;
			justify-content: center;
			align-items: center;
		}

		/* Add hover effect */
		.user-icon:hover {
			transform: scale(1.1); /* Slightly enlarge on hover */
		}

		/* Styling for the "more users" icon */
		.user-icon.more-users {
			display: none; /* Hidden by default */
			width: 50px;
			height: 50px;
			background-color: #f1f1f1;
			color: #007bff;
			font-weight: bold;
			text-align: center;
			line-height: 50px;
			border-radius: 50%;
			border: 2px solid #fff;
		}

		/* Title below user icon */
		.user-title {
			position: absolute;
			bottom: -18px;
			text-align: center;
			font-size: 12px;
			color: #333;
			width: 100%;
			overflow: hidden;
		}

		/* Show the "more users" icon if there are more than 5 users */
		.seen-users.more-than-5 .user-icon-container .user-icon:nth-child(n+6) {
			display: none;
		}

		.seen-users.more-than-5 .user-icon-container .user-icon.more-users {
			display: block;
		}

		h3{
			margin-top: 7px;
			margin-bottom: 10px;
		}
	</style>
	<body>
		<section class="body">
			<div class="inner-wrapper">
				@include('layouts.leftmenu')
				<section role="main" class="content-body">
					
					@include('layouts.homepageheader')
					<!-- start: page -->
					<div class="row cust-pad">
						<!-- summaries -->

						<div class="col-12 col-md-3 mb-2">
							<section class="card card-featured-left card-featured-primary mb-2">
								<div class="card-body" style="background-image: url('/assets/img/pdc-bg-img.webp'); background-position: right bottom; background-repeat: no-repeat; background-size: contain;">
									<h3 class="amount text-dark"><strong>Post Date Cheques</strong></h3>
									@if(isset($pdc) && isset($pdc->Total_Balance))
										<h2 class="amount m-0 text-primary"><strong class="amount m-0 text-primary">{{ $pdc->Total_Balance }}</strong><span class="title text-end text-dark h6"> PKR</span></h2>
									@else
										<h2 class="amount m-0 text-primary"><strong>0</strong><span class="title text-end text-dark h6"> PKR</span></h2>
									@endif
									<div class="summary-footer">
										<a class="text-muted text-uppercase" href="#">View Details</a>
									</div>
								</div>
							</section>
						</div>

						<div class="col-12 col-md-3 mb-2">
							<section class="card card-featured-left card-featured-danger mb-2">
								<div class="card-body" style="background-image: url('/assets/img/pdc-bg-img.webp'); background-position: right bottom; background-repeat: no-repeat; background-size: contain;">
									<h3 class="amount text-dark"><strong>Bank</strong></h3>
									@if(isset($banks) && isset($banks->Total_Balance))
										<h2 class="amount m-0 text-danger"><strong class="amount m-0 text-primary">{{ $banks->Total_Balance }}</strong><span class="title text-end text-dark h6"> PKR</span></h2>
									@else
										<h2 class="amount m-0 text-danger"><strong>0</strong><span class="title text-end text-dark h6"> PKR</span></h2>
									@endif
									<div class="summary-footer">
										<a class="text-muted text-uppercase" href="#">View Details</a>
									</div>
								</div>
							</section>
						</div>

						<div class="col-12 col-md-3 mb-2">
							<section class="card card-featured-left card-featured-success mb-2">
								<div class="card-body" style="background-image: url('/assets/img/pdc-bg-img.webp'); background-position: right bottom; background-repeat: no-repeat; background-size: contain;">
									<h3 class="amount text-dark"><strong>Cash</strong></h3>
									@if(isset($cash) && isset($cash->Total_Balance))
										<h2 class="amount m-0 text-success"><strong class="amount m-0 text-primary">{{ $cash->Total_Balance }}</strong><span class="title text-end text-dark h6"> PKR</span></h2>
									@else
										<h2 class="amount m-0 text-success"><strong>0</strong><span class="title text-end text-dark h6"> PKR</span></h2>
									@endif
									<div class="summary-footer">
										<a class="text-muted text-uppercase" href="#">View Details</a>
									</div>
								</div>
							</section>
						</div>

						<div class="col-12 col-md-3 mb-2">
							<section class="card card-featured-left card-featured-tertiary mb-2">
								<div class="card-body" style="background-image: url('/assets/img/pdc-bg-img.webp'); background-position: right bottom; background-repeat: no-repeat; background-size: contain;">
									<h3 class="amount text-dark"><strong>Foreign Currency</strong></h3>
									@if(isset($foreign) && isset($foreign->Total_Balance))
										<h2 class="amount m-0 text-tertiary"><strong class="amount m-0 text-primary">{{ $foreign->Total_Balance }}</strong><span class="title text-end text-dark h6"> PKR</span></h2>
									@else
										<h2 class="amount m-0 text-tertiary"><strong>0</strong><span class="title text-end text-dark h6"> PKR</span></h2>
									@endif
									<div class="summary-footer">
										<a class="text-muted text-uppercase" href="#">View Details</a>
									</div>
								</div>
							</section>
						</div>
						
						<div class="col-12 col-md-3 mb-2">
							<section class="card card-featured-left card-featured-tertiary">
								<div class="card-body" style="background-image: url('/assets/img/pdc-bg-img.webp'); background-position: right bottom; background-repeat: no-repeat; background-size: contain;">
									<h3 class="amount text-dark"><strong>Last Month Purchase</strong></h3>
									@if(isset($last_month_purchase) && isset($last_month_purchase->total_cr_amt) && isset($last_month_purchase->total_weight))
										<h2 class="amount m-0 text-tertiary"><strong class="amount m-0 text-primary">{{ $last_month_purchase->total_cr_amt }}</strong><span class="title text-end text-dark h6"> PKR</span></h2>
										<h2 class="amount m-0 text-tertiary"><strong class="amount m-0 text-primary">{{ $last_month_purchase->total_weight }}</strong><span class="title text-end text-dark h6"> M-Ton</span></h2>
									@else
										<h2 class="amount m-0 text-tertiary"><strong>0</strong><span class="title text-end text-dark h6"> PKR</span></h2>
										<h2 class="amount m-0 text-tertiary"><strong>0</strong><span class="title text-end text-dark h6"> M-Ton</span></h2>
									@endif
									<div class="summary-footer">
										<a class="text-muted text-uppercase" href="#">View Details</a>
									</div>
								</div>
							</section>

							<section class="card card-featured-left card-featured-tertiary mt-3">
								<div class="card-body" style="background-image: url('/assets/img/pdc-bg-img.webp'); background-position: right bottom; background-repeat: no-repeat; background-size: contain;">
									<h3 class="amount text-dark"><strong>Last Month Sale</strong></h3>
									@if(isset($last_month_sale) && isset($last_month_sale->total_dr_amt) && isset($last_month_sale->total_weight) )
										<h2 class="amount m-0 text-tertiary"><strong class="amount m-0 text-primary">{{ $last_month_sale->total_dr_amt }}</strong><span class="title text-end text-dark h6"> PKR</span></h2>
										<h2 class="amount m-0 text-tertiary"><strong class="amount m-0 text-primary">{{ $last_month_sale->total_weight }}</strong><span class="title text-end text-dark h6"> M-Ton</span></h2>
									@else
										<h2 class="amount m-0 text-tertiary"><strong>0</strong><span class="title text-end text-dark h6"> PKR</span></h2>
										<h2 class="amount m-0 text-tertiary"><strong>0</strong><span class="title text-end text-dark h6"> M-Ton</span></h2>
									@endif
									<div class="summary-footer">
										<a class="text-muted text-uppercase" href="#">View Details</a>
									</div>
								</div>
							</section>
						</div>

						<div class="col-12 col-md-3 mb-2">
							<section class="card card-featured-left card-featured-tertiary">
								<div class="card-body" style="background-image: url('/assets/img/pdc-bg-img.webp'); background-position: right bottom; background-repeat: no-repeat; background-size: contain;">
									<h3 class="amount text-dark"><strong>Total Payables</strong></h3>
									@if(isset($payables) && isset($payables->total_balance))
										<h2 class="amount m-0 text-tertiary"><strong class="amount m-0 text-primary">{{ $payables->total_balance }}</strong><span class="title text-end text-dark h6"> PKR</span></h2>
									@else
										<h2 class="amount m-0 text-tertiary"><strong>0</strong><span class="title text-end text-dark h6"> PKR</span></h2>
									@endif
									<div class="summary-footer">
										<a class="text-muted text-uppercase" href="#">View Details</a>
									</div>
								</div>
							</section>

							<section class="card card-featured-left card-featured-tertiary mt-3">
								<div class="card-body" style="background-image: url('/assets/img/pdc-bg-img.webp'); background-position: right bottom; background-repeat: no-repeat; background-size: contain;">
									<h3 class="amount text-dark"><strong>Total Receivables</strong></h3>
									@if(isset($receivables) && isset($receivables->total_balance))
										<h2 class="amount m-0 text-tertiary"><strong class="amount m-0 text-primary">{{ $receivables->total_balance }}</strong><span class="title text-end text-dark h6"> PKR</span></h2>
									@else
										<h2 class="amount m-0 text-tertiary"><strong>0</strong><span class="title text-end text-dark h6"> PKR</span></h2>
									@endif
									<div class="summary-footer">
										<a class="text-muted text-uppercase" href="#">View Details</a>
									</div>
								</div>
							</section>
						</div>

						<div class="col-12 col-md-3 mb-2">
							<section class="card card-featured-left card-featured-tertiary">
								<div class="card-body" style="background-image: url('/assets/img/pdc-bg-img.webp'); background-position: right bottom; background-repeat: no-repeat; background-size: contain;">
									<h3 class="amount text-dark"><strong>Long Term Loan</strong></h3>
									@if(isset($long_term_loan) && isset($long_term_loan->total_balance))
										<h2 class="amount m-0 text-tertiary"><strong class="amount m-0 text-primary">{{ $long_term_loan->total_balance }}</strong><span class="title text-end text-dark h6"> PKR</span></h2>
									@else
										<h2 class="amount m-0 text-tertiary"><strong>0</strong><span class="title text-end text-dark h6"> PKR</span></h2>
									@endif
									<div class="summary-footer">
										<a class="text-muted text-uppercase" href="#">View Details</a>
									</div>
								</div>
							</section>

							<section class="card card-featured-left card-featured-tertiary mt-3">
								<div class="card-body" style="background-image: url('/assets/img/pdc-bg-img.webp'); background-position: right bottom; background-repeat: no-repeat; background-size: contain;">
									<h3 class="amount text-dark"><strong>Short Term Loan</strong></h3>
									@if(isset($short_term_loan) && isset($short_term_loan->total_balance))
										<h2 class="amount m-0 text-tertiary"><strong class="amount m-0 text-primary">{{ $short_term_loan->total_balance }}</strong><span class="title text-end text-dark h6"> PKR</span></h2>
									@else
										<h2 class="amount m-0 text-tertiary"><strong>0</strong><span class="title text-end text-dark h6"> PKR</span></h2>
									@endif
									<div class="summary-footer">
										<a class="text-muted text-uppercase" href="#">View Details</a>
									</div>
								</div>
							</section>
						</div>

						<div class="col-12 col-md-3 mb-2">
							<section class="card card-featured-left card-featured-tertiary">
								<div class="card-body">
									<h3 class="amount text-dark"><strong>Active Users</strong></h3>

									<table class="table table-responsive-md table-striped mb-0">
										<tbody>
											<tr>
												<td>User Name Here</td>
												<td><span class="badge badge-success">Active</span></td>
											</tr>
											<tr>
												<td>User Name Here</td>
												<td><span class="badge badge-success">Active</span></td>
											</tr>
											<tr>
												<td>User Name Here</td>
												<td><span class="badge badge-success">Active</span></td>
											</tr>
											<tr>
												<td>User Name Here</td>
												<td><span class="badge badge-success">Active</span></td>
											</tr>
											<tr>
												<td>User Name Here</td>
												<td><span class="badge badge-success">Active</span></td>
											</tr>
										</tbody>
									</table>
								</div>
							</section>
						</div>
							
						<!-- summaries ends here -->
						<div class="tabs">
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
		let seenUsers = [
			{ name: 'User 1', image: 'user-icon.png' },
			{ name: 'User 2', image: 'user-icon.png' },
			{ name: 'User 3', image: 'user-icon.png' },
			{ name: 'User 4', image: 'user-icon.png' },
			{ name: 'User 5', image: 'user-icon.png' },
			{ name: 'User 6', image: 'user-icon.png' },
			{ name: 'User 7', image: 'user-icon.png' }
		];

		// Select the container for seen users
		let seenUsersContainer = document.querySelector('.seen-users');
		let userIconContainer = seenUsersContainer.querySelector('.user-icon-container');

		// Loop through the users and create user icons with titles
		seenUsers.forEach((user, index) => {
			if (index < 5) { // Show only up to 5 user icons
				let userIcon = document.createElement('div');
				userIcon.classList.add('user-icon');
				userIcon.style.backgroundImage = `url('/assets/img/${user.image}')`; // Corrected URL interpolation
				let userTitle = document.createElement('span');
				userTitle.classList.add('user-title');
				userTitle.textContent = user.name; // Set user name as title
				userIcon.appendChild(userTitle);
				userIconContainer.appendChild(userIcon);
			}
		});

		// Check if there are more than 5 users and add "+X" icon
		if (seenUsers.length > 5) {
			// Create and append the "+X" icon
			let moreUsersIcon = document.createElement('div');
			moreUsersIcon.classList.add('user-icon', 'more-users');
			moreUsersIcon.textContent = `+${seenUsers.length - 5}`;  // Show the number of additional users
			userIconContainer.appendChild(moreUsersIcon);

			// Add the 'more-than-5' class to the container
			seenUsersContainer.classList.add('more-than-5');
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

			document.addEventListener("DOMContentLoaded", () => {
			const rollNumbers = document.querySelectorAll('.rolling-number');
			
			rollNumbers.forEach((element) => {
				const target = parseFloat(element.getAttribute('data-target')); // Final value
				const duration = 2000; // Animation duration in ms
				const stepTime = 20; // Time between updates
				const increment = target / (duration / stepTime);

				let current = 0;

				const updateNumber = () => {
					current += increment;
					if (current >= target) {
						element.textContent = target.toLocaleString(); // Stop at the target value
					} else {
						element.textContent = current.toFixed(2).toLocaleString(); // Continue animating
						setTimeout(updateNumber, stepTime);
					}
				};

				updateNumber();
			});
		});


		document.addEventListener("DOMContentLoaded", () => {
        const rollNumbers = document.querySelectorAll('.rolling-number');

        rollNumbers.forEach((element) => {
            const target = parseFloat(element.getAttribute('data-target')); // Final value
            const duration = 2000; // Animation duration in ms
            const stepTime = 20; // Time between updates (ms)
            const increment = target / (duration / stepTime); // Calculate incremental value
            let current = 0;

            const roll = () => {
                current += increment;

                // Check if we've reached the target
                if (current >= target) {
                    element.textContent = target.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }); // Final value
                } else {
                    element.textContent = current.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }); // Rolling effect
                    setTimeout(roll, stepTime); // Continue rolling
                }
            };

            roll();
        	});
    	});

		
	</script>
</html>