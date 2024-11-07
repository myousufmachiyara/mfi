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
                                <label class="col-form-label"><strong>Item Group</strong></label>
                                <select data-plugin-selecttwo class="form-control select2-js"  id="acc_id">
                                    <option value="" disabled selected>Item Group</option>
                                    @foreach($items as $key => $row)	
                                        <option value="{{$row->item_group_cod}}">{{$row->group_name}}</option>
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
                                <a class="nav-link nav-link-rep" data-bs-target="#SA" href="#SA" data-bs-toggle="tab">Stock All</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link nav-link-rep" data-bs-target="#SI" href="#SI" data-bs-toggle="tab">Stock In</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link nav-link-rep" data-bs-target="#SO" href="#SO" data-bs-toggle="tab">Stock Out</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link nav-link-rep" data-bs-target="#SAT" href="#SAT" data-bs-toggle="tab">Stock All Tabuller</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="SA" class="tab-pane">
                                <div class="row form-group pb-3">
                                    <div class="col-lg-6 ">
                                        <div class="bill-to">
                                            <h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold" style="display: flex; align-items: center;">
                                                <span style="color: #17365D;">From: &nbsp;</span>
                                                <span style="font-weight: 400; color: black;" id="sa_from"></span>
                                            
                                                <span style="flex: 0.3;"></span> <!-- Spacer to push the "To" to the right -->
                                            
                                                <span style="color: #17365D;">To: &nbsp;</span>
                                                <span style="font-weight: 400; color: black;" id="sa_to"></span>
                                            </h4>
                                                                
                                            <h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
                                                <span style="color:#17365D">Account Name: &nbsp;</span>
                                                <span style="font-weight:400; color:black;" id="sa_acc"></span>
                                            </h4>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6 text-end">
                                        <a class="mb-1 mt-1 me-1 btn btn-warning" aria-label="Download" onclick="downloadPDF('sa')"><i class="fa fa-download"></i> Download</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-danger" aria-label="Print PDF" onclick="printPDF('sa')"><i class="fa fa-file-pdf"></i> Print PDF</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-success" aria-label="Export to Excel" onclick="downloadExcel('sa')"><i class="fa fa-file-excel"></i> Excel</a>   
                                    </div>
                                    
                                    <div class="col-12 mt-4">
                                        <table class="table table-bordered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>S/No</th>
                                                    <th>Item Name</th>
                                                    <th>Remarks</th>
                                                    <th>Qty. in Hand</th>
                                                    <th>Wg. in Hand</th>
                                                </tr>
                                            </thead>
                                            <tbody id="SATbleBody">
                                                    
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
                                        <a class="mb-1 mt-1 me-1 btn btn-warning" aria-label="Download" onclick="downloadPDF('SI')">
                                            <i class="fa fa-download"></i> Download
                                        </a>
                                        <a class="mb-1 mt-1 me-1 btn btn-danger" aria-label="Print PDF" onclick="printPDF('SI')">
                                            <i class="fa fa-file-pdf"></i> Print PDF
                                        </a>
                                           <a class="mb-1 mt-1 me-1 btn btn-success" aria-label="Export to Excel" onclick="downloadExcel('SI')">
                                            <i class="fa fa-file-excel"></i> Excel</a>   
                                    </div>
                                    
                                    <div class="col-12 mt-4">
                                        <table class="table table-bordered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>S/No</th>
                                                    <th>Gate Pass#</th>
                                                    <th>Item Name</th>
                                                    <th>Party Name</th>
                                                    <th>Quantity</th>
                                                    <th>Weight</th>
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
                                        <a class="mb-1 mt-1 me-1 btn btn-warning" aria-label="Download" onclick="downloadPDF('SO')">
                                            <i class="fa fa-download"></i> Download
                                        </a>
                                        <a class="mb-1 mt-1 me-1 btn btn-danger" aria-label="Print PDF" onclick="printPDF('SO')">
                                            <i class="fa fa-file-pdf"></i> Print PDF
                                        </a>
                                           <a class="mb-1 mt-1 me-1 btn btn-success" aria-label="Export to Excel" onclick="downloadExcel('SO ')">
                                            <i class="fa fa-file-excel"></i> Excel</a>   
                                    </div>
                                    
                                    <div class="col-12 mt-4">
                                        <table class="table table-bordered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>S/No</th>
                                                    <th>Gate Pass#</th>
                                                    <th>Item Name</th>
                                                    <th>Party Name</th>
                                                    <th>Quantity</th>
                                                    <th>Weight</th>
                                                </tr>
                                            </thead>
                                            <tbody id="SOTbleBody">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="SAT" class="tab-pane">
                                <div class="row form-group pb-3">
                                    <div class="col-lg-6 ">
                                        <div class="bill-to">
                                            <h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold" style="display: flex; align-items: center;">
                                                <span style="color: #17365D;">From: &nbsp;</span>
                                                <span style="font-weight: 400; color: black;" id="SAT_from"></span>
                                            
                                                <span style="flex: 0.3;"></span> <!-- Spacer to push the "To" to the right -->
                                            
                                                <span style="color: #17365D;">To: &nbsp;</span>
                                                <span style="font-weight: 400; color: black;" id="SAT_to"></span>
                                            </h4>
                                            
                                    
                                            <h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
                                                <span style="color:#17365D">Item Name: &nbsp;</span>
                                                <span style="font-weight:400; color:black;" id="SAT_acc"></span>
                                            </h4>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6 text-end">
                                        <a class="mb-1 mt-1 me-1 btn btn-warning" aria-label="Download" onclick="downloadPDF('SAT')"><i class="fa fa-download"></i> Download</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-danger" aria-label="Print PDF" onclick="printPDF('SAT')"><i class="fa fa-file-pdf"></i> Print PDF</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-success" aria-label="Export to Excel" onclick="downloadExcel('SAT')"><i class="fa fa-file-excel"></i> Excel</a>   
                                    </div>
                                    
                                    <div class="col-12 mt-4">
                                        <table class="table table-bordered table-striped mb-0" id="TSAThead">
                                            <thead>
                                                <tr>
                                                    <th>Item Name</th>
                                                    <th id="12G">12G</th>
                                                    <th id="14G">14G</th>
                                                    <th id="16G">16G</th>
                                                    <th id="1.5">1.5</th>
                                                    <th id="18G">18G</th>
                                                    <th id="1.10">1.10</th>
                                                    <th id="19G">19G</th>
                                                    <th id="20G">20G</th>
                                                    <th id="21G">21G</th>
                                                    <th id="22G">22G</th>
                                                    <th id="23G">23G</th>
                                                    <th id="24G">24G</th>
                                                </tr>
                                            </thead>
                                            <tbody id="SATTble">
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

            if(tabId=="#SA"){
                var table = document.getElementById('SATbleBody');
                while (table.rows.length > 0) {
                    table.deleteRow(0);
                }
                url="/rep-godown-by-item-grp/sa";
                tableID="#SATbleBody";

                $.ajax({
                    type: "GET",
                    url: url,
                    data:{
                        fromDate: fromDate,
                        toDate: toDate,
                        acc_id: acc_id,
                    }, 
                    success: function(result){
                        $('#sa_from').text(formattedfromDate);
                        $('#sa_to').text(formattedtoDate);
                        var selectedAcc = $('#acc_id').find("option:selected").text();
                        $('#sa_acc').text(selectedAcc);

                        $(tableID).empty(); // Clear the loading message

                        $.each(result, function(k,v){
                            var html="<tr>";
                            html += "<td>"+(k+1)+"</td>"
                            html += "<td>" + (v['item_name'] ? v['item_name'] : "") +"</td>";
                            html += "<td>" + (v['item_remarks'] ? v['item_remarks'] : "") + "</td>";
                            html += "<td>" + (v['opp_bal'] ? v['opp_bal'] : "") + "</td>";
                            html += "<td>" + (v['wt'] ? v['wt'] : "") + "</td>";
                            html +="</tr>";
                            $(tableID).append(html);
                        });
                    },
                    error: function(){
                        alert("error");
                    }
                });
            }
            
            else if(tabId=="#SI"){
                var table = document.getElementById('SITbleBody');
                while (table.rows.length > 0) {
                    table.deleteRow(0);
                }
                url="/rep-godown-by-item-grp/si";
                tableID="#SITbleBody";

                $.ajax({
                    type: "GET",
                    url: url,
                    data:{
                        fromDate: fromDate,
                        toDate: toDate,
                        acc_id:acc_id,
                    }, 
                    beforeSend: function() {
                        $(tableID).html('<tr><td colspan="9" class="text-center">Loading Data Please Wait...</td></tr>');
                    }, 
                    success: function(result){
                        $('#si_from').text(formattedfromDate);
                        $('#si_to').text(formattedtoDate);
                        var selectedAcc = $('#acc_id').find("option:selected").text();
                        $('#si_acc').text(selectedAcc);

                        $(tableID).empty(); // Clear the loading message

                        
                        $.each(result, function(k,v){
                            var html="<tr>";
                            html += "<td>"+(k+1)+"</td>"
                            html += "<td>" + (v[''] ? v[''] : "") + "</td>";
                            html += "<td>" + (v['item_name'] ? v['item_name'] : "") +"</td>";
                            html += "<td>" + (v['ac_name'] ? v['ac_name'] : "") + "</td>";
                            html += "<td>" + (v['wt'] ? v['wt'] : "") + "</td>";
                            html += "<td>" + (v['Sales_qty'] ? v['Sales_qty'] : "") + "</td>";
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

                const url = "/rep-godown-by-item-grp/so";
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
                        $.each(result, function(k,v){
                            var html="<tr>";
                            html += "<td>"+(k+1)+"</td>"
                            html += "<td>" + (v[''] ? v[''] : "") + "</td>";
                            html += "<td>" + (v['item_name'] ? v['item_name'] : "") +"</td>";
                            html += "<td>" + (v['ac_name'] ? v['ac_name'] : "") + "</td>";
                            html += "<td>" + (v['wt'] ? v['wt'] : "") + "</td>";
                            html += "<td>" + (v['Sales_qty'] ? v['Sales_qty'] : "") + "</td>";
                            html +="</tr>";
                            $(tableID).append(html);
                        });
                    },
                    error: function () {
                        $(tableID).html('<tr><td colspan="8" class="text-center text-danger">Error loading data. Please try again.</td></tr>');
                    }
                });
            }

            else if(tabId=="#SAT"){
                let table = document.getElementById('SATTble');
                
                // Clear the table
                while (table.rows.length > 0) {
                    table.deleteRow(0);
                }

                const url = "/rep-godown-by-item-grp/sa";
                const tableID = "#SATTble";

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
                        $('#SAT_from').text(formattedfromDate);
                        $('#SAT_to').text(formattedtoDate);

                        const selectedAcc = $('#acc_id').find("option:selected").text();
                        $('#SAT_acc').text(selectedAcc);

                        $(tableID).empty(); // Clear the loading message

                        // Step 1: Break item_name into 3 chunks based on space
                        const processedData = result.map(item => {
                            const itemChunks = item.item_name.split(' ');
                            const item_group = itemChunks[0] || '';   // First chunk (before the first space)
                            const item_gauge = itemChunks[1] || '';   // Second chunk (between the first and second space)
                            const item_name = itemChunks.slice(2).join(' ') || ''; // Everything after the second space

                            return {
                                ...item,
                                item_group: item_group,
                                item_mm: item_gauge,
                                item_name: item_name,
                                // item_qty: item.opp_bal
                            };
                        });

                        // Step 2: Group the items under the third chunk value
                        const groupedByChunk3 = processedData.reduce((acc, item) => {
                            const item_name = item.item_name;

                            // If a group for this item_name doesn't exist, create an empty array
                            if (!acc[item_name]) {
                                acc[item_name] = [];
                            }

                            // Push the item into the corresponding group under the third chunk
                            acc[item_name].push(item);

                            return acc;
                        }, {});
                        $.each(groupedByChunk3, function(k,v){

                            var html="<tr>";
                            html += "<td>"+ (k ? k : "") +"</td>";

                            let table1 = document.getElementById('TSAThead');

                            // Get the first row (<tr>) inside the <thead> section
                            let firstRow = table1.querySelector('thead tr'); 

                            // Check if the first row exists, and then count the number of <th> elements (columns)
                            let columnCount = firstRow ? firstRow.cells.length : 0;

                            for(i=0;v.length;i++){
                                console.log(v[i]);
                                // if(v[i]['item_mm']=="12G"){
                                //     // set value in 1st coloum
                                //     html += "<td>"+ (v[i]['opp_bal'] ? v[i]['opp_bal'] : "") +"</td>"
                                // }
                                // else if(v[i]['item_mm']=="14G"){
                                //     // set value in 1st coloum
                                //     html += "<td>"+ (v[i]['opp_bal'] ? v[i]['opp_bal'] : "") +"</td>"
                                // }
                                // else if(v[i]['item_mm']=="16G"){
                                //     // set value in 1st coloum
                                //     html += "<td>"+ (v[i]['opp_bal'] ? v[i]['opp_bal'] : "") +"</td>"
                                // }
                                // else if(v[i]['item_mm']=="1.5"){
                                //     // set value in 1st coloum
                                //     html += "<td>"+ (m['opp_bal'] ? v[i]['opp_bal'] : "") +"</td>"
                                // }
                                // else if(v[i]['item_mm']=="18G"){
                                //     // set value in 1st coloum
                                //     html += "<td>"+ (v[i]['opp_bal'] ? v[i]['opp_bal'] : "") +"</td>"
                                // }
                                // else if(v[i]['item_mm']=="1.10"){
                                //     // set value in 1st coloum
                                //     html += "<td>"+ (v[i]['opp_bal'] ? v[i]['opp_bal'] : "") +"</td>"
                                // }
                                // else if(v[i]['item_mm']=="19G"){
                                //     // set value in 1st coloum
                                //     html += "<td>"+ (v[i]['opp_bal'] ? v[i]['opp_bal'] : "") +"</td>"
                                // }
                                // else if(v[i]['item_mm']=="20G"){
                                //     // set value in 1st coloum
                                //     html += "<td>"+ (v[i]['opp_bal'] ? v[i]['opp_bal'] : "") +"</td>"
                                // }
                                // else if(v[i]['item_mm']=="21G"){
                                //     // set value in 1st coloum
                                //     html += "<td>"+ (v[i]['opp_bal'] ? v[i]['opp_bal'] : "") +"</td>"
                                // }
                                // else if(v[i]['item_mm']=="22G"){
                                //     // set value in 1st coloum
                                //     html += "<td>"+ (v[i]['opp_bal'] ? v[i]['opp_bal'] : "") +"</td>"
                                // }
                                // else if(v[i]['item_mm']=="23G"){
                                //     // set value in 1st coloum
                                //     html += "<td>"+ (v[i]['opp_bal'] ? v[i]['opp_bal'] : "") +"</td>"
                                // }
                                // else if(v[i]['item_mm']=="24G"){
                                //     // set value in 1st coloum
                                //     html += "<td>"+ (v[i]['opp_bal'] ? v[i]['opp_bal'] : "") +"</td>"
                                // }
                                // else{
                                //     html += "<td>-</td>"
                                // }
                            }
                            html +="</tr>";
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
            else if (tabName === "SO") {
                window.open(`/rep-godown-by-item-name/so/report?outputType=view&fromDate=${fromDate}&toDate=${toDate}&acc_id=${acc_id}`, '_blank');

            }
            else if (tabName === "bal") {
                window.open(`/rep-godown-by-item-name/bal/report?outputType=view&fromDate=${fromDate}&toDate=${toDate}&acc_id=${acc_id}`, '_blank');

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
            else if (tabName === "SO") {
                window.location.href = `/rep-godown-by-item-name/so/report?outputType=download&fromDate=${fromDate}&toDate=${toDate}&acc_id=${acc_id}`;
            }
            else if (tabName === "bal") {
                window.location.href = `/rep-godown-by-item-name/bal/report?outputType=download&fromDate=${fromDate}&toDate=${toDate}&acc_id=${acc_id}`;
            }
        }
    </script>
</html>