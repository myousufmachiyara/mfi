<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<meta name="keywords" content="HTML5 Admin Template" />
		<meta name="description" content="Porto Admin - Responsive HTML5 Template">
		<meta name="author" content="okler.net">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="/assets/vendor/animate/animate.compat.css">
		<link rel="stylesheet" href="/assets/vendor/font-awesome/css/all.min.css" />
		<link rel="stylesheet" href="/assets/vendor/boxicons/css/boxicons.min.css" />
		<link rel="stylesheet" href="/assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="/assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />
		<link rel="stylesheet" href="/assets/vendor/owl.carousel/assets/owl.carousel.css" />
		<link rel="stylesheet" href="/assets/vendor/owl.carousel/assets/owl.theme.default.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="/assets/css/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="/assets/css/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="/assets/css/custom.css">

		<!-- Head Libs -->
		<script src="/assets/vendor/modernizr/modernizr.js"></script>

	</head>
	<body style="background:#fff;">
		<!-- start: page -->
		<div class="row">
			<div class="container">
				<div class="col-12 col-md-6">
					<section class="body-sign" style="max-width:400px !important;">
						<div class="center-sign ">
							<div class="text-center">
								<a href="/" class="logo">
									<img src="/assets/img/logo.png" height="60" alt="MFI Logo" />
								</a>
							</div>
							
							<div class="panel card-sign" style="padding-top:1.5rem">
								<h2 class="mb-0 text-primary">Welcome Back</h2>
								<p class="text-dark mb-4">Please enter your username and password to continue</p>
								<div class="card-body" style="padding: 40px 30px 40px !important">
									<form method="post" action="{{ route('userlogin') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';" id="addForm">
										@csrf							
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-text bg-light text-primary" style="border-top-left-radius:15px;border-bottom-left-radius:15px;" >
													<i class="bx bx-user text-4"></i>
												</span>
												<input class="form-control" name="username" placeholder="username" type="text" style="border-top-right-radius:15px;border-bottom-right-radius:15px;" class="form-control form-control-lg" />
											</div>
										</div>

										<div class="form-group mb-3">
											<div class="clearfix">
												<!-- <a href="pages-recover-password.html" class="float-end">Lost Password?</a> -->
											</div>
											<div class="input-group">
												<span class="input-group-text bg-light text-primary" style="border-top-left-radius:15px;border-bottom-left-radius:15px;">
													<i class="bx bx-lock text-4"></i>
												</span>
												<input name="password" class="form-control" type="password" Placeholder="password" style="border-top-right-radius:15px;border-bottom-right-radius:15px;" class="form-control form-control-lg" />
											</div>
										</div>

										<div class="row">
											<div class="col-sm-12 text-center">
												<button type="submit" class="btn btn-primary mt-2" style="font-size: 0.9rem;padding: 8.52px 18px;border-radius:15px;width:100%">Continue</button>
											</div>
										</div>
									</form>
								</div>
							</div>

							<p class="text-center text-muted mt-3 mb-3">&copy; Copyright 2024. All Rights Reserved.</p>
						</div>
					</section>
				</div>
				<div class="col-6 d-none d-lg-block">
					<div class="owl-carousel owl-theme mb-0" data-plugin-carousel data-plugin-options='{ "dots": false, "nav": true, "items": 1 }'>
						<div class="item"><img src="/assets/img/sample-1.jpg" alt=""></div>
						<!-- <div class="item"><img src="/assets/img/sample-2.webp" alt=""></div> -->
					</div>
				</div>
			</div>
		</div>

		<!-- end: page -->

		<!-- Vendor -->
		<script src="/assets/vendor/jquery/jquery.js"></script>
		<script src="/assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="/assets/vendor/popper/umd/popper.min.js"></script>
		<script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
		<script src="/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="/assets/vendor/common/common.js"></script>
		<script src="/assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="/assets/vendor/magnific-popup/jquery.magnific-popup.js"></script>
		<script src="/assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
		<script src="/assets/vendor/owl.carousel/owl.carousel.js"></script>

		<!-- Specific Page Vendor -->

		<!-- Theme Base, Components and Settings -->
		<script src="/assets/js/theme.js"></script>

		<!-- Theme Custom -->
		<script src="/assets/js/custom.js"></script>

		<!-- Theme Initialization Files -->
		<script src="/assets/js/theme.init.js"></script>

	</body>
</html>