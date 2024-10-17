@include('../layouts.header')
	<body>
		<section class="body">
            @include('layouts.homepageheader')
			<div class="inner-wrapper cust-pad">
				@include('layouts.leftmenu')
				<section role="main" class="content-body">
                    <div class="row">
                        <div class="col">
                            <section class="card">
                               	<div class="tabs">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item active">
                                            <a class="nav-link" data-bs-target="#GL" href="#GL" data-bs-toggle="tab">General Ledger</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-target="#GL_R" href="#GL_R" data-bs-toggle="tab">General Ledger R</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-target="#pur_rep" href="#pur_rep" data-bs-toggle="tab">Pur Report</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-target="#age" href="#age" data-bs-toggle="tab">Ageing</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-target="#ann_sal" href="#ann_sal" data-bs-toggle="tab">Annual Sale</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-target="#GL1" href="#GL1" data-bs-toggle="tab">General Ledger 1</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-target="#GL_R1" href="#GL_R1" data-bs-toggle="tab">General Ledger R 1</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-target="#sal_rep" href="#sal_rep" data-bs-toggle="tab">Sale Report</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-target="#JV" href="#JV" data-bs-toggle="tab">JV</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-target="#ann_rep" href="#ann_rep" data-bs-toggle="tab">Annual Pur</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="GL" class="tab-pane active">
                                            <div class="row form-group pb-3">
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label class="col-form-label">From</label>
                                                        <input type="date" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label class="col-form-label" >To</label>
                                                        <input type="date" class="form-control" >
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label class="col-form-label">Account Name</label>
                                                        <input type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label style="display:block" class="col-form-label">Action</label>
                                                        <a class="mb-1 mt-1 me-1 btn btn-danger"><i class="fa fa-filter"></i> Filter</a>
                                                        <!-- <a class="btn btn-primary"><i class="bx bx-refresh"></i></a> -->
                                                    </div>
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
                    </div>
                </section>		
			</div>
		</section>


        @include('../layouts.footerlinks')
	</body>
</html>