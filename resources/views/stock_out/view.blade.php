@extends('../layouts.header')
	<body>
		<section class="body">
			@extends('../layouts.menu')

			<div class="inner-wrapper">
				<section role="main" class="content-body">
					@extends('../layouts.pageheader')

                    <section class="card">
						<div class="card-body">
							<div class="invoice">
								<header class="clearfix">
									<div class="row">
										<div class="col-sm-6 mt-3">
											<h2 class="h2 mt-0 mb-1 text-dark ">SALE INVOICE NO:</h2>
											<h4 class="h4 m-0 text-dark font-weight-bold">{{$sales->Sal_inv_no}}</h4>
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
										<div class="col-md-6">
											<div class="bill-to">
												<p class="h5 mb-1 text-dark font-weight-semibold">To:</p>
												<h4 style="font-weight:500;color:black">
													{{$sales->ac_name}}
													<br/>
													{{$sales->address}}
													<br/>
													{{$sales->phone_no}}
													<br/>
												</h4>
											</div>
										</div>
										<div class="col-md-6">
											<div class="bill-data text-end">
												<h4 class="mb-0">
													<span class="text-dark">Invoice Date:</span>
													<span style="font-weight:300;color:black" class="value">{{\Carbon\Carbon::parse($sales->sa_date)->format('d-m-y')}}</span>
												</h4>
											</div>
										</div>
									</div>
								</div>

								<table class="table table-responsive-md invoice-items">
									<thead>
										<tr class="text-dark">
											<th width="5%" class="font-weight-semibold">S.No</th>
											<th class="text-center font-weight-semibold">Quantity</th>
											<th width="22%" class="font-weight-semibold">Item</th>
											<th width="22%" class="font-weight-semibold">Remarks</th>
											<th class="text-center font-weight-semibold">Weight</th>
											<th class="text-center font-weight-semibold">Price</th>
											<th class="text-center font-weight-semibold">Amount</th>
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

								<div class="row">
									<div class="col-8">
										<div class="row">
											<div class="col-6">
												<table class="table h6 text-dark">
													<tbody>
														<tr class="b-top-0">
															<td colspan="2">Total Quantity</td>
															<td class="text-left">{{$total_quantity}}</td>
														</tr>
														<tr>
															<td colspan="2">Total Weight</td>
															<td class="text-left">{{$total_weight}}</td>
														</tr>
			
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="col-4 invoice-summary">
										<div class="row justify-content-end">
											<table class="table h6 text-dark">
												<tbody>
													<tr class="b-top-0">
														<td colspan="2">Subtotal</td>
														<td class="text-left">{{$subtotal}}</td>
													</tr>
													<tr>
														<td colspan="2">Labour Charges</td>
														<td class="text-left">{{$sales->LaborCharges}} PKR</td>
													</tr>
														<td colspan="2">Convance Charges</td>
														<td class="text-left">{{$sales->ConvanceCharges}} PKR</td>
													</tr>
													</tr>
														<td colspan="2">Discount</td>
														<td class="text-left">{{$sales->Bill_discount}} PKR</td>
													</tr>
													<tr class="h5">
														<td colspan="2">Net Amount</td>
														<td class="text-left">{{round($subtotal + $sales->LaborCharges + $sales->ConvanceCharges - $sales->Bill_discount)}} PKR</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								<div>
							</div>
							<div class="d-grid gap-3 d-md-flex justify-content-md-end me-4">
								<a href="{{ route('print-sale-invoice', $sales->Sal_inv_no) }}" class="btn btn-danger mt-2 mb-2"> <i class="fas fa-print"></i> Print</a>
								<a href="{{ route('download-sale-invoice', $sales->Sal_inv_no) }}" class="btn btn-primary mt-2 mb-2"> <i class="fas fa-download"></i> Download</a>
							</div>
						</div>
					</section>
				</section>
			</div>
			</div>
		</section>
        @extends('../layouts.footerlinks')
	</body>
</html>