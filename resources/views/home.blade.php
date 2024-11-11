@include('layouts.header')
	<body>
		<section class="body">
			<div class="inner-wrapper">
				@include('layouts.leftmenu')
				<section role="main" class="content-body">
					@include('layouts.homepageheader')
					<!-- start: page -->
					<div class="row" style="padding: 80px 0px 0px 0px;">
						<!-- summaries -->
						<div class="col-12 col-md-3 mb-2">
							<section class="card card-featured-left card-featured-primary mb-2">
								<div class="card-body">
									<div class="widget-summary">
										<div class="widget-summary-col widget-summary-col-icon">
											<div class="summary-icon bg-primary">
												<i class="fas fa-life-ring"></i>
											</div>
										</div>
										<div class="widget-summary-col">
											<div class="summary">
												<h4 class="title">Pending Complains</h4>
												<div class="info">
													<strong class="amount">1281</strong>
													<span class="text-primary">(14 unread)</span>
												</div>
											</div>
											<div class="summary-footer">
												<a class="text-muted text-uppercase" href="#">(view all)</a>
											</div>
										</div>
									</div>
								</div>
							</section>
						</div>
						<div class="col-12 col-md-3 mb-2">
							<section class="card card-featured-left card-featured-secondary mb-2">
								<div class="card-body">
									<div class="widget-summary">
										<div class="widget-summary-col widget-summary-col-icon">
											<div class="summary-icon bg-secondary">
												<i class="fas fa-dollar-sign"></i>
											</div>
										</div>
										<div class="widget-summary-col">
											<div class="summary">
												<h4 class="title">Total Payables</h4>
												<div class="info">
													<strong class="amount">$ 14,890.30</strong>
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
												<i class="fas fa-shopping-cart"></i>
											</div>
										</div>
										<div class="widget-summary-col">
											<div class="summary">
												<h4 class="title">Total Receivables</h4>
												<div class="info">
													<strong class="amount">38</strong>
												</div>
											</div>
											<div class="summary-footer">
												<a class="text-muted text-uppercase" href="#">(statement)</a>
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
													<strong class="amount">3765</strong>
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
												<h4 class="title">Today's Sales</h4>
												<div class="info">
													<strong class="amount">3765</strong>
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
												<h4 class="title">Today's Purchases</h4>
												<div class="info">
													<strong class="amount">3765</strong>
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
										<div class="col-12 col-md-6">
											<section class="card">
												<header class="card-header">
													<div class="card-actions">
														<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
														<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
													</div>

													<h2 class="card-title">Basic Chart</h2>
												</header>
												<div class="card-body">

												</div>
											</section>
										</div>
										<div class="col-12 col-md-6">
											<section class="card">
												<header class="card-header">
													<div class="card-actions">
														<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
														<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
													</div>

													<h2 class="card-title">Real-Time Chart</h2>
												</header>
												<div class="card-body">

												</div>
											</section>
										</div>
										<div class="col-12 col-md-6 mt-2 mb-3">
											<section class="card">
												<div class="card-body">
													<table class="table table-responsive-md table-striped mb-0">
														<thead>
															<tr>
																<th>#</th>
																<th>Project</th>
																<th>Status</th>
																<th>Progress</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>1</td>
																<td>Porto - Responsive HTML5 Template</td>
																<td><span class="badge badge-success">Success</span></td>
																<td>
																	<div class="progress progress-sm progress-half-rounded m-0 mt-1 light">
																		<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
																			100%
																		</div>
																	</div>
																</td>
															</tr>
															<tr>
																<td>2</td>
																<td>Porto - Responsive Drupal 7 Theme</td>
																<td><span class="badge badge-success">Success</span></td>
																<td>
																	<div class="progress progress-sm progress-half-rounded m-0 mt-1 light">
																		<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
																			100%
																		</div>
																	</div>
																</td>
															</tr>
															<tr>
																<td>3</td>
																<td>Tucson - Responsive HTML5 Template</td>
																<td><span class="badge badge-warning">Warning</span></td>
																<td>
																	<div class="progress progress-sm progress-half-rounded m-0 mt-1 light">
																		<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
																			60%
																		</div>
																	</div>
																</td>
															</tr>
															<tr>
																<td>4</td>
																<td>Tucson - Responsive Business WordPress Theme</td>
																<td><span class="badge badge-success">Success</span></td>
																<td>
																	<div class="progress progress-sm progress-half-rounded m-0 mt-1 light">
																		<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 90%;">
																			90%
																		</div>
																	</div>
																</td>
															</tr>
															<tr>
																<td>5</td>
																<td>Porto - Responsive Admin HTML5 Template</td>
																<td><span class="badge badge-warning">Warning</span></td>
																<td>
																	<div class="progress progress-sm progress-half-rounded m-0 mt-1 light">
																		<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 45%;">
																			45%
																		</div>
																	</div>
																</td>
															</tr>
															<tr>
																<td>6</td>
																<td>Porto - Responsive HTML5 Template</td>
																<td><span class="badge badge-danger">Danger</span></td>
																<td>
																	<div class="progress progress-sm progress-half-rounded m-0 mt-1 light">
																		<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">
																			40%
																		</div>
																	</div>
																</td>
															</tr>
															<tr>
																<td>7</td>
																<td>Porto - Responsive Drupal 7 Theme</td>
																<td><span class="badge badge-success">Success</span></td>
																<td>
																	<div class="progress progress-sm progress-half-rounded mt-1 light">
																		<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 95%;">
																			95%
																		</div>
																	</div>
																</td>
															</tr>
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
</html>