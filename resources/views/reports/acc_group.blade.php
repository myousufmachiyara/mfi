@include('../layouts.header')
	<body>
		<section class="body">
            @include('layouts.homepageheader')
			<div class="inner-wrapper cust-pad">
				@include('layouts.leftmenu')
				<section role="main" class="content-body">  
                    <div class="tabs">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link nav-link-rep" data-bs-target="#AG" href="#AG" data-bs-toggle="tab">Acc Group</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link nav-link-rep" data-bs-target="#SHOA" href="#SHOA" data-bs-toggle="tab">SHOA</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link nav-link-rep" data-bs-target="#BA" href="#BA" data-bs-toggle="tab">Balance All</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link nav-link-rep" data-bs-target="#TB" href="#TB" data-bs-toggle="tab"> Trial Balance</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="AG" class="tab-pane">
                                <div class="row form-group pb-3">
                                    <div class="col-lg-8 row">
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <?php $previousDate = date('Y-m-d', strtotime('-30 days')); ?>
                                                <label class="col-form-label"><strong>From</strong></label>
                                                <input type="date" class="form-control" id="fromDate" value="<?php echo $previousDate; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label class="col-form-label" ><strong>To</strong></label>
                                                <input type="date" class="form-control" id="toDate" value="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="col-form-label"><strong>Account Group</strong></label>
                                                <select data-plugin-selecttwo class="form-control select2-js" id="acc_id">
                                                    <option value="" disabled selected>Account Group</option>
                                                    @foreach($ac_group as $key => $row)	
                                                        <option value="{{$row->group_cod}}">{{$row->group_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <a class="btn btn-primary" style="margin-top: 2.1rem;padding: 0.5rem 0.6rem;" onclick="getReport()"><i class="fa fa-filter"></i></a>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4 text-end">
                                        <a class="mb-1 mt-1 me-1 btn btn-warning" aria-label="Download" onclick="downloadPDF('AG')"><i class="fa fa-download"></i> Download</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-danger" aria-label="Print PDF" onclick="printPDF('AG')"><i class="fa fa-file-pdf"></i> Print PDF</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-success" aria-label="Export to Excel" onclick="downloadExcel('AG')"><i class="fa fa-file-excel"></i> Excel</a>   
                                    </div>
                                    
                                    <div class="col-12 mt-4">
                                        <table class="table table-bordered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>S/No</th>
                                                    <th>Date</th>
                                                    <th>Inv No.</th>
                                                    <th>Ord No.</th>
                                                    <th>Basic Amnt</th>
                                                    <th>Comm %</th>
                                                    <th>Comm Amnt</th>
                                                    <th>C.d %</th>
                                                    <th>C.d Amnt</th>
                                                </tr>
                                            </thead>
                                            <tbody id="AGTbleBody">
                                                    
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>		
			</div>
		</section>
        @include('../layouts.footerlinks')
	</body>
    <script>

        document.querySelectorAll('.nav-link-rep').forEach(tabLink => {
            tabLink.addEventListener('click', function() {
                tabId = this.getAttribute('data-bs-target');
                tabChanged(tabId);
            });
        });

        function tabChanged(tabId) {

            const { fromDate, toDate, acc_id } = getInputValues();
            if (!fromDate || !toDate || !acc_id) {
                alert('Please fill in all required fields.');
                return;
            }

            const formattedfromDate = moment(fromDate).format('DD-MM-YYYY'); // Format the date
            const formattedtoDate = moment(toDate).format('DD-MM-YYYY'); // Format the date

            if(tabId=="#AG"){
                var table = document.getElementById('AGTbleBody');
                while (table.rows.length > 0) {
                    table.deleteRow(0);
                }
                url="/rep-by-acc-grp/ag";
                tableID="#AGTbleBody";

                $.ajax({
                    type: "GET",
                    url: url,
                    data:{
                        fromDate: fromDate,
                        toDate: toDate,
                        acc_id: acc_id,
                    }, 
                    success: function(result){
                        $(tableID).empty(); // Clear the loading message
                        
                        $.each(result, function(k,v){
                            var html="<tr>";
                            html += "<td>"+(k+1)+"</td>"
                            html += "<td>" + (v['sa_date'] ? moment(v['sa_date']).format('DD-MM-YYYY') : "") + "</td>";
                            html += "<td>" + (v['Sale_inv_no'] ? v['Sale_inv_no'] : "") +"</td>";
                            html += "<td>" + (v['pur_ord_no'] ? v['pur_ord_no'] : "") + "</td>";
                            html += "<td>" + (v['B_amount'] ? v['B_amount'] : "") + "</td>";
                            html += "<td>" + (v['comm_disc'] ? v['comm_disc'] : "") + "</td>";
                            html += "<td>" + (((v['B_amount']*v['comm_disc'])/100) ? ((v['B_amount']*v['comm_disc'])/100) : "") + "</td>";
                            html += "<td>" + (v['cd_disc'] ? v['cd_disc'] : "") + "</td>";
                            html += "<td>" + (((v['B_amount'] * 1.182 * v['cd_disc'])/118) ? ((v['B_amount'] * 1.182 * v['cd_disc'])/118) : "") + "</td>";
                            html +="</tr>";
                            $(tableID).append(html);
                        });
                    },
                    error: function(){
                        alert("error");
                    }
                });
            }
            
        }

        function getReport() {
            const activeTabLink = document.querySelector('.nav-link.active');
            if (activeTabLink) {
                activeTabLink.click();
            }
        }

        function getInputValues() {
            return {
                fromDate: $('#fromDate').val(),
                toDate: $('#toDate').val(),
                acc_id: $('#acc_id').val()
            };
        }

        function downloadExcel(tabName) {
            const { fromDate, toDate, acc_id } = getInputValues();

            if (!fromDate || !toDate || !acc_id) {
                alert('Please fill in all required fields.');
                return;
            }

            if (tabName === "SI") {
                window.location.href = `/rep-godown-by-item-name/si/excel?fromDate=${fromDate}&toDate=${toDate}&acc_id=${acc_id}`;
            }
        }

        function printPDF(tabName) {
            const { fromDate, toDate, acc_id } = getInputValues();

            if (!fromDate || !toDate || !acc_id) {
                alert('Please fill in all required fields.');
                return;
            }

            if (tabName === "SI") {
                window.open(`/rep-godown-by-item-name/si/report?outputType=view&fromDate=${fromDate}&toDate=${toDate}&acc_id=${acc_id}`, '_blank');

            }
        }

        function downloadPDF(tabName) {
            const { fromDate, toDate, acc_id } = getInputValues();

            if (!fromDate || !toDate || !acc_id) {
                alert('Please fill in all required fields.');
                return;
            }

            if (tabName === "SI") {
                window.location.href = `/rep-godown-by-item-name/si/report?outputType=download&fromDate=${fromDate}&toDate=${toDate}&acc_id=${acc_id}`;
            }
        }
    </script>
</html>