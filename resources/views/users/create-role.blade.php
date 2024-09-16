@include('../layouts.header')
	<body>
		<section class="body">
        @include('../layouts.pageheader')
            <div class="inner-wrapper">
				<section role="main" class="content-body">
					<form method="post" action="{{ route('create-role') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';" id="addForm">
						@csrf
						<div class="row">
							<div class="col-12 mb-3">								
								<section class="card">
									<header class="card-header">
										<h2 class="card-title">New User Role</h2>
									</header>

									<div class="card-body">
										<div class="row form-group mb-2">
											<div class="col-sm-12 col-md-4 mb-2">
												<label class="col-form-label" >Role Id</label>
												<input type="text" placeholder="(New Role)" class="form-control" disabled>
											</div>
											<div class="col-sm-12 col-md-4 mb-2">
												<label class="col-form-label" >Role Name</label>
												<input type="text" name="role_name" class="form-control">
											</div>
											<div class="col-sm-12 col-md-4 mb-2">
												<label class="col-form-label" >Role Shortcode</label>
												<input type="text" placeholder="Shortcode" name="shortcode" class="form-control">
											</div>
									  </div>
									</div>
								</section>
							</div>
							<div class="col-12 mb-3">
								<section class="card">
									<div class="card-body" style="overflow-x:auto;min-height:250px;max-height:450px;overflow-y:auto">
										<table class="table table-bordered table-striped mb-0" id="myTable" >
											<thead>
												<tr>
                                                    <th style="display:none">Module ID</th>
													<th>Module Name</th>
													<th>Create</th>
													<th>Update</th>
													<th>Delete</th>
													<th>View</th>
												</tr>
											</thead>
											<tbody id="UserRoleTable">
                                                @foreach ($modules as $key => $row)
                                                    <tr>
                                                        <td style="display:none">{{$row->id}}</th>
                                                        <td>{{$row->name}}</td>	
                                                        <td><input type="checkbox" class="" name="{{$row->slug}}[]"></td>
                                                        <td><input type="checkbox" class="" name="{{$row->slug}}[]"></td>
                                                        <td><input type="checkbox" class="" name="{{$row->slug}}[]"></td>
                                                        <td><input type="checkbox" class="" name="{{$row->slug}}[]"></td>
                                                    </tr>
                                                @endforeach
											</tbody>
										</table>
									</div>

									<footer class="card-footer">
										<div class="row form-group mb-2">
											<div class="text-end">
												<button type="button" class="btn btn-danger mt-2"  onclick="window.location='{{ route('all-purchases1') }}'"> <i class="fas fa-trash"></i> Discard</button>
												<button type="submit" class="btn btn-primary mt-2"> <i class="fas fa-save"></i> Add Role</button>
											</div>
										</div>
									</footer>
								</section>
							</div>
						</div>
					</form>
				</section>
			</div>
		</section>
        @include('../layouts.footerlinks')
	</body>
</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
	
	$(document).ready(function() {
		$(window).keydown(function(event){
			if(event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		});
	});

</script>