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
											<h4 class="h4 mt-0 mb-1 text-dark ">PURCHASE INVOICE NO: </h4>
											<h4 class="h4 m-0 text-dark font-weight-bold">AB-{{$pur->Sale_inv_no}}</h4>
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
												<h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
													<span class="text-dark">Invoice Date: </span>
													<span style="font-weight:400;color:black" class="value"> {{\Carbon\Carbon::parse($pur->sa_date)->format('d-m-y')}}</span>
												</h4>

												<h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
													<span class="text-dark">To: </span>
													<span style="font-weight:400;color:black" class="value"> {{$pur->ac_name}}</span>
												</h4>

												<h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
													<span class="text-dark">Address: </span>
													<span style="font-weight:400;color:black" class="value"> {{$pur->address}}</span>
												</h4>

												<h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
													<span class="text-dark">Phone No: </span>
													<span style="font-weight:400;color:black" class="value"> {{$pur->phone_no}}</span>
												</h4>
											</div>
										</div>
										<div class="col-md-6">
											<div class="bill-data">
											<h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
													<span class="text-dark">Name Of Person: </span>
													<span style="font-weight:400;color:black" class="value"> {{$pur->Cash_pur_name}}</span>
												</h4>
												
												<h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
													<span class="text-dark">Dispatch To: </span>
													<span style="font-weight:400;color:black" class="value"> {{$pur->disp_to}}</span>
												</h4>

												<h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
													<span class="text-dark">Sale Inv No: </span>
													<span style="font-weight:400;color:black" class="value"> {{$pur->sales_against}}</span>
												</h4>

												<h4 class="mb-0 h6 mb-1 text-dark font-weight-semibold">
													<span class="text-dark">Person Address: </span>
													<span style="font-weight:400;color:black" class="value"> {{$pur->cash_Pur_address}}</span>
												</h4>
											</div>
										</div>
									</div>
								</div>

								<table class="table table-responsive-md invoice-items">
									<thead>
										<tr class="text-dark">
											<th width="5%" class="font-weight-semibold">S.No</th>
											<th width="22%" class="font-weight-semibold">Item</th>
											<th width="22%" class="font-weight-semibold">Remarks</th>
											<th class="text-center font-weight-semibold">Quantity</th>
											<th class="text-center font-weight-semibold">Price/Unit</th>
											<th class="text-center font-weight-semibold">Length</th>
											<th class="text-center font-weight-semibold">%</th>
											<th class="text-center font-weight-semibold">Amount</th>
										</tr>
									</thead>
									@php($subtotal = 0)
									@php($total_quantity = 0)
									@php($total_weight = 0)
									<tbody>
										@foreach($pur2 as $key => $pur_item)
										<tr>
											<td>{{$key+1}}</td>
											<td class="font-weight-semibold text-dark">{{$pur_item->item_name}}</td>
											<td>{{$pur_item->remarks}}</td>
											<td class="text-center">{{$pur_item->Sales_qty2}}</td>
											<td class="text-center">{{$pur_item->sales_price}}</td>
											<td class="text-center">{{$pur_item->length}}</td>
											<td class="text-center">{{$pur_item->discount}}</td>
											<td class="text-center">{{(($pur_item->Sales_qty2 * $pur_item->sales_price)+( ($pur_item->Sales_qty2 * $pur_item->sales_price) * ($pur_item->discount/100))) * $pur_item->length}}</td>
										</tr>
										<?php $subtotal=$subtotal+((($pur_item->Sales_qty2 * $pur_item->sales_price)+( ($pur_item->Sales_qty2 * $pur_item->sales_price) * ($pur_item->discount/100))) * $pur_item->length) ?>
										<?php $total_weight=$total_weight+($pur_item->Sales_qty2*$pur_item->weight_pc) ?>
										<?php $total_quantity=$total_quantity+ $pur_item->Sales_qty2 ?>

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
														<td class="text-left">{{$pur->LaborCharges}} PKR</td>
													</tr>
														<td colspan="2">Convance Charges</td>
														<td class="text-left">{{$pur->ConvanceCharges}} PKR</td>
													</tr>
													</tr>
														<td colspan="2">Discount</td>
														<td class="text-left">{{$pur->Bill_discount}} PKR</td>
													</tr>
													<tr class="h5">
														<td colspan="2">Net Amount</td>
														<td class="text-left">{{round($subtotal + $pur->LaborCharges + $pur->ConvanceCharges - $pur->Bill_discount)}} PKR</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								<div>
							</div>

							<div class="d-grid gap-3 d-md-flex justify-content-md-end me-4">
								<a onclick="window.location='{{ route('all-purchases2') }}'" class="btn btn-primary mt-2 mb-2"> <i class="fas fa-arrow-left"></i> Back</a>
								<a href="{{ route('print-purc2-invoice', $pur->Sale_inv_no) }}" class="btn btn-danger mt-2 mb-2"> <i class="fas fa-print"></i> Print</a>
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