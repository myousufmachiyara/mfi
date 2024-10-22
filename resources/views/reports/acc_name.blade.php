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
                                <label class="col-form-label">From</label>
                                <input type="date" class="form-control" id="fromDate" value="<?php echo $previousDate; ?>">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="col-form-label" >To</label>
                                <input type="date" class="form-control" id="toDate" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="col-form-label">Account Name</label>
                                <select data-plugin-selecttwo class="form-control select2-js"  id="acc_id">
                                    <option value="" disabled selected>Select Account</option>
                                    @foreach($coa as $key => $row)	
                                        <option value="{{$row->ac_code}}">{{$row->ac_name}}</option>
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
                                <a class="nav-link" data-bs-target="#GL" href="#GL" data-bs-toggle="tab">General Ledger</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#GL_R" href="#GL_R" data-bs-toggle="tab">General Ledger R</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#sale_age" href="#sale_age" data-bs-toggle="tab">Sales Ageing</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#pur_age" href="#pur_age" data-bs-toggle="tab">Purchase Ageing</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#sale_1" href="#sale_1" data-bs-toggle="tab">Sale 1</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#sale_2" href="#sale_2" data-bs-toggle="tab">Sale 2</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#comb_sale" href="#comb_sale" data-bs-toggle="tab">Combine Sale</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#purchase_1" href="#purchase_1" data-bs-toggle="tab">Purchase 1</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#purchase_2" href="#purchase_2" data-bs-toggle="tab">Purchase 2</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#comb_purchase" href="#comb_purchase" data-bs-toggle="tab">Combine Purchase</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#JV" href="#JV" data-bs-toggle="tab">Vouchers</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#sal_ret" href="#sal_ret" data-bs-toggle="tab">Sale Return</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#pur_ret" href="#pur_ret" data-bs-toggle="tab">Purchase Return</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="GL" class="tab-pane">
                               
                            </div>
                            <div id="GL_R" class="tab-pane">
                                <p>GL_R</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.</p>
                            </div>
                            <div id="sale_age" class="tab-pane">
                                <p>Purchase Report</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.</p>
                            </div>
                            <div id="pur_age" class="tab-pane">
                                <p>Ageing</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.</p>
                            </div>
                            <div id="sale_1" class="tab-pane">
                                <p>Anuual Sale</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.</p>
                            </div>
                            <div id="sale_2" class="tab-pane">
                                <p>GL1</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.</p>
                            </div>
                            <div id="comb_sale" class="tab-pane">
                                <p>GL_R1</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.</p>
                            </div>
                            <div id="purchase_1" class="tab-pane">
                                <div class="row form-group pb-3">
                                    <div class="col-lg-12 text-end">
                                        <a class="mb-1 mt-1 me-1 btn btn-warning"><i class="fa fa-download"></i> Download</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-danger"><i class="fa fa-file-pdf"></i> Print PDF</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-success"><i class="fa fa-file-excel"></i> Excel</a>   
                                    </div>
                                    <div class="col-12 mt-4">
                                        <table class="table table-bordered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Date</th>
                                                    <th>Item Name</th>
                                                    <th>Remarks</th>
                                                    <th>Weight</th>
                                                    <th>Price</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody id="P1TbleBody">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="purchase_2" class="tab-pane">
                                <div class="row form-group pb-3">
                                    <div class="col-lg-12 text-end">
                                        <a class="mb-1 mt-1 me-1 btn btn-warning"><i class="fa fa-download"></i> Download</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-danger"><i class="fa fa-file-pdf"></i> Print PDF</a>
                                        <a class="mb-1 mt-1 me-1 btn btn-success"><i class="fa fa-file-excel"></i> Excel</a>   
                                    </div>
                                    <div class="col-12 mt-4">
                                        <table class="table table-bordered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Date</th>
                                                    <th>Item Name</th>
                                                    <th>Remarks</th>
                                                    <th>Weight</th>
                                                    <th>Price</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody id="P2TbleBody">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="comb_purchase" class="tab-pane">
                                <p>JV</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.</p>
                            </div>
                            <div id="JV" class="tab-pane">
                                <p>JV</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.</p>
                            </div>
                            <div id="#sal_ret" class="tab-pane">
                                <p>JV</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.</p>
                            </div>
                            <div id="#pur_ret" class="tab-pane">
                                <p>JV</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.</p>
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
            console.log(tabId);
            fromDate=$('#fromDate').val();
            toDate=$('#toDate').val();
            acc_id=$('#acc_id').val();

            if(tabId=="#GL"){
            }
            else if(tabId=="#GL_R"){
            }
            else if(tabId=="#sale_age"){
            }
            else if(tabId=="#pur_age"){
            }
            else if(tabId=="#sale_1"){
            }
            else if(tabId=="#sale_2"){
            }
            else if(tabId=="#comb_sale"){
            }
            else if(tabId=="#purchase_1"){
                var table = document.getElementById('P1TbleBody');
                while (table.rows.length > 0) {
                    table.deleteRow(0);
                }
                url="/rep-by-acc-name/pur1";
                tableID="#P1TbleBody";

                $.ajax({
                    type: "GET",
                    url: url,
                    data:{
                        fromDate: fromDate,
                        toDate: toDate,
                        acc_id:acc_id,
                    }, 
                    success: function(result){
                        $.each(result, function(k,v){
                            var html="<tr>";
                            html+= "<td>"+v['pur_id']+"</td>"
                            html+= "<td>"+v['pur_date']+"</td>"
                            html+= "<td>"+v['item_cod']+"</td>"
                            html+= "<td>"+v['remarks']+"</td>"
                            html+= "<td>"+v['pur_qty']+"</td>"
                            html+= "<td>"+v['pur_price']+"</td>"
                            html+= "<td>"+v['pur_price']+"</td>"
                            html+="</tr>";
                            $(tableID).append(html);
                        });
                    },
                    error: function(){
                        alert("error");
                    }
                });
            }
            else if(tabId=="#purchase_2"){
                var table = document.getElementById('P2TbleBody');
                while (table.rows.length > 0) {
                    table.deleteRow(0);
                }
                url="/rep-by-acc-name/pur2";
                tableID="#P2TbleBody";

                $.ajax({
                    type: "GET",
                    url: url,
                    data:{
                        fromDate: fromDate,
                        toDate: toDate,
                        acc_id:acc_id,
                    }, 
                    success: function(result){
                        $.each(result, function(k,v){
                            var html="<tr>";
                            html+= "<td>"+v['pur_id']+"</td>"
                            html+= "<td>"+v['date']+"</td>"
                            html+= "<td>"+v['item_cod']+"</td>"
                            html+= "<td>"+v['remarks']+"</td>"
                            html+= "<td>"+v['pur_qty']+"</td>"
                            html+= "<td>"+v['pur_price']+"</td>"
                            html+= "<td>"+v['pur_price']+"</td>"
                            html+="</tr>";
                            $(tableID).append(html);
                        });
                    },
                    error: function(){
                        alert("error");
                    }
                });
            }
            else if(tabId=="#comb_purchase"){
            }
            else if(tabId=="#JV"){
            }
            else if(tabId=="#sal_ret"){
            }
            else if(tabId=="#pur_ret"){
            }
        }
    </script>
</html>