@include('../layouts.header')
	<body>
		<section class="body">
			<div class="inner-wrapper" style="padding-top: 50px;">
				@include('layouts.leftmenu')
				<section role="main" class="content-body" >
				@include('../layouts.pageheader')

                    <section class="card">

						<div class="card-body">

							<div class="invoice">

								<header class="clearfix">
									<div class="row">
										<div class="col-sm-6 mt-3">
											<h2 class="h2 mt-0 mb-1" style="color:#17365D">Sale INVOICE NO:</h2>
											<h4 class="h4 m-0 text-dark font-weight-bold">{{$sales->prefix}}{{$sales->Sal_inv_no}}</h4>
										</div>
										<div class="col-sm-6 text-end mt-3 mb-3">
											<div class="ib">
												<img width="100px" src="/assets/img/logo.png" alt="MFI Logo" />
											</div>
										</div>
									</div>
								</header>

								<div class="bill-info">
									<div class="row">
										<div class="col-md-7">
											<div class="bill-to">
												<h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
													<span style="color:#17365D">Invoice Date: &nbsp </span>
													<span style="font-weight:400;color:black" class="value"> {{\Carbon\Carbon::parse($sales->sa_date)->format('d-m-y')}}</span>
												</h4>

												<h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
													<span  style="color:#17365D">To: &nbsp </span>
													<span style="font-weight:400;color:black" class="value"> {{$sales->ac_name}}</span>
												</h4>

												<h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
													<span  style="color:#17365D">Address: &nbsp </span>
													<span style="font-weight:400;color:black" class="value"> {{$sales->address}}</span>
												</h4>

												<h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
													<span  style="color:#17365D">Phone No: &nbsp </span>
													<span style="font-weight:400;color:black" class="value"> {{$sales->phone_no}}</span>
												</h4>
												<h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
													<span  style="color:#17365D">Bill No: &nbsp </span>
													<span style="font-weight:400;color:black" class="value"> {{$sales->pur_ord_no}}</span>
												</h4>
											</div>
										</div>
										<div class="col-md-5">
											<div class="bill-data">

												<h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
													<span  style="color:#17365D">Name Of Person: &nbsp</span>
													<span style="font-weight:400;color:black" class="value"> {{$sales->Cash_pur_name}}</span>
												</h4>
												
												<h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
													<span  style="color:#17365D">Person Address: &nbsp</span>
													<span style="font-weight:400;color:black" class="value"> {{$sales->cash_Pur_address}}</span>
												</h4>
												<h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
													<span style="color:#17365D">Remarks: &nbsp</span>
													<span style="font-weight:400;color:black" class="value"> {{$sales->Sales_remarks}}</span>
												</h4>
											</div>
										</div>
									</div>
								</div>


								<table class="table table-responsive-md invoice-items table-striped">
									<thead>
										<tr class="text-dark">
											<th width="3%" class="font-weight-semibold"  style="color:#17365D">S.No</th>
											<th width="4%" class="text-center font-weight-semibold"  style="color:#17365D">Qty</th>
											<th width="26%" class="font-weight-semibold"  style="color:#17365D">Item</th>
											<th width="26%" class="font-weight-semibold"  style="color:#17365D">Remarks</th>
											<th  width="6%" class="text-center font-weight-semibold"  style="color:#17365D">Weight</th>
											<th  width="6%" class="text-center font-weight-semibold"  style="color:#17365D">Price</th>
											<th  width="8%" class="text-center font-weight-semibold"  style="color:#17365D">Amount</th>
										</tr>
									</thead>
									@php($subtotal = 0)
									@php($total_quantity = 0)
									@php($total_weight = 0)
									<tbody>
										
										@foreach($sale_items as $key => $sale_item)
										<tr>
											<td>{{$key+1}}</td>
											<td class="text-center">{{$sale_item->Sales_qty2}}</td>
											<td class="font-weight-semibold text-dark">{{$sale_item->item_name}}</td>
											<td>{{$sale_item->remarks}}</td>
											<td class="text-center">{{$sale_item->sales_price}}</td>
											<td class="text-center">{{$sale_item->Sales_qty}}</td>
											<td class="text-center">{{$sale_item->sales_price * $sale_item->Sales_qty}}</td>
										</tr>
										<?php $subtotal=$subtotal+($sale_item->sales_price * $sale_item->Sales_qty) ?>
										<?php $total_weight=$total_weight+ $sale_item->Sales_qty ?>
										<?php $total_quantity=$total_quantity+ $sale_item->Sales_qty2 ?>

										@endforeach
									</tbody>
								</table>
								</table>

								<div class="row">
									<div class="col-8">
										<div class="row">
											<div class="col-6">
												<table class="table h6 text-dark">
													<tbody>
														<tr class="b-top-0">
															<td colspan="2"  style="color:#17365D">Total Quantity</td>
															<td class="text-left">{{$total_quantity}}</td>
														</tr>
														<tr>
															<td colspan="2"  style="color:#17365D">Total Weight(KGs)</td>
															<td class="text-left">{{$total_weight}}</td>
														</tr>
													</tbody>
												</table>
												<h3 style="color:#17365D; text-decoration: underline;" id="numberInWords"></h3>

											</div>
										</div>
									</div>
									<div class="col-4 invoice-summary">
										<div class="row justify-content-end">
											<table class="table h6 text-dark">
												<tbody>
													<tr class="b-top-0">
														<td colspan="2"  style="color:#17365D" >Subtotal</td>
														<td class="text-left">{{$subtotal}}</td>
													</tr>
													<tr>
														<td colspan="2"  style="color:#17365D">Labour Charges</td>
														<td class="text-left">{{$sales->LaborCharges}} PKR</td>
													</tr>
														<td colspan="2"  style="color:#17365D">Convance Charges</td>
														<td class="text-left">{{$sales->ConvanceCharges}} PKR</td>
													</tr>
													</tr>
														<td colspan="2"  style="color:#17365D">Discount</td>
														<td class="text-left">{{$sales->Bill_discount}} PKR</td>
													</tr>
													<?php $netamount=round($subtotal + $sales->LaborCharges + $sales->ConvanceCharges - $sales->Bill_discount) ?>
													<tr class="h5">
														<td colspan="2"  style="color:#17365D">Net Amount</td>
														<td class="text-left text-danger" style="font-weight:700">{{number_format($netamount)}} PKR</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								<div>
							</div>

							<div class="d-grid gap-3 d-md-flex justify-content-md-end me-4">
								<a onclick="window.location='{{ route('all-saleinvoices') }}'" class="btn btn-primary mt-2 mb-2"> <i class="fas fa-arrow-left"></i> Back</a>
								<a href="{{ route('print-sale-invoice', $sales->Sal_inv_no) }}" class="btn btn-danger mt-2 mb-2" target="_blank"> <i class="fas fa-print"></i> Print</a>
							</div>

						</div>

					</section>
				</section>
			</div>
			</div>
		</section>
        @include('../layouts.footerlinks')
	</body>
	<script>
		var netAmount = <?php echo json_encode($netamount); ?>;
		var words = convertCurrencyToWords(netAmount);
		document.getElementById('numberInWords').innerHTML = words;
	</script>
	
	
</html>