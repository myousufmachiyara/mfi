@extends('../layouts.header')
	<body>
		<section class="body">
            @include('layouts.pageheader')
            <div class="inner-wrapper">
				<section role="main" class="content-body">
                    <div class="row">
                        <div class="col">
                            <section class="card">
                                <header class="card-header" style="display: flex;justify-content: space-between;">
                                    <h2 class="card-title">All Doors Bad Dabs</h2>
                                    <form class="text-end" action="{{ route('create-bad-dabs') }}" method="GET">
                                        <button type="submit" class="btn btn-primary mt-2"> <i class="fas fa-plus"></i> New Entry</button>
                                    </form>
                                </header>
                                <div class="card-body">
                                    <div class="modal-wrapper">
                                        <table class="table table-bordered table-striped mb-0" id="datatable-default">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Date</th>
                                                    <th>Reason</th>
                                                    <th>Total Add</th>
                                                    <th>Total Less</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($bad_dabs as $key => $row)
                                                <tr>
                                                    <td>{{$row->bad_dabs_id}}</td>
                                                    <td>{{ \Carbon\Carbon::parse($row->date)->format('d-m-y') }}</td>
                                                    <td>{{$row->reason}}</td>
                                                    <td>{{$row->add_sum}}</td>
                                                    <td>{{$row->less_sum}}</td>
                                                    <td class="actions">
                                                        <a href="#" class="text-danger">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                        <span class="separator"> | </span>
                                                        <a href="#" class="">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <span class="separator"> | </span>
                                                        <a href="{{ route('edit-bad-dabs-entry', $row->bad_dabs_id) }}" class="">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </a>
                                                        
                                                        <span class="separator"> | </span>
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="setId({{$row->bad_dabs_id}})" href="#deleteModal">
                                                            <i class="far fa-trash-alt" style="color:red"></i>
                                                        </a>
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

                    <div id="deleteModal" class="zoom-anim-dialog modal-block modal-block-danger mfp-hide">
                        <form method="post" action="{{ route('delete-bad-dabs') }}" enctype="multipart/form-data">
                            @csrf
                            <section class="card">
                                <header class="card-header">
                                    <h2 class="card-title">Delete Pipe Bed Dads</h2>
                                </header>
                                <div class="card-body">
                                    <div class="modal-wrapper">
                                        <div class="modal-icon">
                                            <i class="fas fa-question-circle"></i>
                                        </div>
                                        <div class="modal-text">
                                            <p class="mb-0">Are you sure that you want to delete this Delete Door Bed Dads entry?</p>
                                            <input name="delete_bad_dabs_id" id="deleteID" hidden>
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
			</div>
		</section>
        <div id="deleteModal" class="zoom-anim-dialog modal-block modal-block-danger mfp-hide">
            <form method="post" action="{{ route('delete-bad-dabs') }}" enctype="multipart/form-data">
                @csrf
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">Delete Invoice</h2>
                    </header>
                    <div class="card-body">
                        <div class="modal-wrapper">
                            <div class="modal-icon">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <div class="modal-text">
                                <p class="mb-0">Are you sure that you want to delete this invoice?</p>
                                <input name="invoice_id" id="deleteID" hidden>
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

        
        @extends('../layouts.footerlinks')
	</body>
</html>
<script>
    function setId(id){
        $('#deleteID').val(id);
    }

    function getAttachements(id){
        var table = document.getElementById('sale1_attachements');
        while (table.rows.length > 0) {
            table.deleteRow(0);
        }

        $.ajax({
            type: "GET",
            url: "/sales/attachements",
            data: {id:id},
            success: function(result){
                $.each(result, function(k,v){
                    var html="<tr>";
                    html+= "<td>"+v['att_path']+"</td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 mr-2 me-1 text-danger' href='/sales/download/"+v['att_id']+"'><i class='fas fa-download'></i></a></td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 me-1 text-primary' href='/sales/view/"+v['att_id']+"' target='_blank'><i class='fas fa-eye'></i></a></td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 me-1 text-primary' href='#' onclick='deleteFile("+v['att_id']+")'><i class='fas fa-trash'></i></a></td>"
                    html+="</tr>";
                    $('#sale1_attachements').append(html);
                });
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

        fetch('/sales/deleteAttachment/' + fileId, {
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