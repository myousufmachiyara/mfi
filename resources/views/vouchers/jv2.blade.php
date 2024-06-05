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
                                        <form action="{{ route('new-jv2') }}" method="GET">
                                            @csrf
                                            <button type="submit" class="btn btn-primary"> <i class="fas fa-plus"></i> New Journal Voucher</button>
                                        </form>
                                    </div>
                                    <h2 class="card-title">Journal Voucher 2</h2>
                                </header>
                                <div class="card-body">
                                	<table class="table table-bordered table-striped mb-0" id="datatable-default">
                                        <thead>
                                            <tr>
                                                <th width="5%">Code</th>
                                                <th>Account</th>
                                                <th>Debit</th>
                                                <th>Credit</th>
                                                <th>Bank Name</th>
                                                <th>Instrument #</th>
                                                <th>Remarks</th>
                                                <th>Att.</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- @foreach ($jv2 as $key => $row)
                                                <tr>
                                                    <td>{{$row->auto_lager}}</td>
                                                    <td>{{$row->account_name}}</td>
                                                    @if(substr(strval($row->debit), strpos(strval($row->debit), '.') + 1)>0)
                                                        <td>{{number_format($row->debit, 4)}}</td>
                                                    @else
                                                        <td>{{ number_format(intval($row->debit))}}</td>
                                                    @endif
                                                    @if(substr(strval($row->credit), strpos(strval($row->credit), '.') + 1)>0)
                                                        <td>{{number_format($row->credit, 4)}}</td>
                                                    @else
                                                        <td>{{ number_format(intval($row->credit))}}</td>
                                                    @endif
                                                    <td>{{ \Carbon\Carbon::parse($row->date)->format('d-m-y') }}</td>
                                                    <td>{{$row->remarks}}</td>
                                                    <td><a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="getAttachements({{$row->auto_lager}})" href="#attModal">View Att.</a></td>
                                                    <td class="actions">
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal text-primary" href="#"><i class="fas fa-print"></i></a>
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="getJVSDetails({{$row->auto_lager}})" href="#updateModal"><i class="fas fa-pencil-alt"></i></a>
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="setId({{$row->auto_lager}})" href="#deleteModal"><i class="far fa-trash-alt" style="color:red"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach -->
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
            <form method="post" action="{{ route('delete-jv1') }}" enctype="multipart/form-data">
                @csrf
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">Delete Journal Voucher</h2>
                    </header>
                    <div class="card-body">
                        <div class="modal-wrapper">
                            <div class="modal-icon">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <div class="modal-text">
                                <p class="mb-0">Are you sure that you want to delete this Journal Voucher?</p>
                                <input name="delete_auto_lager" id="deleteID" hidden>
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

        <div id="updateModal" class="modal-block modal-block-primary mfp-hide">
            <section class="card">
                <form method="post" action="{{ route('update-jv1') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
                    @csrf
                    <header class="card-header">
                        <h2 class="card-title">Update Journal Voucher</h2>
                    </header>
                    <div class="card-body">
                        <div class="row form-group">
                            <div class="col-lg-6">
                                <label>JV1 Code</label>
                                <input type="number" class="form-control" placeholder="JV1 Code" id="update_id" required disabled>
                                <input type="hidden" class="form-control" placeholder="JV1 Code" name="update_auto_lager" id="update_id_view" required>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Account Debit</label>
                                <select class="form-control" name="update_ac_dr_sid" required id="update_ac_dr_sid">
                                    <option disabled selected>Select Account</option>
                                    @foreach($acc as $key => $row)	
                                        <option value="{{$row->ac_code}}">{{$row->ac_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Account Credit</label>
                                <select class="form-control" name ="update_ac_cr_sid" required id="update_ac_cr_sid">
                                    <option disabled selected>Select Account</option>
                                    @foreach($acc as $key => $row)	
                                        <option value="{{$row->ac_code}}">{{$row->ac_name}}</option>
                                    @endforeach
                                </select>                            
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Amount</label>
                                <input type="number" class="form-control" placeholder="Amount" id="update_amount" value="0" step=".00001" name="update_amount">
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Date</label>
                                <input type="date" class="form-control" placeholder="Date" id="update_date" name="update_date" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Attachments</label>
                                <input type="file" class="form-control" name="update_att[]" multiple accept=".zip, appliation/zip, application/pdf, image/png, image/jpeg">
                            </div>  
                            <div class="col-lg-12 mb-2">
                                <label>Remarks</label>
                                <textarea rows="4" cols="50" class="form-control" placeholder="Remarks" id="update_remarks" name="update_remarks"> </textarea>                            </div>
                            </div>
                        </div>
                    
                    <footer class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">Update Journal Voucher</button>
                                <button class="btn btn-default modal-dismiss">Cancel</button>
                            </div>
                        </div>
                    </footer>
                    </div>
                </form>
            </section>
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
                                    <th>ID</th>
                                    <th>Attachement Path</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="jv1_attachements">

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
                                <!-- <button type="button" class="btn btn-danger">Download All</button> -->
                                <button class="btn btn-default modal-dismiss">Cancel</button>
                            <!-- </form> -->
                        </div>
                    </div>
                </footer>
            </section>
        </div>
        @extends('../layouts.footerlinks')
	</body>
</html>
<script>
    function setId(id){
        $('#deleteID').val(id);
    }

    function getAttachements(id){

        var table = document.getElementById('jv1_attachements');
        while (table.rows.length > 0) {
            table.deleteRow(0);
        }

        $.ajax({
            type: "GET",
            url: "/vouchers/jv1/attachements",
            data: {id:id},
            success: function(result){
                $.each(result, function(k,v){
                    var html="<tr>";
                    html+= "<td>"+v['att_id']+"</td>"
                    html+= "<td>"+v['att_path']+"</td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 mr-2 me-1 text-danger' href='/vouchers/jv1/download/"+v['att_id']+"'><i class='fas fa-download'></i></a>"
                    html+="<a class='mb-1 mt-1 me-1 text-primary' href='/vouchers/jv1/view/"+v['att_id']+"' target='_blank'><i class='fas fa-eye'></i></a>"
                    html+="<a class='mb-1 mt-1 me-1 text-primary' href='/vouchers/jv1/view/"+v['att_id']+"' target='_blank'><i class='fas fa-trash'></i></a>"
                    html+="</td>"
                    html+="</tr>";
                    $('#jv1_attachements').append(html);
                });
                $('#download_id').val(result[0]['ac_code']);
            },
            error: function(){
                alert("error");
            }
        });
    }

    function getJVSDetails(id){
        $.ajax({
            type: "GET",
            url: "/vouchers/jv1/detail",
            data: {id:id},
            success: function(result){
                $('#update_id').val(result['auto_lager']);
                $('#update_id_view').val(result['auto_lager']);
                $('#update_ac_cr_sid').val(result['ac_cr_sid']);
                $('#update_ac_dr_sid').val(result['ac_dr_sid']);
                $('#update_amount').val(result['amount']);
                $('#update_date').val(result['date']);
                $('#update_remarks').val(result['remarks']);
            },
            error: function(){
                alert("error");
            }
        });
	}

</script>