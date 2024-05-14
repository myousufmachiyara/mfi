@extends('../layouts.header')
	<body>

		<section class="body">
			@extends('../layouts.menu')
			<div class="inner-wrapper">
				<section role="main" class="content-body">
					@extends('../layouts.pageheader')
                    <div class="row">
                        <div class="col">
                            <section class="card">
                                <header class="card-header">
                                    <div class="card-actions">
                                        <button type="button" class="btn btn-primary mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" href="#createModal"> <i class="fas fa-plus" ></i> New Item</button>
                                    </div>
                                    <h2 class="card-title">Items</h2>

                                </header>
                                <div class="card-body">
                                	<table class="table table-bordered table-striped mb-0" id="datatable-default">
                                        <thead>
                                            <tr>
                                                <th width="5%">Code</th>
                                                <th width="15%">Name</th>
                                                <th width="15%">Group</th>
                                                <th width="15%">Remarks</th>
                                                <th width="5%">Qty</th>
                                                <th width="5%">P.Price</th>
                                                <th width="5%">L.Price</th>
                                                <th width="5%">S.Price</th>
                                                <th width="5%">S.Date</th>
                                                <th width="5%">P.Date</th>
                                                <th width="5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $key => $row)
                                                <tr>
                                                    <td>{{$row->it_cod}}</td>
                                                    <td>{{$row->item_name}}</td>
                                                    <td>{{$row->group_name}}</td>
                                                    <td>{{$row->item_remark}}</td>
                                                    <td>{{$row->opp_qty}}</td>
                                                    <td>{{$row->Opp_qty_cost}}</td>
                                                    <td>{{$row->labourprice}}</td>
                                                    <td>{{$row->sales_price}}</td>
                                                    <td>{{$row->opp_date}}</td>
                                                    <td>{{$row->opp_date}}</td>
                                                    <td class="actions">
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="getItemDetails({{$row->it_cod}})" href="#updateModal"><i class="fas fa-pencil-alt"></i></a>
                                                        <a class="mb-1 mt-1 me-1 modal-with-zoom-anim ws-normal" onclick="setId({{$row->it_cod}})" href="#deleteModal"><i class="far fa-trash-alt" style="color:red"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
									</table>
                                </div>
                            </section>
                        </div>
                    </div>
                </section>		
			</div>
		</section>

        <div id="deleteModal" class="zoom-anim-dialog modal-block modal-block-danger mfp-hide">
            <form method="post" action="{{ route('delete-item') }}" enctype="multipart/form-data">
                @csrf
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">Delete Item</h2>
                    </header>
                    <div class="card-body">
                        <div class="modal-wrapper">
                            <div class="modal-icon">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <div class="modal-text">
                                <p class="mb-0">Are you sure that you want to delete this item?</p>
                                <input name="item_id" id="deleteID" hidden>
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

        <div id="updateModal" class="modal-block modal-block-primary mfp-hide">
            <section class="card">
                <form method="post" action="{{ route('update-item') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
                    @csrf
                    <header class="card-header">
                        <h2 class="card-title">Update Item</h2>
                    </header>
                    <div class="card-body">
                        <div class="row form-group">
                            <div class="col-lg-6">
                                <label>Item Code</label>
                                <input type="number" class="form-control" id="it_cod" placeholder="Item Code" name="it_cod" required disabled>
                            </div>
                            <div class="col-lg-6">
                                <label>Item Group</label>
                                <select class="form-control" id="item_group" name ="item_group" required>
                                    <option selected>Select Group</option>
                                    @foreach($itemGroups as $key => $row)	
                                        <option value="{{$row->item_group_cod}}">{{$row->group_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label>Item Name</label>
                                <input type="text" class="form-control" id="item_name" placeholder="Item Name" name="item_name">
                            </div>
                            <div class="col-lg-6">
                                <label>Remarks</label>
                                <input type="text" class="form-control" id="item_remark" placeholder="Remarks" name="item_remark">
                            </div>
                            <div class="col-lg-6">
                                <label>Sale Price</label>
                                <input type="text" class="form-control" id="sales_price" placeholder="Sale Price" name="sales_price">
                            </div>
                            <div class="col-lg-6">
                                <label>Purchase Price</label>
                                <input type="text" class="form-control" id="OPP_qty_cost" placeholder="Purchase Price" name="OPP_qty_cost">
                            </div>
                            <div class="col-lg-6">
                                <label>Stock</label>
                                <input type="text" class="form-control" id="qty" placeholder="Stock" name="qty">
                            </div>
                            <div class="col-lg-6">
                                <label>Date</label>
                                <input type="date" class="form-control" id="date" placeholder="Date" name="date">
                            </div>  
                            <div class="col-lg-6">
                                <label>Stock Level</label>
                                <input type="text" class="form-control" id="stock_level" placeholder="Stock Level" name="stock_level">
                            </div>  
                            <div class="col-lg-6">
                                <label>Labour Price</label>
                                <input type="text" class="form-control" id="labourprice" placeholder="Labour Price" name="labourprice">
                            </div>  
                        </div>
                    </div>
                    <footer class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">Update Item</button>
                                <button class="btn btn-default modal-dismiss">Cancel</button>
                            </div>
                        </div>
                    </footer>
                </form>
            </section>
        </div>

        <div id="createModal" class="modal-block modal-block-primary mfp-hide">
            <section class="card">
                <form method="post" action="{{ route('update-item') }}" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
                    @csrf
                    <header class="card-header">
                        <h2 class="card-title">Add Item</h2>
                    </header>
                    <div class="card-body">
                        <div class="row form-group">
                            <div class="col-lg-6">
                                <label>Item Code</label>
                                <input type="number" class="form-control" placeholder="Item Code" name="it_cod" required disabled>
                            </div>
                            <div class="col-lg-6">
                                <label>Item Group</label>
                                <select class="form-control" name ="item_group" required>
                                    <option selected>Select Group</option>
                                    @foreach($itemGroups as $key => $row)	
                                        <option value="{{$row->item_group_cod}}">{{$row->group_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label>Item Name</label>
                                <input type="text" class="form-control" placeholder="Item Name" name="item_name">
                            </div>
                            <div class="col-lg-6">
                                <label>Remarks</label>
                                <input type="text" class="form-control"  placeholder="Remarks" name="item_remark">
                            </div>
                            <div class="col-lg-6">
                                <label>Sale Price</label>
                                <input type="text" class="form-control" placeholder="Sale Price" name="sales_price">
                            </div>
                            <div class="col-lg-6">
                                <label>Purchase Price</label>
                                <input type="text" class="form-control" placeholder="Purchase Price" name="OPP_qty_cost">
                            </div>
                            <div class="col-lg-6">
                                <label>Stock</label>
                                <input type="text" class="form-control" placeholder="Stock" name="qty">
                            </div>
                            <div class="col-lg-6">
                                <label>Date</label>
                                <input type="date" class="form-control" placeholder="Date" name="date">
                            </div>  
                            <div class="col-lg-6">
                                <label>Stock Level</label>
                                <input type="text" class="form-control" placeholder="Stock Level" name="stock_level">
                            </div>  
                            <div class="col-lg-6">
                                <label>Labour Price</label>
                                <input type="text" class="form-control" placeholder="Labour Price" name="labourprice">
                            </div>  
                        </div>
                    </div>
                    <footer class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">Add Item</button>
                                <button class="btn btn-default modal-dismiss">Cancel</button>
                            </div>
                        </div>
                    </footer>
                </form>
            </section>
        </div>

        @extends('../layouts.footerlinks')
	</body>
</html>
<script>
    function setId(id){
        $('#deleteID').val(id);
    }

    function getItemDetails(id){
        $.ajax({
            type: "GET",
            url: "/item/detail",
            data: {id:id},
            success: function(result){
                var dateParts = result[0]['opp_date'].split("-");
                var year = dateParts[0];
                var month = dateParts[1];
                var day = dateParts[2];
                var date = year + "-" + month + "-" + day;  
                
                $('#it_cod').val(result[0]['it_cod']);
                $('#item_group').val(result[0]['item_group']);
                $('#item_name').val(result[0]['item_name']);
                $('#item_remark').val(result[0]['item_remark']);
                $('#sales_price').val(result[0]['sales_price']);
                $('#OPP_qty_cost').val(result[0]['OPP_qty_cost']);
                $('#qty').val(result[0]['qty']);
                $("#date" ).val(date)
                $('#stock_level').val(result[0]['stock_level']);
                $('#labourprice').val(result[0]['labourprice']);
            },
            error: function(){
                alert("error");
            }
        });
	}
</script>