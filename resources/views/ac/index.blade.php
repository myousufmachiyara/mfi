@extends('../layouts.header')
	<body>
		<section class="body">
			@extends('../layouts.menu')
			<div class="inner-wrapper">
				<section role="main" class="content-body">
					@extends('../layouts.pageheader')
                    <div class="row">
                        <div class="col">
                            <section class="card">
                                <header class="card-header">
                                    <div class="card-actions">
                                        <button type="button" class="modal-with-form btn btn-primary" href="#addModal"> <i class="fas fa-plus">  </i>  New Account</button>
                                        <button type="button" class="modal-with-form btn btn-danger" href="#printModal"> <i class="fas fa-file-pdf">  </i>  Print Report</button>
                                    </div>
                                    <h2 class="card-title">Chart Of Accounts</h2>
                                </header>
                                <div class="card-body">
                                	<table class="table table-bordered table-striped mb-0" id="datatable-default">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Receivable</th>
                                                <th>Payable</th>
                                                <th>Date</th>
                                                <th>Remarks</th>
                                                <th>Address</th>
                                                <th>Phone No.</th>
                                                <th>Group</th>
                                                <th>Account Type</th>
                                                <th>Att.</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($acc as $key => $row)
                                                <tr>
                                                    <td>{{$row->ac_code}}</td>
                                                    <td>{{$row->ac_name}}</td>
                                                    <td>{{$row->rec_able}}</td>
                                                    <td>{{$row->pay_able}}</td>
                                                    <td>{{ \Carbon\Carbon::parse($row->opp_date)->format('d-m-y') }}</td>
                                                    <td>{{$row->remarks}}</td>
                                                    <td>{{$row->address}}</td>
                                                    <td>{{$row->phone_no}}</td>
                                                    <td>{{$row->group_name}}</td>
                                                    <td>{{$row->sub}}</td>
                                                    <td>
                                                        @if($row->att!=null)
                                                            <a class="mb-1 mt-1 me-1 text-danger" href="{{ route('coa-att-download', $row->ac_code ) }}"><i class="fas fa-download"></i></a>
                                                            <a class="mb-1 mt-1 me-1 " href="{{ route('coa-att-view', $row->ac_code ) }}" target="_blank"><i class="fas fa-eye"></i></a>
                                                        @endif
                                                    </td>
                                                    <td class="actions">
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="getAccountDetails({{$row->ac_code}})" href="#updateModal"><i class="fas fa-pencil-alt"></i></a>
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="setId({{$row->ac_code}})" href="#deleteModal"><i class="far fa-trash-alt" style="color:red"></i></a>
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
            <form method="post" action="{{ route('delete-acc') }}" enctype="multipart/form-data">
                @csrf
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">Delete Account</h2>
                    </header>
                    <div class="card-body">
                        <div class="modal-wrapper">
                            <div class="modal-icon">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <div class="modal-text">
                                <p class="mb-0">Are you sure that you want to delete this account?</p>
                                <input name="acc_id" id="deleteID" hidden>
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
                <form method="post" action="{{ route('store-acc') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
                    @csrf
                    <header class="card-header">
                        <h2 class="card-title">Add New Account</h2>
                    </header>
                    <div class="card-body">
                        <div class="row form-group">
                            <div class="col-lg-6">
                                <label>Account Code</label>
                                <input type="number" class="form-control" placeholder="Account Code" name="ac_cod" required disabled>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Account Name</label>
                                <input type="text" class="form-control" placeholder="Account Name"  name="ac_name" required>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Receivables</label>
                                <input type="number" class="form-control" placeholder="Receivables" value="0" name="rec_able" step="0.1">
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Payables</label>
                                <input type="number" class="form-control" placeholder="Payables" value="0" name="pay_able" step="0.1">
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Date</label>
                                <input type="date" class="form-control" placeholder="Date" name="opp_date" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>  
                            <div class="col-lg-6 mb-2">
                                <label>Remarks</label>
                                <input type="text" class="form-control" value=" " placeholder="Remarks" name="remarks" >
                            </div>
                            <div class="col-lg-12 mb-2">
                                <label>Address</label>
                                <textarea type="text" class="form-control" rows="2" placeholder="Address" name="address"></textarea>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Phone No.</label>
                                <input type="text" class="form-control"  placeholder="Phone No." name="phone_no" >
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Account Group</label>
                                <select class="form-control" name ="group_cod">
                                    <option value="">Select Group</option>
                                    @foreach($ac_group as $key => $row)	
                                        <option value="{{$row->group_cod}}">{{$row->group_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6 mb-2">
                                <label>Account Type</label>
                                <select class="form-control" name ="AccountType" required>
                                    <option value="" disabled selected>Select Account Type</option>
                                    @foreach($sub_head_of_acc as $key => $row)	
                                        <option value="{{$row->id}}">{{$row->sub}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6 mb-2">
                                <label>Attachement</label>
                                <input type="file" class="form-control" name="att" accept=".zip, appliation/zip, application/pdf, image/png, image/jpeg">
                            </div>
  
                        </div>
                    </div>
                    <footer class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">Add New Account</button>
                                <button class="btn btn-default modal-dismiss">Cancel</button>
                            </div>
                        </div>
                    </footer>
                </form>
            </section>
        </div>

        <div id="updateModal" class="modal-block modal-block-primary mfp-hide">
            <section class="card">
                <form method="post" action="{{ route('update-acc') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
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
                                <input type="number" class="form-control" placeholder="Receivables" value="0" name="rec_able" id="update_rec_able">
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Payables</label>
                                <input type="number" class="form-control" placeholder="Payables" value="0" name="pay_able" id="update_pay_able">
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
                                <select class="form-control" name="group_cod" id="update_group_cod">
                                    <option value="">Select Group</option>
                                    @foreach($ac_group as $key => $row)	
                                        <option value="{{$row->group_cod}}">{{$row->group_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6 mb-2">
                                <label>Account Type</label>
                                <select class="form-control" name="AccountType" required id="update_AccountType">
                                    <option  value="" disabled selected>Select Account Type</option>
                                    @foreach($sub_head_of_acc as $key => $row)	
                                        <option value="{{$row->id}}">{{$row->sub}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6 mb-2">
                                <label>Att.</label>
                                <input type="file" class="form-control" placeholder="Att." name="att" id="update_att">
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

        @extends('../layouts.footerlinks')
	</body>
</html>
<script>
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
</script>