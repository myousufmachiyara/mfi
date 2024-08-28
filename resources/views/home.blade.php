@extends('layouts.header')
	<body>
		<section class="body">
			@extends('layouts.menu')

			<div class="inner-wrapper" style="padding-top: 110px;">
				<aside id="sidebar-left" class="sidebar-left" >

					<div class="sidebar-header">
						<div class="sidebar-title">
							<strong>MENU</strong>
						</div>
						<div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
							<i class="fas fa-bars" aria-label="Toggle sidebar"></i>
						</div>
					</div>

					<div class="nano">
						<div class="nano-content">
							@include('layouts.leftmenu')
						</div>

						<script>
							// Maintain Scroll Position
							if (typeof localStorage !== 'undefined') {
								if (localStorage.getItem('sidebar-left-position') !== null) {
									var initialPosition = localStorage.getItem('sidebar-left-position'),
										sidebarLeft = document.querySelector('#sidebar-left .nano-content');

									sidebarLeft.scrollTop = initialPosition;
								}
							}
						</script>

					</div>
				</aside>

				<section role="main" class="content-body" >

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
					
					<!-- end: page -->
				</section>
			</div>
		</section>
        @extends('layouts.footerlinks')
	</body>
</html>