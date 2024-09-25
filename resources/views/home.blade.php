@include('layouts.header')
	<body>
		<section class="body">
			@include('layouts.homepageheader')
			<div class="inner-wrapper" style="padding-top: 50px;">
				@include('layouts.leftmenu')
				<section role="main" class="content-body" style="margin-left: 270px;margin-right: 20px;">
					<!-- <header class="page-header" >
						<div class="right-wrapper text-end">
							<ol class="breadcrumbs">
								<li>
									<a href="/">
										<i class="bx bx-home-alt"></i>
									</a>
								</li>

								<li><span>Software</span></li>

								<li><span>Home</span></li>

							</ol>

							<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fas fa-chevron-left"></i></a>
						</div>
					</header> -->
					<!-- start: page -->
					<!-- <div class="row">
						<div class="col-3 mb-4">
							<section class="card card-featured-left card-featured-primary mb-3">
								<div class="card-body">
									<div class="widget-summary">
										<div class="widget-summary-col widget-summary-col-icon">
											<div class="summary-icon bg-primary">
												<i class="fas fa-life-ring"></i>
											</div>
										</div>
										<div class="widget-summary-col">
											<div class="summary">
												<h4 class="title">Support Questions</h4>
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
						<div class="col-3 mb-4">
							<section class="card card-featured-left card-featured-secondary">
								<div class="card-body">
									<div class="widget-summary">
										<div class="widget-summary-col widget-summary-col-icon">
											<div class="summary-icon bg-secondary">
												<i class="fas fa-dollar-sign"></i>
											</div>
										</div>
										<div class="widget-summary-col">
											<div class="summary">
												<h4 class="title">Total Profit</h4>
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
						<div class="col-3 mb-4">
							<section class="card card-featured-left card-featured-tertiary mb-3">
								<div class="card-body">
									<div class="widget-summary">
										<div class="widget-summary-col widget-summary-col-icon">
											<div class="summary-icon bg-tertiary">
												<i class="fas fa-shopping-cart"></i>
											</div>
										</div>
										<div class="widget-summary-col">
											<div class="summary">
												<h4 class="title">Today's Orders</h4>
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
						<div class="col-3 mb-4">
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
												<h4 class="title">Today's Visitors</h4>
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
						<div class="col-12 mb-5">
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
						<div class="col-6">
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
						<div class="col-6">
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
					</div> -->
					<!-- end: page -->
				</section>
			</div>
		</section>
        @include('layouts.footerlinks')
	</body>
</html>