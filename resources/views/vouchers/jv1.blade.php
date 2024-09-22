@include('../layouts.header')
	<body>
		<section class="body">
            @include('layouts.pageheader')
			<div class="inner-wrapper">
				<section role="main" class="content-body">
                    <div class="row">
                        <div class="col">
                            <section class="card">
                                <header class="card-header" style="display: flex;justify-content: space-between;">
                                    <h2 class="card-title">Journal Voucher 1</h2>
                                    <div class="text-end">
                                        <button type="button" class="modal-with-form btn btn-primary mt-2" href="#addModal"> <i class="fas fa-plus"></i> New Voucher</button>
                                    </div>
                                </header>
                                <div class="card-body">
                                	<table class="table table-bordered table-striped mb-0" id="datatable-default">
                                        <thead>
                                            <tr>
                                                <th width="5%">Voch#</th>
                                                <th width="8%">Date</th>
                                                <th width="15%">Account Debit</th>
                                                <th width="15%">Account Credit</th>
                                                <th width="30%">Remarks</th>
                                                <th>Amount</th>
                                                <th>Att.</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jv1 as $key => $row)
                                                <tr>
                                                    <td>{{$row->auto_lager}}</td>
                                                    <td>{{ \Carbon\Carbon::parse($row->date)->format('d-m-y') }}</td>
                                                    <td>{{$row->debit_account}}</td>
                                                    <td>{{$row->credit_account}}</td>
                                                    <td >{{$row->remarks}}</td>
                                                    @if (strpos($row->amount, '.') !== false && substr($row->amount, strpos($row->amount, '.') + 1) > '0')
                                                    <td><strong style="font-size:15px">{{ number_format($row->amount, 0, '.', ',') }}</strong></td>
                                                @else
                                                    <td><strong style="font-size:15px">{{ number_format($row->amount, 0, '.', ',') }}</strong></td>
                                                @endif
                                                
                                                
                                                    <td><a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="getAttachements({{$row->auto_lager}})" href="#attModal">View Att.</a></td>
                                                    <td class="actions">
                                                        <a class="mb-1 mt-1 me-1" href="{{ route('show-jv1', $row->auto_lager) }}">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                        <span class="separator"> | </span>
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="getJVSDetails({{$row->auto_lager}})" href="#updateModal">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </a>
                                                        <span class="separator"> | </span>
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="setId({{$row->auto_lager}})" href="#deleteModal">
                                                            <i class="far fa-trash-alt" style="color:red"></i>
                                                        </a>
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

        <div id="addModal" class="modal-block modal-block-primary mfp-hide">
            <section class="card">
                <form method="post" action="{{ route('store-jv1') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
                    @csrf
                    <header class="card-header">
                        <h2 class="card-title">Add Journal Voucher</h2>
                    </header>
                    <div class="card-body">
                        <div class="row form-group">
                            <div class="col-lg-6">
                                <label>JV1 Code</label>
                                <input type="number" class="form-control" placeholder="JV1 Code" required disabled>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Date</label>
                                <input type="date" class="form-control" placeholder="Date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Account Debit<span style="color: red;"><strong>*</strong></span></label>
                                <select  data-plugin-selecttwo class="form-control select2-js" name ="ac_dr_sid" required>
                                    <option value="" disabled selected>Select Account</option>
                                    @foreach($acc as $key => $row)	
                                        <option value="{{$row->ac_code}}">{{$row->ac_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Account Credit<span style="color: red;"><strong>*</strong></span></label>

                                <select  data-plugin-selecttwo class="form-control select2-js" name ="ac_cr_sid" required>
                                    <option value="" disabled selected>Select Account</option>
                                    @foreach($acc as $key => $row)	
                                        <option value="{{$row->ac_code}}">{{$row->ac_name}}</option>
                                    @endforeach
                                </select>                            
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Amount<span style="color: red;"><strong>*</strong></span></label>
                                <input type="number" class="form-control" placeholder="Amount" value="0" step="any" name="amount" required>
                            </div>

                            <div class="col-lg-6 mb-2">
                                <label>Attachments</label>
                                <input type="file" class="form-control" name="att[]" multiple accept=".zip, appliation/zip, application/pdf, image/png, image/jpeg">
                            </div>  
                            <div class="col-lg-12 mb-2">
                                <label>Remarks</label>
                                <textarea rows="4" cols="50" class="form-control cust-textarea" placeholder="Remarks" name="remarks"> </textarea>                            </div>
                            </div>
                        </div>
                        <footer class="card-footer">
                            <div class="row">
                                <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-primary">Add Journal Voucher</button>
                                    <button class="btn btn-default modal-dismiss">Cancel</button>
                                </div>
                            </div>
                        </footer>
                    </div>
                </form>
            </section>
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
                                <label>Date</label>
                                <input type="date" class="form-control" placeholder="Date" id="update_date" name="update_date" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Account Debit<span style="color: red;"><strong>*</strong></span></label>
                                <select data-plugin-selecttwo class="form-control select2-js"  name="update_ac_dr_sid" required id="update_ac_dr_sid">
                                    <option disabled selected>Select Account</option>
                                    @foreach($acc as $key => $row)	
                                        <option value="{{$row->ac_code}}">{{$row->ac_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Account Credit<span style="color: red;"><strong>*</strong></span></label>
                                <select data-plugin-selecttwo class="form-control select2-js"  name ="update_ac_cr_sid" required id="update_ac_cr_sid">
                                    <option disabled selected>Select Account</option>
                                    @foreach($acc as $key => $row)	
                                        <option value="{{$row->ac_code}}">{{$row->ac_name}}</option>
                                    @endforeach
                                </select>                            
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label>Amount<span style="color: red;"><strong>*</strong></span></label>
                                <input type="number" class="form-control" placeholder="Amount" id="update_amount" value="0" step="any" name="update_amount" required>
                            </div>

                            <div class="col-lg-6 mb-2">
                                <label>Attachments</label>
                                <input type="file" class="form-control" name="update_att[]" multiple accept=".zip, appliation/zip, application/pdf, image/png, image/jpeg">
                            </div>  
                            <div class="col-lg-12 mb-2">
                                <label>Remarks</label>
                                <textarea rows="4" cols="50" class="form-control cust-textarea" placeholder="Remarks" id="update_remarks" name="update_remarks"> </textarea>                            </div>
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
                                    <th>Attachement Path</th>
                                    <th>Download</th>
                                    <th>View</th>
                                    <th>Delete</th>
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
        @include('../layouts.footerlinks')
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
            url: "/vouchers/attachements",
            data: {id:id},
            success: function(result){
                $.each(result, function(k,v){
                    var html="<tr>";
                    html+= "<td>"+v['att_path']+"</td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 mr-2 me-1 text-danger' href='/vouchers/download/"+v['att_id']+"'><i class='fas fa-download'></i></a></td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 me-1 text-primary' href='/vouchers/view/"+v['att_id']+"' target='_blank'><i class='fas fa-eye'></i></a></td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 me-1 text-primary' href='#' onclick='deleteFile("+v['att_id']+")'><i class='fas fa-trash'></i></a></td>"
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
            url: "/vouchers/detail",
            data: {id:id},
            success: function(result){
                console.log(result);
                $('#update_id').val(result['auto_lager']);
                $('#update_id_view').val(result['auto_lager']);
                $('#update_ac_cr_sid').val(result['ac_cr_sid']).trigger('change');
                $('#update_ac_dr_sid').val(result['ac_dr_sid']).trigger('change');
                $('#update_amount').val(result['amount']);
                $('#update_date').val(result['date']);
                $('#update_remarks').val(result['remarks']);
            },
            error: function(){
                alert("error");
            }
        });
	}

    function deleteFile(fileId) {
        if (!confirm('Are you sure you want to delete this file?')) {
            return;
        }

        fetch('/vouchers/deleteAttachment/' + fileId, {
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