@include('../layouts.header')
	<body>
		<section class="body">
            @include('../layouts.homepageheader')
			<div class="inner-wrapper cust-pad">
                @include('layouts.leftmenu')
				<section role="main" class="content-body">
                    <div class="row">
                        <div class="col">
                            <section class="card">
                                <header class="card-header" style="display: flex;justify-content: space-between;">
                                    <h2 class="card-title">All Roles</h2>
                                    <form class="text-end" action="{{ route('new-role') }}" method="GET">
                                        <button type="submit" class="btn btn-primary mt-2"> <i class="fas fa-plus"></i> New Role</button>
                                    </form>
                                </header>
                                <div class="card-body">
                                    <div>
                                        <div class="col-md-5" style="display:flex;">
                                            <select class="form-control" style="margin-right:10px" id="columnSelect">
                                                <option selected disabled>Search by</option>
                                                <option value="0">by ID</option>
                                                <option value="1">by Name</option>
                                                <option value="2">by Code</option>
                                                <option value="3">by Total Users</option>
                                            </select>
                                            <input type="text" class="form-control" id="columnSearch" placeholder="Search By Column"/>
                                        </div>
                                    </div>
                                    <div class="modal-wrapper" style="overflow-x:auto">
                                        <table class="table table-bordered table-striped mb-0" id="searchableTable">
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
                                                @foreach ($roles as $key => $row)
                                                    <tr>
                                                        <td>{{$row->id}}</td>
                                                        <td>{{$row->name}}</td>
                                                        <td>{{$row->shortcode}}</td>
                                                        <td>{{$row->user_count}}</td>
                                                        <td class="actions">
                                                            <a href="{{ route('edit-role', $row->id) }}" class="mb-1 mt-1 me-1"><i class="fas fa-pencil-alt"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
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
 
