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
                                <input type="date" class="form-control" value="<?php echo $previousDate; ?>">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="col-form-label" >To</label>
                                <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="col-form-label">Account Name</label>
                                <select data-plugin-selecttwo class="form-control select2-js"  id="company_name" required>
                                    <option value="" disabled selected>Select Account</option>
                                    @foreach($coa as $key => $row)	
                                        <option value="{{$row->ac_code}}">{{$row->ac_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>   
                    <div class="tabs">
                        <ul class="nav nav-tabs">
                            <li class="nav-item active">
                                <a class="nav-link" data-bs-target="#GL" href="#GL" data-bs-toggle="tab">General Ledger</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#GL_R" href="#GL_R" data-bs-toggle="tab">General Ledger R</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#age" href="#age" data-bs-toggle="tab">Sales Ageing</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#age" href="#age" data-bs-toggle="tab">Purchase Ageing</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#pur_rep" href="#pur_rep" data-bs-toggle="tab">Sale 1</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#pur_rep" href="#pur_rep" data-bs-toggle="tab">Sale 2</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#pur_rep" href="#pur_rep" data-bs-toggle="tab">Combine Sale</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#pur_rep" href="#pur_rep" data-bs-toggle="tab">Purchase 1</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#pur_rep" href="#pur_rep" data-bs-toggle="tab">Purchase 2</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#pur_rep" href="#pur_rep" data-bs-toggle="tab">Combine Purchase</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#JV" href="#JV" data-bs-toggle="tab">Vouchers</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#ann_sal" href="#ann_sal" data-bs-toggle="tab">Sale Return</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-target="#ann_rep" href="#ann_rep" data-bs-toggle="tab">Purchase Return</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="GL" class="tab-pane active">
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
                                                    <th>Rendering engine</th>
                                                    <th>Browser</th>
                                                    <th>Platform(s)</th>
                                                    <th>Browser</th>
                                                    <th>Platform(s)</th>
                                                    <th>Browser</th>
                                                    <th>Platform(s)</th>
                                                    <th>Engine version</th>
                                                    <th>CSS grade</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="GL_R" class="tab-pane">
                                <p>GL_R</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.</p>
                            </div>
                            <div id="pur_rep" class="tab-pane">
                                <p>Purchase Report</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.</p>
                            </div>
                            <div id="age" class="tab-pane">
                                <p>Ageing</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.</p>
                            </div>
                            <div id="ann_sal" class="tab-pane">
                                <p>Anuual Sale</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.</p>
                            </div>
                            <div id="GL1" class="tab-pane">
                                <p>GL1</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.</p>
                            </div>
                            <div id="GL_R1" class="tab-pane">
                                <p>GL_R1</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.</p>
                            </div>
                            <div id="sal_rep" class="tab-pane">
                                <p>Sale Report</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.</p>
                            </div>
                            <div id="JV" class="tab-pane">
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
</html>