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
						<div class="row">
							<!-- PDC, Bank, Cash, and Foreign Currency Summary -->
							<div class="col-12 col-md-5 mb-2">
								<section class="card card-featured-left card-featured-secondary mb-2">
									<div class="card-body">
										<div class="widget-summary">
											<div class="widget-summary-col widget-summary-col-icon">
												<div class="summary-icon bg-secondary">
													<i class="fas fa-university"></i>
												</div>
											</div>
											<div class="row">
												@foreach (['PDC' => $pdc, 'Bank' => $banks, 'Cash' => $cash, 'Foreign Currency' => $foreign] as $label => $data)
												<div class="summary col-6">
													<strong class="amount">{{ $label }}</strong>
													<div class="info">
														@if (isset($data) && isset($data->Total_Balance) && strpos($data->Total_Balance, '.') !== false && substr($data->Total_Balance, strpos($data->Total_Balance, '.') + 1) > '0')
														<h4 class="amount m-0 text-primary">
															<strong class="rolling-number amount m-0 text-primary" data-target="{{ number_format($data->Total_Balance, 0, '.', ',') }}">0</strong>
															<span class="title text-end text-dark"> PKR</span>
														</h4>
														@elseif(isset($data) && isset($data->Total_Balance))
														<h4 class="amount m-0 text-primary">
															<strong class="rolling-number amount m-0 text-primary" data-target="{{ number_format($data->Total_Balance, 0, '.', ',') }}">0</strong>
															<span class="title text-end text-dark"> PKR</span>
														</h4>
														@else
														<h4 class="amount m-0 text-primary"><strong>0</strong><span class="title text-end text-dark"> PKR</span></h4>
														@endif
													</div>
												</div>
												@endforeach
											</div>
											<div class="summary-footer">
												<a class="text-muted text-uppercase" href="#">(View Details)</a>
											</div>
										</div>
									</div>
								</section>
							</div>
						
							<!-- Payables, Receivables, and Balance Summary -->
							<div class="col-12 col-md-4 mb-2">
								<section class="card card-featured-left card-featured-secondary mb-2">
									<div class="card-body">
										<div class="widget-summary">
											<div class="widget-summary-col widget-summary-col-icon">
												<div class="summary-icon bg-secondary">
													<i class="fas fa-university"></i>
												</div>
											</div>
											<div class="row">
												@foreach (['Total Payables' => $payables, 'Total Receivables' => $receivables] as $label => $data)
												<div class="summary col-6">
													<strong class="amount">{{ $label }}</strong>
													<div class="info">
														@if (isset($data) && isset($data->total_balance) && strpos($data->total_balance, '.') !== false && substr($data->total_balance, strpos($data->total_balance, '.') + 1) > '0')
														<h4 class="amount m-0 text-primary">
															<strong>{{ number_format($data->total_balance, 0, '.', ',') }}</strong>
															<span class="title text-end text-dark"> PKR</span>
														</h4>
														@elseif(isset($data) && isset($data->total_balance))
														<h4 class="amount m-0 text-primary">
															<strong>{{ number_format($data->total_balance, 0, '.', ',') }}</strong>
															<span class="title text-end text-dark"> PKR</span>
														</h4>
														@else
														<h4 class="amount m-0 text-primary"><strong>0</strong><span class="title text-end text-dark"> PKR</span></h4>
														@endif
													</div>
												</div>
												@endforeach
						
												<div class="summary col-6">
													<strong class="amount">Total Balance</strong>
													<div class="info">
														@php
														$balance = isset($receivables, $payables)
															? $receivables->total_balance - $payables->total_balance
															: (isset($receivables) ? $receivables->total_balance : -($payables->total_balance ?? 0));
														@endphp
														<h4 class="amount m-0 text-primary">
															<strong>{{ number_format($balance, 0, '.', ',') }}</strong>
															<span class="title text-end text-dark"> PKR</span>
														</h4>
													</div>
												</div>
											</div>
											<div class="summary-footer">
												<a class="text-muted text-uppercase" href="#">(View Details)</a>
											</div>
										</div>
									</div>
								</section>
							</div>
						
							<!-- Login Users Summary -->
							<div class="col-12 col-md-3 mb-2">
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
														<strong class="amount">{{ $login_users ?? '-' }}</strong>
													</div>
												</div>
												<div class="summary-footer mt-5">
													<a class="text-muted text-uppercase" href="{{ route('all-users') }}">(View All Users)</a>
												</div>
											</div>
										</div>
									</div>
								</section>
							</div>
						
							<!-- Last Month Purchase/Sale Summary -->
							<div class="col-12 col-md-5 mb-2">
								<section class="card card-featured-left card-featured-tertiary mb-2">
									<div class="card-body">
										<div class="widget-summary">
											<div class="widget-summary-col widget-summary-col-icon">
												<div class="summary-icon bg-tertiary">
													<i class="fas fa-wallet"></i>
												</div>
											</div>
											<div class="row">
												@foreach (['Last Month Purchase' => $last_month_purchase, 'Last Month Sale' => $last_month_sale] as $label => $data)
												<div class="summary col-6">
													<h4 class="amount mb-2"><strong>{{ $label }}</strong></h4>
													<div class="info">
														@if (isset($data->total_cr_amt))
														<h4 class="amount m-0 text-danger mt-2 mb-3">
															<strong>{{ number_format($data->total_cr_amt, 0, '.', ',') }}</strong>
															<span class="title text-end text-dark"> PKR</span>
														</h4>
														@endif
														@if (isset($data->total_weight))
														<h4 class="amount m-0 text-danger mt-2 mb-3">
															<strong>{{ number_format($data->total_weight, 0, '.', ',') }}</strong>
															<span class="title text-end text-dark"> M-Ton</span>
														</h4>
														@endif
													</div>
												</div>
												@endforeach
											</div>
										</div>
									</div>
								</section>
							</div>
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