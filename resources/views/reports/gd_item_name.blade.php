@include('../layouts.header')
	<body>
		<section class="body">
            @include('layouts.homepageheader')
			<div class="inner-wrapper cust-pad">
				@include('layouts.leftmenu')
				<section role="main" class="content-body">
                    <div class="row mb-4">
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
                                <label class="col-form-label"><strong>Item Name</strong></label>
                                <select data-plugin-selecttwo class="form-control select2-js"  id="acc_id">
                                    <option value="" disabled selected>Item Name</option>
                                    @foreach($items as $key => $row)	
                                        <option value="{{$row->it_cod}}">{{$row->item_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <a class="btn btn-primary" style="margin-top: 2.1rem;padding: 0.5rem 0.6rem;" onclick="getReport()"><i class="fa fa-filter"></i></a>
                        </div>
                    </div>   
                    <div class="tabs">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#IL" href="#IL" data-bs-toggle="tab">Item Ledger</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#SI" href="#SI" data-bs-toggle="tab">Stock IN</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#SO" href="#SO" data-bs-toggle="tab">Stock Out</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#bal" href="#bal" data-bs-toggle="tab">Stock Balance</a>
                            </li>
                           
                        </ul>
                        <div class="tab-content">
                            <div id="IL" class="tab-pane">
                                <div class="row form-group pb-3">
                                    <div class="col-lg-6 ">
                                        <div class="bill-to">
                                            <h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold" style="display: flex; align-items: center;">
                                                <span style="color: #17365D;">From: &nbsp;</span>
                                                <span style="font-weight: 400; color: black;" id="pur1_from"></span>
                                            
                                                <span style="flex: 0.3;"></span> <!-- Spacer to push the "To" to the right -->
                                            
                                                <span style="color: #17365D;">To: &nbsp;</span>
                                                <span style="font-weight: 400; color: black;" id="pur1_to"></span>
                                            </h4>
                                            
                                            
                                            <!-- <h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
                                                <span style="color:#17365D">To: &nbsp;</span>
                                                <span style="font-weight:400; color:black;" class="value"></span>
                                            </h4> -->
                                    
                                            <h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
                                                <span style="color:#17365D">Account Name: &nbsp;</span>
                                                <span style="font-weight:400; color:black;" id="pur1_acc"></span>
                                            </h4>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6 text-end">
                                        <a class="mb-1 mt-1 me-1 btn btn-warning" aria-label="Download" onclick="downloadPDF('purchase1')"><i class="fa fa-download"></i> Download</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-danger" aria-label="Print PDF" onclick="printPDF('purchase1')"><i class="fa fa-file-pdf"></i> Print PDF</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-success" aria-label="Export to Excel" onclick="downloadExcel('purchase1')"><i class="fa fa-file-excel"></i> Excel</a>   
                                    </div>
                                    
                                    <div class="col-12 mt-4">
                                        <table class="table table-bordered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>S/No</th>
                                                    <th>Sales Date</th>
                                                    <th>Inv No.</th>
                                                    <th>Mill No.</th>
                                                    <th>Dispatch To Party</th>
                                                    <th>Sale Inv</th>
                                                    <th>Remarks</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody id="P1TbleBody">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="SI" class="tab-pane">
                                <div class="row form-group pb-3">
                                    <div class="col-lg-6 ">
                                        <div class="bill-to">
                                            <h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold" style="display: flex; align-items: center;">
                                                <span style="color: #17365D;">From: &nbsp;</span>
                                                <span style="font-weight: 400; color: black;" id="si_from"></span>
                                            
                                                <span style="flex: 0.3;"></span> <!-- Spacer to push the "To" to the right -->
                                            
                                                <span style="color: #17365D;">To: &nbsp;</span>
                                                <span style="font-weight: 400; color: black;" id="si_to"></span>
                                            </h4>
                                            
                                    
                                            <h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
                                                <span style="color:#17365D">Item Name: &nbsp;</span>
                                                <span style="font-weight:400; color:black;" id="si_acc"></span>
                                            </h4>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6 text-end">
                                        <a class="mb-1 mt-1 me-1 btn btn-warning" aria-label="Download" onclick="downloadPDF('si')">
                                            <i class="fa fa-download"></i> Download
                                        </a>
                                        <a class="mb-1 mt-1 me-1 btn btn-danger" aria-label="Print PDF" onclick="printPDF('si')">
                                            <i class="fa fa-file-pdf"></i> Print PDF
                                        </a>
                                           <a class="mb-1 mt-1 me-1 btn btn-success" aria-label="Export to Excel" onclick="downloadExcel('si')">
                                            <i class="fa fa-file-excel"></i> Excel</a>   
                                    </div>
                                    
                                    <div class="col-12 mt-4">
                                        <table class="table table-bordered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>S/No</th>
                                                    <th>SI No.</th>
                                                    <th>Date</th>
                                                    <th>Pur Inv#</th>
                                                    <th>Company Name</th>
                                                    <th>Gate Pass#</th>
                                                    <th>Remarks</th>
                                                    <th>Quantity</th>
                                                </tr>
                                            </thead>
                                            <tbody id="SITbleBody">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="SO" class="tab-pane">
                                <div class="row form-group pb-3">
                                    <div class="col-lg-6 ">
                                        <div class="bill-to">
                                            <h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold" style="display: flex; align-items: center;">
                                                <span style="color: #17365D;">From: &nbsp;</span>
                                                <span style="font-weight: 400; color: black;" id="so_from"></span>
                                            
                                                <span style="flex: 0.3;"></span> <!-- Spacer to push the "To" to the right -->
                                            
                                                <span style="color: #17365D;">To: &nbsp;</span>
                                                <span style="font-weight: 400; color: black;" id="so_to"></span>
                                            </h4>
                                            
                                    
                                            <h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
                                                <span style="color:#17365D">Item Name: &nbsp;</span>
                                                <span style="font-weight:400; color:black;" id="so_acc"></span>
                                            </h4>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6 text-end">
                                        <a class="mb-1 mt-1 me-1 btn btn-warning" aria-label="Download" onclick="downloadPDF('purchase1')"><i class="fa fa-download"></i> Download</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-danger" aria-label="Print PDF" onclick="printPDF('purchase1')"><i class="fa fa-file-pdf"></i> Print PDF</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-success" aria-label="Export to Excel" onclick="downloadExcel('purchase1')"><i class="fa fa-file-excel"></i> Excel</a>   
                                    </div>
                                    
                                    <div class="col-12 mt-4">
                                        <table class="table table-bordered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>S/No</th>
                                                    <th>SI No.</th>
                                                    <th>Date</th>
                                                    <th>Sale Inv#</th>
                                                    <th>Customer Name</th>
                                                    <th>Gate Pass#</th>
                                                    <th>Remarks</th>
                                                    <th>Quantity</th>
                                                </tr>
                                            </thead>
                                            <tbody id="SOTbleBody">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="bal" class="tab-pane">
                                <div class="row form-group pb-3">
                                    <div class="col-lg-6 ">
                                        <div class="bill-to">
                                            <h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold" style="display: flex; align-items: center;">
                                                <span style="color: #17365D;">From: &nbsp;</span>
                                                <span style="font-weight: 400; color: black;" id="bal_from"></span>
                                            
                                                <span style="flex: 0.3;"></span> <!-- Spacer to push the "To" to the right -->
                                            
                                                <span style="color: #17365D;">To: &nbsp;</span>
                                                <span style="font-weight: 400; color: black;" id="bal_to"></span>
                                            </h4>
                                            
                                    
                                            <h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
                                                <span style="color:#17365D">Item Name: &nbsp;</span>
                                                <span style="font-weight:400; color:black;" id="bal_acc"></span>
                                            </h4>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6 text-end">
                                        <a class="mb-1 mt-1 me-1 btn btn-warning" aria-label="Download" onclick="downloadPDF('purchase1')"><i class="fa fa-download"></i> Download</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-danger" aria-label="Print PDF" onclick="printPDF('purchase1')"><i class="fa fa-file-pdf"></i> Print PDF</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-success" aria-label="Export to Excel" onclick="downloadExcel('purchase1')"><i class="fa fa-file-excel"></i> Excel</a>   
                                    </div>
                                    
                                    <div class="col-12 mt-4">
                                        <table class="table table-bordered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>S/No</th>
                                                    <th>ID</th>
                                                    <th>Date</th>
                                                    <th>Reason</th>
                                                    <th>Remarks</th>
                                                    <th>Qty Add</th>
                                                    <th>Qty Less</th>
                                                </tr>
                                            </thead>
                                            <tbody id="balTbleBody">

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

        document.querySelectorAll('.nav-link').forEach(tabLink => {
            tabLink.addEventListener('click', function() {
                tabId = this.getAttribute('data-bs-target');
                tabChanged(tabId);
            });
        });

        function tabChanged(tabId) {
            fromDate=$('#fromDate').val();
            toDate=$('#toDate').val();
            acc_id=$('#acc_id').val();
            const formattedfromDate = moment(fromDate).format('DD-MM-YYYY'); // Format the date
            const formattedtoDate = moment(toDate).format('DD-MM-YYYY'); // Format the date

            if(tabId=="#IL"){
            }
            
            else if(tabId=="#SI"){
                var table = document.getElementById('SITbleBody');
                while (table.rows.length > 0) {
                    table.deleteRow(0);
                }
                url="/rep-godown-by-item-name/si";
                tableID="#SITbleBody";

                $.ajax({
                    type: "GET",
                    url: url,
                    data:{
                        fromDate: fromDate,
                        toDate: toDate,
                        acc_id:acc_id,
                    }, 
                    success: function(result){
                        $('#si_from').text(formattedfromDate);
                        $('#si_to').text(formattedtoDate);
                        var selectedAcc = $('#acc_id').find("option:selected").text();
                        $('#si_acc').text(selectedAcc);

                        $.each(result, function(k,v){
                            var html="<tr>";
                            html += "<td>"+(k+1)+"</td>"
                            html += "<td>" + (v['prefix'] ? v['prefix'] : "") + (v['pur_id'] ? v['pur_id'] : "") +"</td>";
                            html += "<td>" + (v['pur_date'] ? moment(v['pur_date']).format('DD-MM-YYYY') : "") + "</td>";
                            html += "<td>" + (v['pur_bill_no'] ? v['pur_bill_no'] : "") + "</td>";
                            html += "<td>" + (v['ac_name'] ? v['ac_name'] : "") + "</td>";
                            html += "<td>" + (v['mill_gate_no'] ? v['mill_gate_no'] : "") + "</td>";
                            html += "<td>" + (v['Pur_remarks'] ? v['Pur_remarks'] : "") + "</td>";
                            html += "<td>" + (v['pur_qty'] ? v['pur_qty'] : "") + "</td>";
                            html +="</tr>";
                            $(tableID).append(html);
                        });
                    },
                    error: function(){
                        alert("error");
                    }
                });
            }
            
            else if (tabId === "#SO") {
                let table = document.getElementById('SOTbleBody');
                
                // Clear the table
                while (table.rows.length > 0) {
                    table.deleteRow(0);
                }

                const url = "/rep-godown-by-item-name/so";
                const tableID = "#SOTbleBody";

                // Helper function to safely access data
                const safeVal = (val) => val ? val : "";

                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        fromDate: fromDate,
                        toDate: toDate,
                        acc_id: acc_id,
                    },
                    beforeSend: function() {
                        $(tableID).html('<tr><td colspan="8" class="text-center">Loading Data Please Wait...</td></tr>');
                    },
                    success: function (result) {
                        $('#so_from').text(formattedfromDate);
                        $('#so_to').text(formattedtoDate);

                        const selectedAcc = $('#acc_id').find("option:selected").text();
                        $('#so_acc').text(selectedAcc);

                        $(tableID).empty(); // Clear the loading message

                        // Populate the table with new data
                        $.each(result, function (k, v) {
                            let html = `<tr>
                                <td>${k + 1}</td>
                                <td>${safeVal(v['prefix'])}${safeVal(v['Sal_inv_no'])}</td>
                                <td>${v['sa_date'] ? moment(v['sa_date']).format('DD-MM-YYYY') : ""}</td>
                                <td>${safeVal(v['pur_inv'])}</td>
                                <td>${safeVal(v['ac_name'])}</td>
                                <td>${safeVal(v['mill_gate'])}</td>
                                <td>${safeVal(v['remarks'])}</td>
                                <td>${safeVal(v['sales_qty'])}</td>
                            </tr>`;
                            $(tableID).append(html);
                        });
                    },
                    error: function () {
                        $(tableID).html('<tr><td colspan="8" class="text-center text-danger">Error loading data. Please try again.</td></tr>');
                    }
                });
            }

            else if(tabId=="#bal"){
                let table = document.getElementById('balTbleBody');
                
                // Clear the table
                while (table.rows.length > 0) {
                    table.deleteRow(0);
                }

                const url = "/rep-godown-by-item-name/bal";
                const tableID = "#balTbleBody";

                // Helper function to safely access data
                const safeVal = (val) => val ? val : "";

                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        fromDate: fromDate,
                        toDate: toDate,
                        acc_id: acc_id,
                    },
                    beforeSend: function() {
                        $(tableID).html('<tr><td colspan="8" class="text-center">Loading Data Please Wait...</td></tr>');
                    },
                    success: function (result) {
                        $('#bal_from').text(formattedfromDate);
                        $('#bal_to').text(formattedtoDate);

                        const selectedAcc = $('#acc_id').find("option:selected").text();
                        $('#bal_acc').text(selectedAcc);

                        $(tableID).empty(); // Clear the loading message

                        // Populate the table with new data
                        $.each(result, function (k, v) {
                            let html = `<tr>
                                <td>${k + 1}</td>
                                <td>${safeVal(v['Sal_inv_no'])}</td>
                                <td>${v['sa_date'] ? moment(v['sa_date']).format('DD-MM-YYYY') : ""}</td>
                                <td>${safeVal(v['reason'])}</td>
                                <td>${safeVal(v['remarks'])}</td>
                                <td>${safeVal(v['pc_add'])}</td>
                                <td>${safeVal(v['pc_less'])}</td>
                            </tr>`;
                            $(tableID).append(html);
                        });
                    },
                    error: function () {
                        $(tableID).html('<tr><td colspan="8" class="text-center text-danger">Error loading data. Please try again.</td></tr>');
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

        function downloadExcel(tabName){
            fromDate=$('#fromDate').val();
            toDate=$('#toDate').val();
            acc_id=$('#acc_id').val();

            if (tabName === "SI") {
                window.location.href = `/rep-godown-by-item-name/si/excel?fromDate=${fromDate}&toDate=${toDate}&acc_id=${acc_id}`;
            }
        }

        function printPDF(tabName){
            fromDate=$('#fromDate').val();
            toDate=$('#toDate').val();
            acc_id=$('#acc_id').val();

            if (tabName === "si") {
                // window.open(`/rep-godown-by-item-name/si/PDF?fromDate=${fromDate}&toDate=${toDate}&acc_id=${acc_id}`, '_blank');
                window.location.href = `/rep-godown-by-item-name/si?outputType=view&fromDate=${fromDate}&toDate=${toDate}&acc_id=${acc_id}`;

            }

        }

        function downloadPDF(tabName){
            fromDate=$('#fromDate').val();
            toDate=$('#toDate').val();
            acc_id=$('#acc_id').val();

            if (tabName === "si") {
                window.location.href = `/rep-godown-by-item-name/si?outputType=download&fromDate=${fromDate}&toDate=${toDate}&acc_id=${acc_id}`;
            }

        }

        // function printPDF(tabName) {
        //     fromDate=$('#fromDate').val();
        //     toDate=$('#toDate').val();
        //     acc_id=$('#acc_id').val();

        //     if (tabName === "si") {
        //         // Redirect to download the PDF (you might want to change this based on your server logic)
        //         window.location.href = `/rep-godown-by-item-name/si?outputType=view&fromDate=${fromDate}&toDate=${toDate}&acc_id=${acc_id}`;
        //     }
        // }

        // function downloadPDF(tabName) {
            
        //     fromDate=$('#fromDate').val();
        //     toDate=$('#toDate').val();
        //     acc_id=$('#acc_id').val();

        //     if (tabName === "si") {
        //         // Redirect to view the report (not for downloading)
        //         window.location.href = `/rep-godown-by-item-name/si?outputType=download&fromDate=${fromDate}&toDate=${toDate}&acc_id=${acc_id}`;
        //     }
        // }

    </script>
</html>