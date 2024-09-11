@include('../layouts.header')
	<body>
		<section class="body">
			@include('../layouts.pageheader')
			<div class="inner-wrapper">
				<section role="main" class="content-body">
                    <div class="row">
                        <div class="col">
                            <section class="card">
                                <header class="card-header" style="display: flex;justify-content: space-between;">
                                    <h2 class="card-title">All Purchase Invoices</h2>
                                    <form class="text-end" action="{{ route('new-purchases1') }}" method="GET">
                                        <button type="submit" class="btn btn-primary mt-2"> <i class="fas fa-plus"></i> New Purchase Invoice</button>
                                    </form>
                                </header>
                                <div class="card-body">
                                    <div class="modal-wrapper">
                                        <table class="table table-bordered table-striped mb-0" id="datatable">
                                            <thead>
                                                <tr>
                                                    <th style="display:none">Inv #</th>
                                                    <th>Code</th>
                                                    <th>Date</th>
                                                    <th>Account Name</th>
                                                    <th>Person Name</th>
                                                    <th>Remarks</th>
                                                    <th>SaleInv #</th>
                                                    <th>Weight (kg)</th>
                                                    <th>Bill Amount</th>
                                                    <th>Convance Charges</th>
                                                    <th>Labour Charges</th>
                                                    <th>Discount</th>
                                                    <th>Net Amount</th>
                                                    <th>Att.</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- @foreach ($pur1 as $key => $row)
                                                <tr>
                                                    <td style="display:none">{{$row->pur_id}}</td>
                                                    <td>{{$row->prefix}}{{$row->pur_id}}</td>
                                                    <td>{{ \Carbon\Carbon::parse($row->pur_date)->format('d-m-y') }}</td>
                                                    <td><strong>{{$row->ac_name}}</strong></td>
                                                    <td>{{$row->cash_saler_name}}</td>
                                                    <td>{{$row->pur_remarks}}</td>
                                                    <td>{{$row->sale_against}}</td>
                                                    <td>{{$row->weight_sum}}</td>
                                                    <td>{{$row->total_bill}}</td>
                                                    <td>{{$row->pur_convance_char}}</td>
                                                    <td>{{$row->pur_labor_char}}</td>
                                                    <td>{{$row->pur_discount}}</td>
                                                    @php ($net_amount=$row->total_bill+$row->pur_convance_char+$row->pur_labor_char-$row->pur_discount)
                                                    @if(substr(strval($row->net_amount), strpos(strval($row->net_amount), '.') + 1)>0) 
                                                        <td><strong style="font-size:15px">{{ rtrim(rtrim(number_format($net_amount), '0'), '.') }}</strong></td>
                                                    @else
                                                        <td><strong style="font-size:15px">{{ number_format(intval($net_amount))}}</strong></td>
                                                    @endif
                                                    <td><a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="getAttachements({{$row->pur_id}})" href="#attModal">View</a></td>
                                                    <td class="actions">
                                                         <a href="{{ route('print-purc1-invoice', $row->pur_id) }}" class="text-danger">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                        <span class="separator"> | </span>
                                                        <a href="{{ route('show-purchases1',$row->pur_id) }}" class="">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <span class="separator"> | </span>
                                                        <a href="{{ route('edit-purchases1',$row->pur_id) }}" class="">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </a>
                                                        <span class="separator"> | </span>
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="setId({{$row->pur_id}})" href="#deleteModal">
                                                            <i class="far fa-trash-alt" style="color:red"></i>
                                                        </a>
                                                    </td>

                                                </tr>
                                                @endforeach -->
                                            </tbody>
                                        </table>
                                        <div class="dataTables_paginate paging_simple_numbers" id="pagination"></div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </section>		
			</div>
		</section>

        <div id="deleteModal" class="zoom-anim-dialog modal-block modal-block-danger mfp-hide">
            <form method="post" action="{{ route('delete-purchases1') }}" enctype="multipart/form-data">
                @csrf
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">Delete Purchase Invoice</h2>
                    </header>
                    <div class="card-body">
                        <div class="modal-wrapper">
                            <div class="modal-icon">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <div class="modal-text">
                                <p class="mb-0">Are you sure that you want to delete this Purchase Invoice?</p>
                                <input name="delete_purc1" id="deleteID" hidden>
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

        <div id="attModal" class="zoom-anim-dialog modal-block modal-block-danger mfp-hide">
            <section class="card">
                <header class="card-header">
                    <h2 class="card-title">All Attachements</h2>
                </header>
                <div class="card-body">

                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Attachement Path</th>
                                    <th>Download</th>
                                    <th>View</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody id="pur1_attachements">

                            </tbody>
                        </table>
                </div>
                <footer class="card-footer">
                    <div class="row">
                        <div class="col-md-12 text-end">
                            <button class="btn btn-default modal-dismiss">Cancel</button>
                        </div>
                    </div>
                </footer>
            </section>
        </div>
        @extends('../layouts.footerlinks')
	</body>
</html>
<script>
    const items = @json($pur1);
    const rowsPerPage = 1000; // Number of rows per page
    let currentPage = 1;

    function renderTable(page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const paginatedData = items.slice(start, end);

        const tbody = document.querySelector('#datatable tbody');
        tbody.innerHTML = '';

        paginatedData.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td style="display:none"></td>
                <td>${row.pur_id}</td>
                <td>${row.pur_date}</td>
                <td>${row.ac_name}</td>
                <td>${row.cash_saler_name}</td>
                <td>${row.pur_remarks}</td>
                <td>${row.sale_against}</td>
                <td>${row.weight_sum}</td>
                <td>${row.total_bill}</td>
                <td>${row.pur_convance_char}</td>
                <td>${row.pur_labor_char}</td>
                <td>${row.pur_discount}</td>
                <td></td>
                <td></td>
                <td class="actions">
                    <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="getItemDetails(${row.pur_id})" href="#updateModal">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    <span class="separator"> | </span>
                    <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="setId(${row.pur_id})" href="#deleteModal">
                        <i class="far fa-trash-alt" style="color:red"></i>
                    </a>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function setupPagination() {
        const pageCount = Math.ceil(items.length / rowsPerPage);
        const pagination = document.querySelector('#pagination');
        pagination.innerHTML = '';

        for (let i = 1; i <= pageCount; i++) {
            const button = document.createElement('button');
            button.textContent = i;
            button.classList.add('paginate_button');
            button.classList.add('page-item');

            if (i === currentPage) {
                button.classList.add('active');
            }
            button.addEventListener('click', () => {
                currentPage = i;
                renderTable(currentPage);
                updatePaginationButtons();
            });
            pagination.appendChild(button);
        }
    }

    function updatePaginationButtons() {
        document.querySelectorAll('.paginate_button').forEach(button => {
            button.classList.remove('active');
        });
        document.querySelectorAll('.paginate_button')[currentPage - 1].classList.add('active');
    }

    // Initial setup
    setupPagination();
    renderTable(currentPage);
</script>
<script>
    function setId(id){
        $('#deleteID').val(id);
    }

    function getAttachements(id){

        var table = document.getElementById('pur1_attachements');
        while (table.rows.length > 0) {
            table.deleteRow(0);
        }

        $.ajax({
            type: "GET",
            url: "/purchase1/attachements",
            data: {id:id},
            success: function(result){
                console.log(result);
                $.each(result, function(k,v){
                    var html="<tr>";
                    html+= "<td>"+v['att_path']+"</td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 mr-2 me-1 text-danger' href='/purchase1/download/"+v['att_id']+"'><i class='fas fa-download'></i></a></td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 me-1 text-primary' href='/purchase1/view/"+v['att_id']+"' target='_blank'><i class='fas fa-eye'></i></a></td>"
                    html+= "<td class='text-center'><a class='mb-1 mt-1 me-1 text-primary' href='#' onclick='deleteFile("+v['att_id']+")'><i class='fas fa-trash'></i></a></td>"
                    html+="</tr>";
                    $('#pur1_attachements').append(html);
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

        fetch('/purchase1/deleteAttachment/' + fileId, {
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