	@extends('layouts::header')

	@section('contents')

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
				ORDERS
			</h1>
			<ol class="breadcrumb">
				<li><a href="{{ url('admin/home') }}"><i class="fa fa-home"></i> Home</a></li>
				<li class="active">Orders</li>
			</ol>
		</section>
		<!-- /.content header -->
		<!-- Main content -->
		<section class="content">
			@if($errors->any())
				<div class="alert alert-danger alert-dismissible fade in" role=alert> 
					<button type=button class=close data-dismiss=alert aria-label=Close>
						<span aria-hidden=true>&times;</span>
					</button> 
					<ul>
			            @foreach ($errors->all() as $error)
			                <li>{{ $error }}</li>
			            @endforeach
			        </ul>
				</div>
			@endif
			@if(session()->has('success'))
				<div class="alert alert-success alert-dismissible fade in" role=alert>
					<button type=button class=close data-dismiss=alert aria-label=Close>
						<span aria-hidden=true>&times;</span>
					</button> 
					{{ session()->get('success') }}
				</div>
			@endif
			@if(session()->has('error'))
				<div class="alert alert-danger alert-dismissible fade in" role=alert> 
					<button type=button class=close data-dismiss=alert aria-label=Close>
						<span aria-hidden=true>&times;</span>
					</button> 
					{{ session()->get('error') }}
				</div>
			@endif


			<!-- Default box -->
			<div class="box">
				<div class="box-header with-border">
					<p class="box-title" style="font-size: 14px;">
						<a href="{{ route('admin.orders', ['from' => $today, 'to' => $today]) }}">Today</a> | 
						<a href="{{ route('admin.orders', ['from' => $yesterday, 'to' => $yesterday]) }}">Yesterday</a> | 
						<a href="{{ route('admin.orders', ['from' => $thisMonthStart, 'to' => $thisMonthEnd]) }}">This Month</a> | 
						<a href="{{ route('admin.orders', ['from' => $lastMonthStart, 'to' => $lastMonthEnd]) }}">Last Month</a> | 
						<a href="{{ route('admin.orders', ['from' => $thisYearStart, 'to' => $thisYearEnd]) }}">This Year</a> | 
						<a href="{{ route('admin.orders', ['from' => $lastYearStart, 'to' => $lastYearEnd]) }}">Last Year</a>
					</p>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
						<i class="fa fa-minus"></i></button>
					</div>
				</div>
				<form action="{{ route('admin.orders') }}" data-pjax method="GET">
					<div class="box-body">
						<div class="col-md-4" style="">
							<div class="form-group">
								<label for="" class="col-sm-4 control-label">From</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="from" id="from" value="{{ session()->get('ordersFromSearch') }}" autocomplete="off" style="background-color: white;" readonly>
								</div>
							</div>
						</div>
						<div class="col-md-4" style="">
							<div class="form-group">
								<label for="" class="col-sm-4 control-label">To</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="to" id="to" value="{{ session()->get('ordersToSearch') }}" autocomplete="off" style="background-color: white;" readonly>
								</div>
							</div>
						</div>
						<div class="col-md-1" style="padding:0px;">
						</div>
						<div class="col-md-3" style="padding:0px;">
							<button type="submit" class="btn btn-primary" style="width:135px;">
								<i class="fa fa-search"></i> Search
							</button>
						</div>
					</div>
				</form>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->

			<!-- Default box -->
			<div class="box">
				<form action="{{ url('admin/orders/bulk_destroy') }}" method="POST" class="form-horizontal">
					<div class="box-header with-border">
						<div class="col-md-2" style="padding-left: 0px; padding-top: 8px;">
							<h3 class="box-title">
								<b>Orders</b>
							</h3>
						</div>
						<div class="col-md-10">
							{{ csrf_field() }}
							{{ method_field('DELETE') }}
							<button type="submit" onclick="return confirm('Are you sure want to delete with Bulk Delete?')" class="btn btn-danger" title="Bulk Delete"><i class="fa fa-trash-o"></i> Bulk Delete</button>
							@if(session()->has('ordersFromSearch') && session()->has('ordersToSearch'))
								<a href="{{ route('admin.orders.download_excel', ['from' => session()->get('ordersFromSearch'), 'to' => session()->get('ordersToSearch')]) }}" class="btn btn-success" title="Excel"  style="width: 110px;"><i class="fa fa-download"></i> Excel</a>
							@else
								<a href="{{ route('admin.orders.download_excel') }}" class="btn btn-success" title="Excel"  style="width: 110px;"><i class="fa fa-download"></i> Excel</a>
							@endif
						</div>
					</div>
					
					<div class="box-body">
						<table id="myTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th class="text-center">
										<div class="checkbox">
											<input type="checkbox" id="select_all">
										</div>
									</th>
									<th class="text-center">Order Datetime</th>
									<th class="text-center">Order Number</th>
									<th class="text-center">User</th>
									<th class="text-center">Grand Total (Rp)</th>
									<th class="text-center">Status</th>
									<th class="text-center">Actions</th>
								</tr>
							</thead>
							<tbody>
								@foreach($orders as $order)
									<tr>
										<td class="text-center">
											<div class="checkbox">
												<input type="checkbox" name="row_id[]" value="{{ $order->id }}">
											</div>
	          							</td>
	          							<td class="text-center">{{ $order->created_at }}</td>
										<td class="text-center">{{ $order->invoice_number ? $order->invoice_number : $order->order_no }}</td>
										<td class="text-center">{{ $order->getUserName() }}</td>
										<td class="text-right">{{ number_format($order->grand_total, 0, ',', '.') }}</td>
										<td class="text-center">{{ $order->status }}</td>
										<td class="text-center">
											<!--<a href="{{ url('admin/orders/'.$order->id.'/edit') }}" class="btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i> Edit</a>-->
											<a href="{{ url('admin/orders/'.$order->id.'/view') }}" class="btn-sm btn-primary" title="View"><i class="fa fa-eye"></i> View</a>
											<input type="hidden" value="{{ url('admin/orders/destroy/'.$order->id) }}" id="action_id_{{ $order->id }}">
											<a href="javascript:;" class="btn-sm btn-danger" onclick="destroy({{ $order->id }})" Title="Delete"><i class="fa fa-trash-o"></i> Delete</a>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
		            </div>
		            <!-- /.box-body -->
	            </form>
			</div>
			<!-- /.box -->
		</section>
		<!-- /.content -->
	</div>

	<form action="#" id="delete_form" method="POST">
		<!-- Modal -->
		<div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel"><span style="color:red;"><b>Are you sure want to delete this data?</b></span></h4>
					</div>
					<div class="modal-footer">
						{{ csrf_field() }}
						{{ method_field('DELETE') }}
						<button type="button" class="btn btn-default" data-dismiss="modal" style="width:125px;">
							Cancel
						</button>
						<button type="submit" class="btn btn-danger" style="width:125px;">
							Yes, Delete it!
						</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		<!-- /.modal -->
	</form>

	@endsection

	@push('scripts')

		<script type="text/javascript">

			$(function () {

				$('#myTable').DataTable({
					"paging": true,
					"lengthChange": true,
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": true
				});

			});

			$('#select_all').on('click', function(e) {
	        	$('input[name="row_id[]"]').prop('checked', $(this).prop('checked'));
	        });
			
		   	function destroy(id) {
		   		$('#delete_form')[0].action = $('#action_id_'+id).val();
		        $('#modal_delete').modal('show');
		   	}

		   	$('#from').datepicker({
				autoclose: true,
				format: 'yyyy-mm-dd'
			});

			$('#to').datepicker({
				autoclose: true,
				format: 'yyyy-mm-dd'
			});

		</script>

	@endpush