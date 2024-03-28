@extends('layouts::header')

@section('contents')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @include('flash::message')

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Order Number ({{ $order->invoice_number ? $order->invoice_number : $order->order_no }})</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin.orders') }}">Orders</a>
                </li>
                <li>
                    <a href="{{ route('admin.orders.view', $order->id) }}">{{ $order->id }}</a>
                </li>
                <li class="active">Edit</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <form action="{{ route('admin.orders.update', $order->id) }}" method="post">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <!-- Default box -->
                <div class="box">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select class="form-control" id="status" name="status">
                                @foreach ($order->getStatusOptions() as $status => $statusName)
                                    <option {{ $status == old('status', $order->status) ? 'selected' : '' }} value="{{ $status }}">{{ $statusName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button class="btn btn-success" type="submit">Save</button>
                        <a href="{{ url('admin/shipping') }}" class="btn btn-danger" style="width:150px;">
							<i class="fa fa-sign-out"></i> Back To Shipping
						</a>
						<a href="{{ url('admin/orders') }}" class="btn btn-danger" style="width:150px;">
							<i class="fa fa-sign-out"></i> Back To Orders
						</a>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </form>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection
