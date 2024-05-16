<header class="header header-nav-menu header-nav-top-line ">
	<div class="logo-container">
		<a href="/" class="logo">
			<img src="/assets/img/logo.png" width="55" height="35" alt="MFI Logo" />
		</a>
		<button class="btn header-btn-collapse-nav d-lg-none" data-bs-toggle="collapse" data-bs-target=".header-nav">
			<i class="fas fa-bars"></i>
		</button>

		<!-- start: header nav menu -->
		<div class="header-nav collapse">
			<div class="header-nav-main header-nav-main-effect-1 header-nav-main-sub-effect-1 header-nav-main-square">
				<nav>
					<ul class="nav nav-pills" id="mainNav">
						<li class="active">
							<a class="nav-link" href="layouts-default.html">
								Home
							</a>    
						</li>
						<li class="dropdown">
							<a class="nav-link dropdown-toggle" href="#">
								Sale
							</a>
							<ul class="dropdown-menu">
								<li>
									<a  class="dropdown-item" href="{{ route('all-saleinvoices')}}">
										All Invoice
									</a>
								</li>
								<li>
									<a class="nav-link" href="/sales/new-invoice">
										New Invoice
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
									<a class="nav-link" href="{{ route('create-item')}}">
										New Item
									</a>
								</li>	
								<li>
									<a class="nav-link" href="{{ route('all-items')}}">
										All Items
									</a>
								</li>
								<li>
									<a class="nav-link" href="{{ route('create-item-2')}}">
										New Item Pipes
									</a>
								</li>	
								<li>
									<a class="nav-link" href="{{ route('all-items-2')}}">
										All Item Pipes
									</a>
								</li>
							</ul>
						</li>

					</ul>	
				</nav>
			</div>
		</div>

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