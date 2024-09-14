@include('../layouts.header')
	<body>
		<section class="body">
			@include('../layouts.menu')
			<div class="inner-wrapper">
				<section role="main" class="content-body">
					@include('../layouts.pageheader')
                    <div class="row">
                        <div class="col">
                            <section class="card">
                                <header class="card-header">
                                    <h2 class="card-title mb-2">All Roles</h2>
                                    <div class="card-actions">
                                        <button type="button" class="btn btn-primary"> <i class="fas fa-plus">  </i>  New Role</button>
                                    </div>
                                </header>
                                <div class="card-body">
                                	<table class="table table-bordered table-striped mb-0" id="datatable-default">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Code</th>
                                                <th>Total Users</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="actions">
                                                    <a class="mb-1 mt-1 me-1"><i class="fas fa-pencil-alt"></i></a>
                                                </td>
                                            </tr>
                                        </tbody>
									</table>
                                </div>
                            </section>
                        </div>
                    </div>
                </section>		
			</div>
		</section>

        @include('../layouts.footerlinks')
	</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
