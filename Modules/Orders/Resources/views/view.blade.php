	@extends('layouts::header')

	@section('contents')

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
        @include('flash::message')

		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
				ORDERS
			</h1>
			<ol class="breadcrumb">
				<li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li><a href="{{ url('admin/orders') }}">Orders</a></li>
				<li class="active">View</li>
			</ol>
		</section>
		<!-- Main content -->
		<section class="content">
			<!-- Default box -->
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">Order Number ({{ $order->invoice_number ? $order->invoice_number : $order->order_no }})</h3>
				</div>
				<div class="box-body">
					<div class="col-md-9">
						<table>
							<tbody>
								<tr>
									<td style="width:150px;">
										<div class="form-group">
											<label>Order Datetime</label>
										</div>
									</td>
									<td>
										<div class="form-group">
											: {{ $order->created_at }}
										</div>
									</td>
								</tr>
								<tr>
									<td style="width:150px;">
										<div class="form-group">
											<label>User</label>
										</div>
									</td>
									<td>
										<div class="form-group">
											: {{ $order->getUserName() }}
										</div>
									</td>
								</tr>
								<tr>
									<td style="width:150px;">
										<div class="form-group">
											<label>Payment Method</label>
										</div>
									</td>
									<td>
										<div class="form-group">
											: {{ $order->getPaymentMethodName() }}
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label>Payment Date</label>
										</div>
									</td>
									<td>
										<div class="form-group">
											: {{ $order->payment_date }}
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label>Payment Status</label>
										</div>
									</td>
									<td>
										<div class="form-group">
											: {{ $order->payment_status }}
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label>Shipping Address</label>
										</div>
									</td>
									<td>
										<div class="form-group">
											: {{ $order->orderShippingAddress->title.'. '.$order->orderShippingAddress->name.', '.$order->orderShippingAddress->address.' - '.$order->orderShippingAddress->regency.', '.$order->orderShippingAddress->province.' - '.$order->orderShippingAddress->postal_code }}
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label>Phone Number</label>
										</div>
									</td>
									<td>
										<div class="form-group">
											: {{ $order->orderShippingAddress->phone_number }}
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label>Shipping Method</label>
										</div>
									</td>
									<td>
										<div class="form-group">
											: {{ $order->orderShippingMethod->code.' '.$order->orderShippingMethod->service.' - Rp. '.number_format($order->orderShippingMethod->cost, 0, ',', '.') }}
										
										@if($order->status == 'New' || $order->status =='Sent')
											<a href="{{ url('admin/transactions/'.$order->id.'/print_label') }}" class="btn-sm btn-warning" title="View"><i class="fa fa-print"></i> Print Label</a>
										@endif
										
										</div>
									</td>
								</tr>
								@if($order->voucher)
									<tr>
										<td>
											<div class="form-group">
												<label>Voucher Code</label>
											</div>
										</td>
										<td>
											<div class="form-group">
												: {{ $order->voucher->code }}
											</div>
										</td>
									</tr>
								@endif
								<tr>
									<td>
										<div class="form-group">
											<label>Notes</label>
										</div>
									</td>
									<td>
										<div class="form-group">
											: {{ $order->notes }}
										</div>
									</td>
								</tr>
                                <tr>
                                    <td>
                                        <label for="order_shipping_method__waybill">@lang('cms.waybill')</label>
                                    </td>
                                    <td>
                                        @if (in_array($order->status, [$order::$statusNew, $order::$statusSent]))
                                            <form action="{{ route('orders.backend.orders.order-shipping-method.update', $order->id) }}" class="form-inline" method="post">
                                                {{ csrf_field() }}
                                                {{ method_field('PUT') }}
                                                :
                                                <input class="form-control" id="order_shipping_method__waybill" name="order_shipping_method[waybill]" required type="text" value="{{ old('order_shipping_method.waybill', optional($order->orderShippingMethod)->waybill) }}" />
                                                <button class="btn btn-success" type="submit">@lang('cms.save')</button>
                                                <label class="text-danger">{{ $errors->first('order_shipping_method.waybill') }}</label>
                                            </form>
                                        @else
                                            : {{ optional($order->orderShippingMethod)->waybill }}
                                        @endif
                                    </td>
                                </tr>
							</tbody>
						</table>
					</div>

					<div class="col-md-3" style="padding:0px;">
						<div class="callout callout-success">
							<h4>Status: </h4>
							<p>{{ $order->status }}</p>
						</div>
					</div>

					<div class="col-md-12" style="padding:0px;">
						<br>
						<table class="table table-bordered table-hover">
							<tr>
								<th class="text-center">No</th>
								<th class="text-center">Product Name</th>
								<th class="text-center">Packaging Size</th>
								<th class="text-center">Quantity</th>
								<th class="text-center">Price (Rp)</th>
								<th class="text-center">Total (Rp)</th>
							</tr>
							@php
								$no = 1;
								$columns = 6;
								$subTotal = 0;
							@endphp
							@foreach($orderDetails as $orderDetail)
								@php
									$total = $orderDetail->quantity*$orderDetail->price;
									$subTotal = $subTotal + $total;
								@endphp
								<tr>
									<td class="text-center">{{ $no++ }}</td>
									<td class="text-left">{{ $orderDetail->product->product_name }}</td>
									<td class="text-center">{{ $orderDetail->packagingSize->packaging_size }}</td>
									<td class="text-right">{{ number_format($orderDetail->quantity, 0, ',', '.') }}</td>
									<td class="text-right">{{ number_format($orderDetail->price, 0, ',', '.') }}</td>
									<td class="text-right">{{ number_format($total, 0, ',', '.') }}</td>
								</tr>
							@endforeach
								<tr>
									<td class="text-right" colspan="{{ $columns - 1 }}"><label>Subtotal</label></td>
									<td class="text-right"><label>{{ number_format($subTotal, 0, ',', '.') }}</label></td>
								</tr>
								@if($order->voucher)
									@if($order->voucher_type == 'Total Only')
										@php
											if($order->voucher_unit == 'Amount') {
												$disc = $order->voucher_value;
											} else {
												$disc = ($subTotal * $order->voucher_value) / 100;
											}
										@endphp
										<tr>
											<td class="text-right" colspan="{{ $columns - 1 }}"><label>Voucher Disc. {{ $order->voucher_unit == 'Percentage' ? '('.$order->voucher_value.'%)' : '' }}</label></td>
											<td class="text-right"><label>- {{ number_format($disc, 0, ',', '.') }}</label></td>
										</tr>
									@endif
								@endif
								<!--
								<tr>
									<td class="text-right" colspan="{{ $columns - 1 }}"><label>Tax</label></td>
									<td class="text-right"><label>{{ number_format($order->tax, 0, ',', '.') }}</label></td>
								</tr>
								-->
								<tr>
									<td class="text-right" colspan="{{ $columns - 1 }}"><label>Shipping</label></td>
									<td class="text-right"><label>{{ number_format($order->total_shipping_cost, 0, ',', '.') }}</label></td>
								</tr>
								@if($order->voucher)
									@if($order->voucher_type == 'Shipping Only')
										@php
											if($order->voucher_unit == 'Amount') {
												$disc = $order->voucher_value;
											} else {
												$disc = ($order->total_shipping_cost * $order->voucher_value) / 100;
											}
										@endphp
										<tr>
											<td class="text-right" colspan="{{ $columns - 1 }}"><label>Shipping Coupon {{ $order->voucher_unit == 'Percentage' ? '('.$order->voucher_value.'%)' : '' }}</label></td>
											<td class="text-right"><label>- {{ number_format($disc, 0, ',', '.') }}</label></td>
										</tr>
									@endif
								@endif
								<tr>
									<td class="text-right" colspan="{{ $columns - 1 }}"><label>Total</label></td>
									<td class="text-right"><label>{{ number_format($order->grand_total, 0, ',', '.') }}</label></td>
								</tr>
						</table>

						<div class="text-right">
							<br>
							@if(request()->query('menu') == 'transactions')
								<a href="{{ url('admin/transactions') }}" class="btn btn-danger" style="width:110px;">
									<i class="fa fa-sign-out"></i> Back
								</a>
							@elseif(request()->query('menu') == 'orders_refund')
								<a href="{{ url('admin/orders_refund') }}" class="btn btn-danger" style="width:110px;">
									<i class="fa fa-sign-out"></i> Back
								</a>
							@else
								<a href="{{ url('admin/orders') }}" class="btn btn-danger" style="width:110px;">
									<i class="fa fa-sign-out"></i> Back
								</a>
							@endif
                            <a class="btn btn-primary" href="{{ route('admin.orders.edit', $order->id) }}">Edit</a>
						</div>
					</div>
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->

		</section>
		<!-- /.content -->
	</div>
  	<!-- /.content-wrapper -->

	@endsection
