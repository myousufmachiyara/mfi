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
                                    <h2 class="card-title mb-2">All Users</h2>
                                    <div class="card-actions">
                                        <button type="button" class="modal-with-form btn btn-primary" href="#addModal"> <i class="fas fa-plus">  </i>  New User</button>
                                        <!-- <button type="button" class="btn btn-danger" onclick="printReport()"> <i class="fas fa-file-pdf">  </i>  Print Report</button> -->
                                    </div>
                                </header>
                                <div class="card-body">
                                	<table class="table table-bordered table-striped mb-0" id="datatable-default">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Image</th>
                                                <th>Employee Name</th>
                                                <th>Username</th>
                                                <th>Designation</th>
                                                <th>CNIC</th>
                                                <th>Address</th>
                                                <th>Phone No.</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="actions">
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal text-primary" onclick="getAccountDetails(1)" href="#userRolesModal"><i class="fa fa-user-lock"></i></a>
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="getAccountDetails(1)" href="#updateModal"><i class="fas fa-pencil-alt"></i></a>
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal text-danger" onclick="setId(1)" href="#deleteModal"><i class="fa fa-times"></i></a>
                                                        <a href="{{ route('activate-acc',1)}}"><i style="color:green" class="fas fa-check"></i></a>
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

        <div id="deleteModal" class="zoom-anim-dialog modal-block modal-block-danger mfp-hide">
            <form method="post" action="{{ route('delete-acc') }}" enctype="multipart/form-data">
                @csrf
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">Deactivate User</h2>
                    </header>
                    <div class="card-body">
                        <div class="modal-wrapper">
                            <div class="modal-icon">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <div class="modal-text">
                                <p class="mb-0">Are you sure that you want to deactivate this user?</p>
                                <input name="acc_id" id="deleteID" hidden>
                            </div>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-danger">Deactivate</button>
                                <button class="btn btn-default modal-dismiss">Cancel</button>
                            </div>
                        </div>
                    </footer>
                </section>
            </form>
        </div>

        <div id="attModal" class="zoom-anim-dialog modal-block modal-block-danger mfp-hide">
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">All Attachements</h2>
                    </header>
                    <div class="card-body">
                        <div class="modal-wrapper">

                            <table class="table table-bordered table-striped mb-0" id="datatable-default">
                                <thead>
                                    <tr>
                                        <th>Attachement Path</th>
                                        <th>Download</th>
                                        <th>View</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody id="acc_attachements">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <!-- <form method="post" action="{{ route('coa-att-download-all') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
                                    @csrf   -->
                                    <input type="hidden" id="download_id" name="download_id">                              
                                    <!-- <button type="button" class="btn btn-danger">Delete All</button> -->
                                    <button class="btn btn-default modal-dismiss">Cancel</button>
                                <!-- </form> -->
                            </div>
                        </div>
                    </footer>
                </section>
        </div>

        <div id="printModal" class="zoom-anim-dialog modal-block modal-block-danger mfp-hide">
            <form method="post" action="{{ route('print-acc') }}" enctype="multipart/form-data">
                @csrf
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">Print COA Report Account</h2>
                    </header>
                    <div class="card-body">
                        <div class="row form-group">
                            <div class="col-lg-6">
                                <label>From Date:</label>
                                <input type="date" class="form-control" name="print_from_date" value="<?php echo date('Y-m-d',strtotime("yesterday")); ?>">
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>To Date:</label>
                                <input type="date" class="form-control" name="print_to_date" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-danger">Print</button>
                                <button class="btn btn-default modal-dismiss">Cancel</button>
                            </div>
                        </div>
                    </footer>
                </section>
            </form>
        </div>

        <div id="addModal" class="modal-block modal-block-primary mfp-hide">
            <section class="card">
                <form method="post" id="addForm" action="{{ route('store-acc') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
                    @csrf
                    <header class="card-header">
                        <h2 class="card-title">Add New User</h2>
                    </header>
                    <div class="card-body">
                        <div class="row form-group">
                            <div class="col-lg-6">
                                <label>User ID</label>
                                <input type="number" class="form-control" placeholder="User ID" name="ac_cod" required disabled>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Picture</label>
                                <input type="file" class="form-control" name="att[]" multiple accept="image/png, image/jpeg">
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Employee Name</label>
                                <input type="text" class="form-control" placeholder="Account Name"  name="ac_name" required>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>CNIC No.</label>
                                <input type="text" class="form-control" placeholder="Account Name"  name="ac_name" required>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Phone No.</label>
                                <input type="text" class="form-control"  placeholder="Phone No." name="phone_no" >
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Email Address</label>
                                <input type="email" class="form-control" placeholder="Account Name" >
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>CNIC No.</label>
                                <input type="text" class="form-control" placeholder="Account Name"  required>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Address</label>
                                <textarea type="text" class="form-control" rows="2" placeholder="Address" name="address"></textarea>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Designation</label>
                                <select data-plugin-selectTwo class="form-control" autofocus>
                                    <option value="">Select Designation</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Date</label>
                                <input type="date" class="form-control" placeholder="Date" value="<?php echo date('Y-m-d'); ?>">
                            </div> 
                            <div class="col-lg-6 mb-2">
                                <label>Username</label>
                                <input type="text" class="form-control" placeholder="@username">
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Password</label>
                                <input type="text" class="form-control" placeholder="password">
                            </div>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">Add User</button>
                                <button class="btn btn-default modal-dismiss">Cancel</button>
                            </div>
                        </div>
                    </footer>
                </form>
            </section>
        </div>

        <div id="updateModal" class="modal-block modal-block-primary mfp-hide">
            <section class="card">
                <form method="post" id="updateForm" action="{{ route('update-acc') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
                    @csrf
                    <header class="card-header">
                        <h2 class="card-title">Update Account</h2>
                    </header>
                    <div class="card-body">
                        <div class="row form-group">
                            <div class="col-lg-6">
                                <label>Account Code</label>
                                <input type="number" class="form-control" placeholder="Account Code" id="ac_id" required disabled>
                                <input type="number" class="form-control"  name="ac_cod" id="update_ac_id" required hidden>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Account Name</label>
                                <input type="text" class="form-control" placeholder="Account Name"  name="ac_name" id="update_ac_name" required>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Receivables</label>
                                <input type="number" class="form-control" placeholder="Receivables" value="0" name="rec_able" id="update_rec_able" step=".00001">
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Payables</label>
                                <input type="number" class="form-control" placeholder="Payables" value="0" name="pay_able" id="update_pay_able" step=".00001">
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Date</label>
                                <input type="date" class="form-control" placeholder="Date" name="opp_date" id="update_opp_date">
                            </div>  
                            <div class="col-lg-6 mb-2">
                                <label>Remarks</label>
                                <input type="text" class="form-control"  placeholder="Remarks" name="remarks" id="update_remarks">
                            </div>
                            <div class="col-lg-12 mb-2">
                                <label>Address</label>
                                <textarea type="text" class="form-control" rows="2" placeholder="Address" name="address" id="update_address"></textarea>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Phone No.</label>
                                <input type="text" class="form-control"  placeholder="Phone No." name="phone_no" id="update_phone_no">
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Account Group</label>
                                <select class="form-control" autofocus name="group_cod" id="update_group_cod">
                                    <option value="">Select Group</option>
                                </select>
                                <a href="{{ route('all-acc-groups') }}">Add New A.Group</a>
                            </div>

                            <div class="col-lg-6 mb-2">
                                <label>Account Type</label>
                                <select class="form-control" autofocus name="AccountType" required id="update_AccountType">
                                    <option disabled selected>Select Account Type</option>
                                </select>
                                <a href="{{ route('all-acc-sub-heads-groups') }}">Add New A.Type</a>
                            </div>

                            <div class="col-lg-6 mb-2">
                                <label>Attachements</label>
                                <input type="file" class="form-control" name="att[]" id="update_att" multiple accept=".zip, appliation/zip, application/pdf, image/png, image/jpeg">
                            </div>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">Update Account</button>
                                <button class="btn btn-default modal-dismiss">Cancel</button>
                            </div>
                        </div>
                    </footer>
                </form>
            </section>
        </div>

        <div id="userRolesModal" class="modal-block modal-block-primary mfp-hide">
            <section class="card">
                <form method="post" id="updateForm" action="{{ route('update-acc') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
                    @csrf
                    <header class="card-header">
                        <h2 class="card-title">Update Account</h2>
                    </header>
                    <div class="card-body">
                        <div class="row form-group">
                            <div class="col-lg-6">
                                <label>Account Code</label>
                                <input type="number" class="form-control" placeholder="Account Code" id="ac_id" required disabled>
                                <input type="number" class="form-control"  name="ac_cod" id="update_ac_id" required hidden>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Account Name</label>
                                <input type="text" class="form-control" placeholder="Account Name"  name="ac_name" id="update_ac_name" required>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Receivables</label>
                                <input type="number" class="form-control" placeholder="Receivables" value="0" name="rec_able" id="update_rec_able" step=".00001">
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Payables</label>
                                <input type="number" class="form-control" placeholder="Payables" value="0" name="pay_able" id="update_pay_able" step=".00001">
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Date</label>
                                <input type="date" class="form-control" placeholder="Date" name="opp_date" id="update_opp_date">
                            </div>  
                            <div class="col-lg-6 mb-2">
                                <label>Remarks</label>
                                <input type="text" class="form-control"  placeholder="Remarks" name="remarks" id="update_remarks">
                            </div>
                            <div class="col-lg-12 mb-2">
                                <label>Address</label>
                                <textarea type="text" class="form-control" rows="2" placeholder="Address" name="address" id="update_address"></textarea>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Phone No.</label>
                                <input type="text" class="form-control"  placeholder="Phone No." name="phone_no" id="update_phone_no">
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Account Group</label>
                                <select class="form-control" autofocus name="group_cod" id="update_group_cod">
                                    <option value="">Select Group</option>
                                </select>
                                <a href="{{ route('all-acc-groups') }}">Add New A.Group</a>
                            </div>

                            <div class="col-lg-6 mb-2">
                                <label>Account Type</label>
                                <select class="form-control" autofocus name="AccountType" required id="update_AccountType">
                                    <option disabled selected>Select Account Type</option>
                                </select>
                                <a href="{{ route('all-acc-sub-heads-groups') }}">Add New A.Type</a>
                            </div>

                            <div class="col-lg-6 mb-2">
                                <label>Attachements</label>
                                <input type="file" class="form-control" name="att[]" id="update_att" multiple accept=".zip, appliation/zip, application/pdf, image/png, image/jpeg">
                            </div>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">Update Account</button>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    
    $(document).ready(function(){
    
        $('#addForm').on('submit', function(e){
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: '/coa/acc/validate',
                data: formData,
                success: function(response){
                    var form = document.getElementById('addForm');
                    form.submit();
                },
                error: function(response){
                    var errors = response.responseJSON.errors;
                    var errorMessage = 'Account Already Exists';

                    alert(errorMessage);
                }
            });
        });

    });

    function setId(id){
        $('#deleteID').val(id);
    }

    function getAccountDetails(id){
        $.ajax({
            type: "GET",
            url: "/coa/acc/detail",
            data: {id:id},
            success: function(result){
                $('#ac_id').val(result['ac_code']);
                $('#update_ac_id').val(result['ac_code']);
                $('#update_ac_name').val(result['ac_name']);
                $('#update_rec_able').val(result['rec_able']);
                $('#update_pay_able').val(result['pay_able']);
                $('#update_opp_date').val(result['opp_date']);
                $('#update_remarks').val(result['remarks']);
                $('#update_address').val(result['address']);
                $('#update_phone_no').val(result['phone_no']);
                $('#update_group_cod').val(result['group_cod']);
                $('#update_AccountType').val(result['AccountType']);
                $('#update_att').val(result['att']);
            },
            error: function(){
                alert("error");
            }
        });
	}

    function getAttachements(id){

        var table = document.getElementById('acc_attachements');
        while (table.rows.length > 0) {
            table.deleteRow(0);
        }

        $.ajax({
            type: "GET",
            url: "/coa/acc/attachements",
            data: {id:id},
            success: function(result){
                $.each(result, function(k,v){
                    var html="<tr>";
                    html+= "<td>"+v['att_path']+"</td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 me-1 text-danger' href='/coa/acc/download/"+v['att_id']+"'><i class='fas fa-download'></i></a></td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 me-1 text-primary' href='/coa/acc/view/"+v['att_id']+"' target='_blank'><i class='fas fa-eye'></i></a></td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 me-1 text-primary' href='#' onclick='deleteFile("+v['att_id']+")'><i class='fas fa-trash'></i></a></td>"
                    html+="</tr>";
                    $('#acc_attachements').append(html);
                });
                $('#download_id').val(result[0]['ac_code']);
            },
            error: function(){
                alert("error");
            }
        });
	}

    function printReport(){
        window.location.href = "{{ route('print-acc')}}";
    }

    function deleteFile(fileId) {
        if (!confirm('Are you sure you want to delete this file?')) {
            return;
        }

        fetch('/coa/acc/deleteAtt/' + fileId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                alert('File deleted successfully.');
                // Optionally, remove the element or reload the page
                location.reload();
            } else {
                return response.json().then(data => {
                    throw new Error(data.message || 'An error occurred.');
                });
            }
        })
        .catch(error => {
            alert(error.message);
        });
    }

    
</script>