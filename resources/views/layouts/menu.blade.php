<header class="header header-nav-menu header-nav-top-line ">
	<div class="logo-container">
		<a href="/" class="logo">
			<img src="/assets/img/logo.png" width="55" height="35" alt="MFI Logo" />
		</a>
		<button class="btn header-btn-collapse-nav d-lg-none" data-bs-toggle="collapse" data-bs-target=".header-nav">
			<i class="fas fa-bars"></i>
		</button>

		<!-- start: header nav menu -->
		<!-- <div class="header-nav collapse d-none">
			<div class="header-nav-main header-nav-main-effect-1 header-nav-main-sub-effect-1 header-nav-main-square">
				<nav>
					<ul class="nav nav-pills" id="mainNav">
						<li class="active">
							<a class="nav-link" href="/">
								Home
							</a>    
						</li>
						<li class="dropdown">
							<a class="nav-link dropdown-toggle" href="#">
								Users
							</a>
							<ul class="dropdown-menu">
								<li>
									<a class="nav-link">
										User Accounts
									</a>
								</li>	
								<li>
									<a class="nav-link">
										User Roles
									</a>
								</li>							
							</ul>
						</li>
						<li class="dropdown">
							<a class="nav-link dropdown-toggle" href="#">
								Items
							</a>
							<ul class="dropdown-menu">
								<li>
									<a class="nav-link" href="{{ route('all-item-groups')}}">
										Item Groups
									</a>
								</li>	
								<li>
									<a class="nav-link" href="{{ route('all-items')}}">
										Items
									</a>
								</li>
								<li>
									<a class="nav-link" href="{{ route('all-items-2')}}">
										Item Pipes
									</a>
								</li>
							</ul>
						</li>
						<li class="dropdown">
							<a class="nav-link dropdown-toggle" href="#">
								Accounts
							</a>
							<ul class="dropdown-menu">
								<li>
									<a class="nav-link" href="{{ route('all-acc')}}">
										Chart Of Accounts
									</a>
								</li>	
								<li>
									<a class="nav-link" href="{{ route('all-acc-groups')}}">
										COA Groups
									</a>
								</li>	
								<li>
									<a class="nav-link" href="{{ route('all-acc-sub-heads-groups')}}">
										COA Sub Heads
									</a>
								</li>
							</ul>
						</li>
						<li class="dropdown">
							<a class="nav-link dropdown-toggle" href="#">
								Vouchers
							</a>
							<ul class="dropdown-menu">
								<li>
									<a class="nav-link" href="{{ route('all-jv1')}}">
										Journal Voucher 1
									</a>
								</li>	
								<li>
									<a class="nav-link" href="{{ route('all-jv2')}}">
										Journal Voucher 2
									</a>
								</li>	
								<li>
									<a class="nav-link" href="#">
										Receipts
									</a>
								</li>
								<li>
									<a class="nav-link" href="#">
										Payments
									</a>
								</li>
							</ul>
						</li>
						<li class="dropdown">
							<a class="nav-link dropdown-toggle" href="#">
								Purchase
							</a>
							<ul class="dropdown-menu">
								<li>
									<a class="nav-link" href="{{ route('all-purchases1')}}">
										Purchase 1
									</a>
								</li>
								<li>
									<a class="dropdown-item" href="{{ route('all-purchases2')}}" >
										Purchase 2
									</a>
								</li>
								<li>
									<a class="nav-link" href="/sales/new-invoice">
										Purchase Return
									</a>
								</li>								
							</ul>
						</li>
						<li class="dropdown">
							<a class="nav-link dropdown-toggle" href="#">
								Sale
							</a>
							<ul class="dropdown-menu">
								<li>
									<a class="nav-link" href="{{ route('all-saleinvoices')}}">
										Sale 1
									</a>
								</li>
								<li>
									<a class="dropdown-item" href="{{ route('all-sale2invoices')}}">
										Sale 2
									</a>
								</li>
								<li>
									<a class="nav-link" href="#">
										Sale Return
									</a>
								</li>								
							</ul>
						</li>
						<li class="dropdown">
							<a class="nav-link dropdown-toggle" href="#">
								Stock Pipe
							</a>
							<ul class="dropdown-menu">
								<li>
									<a class="nav-link" href="{{ route('all-tstock-in')}}">
										Stock In
									</a>
								</li>
								<li>
									<a class="dropdown-item" href="{{ route('all-tstock-out')}}">
										Stock Out
									</a>
								</li>
								<li>
									<a class="nav-link" href="{{ route('all-tbad-dabs')}}">
										Stock Balance
									</a>
								</li>								
							</ul>
						</li>
						<li class="dropdown">
							<a class="nav-link dropdown-toggle" href="#">
								Stock Doors
							</a>
							<ul class="dropdown-menu">
								<li>
									<a class="nav-link" href="{{ route('all-stock-in')}}">
										Stock In
									</a>
								</li>
								<li>
									<a class="dropdown-item" href="{{ route('all-stock-out')}}">
										Stock Out
									</a>
								</li>
								<li>
									<a class="nav-link" href="{{ route('all-bad-dabs')}}">
										Stock Balance
									</a>
								</li>								
							</ul>
						</li>

						<li class="dropdown">
							<a class="nav-link dropdown-toggle" href="#">
								Reports
							</a>
							<ul class="dropdown-menu">
								<li>
									<a class="nav-link">
										Main Option
									</a>
								</li>	
								<li>
									<a class="nav-link">
										Main Option
									</a>
								</li>							
							</ul>
						</li>
						<li class="dropdown">
							<a class="nav-link dropdown-toggle" href="#">
								Others
							</a>
							<ul class="dropdown-menu">
								<li>
									<a class="nav-link">
										Stock In/Out
									</a>
								</li>	
								<li>
									<a class="nav-link">
										Complains
									</a>
								</li>							
							</ul>
						</li>
					</ul>	
				</nav>
			</div>
		</div> -->

		<!-- end: header nav menu -->
	</div>

	<!-- start: search & user box -->
	<div class="header-right">	
		<span class="separator"></span>

		<div id="userbox" class="userbox">
			<a href="#" data-bs-toggle="dropdown">
				<figure class="profile-picture">
					<img src="/assets/img/!logged-user.jpg" alt="Joseph Doe" class="rounded-circle" data-lock-picture="/assets/img/!logged-user.jpg" />
				</figure>
				<div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@okler.com">
					<span class="name">User Name</span>
					<span class="role">admin</span>
				</div>

				<i class="fa custom-caret"></i>
			</a>

			<div class="dropdown-menu">
				<ul class="list-unstyled">
					<li class="divider"></li>
					<!-- <li>
						<a role="menuitem" tabindex="-1" href="#" data-lock-screen="true"><i class="bx bx-lock"></i> Lock Screen</a>
					</li> -->
					<li>
						<a role="menuitem" tabindex="-1" href="pages-signin.html"><i class="bx bx-power-off"></i> Logout</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- end: search & user box -->
</header>