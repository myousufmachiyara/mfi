@include('../layouts.header')
	<body>
		<section class="body">
            @include('layouts.pageheader')
            <div class="inner-wrapper">
				<section role="main" class="content-body">
                    <div class="row">
                        <div class="col">
                            <section class="card">
                                <header class="card-header">
                                    <div class="card-actions">
                                        <button type="button" class="modal-with-form btn btn-primary" href="#addModal"> <i class="fas fa-plus"></i> New Group</button>
                                    </div>
                                    <h2 class="card-title">COA Groups</h2>
                                </header>
                                <div class="card-body">
                                	<table class="table table-bordered table-striped mb-0" id="datatable-default">
                                        <thead>
                                            <tr>
                                                <th width="5%">Code</th>
                                                <th>Group Name</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($accGroups as $key => $row)
                                                <tr>
                                                    <td>{{$row->group_cod}}</td>
                                                    <td>{{$row->group_name}}</td>
                                                    <td class="actions text-end">
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="getDetails({{$row->group_cod}})" href="#updateModal"><i class="fas fa-pencil-alt"></i></a>
                                                        <span class="separator"> | </span>
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="setId({{$row->group_cod}})" href="#deleteModal"><i class="far fa-trash-alt" style="color:red"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
									</table>
                                </div>
                            </section>
                        </div>
                    </div>
                </section>		
			</div>
		</section>

        <div id="deleteModal" class="zoom-anim-dialog modal-block modal-block-danger mfp-hide">
            <form method="post" action="{{ route('delete-acc-groups') }}" enctype="multipart/form-data">
                @csrf
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">Delete COA Group</h2>
                    </header>
                    <div class="card-body">
                        <div class="modal-wrapper">
                            <div class="modal-icon">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <div class="modal-text">
                                <p class="mb-0">Are you sure that you want to delete this COA Group?</p>
                                <input name="acc_group_cod" id="deleteID" hidden>
                            </div>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-danger">Delete</button>
                                <button class="btn btn-default modal-dismiss">Cancel</button>
                            </div>
                        </div>
                    </footer>
                </section>
            </form>
        </div>

        <div id="addModal" class="modal-block modal-block-primary mfp-hide">
            <section class="card">
                <form method="post" action="{{ route('store-acc-groups') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
                    @csrf
                    <header class="card-header">
                        <h2 class="card-title">Add COA Group</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group mt-2">
                            <input type="number" class="form-control" placeholder="group code" disabled>
                        </div>
                        <div class="form-group mb-3">
                            <label>Account Group Name<span style="color: red;"><strong>*</strong></span></label>
                            <input type="text" class="form-control" placeholder="Name" name="acc_group_name" required>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">Add COA Group</button>
                                <button class="btn btn-default modal-dismiss">Cancel</button>
                            </div>
                        </div>
                    </footer>
                </form>
            </section>
        </div>

        <div id="updateModal" class="modal-block modal-block-primary mfp-hide">
            <section class="card">
                <form method="post" action="{{ route('update-acc-groups') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
                    @csrf
                    <header class="card-header">
                        <h2 class="card-title">Update COA Group</h2>
                    </header>
                    <div class="card-body">
                       <div class="form-group">
                            <input type="number" class="form-control" id="update_group_id" required disabled>
                        </div>
                        <div class="form-group">
                            <label>Account Group Name<span style="color: red;"><strong>*</strong></span></label>
                            <input type="text" class="form-control" id="update_group_name" placeholder="Name" name="group_name" required>
                            <input type="hidden" class="form-control" id="group_id" name="group_cod" required>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">Update COA Group</button>
                                <button class="btn btn-default modal-dismiss">Cancel</button>
                            </div>
                        </div>
                    </footer>
                </form>
            </section>
        </div>

        @include('../layouts.footerlinks')
	</body>
</html>
<script>
    function setId(id){
        $('#deleteID').val(id);
    }

    function getDetails(id){
        $.ajax({
            type: "GET",
            url: "/coa/coa-groups/detail",
            data: {id:id},
            success: function(result){
                console.log(result)
                $('#group_id').val(result['group_cod']);
                $('#update_group_id').val(result['group_cod']);
                $('#update_group_name').val(result['group_name']);
            },
            error: function(){
                alert("error");
            }
        });
	}
</script>