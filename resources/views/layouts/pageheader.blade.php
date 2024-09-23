<div class="loader">
	<div></div>
</div>
<header class="header header-nav-menu header-nav-top-line ">
	<div class="logo-container">
		<a href="/home" class="logo">						
			<img src="/assets/img/logo.png" width="55" height="35" alt="MFI Logo" />
		</a>					
		<div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
			<i class="fas fa-user d-lg-none" aria-label="Toggle sidebar" style="line-height:2.3"></i>		
			<span class="separator"></span>					
			<i class="fas fa-bars" aria-label="Toggle sidebar" style="line-height:2.3"></i>		

		</div>
	</div>

	<div class="header-right d-none d-lg-block">	
		<span class="separator"></span>
		<div id="userbox" class="userbox">
			<a href="#" data-bs-toggle="dropdown">
				<div class="profile-info">
					<span class="name">{{session('user_name')}}</span>
					<span class="role">{{session('role_name')}}</span>
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
						<a role="menuitem" tabindex="-1" href="#"><i class="bx bx-user"></i> Profile</a>
					</li>
					<li>
						<a role="menuitem" tabindex="-1" href="#"><i class="bx bx-lock"></i> Reset Password</a>
					</li>
					<li>
						<a role="menuitem" tabindex="-1" href="/logout"><i class="bx bx-power-off"></i> Logout</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	
</header>