@include('layouts.header')
	<style>
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
						<div class="col-12 col-md-5 mb-2">
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
													@if (isset($pdc) && isset($pdc->Total_Balance) && strpos($pdc->Total_Balance, '.') !== false && substr($pdc->Total_Balance, strpos($pdc->Total_Balance, '.') + 1) > '0')
														<h4 class="amount m-0 text-primary"><strong>{{ number_format($pdc->Total_Balance, 0, '.', ',') }}</strong><span class="title text-end text-dark"> PKR</span></h4>
													@elseif(isset($pdc) && isset($pdc->Total_Balance))
														<h4 class="amount m-0 text-primary"><strong>{{ number_format($pdc->Total_Balance, 0, '.', ',') }}</strong><span class="title text-end text-dark"> PKR</span></h4>
													@else
														<h4 class="amount m-0 text-primary"><strong>0</strong><span class="title text-end text-dark"> PKR</span></h4>
													@endif
												</div>
											</div>
											<div class="summary col-6">
												<strong class="amount">Bank</strong>
												<div class="info">
													@if (isset($banks) && isset($banks->Total_Balance) && strpos($banks->Total_Balance, '.') !== false && substr($banks->Total_Balance, strpos($banks->Total_Balance, '.') + 1) > '0')
														<h4 class="amount m-0 text-primary"><strong>{{ number_format($banks->Total_Balance, 0, '.', ',') }}</strong><span class="title text-end text-dark"> PKR</span></h4>
													@elseif(isset($banks) && isset($banks->Total_Balance))
														<h4 class="amount m-0 text-primary"><strong>{{ number_format($banks->Total_Balance, 0, '.', ',') }}</strong><span class="title text-end text-dark"> PKR</span></h4>
													@else
														<h4 class="amount m-0 text-primary"><strong>0</strong><span class="title text-end text-dark"> PKR</span></h4>
													@endif
												</div>
											</div>
											<div class="summary col-6">
												<strong class="amount">Cash</strong>
												<div class="info">
													@if (isset($cash) && isset($cash->Total_Balance) && strpos($cash->Total_Balance, '.') !== false && substr($cash->Total_Balance, strpos($cash->Total_Balance, '.') + 1) > '0')
														<h4 class="amount m-0 text-primary"><strong>{{ number_format($cash->Total_Balance, 0, '.', ',') }}</strong><span class="title text-end text-dark"> PKR</span></h4>
													@elseif(isset($cash) && isset($cash->Total_Balance))
														<h4 class="amount m-0 text-primary"><strong>{{ number_format($cash->Total_Balance, 0, '.', ',') }}</strong><span class="title text-end text-dark"> PKR</span></h4>
													@else
														<h4 class="amount m-0 text-primary"><strong>0</strong><span class="title text-end text-dark"> PKR</span></h4>
													@endif
												</div>
											</div>
											<div class="summary col-6">
												<strong class="amount">Foreign Currency
												</strong>
												<div class="info">
													@if (isset($foreign) && isset($foreign->Total_Balance) && strpos($foreign->Total_Balance, '.') !== false && substr($foreign->Total_Balance, strpos($foreign->Total_Balance, '.') + 1) > '0')
														<h4 class="amount m-0 text-primary"><strong>{{ number_format($foreign->Total_Balance, 0, '.', ',') }}</strong><span class="title text-end text-dark"> PKR</span></h4>
													@elseif(isset($foreign) && isset($foreign->Total_Balance))
														<h4 class="amount m-0 text-primary"><strong>{{ number_format($foreign->Total_Balance, 0, '.', ',') }}</strong><span class="title text-end text-dark"> PKR</span></h4>
													@else
														<h4 class="amount m-0 text-primary"><strong>0</strong><span class="title text-end text-dark"> PKR</span></h4>
													@endif
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
												<strong class="amount">Total Payables</strong>
												<div class="info">
													@if (isset($payables) && isset($payables->total_balance) && strpos($payables->total_balance, '.') !== false && substr($payables->total_balance, strpos($payables->total_balance, '.') + 1) > '0')
														<h4 class="amount m-0 text-primary"><strong>{{ number_format($payables->total_balance, 0, '.', ',') }}</strong><span class="title text-end text-dark"> PKR</span></h4>
													@elseif(isset($payables) && isset($payables->total_balance))
														<h4 class="amount m-0 text-primary"><strong>{{ number_format($payables->total_balance, 0, '.', ',') }}</strong><span class="title text-end text-dark"> PKR</span></h4>
													@else
														<h4 class="amount m-0 text-primary"><strong>0</strong><span class="title text-end text-dark"> PKR</span></h4>
													@endif
												</div>
												
											</div>
											<div class="summary col-6">
												<strong class="amount">Total Receivables</strong>
												<div class="info">
													@if (isset($receivables) && isset($receivables->total_balance) && strpos($receivables->total_balance, '.') !== false && substr($receivables->total_balance, strpos($receivables->total_balance, '.') + 1) > '0')
														<h4 class="amount m-0 text-primary"><strong>{{ number_format($receivables->total_balance, 0, '.', ',') }}</strong><span class="title text-end text-dark"> PKR</span></h4>
													@elseif(isset($receivables) && isset($receivables->total_balance))
														<h4 class="amount m-0 text-primary"><strong>{{ number_format($receivables->total_balance, 0, '.', ',') }}</strong><span class="title text-end text-dark"> PKR</span></h4>
													@else
														<h4 class="amount m-0 text-primary"><strong>0</strong><span class="title text-end text-dark"> PKR</span></h4>
													@endif
												</div>
											</div>
											@php
												// Check if the variables are set and have non-null values
												$balance = 0;  // Default value to prevent any errors

												if (isset($receivables) && isset($payables) && !empty($receivables->total_balance) && !empty($payables->total_balance)) {
													// If both variables exist and have a total_balance value
													$balance = $receivables->total_balance - $payables->total_balance;
												} elseif (isset($receivables) && !empty($receivables->total_balance)) {
													// If only receivables has a total_balance value
													$balance = $receivables->total_balance;
												} elseif (isset($payables) && !empty($payables->total_balance)) {
													// If only payables has a total_balance value
													$balance = -$payables->total_balance;  // Assuming you want a negative balance when only payables exist
												}
											@endphp

											<div class="summary col-6">
												<strong class="amount">Total Balance</strong>
												<div class="info">
													@if (strpos($balance, '.') !== false && substr($balance, strpos($balance, '.') + 1) > '0')
														<h4 class="amount m-0 text-primary"><strong>{{ number_format($balance, 0, '.', ',') }} </strong> <span class="title text-end text-dark"> PKR</span></h4>
													@else
														<h4 class="amount m-0 text-primary"><strong>{{ number_format($balance, 0, '.', ',') }} </strong> <span class="title text-end text-dark"> PKR</span></h4>
													@endif
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
													@if (isset($login_users))
														<strong class="amount">{{$login_users}}</strong>
													@else
														<h4 class="amount m-0 text-primary"><strong>-</strong>
													@endif
													<div class="seen-users">
														<div class="user-icon-container">
															
														</div>
													</div>
												</div>
											</div>
											<div class="summary-footer mt-5">
												<a class="text-muted text-uppercase" href="#">(View All Users)</a>
											</div>
										</div>
									</div>
								</div>
							</section>
						</div>
						<div class="col-12 col-md-5 mb-2">
							<section class="card card-featured-left card-featured-tertiary mb-2">
								<div class="card-body">
									<div class="widget-summary">
										<div class="widget-summary-col widget-summary-col-icon">
											<div class="summary-icon bg-tertiary">
												<i class="fas fa-wallet"></i> <!-- Cash Icon -->
											</div>
										</div>
										<div class="widget-summary-col">
											<div class="row">
												<div class="summary col-6">
													<h4 class="amount mb-2"><strong>Last Month Purchase</strong></h4>
													<div class="info">
														@if (isset($last_month_purchase) && isset($last_month_purchase->total_cr_amt) && strpos($last_month_purchase->total_cr_amt, '.') !== false && substr($last_month_purchase->total_cr_amt, strpos($last_month_purchase->total_cr_amt, '.') + 1) > '0')
															<h4 class="amount m-0 text-danger mt-2 mb-3"><strong>{{ number_format($last_month_purchase->total_cr_amt, 0, '.', ',') }}</strong><span class="title text-end text-dark"> PKR</span></h4>
														@elseif(isset($last_month_purchase) && isset($last_month_purchase->total_cr_amt))
															<h4 class="amount m-0 text-danger mt-2 mb-3"><strong>{{ number_format($last_month_purchase->total_cr_amt, 0, '.', ',') }}</strong><span class="title text-end text-dark"> PKR</span></h4>
														@else
															<h4 class="amount m-0 text-danger mt-2 mb-3"><strong>0</strong><span class="title text-end text-dark"> PKR</span></h4>
														@endif

														@if (isset($last_month_purchase) && isset($last_month_purchase->total_weight) && strpos($last_month_purchase->total_weight, '.') !== false && substr($last_month_purchase->total_weight, strpos($last_month_purchase->total_weight, '.') + 1) > '0')
															<h4 class="amount m-0 text-danger mt-2 mb-3"><strong>{{ number_format($last_month_purchase->total_weight, 0, '.', ',') }}</strong><span class="title text-end text-dark"> M-Ton</span></h4>
														@elseif(isset($last_month_purchase) && isset($last_month_purchase->total_weight))
															<h4 class="amount m-0 text-danger mt-2 mb-3"><strong>{{ number_format($last_month_purchase->total_weight, 0, '.', ',') }}</strong><span class="title text-end text-dark"> M-Ton</span></h4>
														@else
															<h4 class="amount m-0 text-danger mt-2 mb-3"><strong>0</strong><span class="title text-end text-dark"> M-Ton</span></h4>
														@endif
													</div>
												</div>
												<div class="summary col-6">
													<h4 class="amount mb-2"><strong>Last Month Sale</strong></h4>
													<div class="info">
														@if (isset($last_month_sale) && isset($last_month_sale->total_dr_amt) && strpos($last_month_sale->total_dr_amt, '.') !== false && substr($last_month_sale->total_dr_amt, strpos($last_month_sale->total_dr_amt, '.') + 1) > '0')
															<h4 class="amount m-0 text-danger mt-2 mb-3"><strong>{{ number_format($last_month_sale->total_dr_amt, 0, '.', ',') }}</strong><span class="title text-end text-dark"> PKR</span></h4>
														@elseif(isset($last_month_sale) && isset($last_month_sale->total_dr_amt))
															<h4 class="amount m-0 text-danger mt-2 mb-3"><strong>{{ number_format($last_month_sale->total_dr_amt, 0, '.', ',') }}</strong><span class="title text-end text-dark"> PKR</span></h4>
														@else
															<h4 class="amount m-0 text-danger mt-2 mb-3"><strong>0</strong><span class="title text-end text-dark"> PKR</span></h4>
														@endif

														@if (isset($last_month_sale) && isset($last_month_sale->total_weight) && strpos($last_month_sale->total_weight, '.') !== false && substr($last_month_sale->total_weight, strpos($last_month_sale->total_weight, '.') + 1) > '0')
															<h4 class="amount m-0 text-danger mt-2 mb-3"><strong>{{ number_format($last_month_sale->total_weight, 0, '.', ',') }}</strong><span class="title text-end text-dark"> M-Ton</span></h4>
														@elseif(isset($last_month_sale) && isset($last_month_sale->total_weight))
															<h4 class="amount m-0 text-danger mt-2 mb-3"><strong>{{ number_format($last_month_sale->total_weight, 0, '.', ',') }}</strong><span class="title text-end text-dark"> M-Ton</span></h4>
														@else
															<h4 class="amount m-0 text-danger mt-2 mb-3"><strong>0</strong><span class="title text-end text-dark"> M-Ton</span></h4>
														@endif
													</div>
												</div>
											<div>
											<div class="summary-footer">
												<a class="text-muted text-uppercase" href="#">(withdraw)</a>
											</div>
										</div>
									</div>
								</div>
							</section>
						</div>
						<div class="col-12 col-md-4 mb-2">
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
													@if (isset($long_term_loan) && isset($long_term_loan->total_balance) && strpos($long_term_loan->total_balance, '.') !== false && substr($long_term_loan->total_balance, strpos($long_term_loan->total_balance, '.') + 1) > '0')
														<h4 class="amount m-0 text-success"><strong>{{ number_format($long_term_loan->total_balance, 0, '.', ',') }}</strong><span class="title text-end text-dark"> PKR</span></h4>
													@elseif(isset($long_term_loan) && isset($long_term_loan->total_balance))
														<h4 class="amount m-0 text-success"><strong>{{ number_format($long_term_loan->total_balance, 0, '.', ',') }}</strong><span class="title text-end text-dark"> PKR</span></h4>
													@else
														<h4 class="amount m-0 text-success"><strong>0</strong><span class="title text-end text-dark"> PKR</span></h4>
													@endif
												</div>
											</div>
											<div class="summary">
												<strong class="amount">Short Term Loan
												</strong>
												<div class="info">
													@if (isset($short_term_loan) && isset($short_term_loan->total_balance) && strpos($short_term_loan->total_balance, '.') !== false && substr($short_term_loan->total_balance, strpos($short_term_loan->total_balance, '.') + 1) > '0')
														<h4 class="amount m-0 text-success"><strong>{{ number_format($short_term_loan->total_balance, 0, '.', ',') }}</strong><span class="title text-end text-dark"> PKR</span></h4>
													@elseif(isset($short_term_loan) && isset($short_term_loan->total_balance))
														<h4 class="amount m-0 text-success"><strong>{{ number_format($short_term_loan->total_balance, 0, '.', ',') }}</strong><span class="title text-end text-dark"> PKR</span></h4>
													@else
														<h4 class="amount m-0 text-success"><strong>0</strong><span class="title text-end text-dark"> PKR</span></h4>
													@endif
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
												<h4 class="amount mb-2"><strong>Last Month Cash Flow</strong></h4>
												<div class="info ">
													
													<h4 class="amount m-0 text-danger mb-3"><span class="h6 text-dark">CASH IN:  </span><strong>14,890.30</strong>
														<span class="title text-end text-dark">PKR</span>
													</h4>
													
													<h4 class="amount m-0 text-danger mb-3"><span class="h6 text-dark">CASH OUT:  </span><strong>14,890.30</strong>
														<span class="title text-end text-dark">M-Ton</span>
													</h4>
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
			{ name: 'User 1', image: 'user1.jpg' },
			{ name: 'User 2', image: 'user2.jpg' },
			{ name: 'User 3', image: 'user3.jpg' },
			{ name: 'User 4', image: 'user4.jpg' },
			{ name: 'User 5', image: 'user5.jpg' },
			{ name: 'User 6', image: 'user6.jpg' },
			{ name: 'User 7', image: 'user7.jpg' }
		];

		// Select the container for seen users
		let seenUsersContainer = document.querySelector('.seen-users');
		let userIconContainer = seenUsersContainer.querySelector('.user-icon-container');

		// Loop through the users and create user icons with titles
		seenUsers.forEach((user, index) => {
			if (index < 5) { // Show only up to 5 user icons
				let userIcon = document.createElement('div');
				userIcon.classList.add('user-icon');
				userIcon.style.backgroundImage = `url('${user.image}')`; // Set user image
				let userTitle = document.createElement('span');
				userTitle.classList.add('user-title');
				userTitle.textContent = user.name; // Set user name as title
				userIcon.appendChild(userTitle);
				userIconContainer.appendChild(userIcon);
			}
		});

		// Check if there are more than 5 users
		if (seenUsers.length > 5) {
			seenUsersContainer.classList.add('more-than-5'); // Show the "+5" icon
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