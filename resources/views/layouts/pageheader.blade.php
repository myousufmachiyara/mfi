<div class="loader">
	<div></div>
</div>
<header class="header header-nav-menu header-nav-top-line ">
	
	<div class="logo-container">
		<a href="/home" class="logo" style="float:left !important">						
			<img src="/assets/img/logo.png" width="55" height="35" alt="MFI Logo" />
		</a>	
		<div id="userbox" class="userbox" style="float:right !important;margin:16px 10px 0 0px">
			<a href="#" data-bs-toggle="dropdown">
				<div class="profile-info">
					<span class="name">{{session('user_name')}}</span>
					<span class="role">{{session('role_name')}}</span>
				</div>

				<i class="fa custom-caret"></i>
			</a>

			<div class="dropdown-menu" >
				<ul class="list-unstyled">
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