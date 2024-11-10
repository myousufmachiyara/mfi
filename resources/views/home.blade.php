@include('layouts.header')
	<body>
		<div id="loader">
			<div class="spinner-border" role="status">
				<span class="sr-only">Loading...</span>
			</div>
		</div>
		<section class="body">
			<div class="inner-wrapper">
				@include('layouts.leftmenu')
				<section role="main" class="content-body">
					<header class="page-header" >
						<div class="right-wrapper text-end">
							<div id="userbox" class="userbox" style="float:right !important;">
								<a href="#" data-bs-toggle="dropdown">
									<!-- <i style="font-size:1.3rem;border: 1px solid #000;border-radius: 25px;padding: 5px;color:#000" class="fa fa-user"></i> -->

									<div class="profile-info"> 
										<span class="name">{{session('user_name')}}</span>
										<span class="role">{{session('role_name')}}</span>
									</div>
									<i class="fa custom-caret"></i>
								</a>

								<div class="dropdown-menu" >
									<ul class="list-unstyled">
										<li>
											<a role="menuitem" tabindex="-1" href="#changePassword" class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal"><i class="bx bx-lock"></i> Change Password</a>
										</li>
										<li>
											<a role="menuitem" tabindex="-1" href="/logout"><i class="bx bx-power-off"></i> Logout</a>
										</li>
									</ul>
								</div>
							</div>	
						</div>
						<div id="changePassword" class="zoom-anim-dialog modal-block modal-block-danger mfp-hide">
							<form id="changePasswordForm" method="post" action="{{ route('change-user-password') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
								@csrf
								<header class="card-header">
									<h2 class="card-title">Change Password</h2>
								</header>
								<div class="card-body">
									<div class="row form-group">    
										<div class="col-lg-6 mb-2">
											<label>Current Password</label>
											<input type="password" class="form-control" placeholder="Current Password" id="current_passowrd" name="current_passowrd" required>
										</div> 
										<div class="col-lg-6 mb-2">
											<label>New Password</label>
											<input type="password" class="form-control" placeholder="New Password" name="new_password" required>
										</div>
									</div>
								</div>
								<footer class="card-footer">
									<div class="row">
										<div class="col-md-12 text-end">
											<button type="submit" class="btn btn-primary">Change Password</button>
											<button class="btn btn-default modal-dismiss">Cancel</button>
										</div>
									</div>
								</footer>
							</form>
						</div>
					</header>
					<!-- start: page -->
					<div class="row" style="padding: 80px 0px 50px 0px;margin-left: 10px;margin-right: 10px;">
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
					</div>
					<!-- end: page -->
				</section>
			</div>
		</section>
        @include('layouts.footerlinks')
	</body>
</html>