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
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="col-form-label"><strong>Account Group</strong></label>
                                                <select data-plugin-selecttwo class="form-control select2-js" id="ag_acc_id">
                                                    <option value="" disabled selected>Account Group</option>
                                                    @foreach($ac_group as $key => $row)	
                                                        <option value="{{$row->group_cod}}">{{$row->group_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <a class="btn btn-primary" style="margin-top: 2.1rem;padding: 0.5rem 0.6rem;" onclick="tabChanged('#AG')"><i class="fa fa-filter"></i></a>
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
                                                    <th>AC</th>
                                                    <th>AC Name</th>
                                                    <th>Address</th>
                                                    <th>Phone</th>
                                                    <th>Debit</th>
                                                    <th>Credit</th>
                                                </tr>
                                            </thead>
                                            <tbody id="AGTbleBody">
                                                    
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div id="SHOA" class="tab-pane">
                                <div class="row form-group pb-3">
                                    <div class="col-lg-8 row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="col-form-label"><strong>Sub Head Of Account</strong></label>
                                                <select data-plugin-selecttwo class="form-control select2-js" id="shoa_acc_id">
                                                    <option value="" disabled selected>Sub Head Of Account</option>
                                                    @foreach($sub_head_of_acc as $key => $row)	
                                                        <option value="{{$row->id}}">{{$row->sub}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <a class="btn btn-primary" style="margin-top: 2.1rem;padding: 0.5rem 0.6rem;" onclick="tabChanged('#SHOA')"><i class="fa fa-filter"></i></a>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4 text-end">
                                        <a class="mb-1 mt-1 me-1 btn btn-warning" aria-label="Download" onclick="downloadPDF('SHOA')"><i class="fa fa-download"></i> Download</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-danger" aria-label="Print PDF" onclick="printPDF('SHOA')"><i class="fa fa-file-pdf"></i> Print PDF</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-success" aria-label="Export to Excel" onclick="downloadExcel('SHOA')"><i class="fa fa-file-excel"></i> Excel</a>   
                                    </div>
                                    
                                    <div class="col-12 mt-4">
                                        <table class="table table-bordered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>S/No</th>
                                                    <th>AC</th>
                                                    <th>AC Name</th>
                                                    <th>Address</th>
                                                    <th>Debit</th>
                                                    <th>Credit</th>
                                                </tr>
                                            </thead>
                                            <tbody id="SHOATbleBody">
                                                    
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div id="BA" class="tab-pane">
                                <div class="row form-group pb-3">
                                    <div class="col-lg-12 text-end">
                                        <a class="mb-1 mt-1 me-1 btn btn-warning" aria-label="Download" onclick="downloadPDF('BA')"><i class="fa fa-download"></i> Download</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-danger" aria-label="Print PDF" onclick="printPDF('BA')"><i class="fa fa-file-pdf"></i> Print PDF</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-success" aria-label="Export to Excel" onclick="downloadExcel('BA')"><i class="fa fa-file-excel"></i> Excel</a>   
                                    </div>
                                    
                                    <div class="col-12 mt-4">
                                        <table class="table table-bordered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>S/No</th>
                                                    <th>AC</th>
                                                    <th>Account Name</th>
                                                    <th>Address</th>
                                                    <th>Debit</th>
                                                    <th>Credit</th>
                                                </tr>
                                            </thead>
                                            <tbody id="BATbleBody">
                                                    
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
                if(tabId=="#BA"){
                    tabChanged(tabId);
                }
            });
        });

        function tabChanged(tabId) {

            if(tabId=="#AG"){
                var acc_id = $('#ag_acc_id').val()

                if (!acc_id) {
                    alert('Please fill in all required fields.');
                    return;
                }
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
                        acc_id: acc_id,
                    },
                    beforeSend: function() {
                        $(tableID).html('<tr><td colspan="7" class="text-center">Loading Data Please Wait...</td></tr>');
                    },
                    success: function(result){
                        $(tableID).empty(); // Clear the loading message
                        
                        $.each(result, function(k,v){
                            var html="<tr>";
                            html += "<td>"+(k+1)+"</td>"
                            html += "<td>" + (v['ac_code'] ? v['ac_code'] : "") +"</td>";
                            html += "<td>" + (v['ac_name'] ? v['ac_name'] : "") + "</td>";
                            html += "<td>" + (v['address'] ? v['address'] : "") + "</td>";
                            html += "<td>" + (v['phone_no'] ? v['phone_no'] : "") + "</td>";
                            html += "<td>" + (v['Debit'] ? v['Debit'] : "") + "</td>";
                            html += "<td>" + (v['Credit'] ? v['Credit'] : "") + "</td>";
                            html +="</tr>";
                            $(tableID).append(html);
                        });
                    },
                    error: function(){
                        $(tableID).html('<tr><td colspan="7" class="text-center text-danger">Error loading data. Please try again.</td></tr>');
                    }
                });
            }

            if(tabId=="#SHOA"){
                var acc_id = $('#shoa_acc_id').val()

                if (!acc_id) {
                    alert('Please fill in all required fields.');
                    return;
                }

                var table = document.getElementById('SHOATbleBody');
                while (table.rows.length > 0) {
                    table.deleteRow(0);
                }
                url="/rep-by-acc-grp/shoa";
                tableID="#SHOATbleBody";

                $.ajax({
                    type: "GET",
                    url: url,
                    data:{
                        acc_id: acc_id,
                    },
                    beforeSend: function() {
                        $(tableID).html('<tr><td colspan="6" class="text-center">Loading Data Please Wait...</td></tr>');
                    },
                    success: function(result){
                        $(tableID).empty(); // Clear the loading message
                        
                        $.each(result, function(k,v){
                            var html="<tr>";
                            html += "<td>"+(k+1)+"</td>"
                            html += "<td>" + (v['ac_code'] ? v['ac_code'] : "") +"</td>";
                            html += "<td>" + (v['ac_name'] ? v['ac_name'] : "") + "</td>";
                            html += "<td>" + (v['address'] ? v['address'] : "") + "</td>";
                            html += "<td>" + (v['Debit'] ? v['Debit'] : "") + "</td>";
                            html += "<td>" + (v['Credit'] ? v['Credit'] : "") + "</td>";
                            html +="</tr>";
                            $(tableID).append(html);
                        });
                    },
                    error: function(){
                        $(tableID).html('<tr><td colspan="6" class="text-center text-danger">Error loading data. Please try again.</td></tr>');
                    }
                });
            }

            if(tabId=="#BA"){
                var table = document.getElementById('BATbleBody');
                while (table.rows.length > 0) {
                    table.deleteRow(0);
                }
                url="/rep-by-acc-grp/ba";
                tableID="#BATbleBody";

                $.ajax({
                    type: "GET",
                    url: url,
                    beforeSend: function() {
                        $(tableID).html('<tr><td colspan="6" class="text-center">Loading Data Please Wait...</td></tr>');
                    },
                    success: function(result){
                        $(tableID).empty(); // Clear the loading message
                        const groupedData = groupBySub(result);
                        Object.keys(groupedData).forEach(sub => {
                            const subData = groupedData[sub];
                            // Loop through each head (e.g., Assets, Liabilities) and render corresponding rows
                            Object.keys(subData).forEach(head => {
                                const items = subData[head];
                                // Render each item under the current head
                                items.forEach(item => {
                                    // console.log(item);
                                });
                            });
                        });
                    },
                    error: function(){
                        $(tableID).html('<tr><td colspan="6" class="text-center text-danger">Error loading data. Please try again.</td></tr>');
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

        function groupByHeadAndSub(data) {
            const groupedData = {};
            console.log(data);

            // Loop through all available heads (keys in the data object)
            Object.keys(data).forEach(head => {
                groupedData[head] = {}; // Initialize the head category

                // Loop through each item under the current head (Assets or Liabilities, etc.)
                data[head].forEach(item => {
                    const sub = item.sub; // Get the subhead from each item
                    if (!groupedData[head][sub]) {
                        groupedData[head][sub] = []; // Initialize subhead if it doesn't exist
                    }
                    // Push the item into the appropriate subhead category under the head
                    groupedData[head][sub].push(item);
                });
            });

            return groupedData;
        }
    </script>
</html>