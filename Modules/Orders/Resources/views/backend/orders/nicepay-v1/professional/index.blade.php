@extends('layouts::header')

@section('contents')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('cms.orders')</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ url('admin/home') }}">
                        <i class="fa fa-home"></i> @lang('cms.home')
                    </a>
                </li>
                <li class="active">@lang('cms.orders')</li>
            </ol>
        </section>

        <section class="content">
            <form action="{{ route('orders.backend.orders.nicepay-v1.professional.store') }}" method="post">
                {{ csrf_field() }}
                <div class="box">
                    <div class="box-body">
                        <div class="form-group">
                            <label>@lang('cms.order') *</label>
                            <select class="form-control" name="id" required>
                                <option></option>
                                @foreach ($orders as $order)
                                    <option value="{{ $order->id }}">{{ $order->id }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>payMethod *</label>
                            <select class="form-control" name="payMethod" required>
                                <option></option>
                                @foreach ($nicepayCode->getPaymentMethodOptions() as $paymentMethodCode => $paymentMethod)
                                    <option value="{{ $paymentMethodCode }}">{{ $paymentMethod }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('cms.auto_submit')</label>
                            <select class="form-control select2" name="auto_submit">
                                <option value="0">@lang('cms.no')</option>
                                <option value="1">@lang('cms.yes')</option>
                            </select>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="form-group">
                            <input class="btn btn-success" type="submit" value="@lang('cms.save')" />
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection
