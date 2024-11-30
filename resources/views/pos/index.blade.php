@include('../layouts.header')
	<body>
		<section class="body">
		    @include('../layouts.pageheader')
            <div class="inner-wrapper cust-pad">
                <section role="main" class="content-body" style="margin:0px">
                <div class="row">
                    <div class="col-12 mb-3">								
                        <section class="card">
                            <header class="card-header" style="display: flex;justify-content: space-between;">
                                <h2 class="card-title">New Invoice</h2>
                                <div class="card-actions">
                                    <button type="button" class="btn btn-primary" onclick="addNewRow_btn()"> <i class="fas fa-plus"></i> Add New Item </button>
                                </div>
                            </header>
                            <div class="card-body">
                                <div class="row form-group mb-2">
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