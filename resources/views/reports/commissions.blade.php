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
                                <select data-plugin-selecttwo class="form-control select2-js" id="acc_id">
                                    <option value="" disabled selected>Item Group</option>
                                    @foreach($item_group as $key => $row)	
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
                                <a class="nav-link nav-link-rep" data-bs-target="#Comm" href="#Comm" data-bs-toggle="tab">Commissions</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="Comm" class="tab-pane">
                                <div class="row form-group pb-3">
                                    <div class="col-lg-6 ">
                                        <div class="bill-to">
                                            <h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold" style="display: flex; align-items: center;">
                                                <span style="color: #17365D;">From: &nbsp;</span>
                                                <span style="font-weight: 400; color: black;" id="comm_from"></span>
                                            
                                                <span style="flex: 0.3;"></span> <!-- Spacer to push the "To" to the right -->
                                            
                                                <span style="color: #17365D;">To: &nbsp;</span>
                                                <span style="font-weight: 400; color: black;" id="comm_to"></span>
                                            </h4>
                                                                
                                            <h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
                                                <span style="color:#17365D;font-size:20px;">Item Group: &nbsp;</span>
                                                <span style="font-weight:400; color:rgb(238, 19, 19);font-size:20px;" id="comm_acc"></span>
                                            </h4>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6 text-end">
                                        <a class="mb-1 mt-1 me-1 btn btn-warning" aria-label="Download" onclick="downloadPDF('comm')"><i class="fa fa-download"></i> Download</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-danger" aria-label="Print PDF" onclick="printPDF('comm')"><i class="fa fa-file-pdf"></i> Print PDF</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-success" aria-label="Export to Excel" onclick="downloadExcel('comm')"><i class="fa fa-file-excel"></i> Excel</a>   
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
                                            <tbody id="CommTbleBody">
                                                    
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
            fromDate = $('#fromDate').val();
            toDate = $('#toDate').val();
            acc_id = $('#acc_id').val();

            const formattedfromDate = moment(fromDate).format('DD-MM-YYYY'); // Format the date
            const formattedtoDate = moment(toDate).format('DD-MM-YYYY'); // Format the date

            if (tabId == "#Comm") {
                var table = document.getElementById('CommTbleBody');
                while (table.rows.length > 0) {
                    table.deleteRow(0);
                }
                url = "/rep-comm/comm";
                tableID = "#CommTbleBody";

                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        fromDate: fromDate,
                        toDate: toDate,
                        acc_id: acc_id,
                    },
                    success: function(result) {
                        $('#comm_from').text(formattedfromDate);
                        $('#comm_to').text(formattedtoDate);
                        var selectedAcc = $('#acc_id').find("option:selected").text();
                        $('#comm_acc').text(selectedAcc);

                        $(tableID).empty(); // Clear the loading message

                        var lastAcName = null; // Track the last account name to group rows
                        var totalBAmount = 0;  // Total for B_amount
                        var totalCommDisc = 0;  // Total for commission discount
                        var totalCdDisc = 0;  // Total for cd_disc
                        var rowCounter = 1; // Initialize row counter for each account

                        $.each(result, function(k, v) {
                            var html = "";

                            // Check if the account name has changed and insert a group header
                            if (v['ac_name'] !== lastAcName) {
                                // Add a header row for this new account name
                                if (lastAcName !== null) {
                                    // Add a subtotal for the previous account
                                    var subtotalHtml = "<tr><td colspan='4' style='text-align: center;'><strong>Subtotal for " + lastAcName + "</strong></td>";
                                    subtotalHtml += "<td>" + totalBAmount.toFixed(0) + "</td>";
                                    subtotalHtml += "<td></td>";  // Empty cell for this column
                                    subtotalHtml += "<td>" + totalCommDisc.toFixed(0) + "</td>";
                                    subtotalHtml += "<td></td>";  // Empty cell for this column
                                    subtotalHtml += "<td>" + totalCdDisc.toFixed(0) + "</td>";
                                    subtotalHtml += "</tr>";
                                    $(tableID).append(subtotalHtml); // Append subtotal row

                                    // Reset totals for next account
                                    totalBAmount = 0;
                                    totalCommDisc = 0;
                                    totalCdDisc = 0;
                                }

                                html += "<tr><td colspan='9' style='background-color: #cfe8e3;text-align: center;'>" + (v['ac_name'] ? v['ac_name'] : "No Account Name") + "</td></tr>";
                                lastAcName = v['ac_name']; // Update last account name
                                rowCounter = 1; // Reset row counter for this new account group
                            }

                            var bAmount = v['B_amount'] ? v['B_amount'] : 0;
                            var commDisc = (bAmount * v['comm_disc']) / 100 || 0;
                            var cdDisc = (bAmount * 1.182 * v['cd_disc']) / 118 || 0;

                            // Add the data row under the current account name group
                            html += "<tr>";
                            html += "<td>" + rowCounter++ + "</td>";  // Use rowCounter and then increment
                            html += "<td>" + (v['sa_date'] ? moment(v['sa_date']).format('DD-MM-YYYY') : "") + "</td>";
                            html += "<td>" + (v['Sale_inv_no'] ? v['Sale_inv_no'] : "") + "</td>";
                            html += "<td>" + (v['pur_ord_no'] ? v['pur_ord_no'] : "") + "</td>";
                            html += "<td>" + bAmount + "</td>";
                            html += "<td>" + (v['comm_disc'] ? v['comm_disc'] : "") + "</td>";
                            html += "<td>" + commDisc + "</td>";
                            html += "<td>" + (v['cd_disc'] ? v['cd_disc'] : "") + "</td>";
                            html += "<td>" + cdDisc + "</td>";
                            html += "</tr>";

                            // Update totals
                            totalBAmount += bAmount;
                            totalCommDisc += commDisc;
                            totalCdDisc += cdDisc;

                            $(tableID).append(html); // Append data row
                        });

                        // Add subtotal for the last account after the loop
                        if (lastAcName !== null) {
                            var subtotalHtml = "<tr><td colspan='4' style='text-align: center;'><strong>Subtotal for " + lastAcName + "</strong></td>";
                            subtotalHtml += "<td>" + totalBAmount.toFixed(0) + "</td>";
                            subtotalHtml += "<td></td>";  // Empty cell for this column
                            subtotalHtml += "<td>" + totalCommDisc.toFixed(0) + "</td>";
                            subtotalHtml += "<td></td>";  // Empty cell for this column
                            subtotalHtml += "<td>" + totalCdDisc.toFixed(0) + "</td>";
                            subtotalHtml += "</tr>";
                            $(tableID).append(subtotalHtml); // Append last subtotal row
                        }
                    },
                    error: function() {
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