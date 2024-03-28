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
            <form action="{{ route('orders.backend.orders.nicepay-v1.enterprise.store') }}" method="post">
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
                        <div class="convenienceStoreDiv form-group hidden">
                            <label>mitraCd *</label>
                            <select class="form-control convenienceStoreInput" name="mitraCd">
                                <option></option>
                                @foreach ($nicepayCode->getMitraCodeOptions() as $mitraCode => $mitra)
                                    <option value="{{ $mitraCode }}">{{ $mitra }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="virtualAccountDiv form-group hidden">
                            <label>bankCd *</label>
                            <select class="form-control virtualAccountInput" name="bankCd">
                                <option></option>
                                @foreach ($nicepayCode->getBankCodeOptions() as $bankCode => $bank)
                                    <option value="{{ $bankCode }}">{{ $bank }}</option>
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

@push('scripts')
    <script>
    class BackendOrdersNicepayV1Enterprise
    {
        constructor()
        {
            this.convenienceStoreDiv = document.querySelector('.convenienceStoreDiv');
            this.convenienceStoreInput = document.querySelector('.convenienceStoreInput');
            this.virtualAccountDiv = document.querySelector('.virtualAccountDiv');
            this.virtualAccountInput = document.querySelector('.virtualAccountInput');
            this.payMethod = document.querySelector('[name=payMethod]');
        }

        payMethodChange()
        {
            this.convenienceStoreDiv.classList.add('hidden');
            this.convenienceStoreInput.required = false;
            this.virtualAccountDiv.classList.add('hidden');
            this.virtualAccountInput.required = false;

            switch (this.payMethod.value) {
                case '02':
                    this.virtualAccountDiv.classList.remove('hidden');
                    this.virtualAccountInput.required = true;
                    break;
                case '03':
                    this.convenienceStoreDiv.classList.remove('hidden');
                    this.convenienceStoreInput.required = true;
                    break;
                case '04':
                    this.convenienceStoreDiv.classList.remove('hidden');
                    this.convenienceStoreInput.required = true;
                    break;
                case '05':
                    this.convenienceStoreDiv.classList.remove('hidden');
                    this.convenienceStoreInput.required = true;
                    break;
                default:
            }
        }
    }

    var backendOrdersNicepayV1Enterprise = new BackendOrdersNicepayV1Enterprise;
    backendOrdersNicepayV1Enterprise.payMethod.addEventListener('change', function() {
        backendOrdersNicepayV1Enterprise.payMethodChange()
    });
    backendOrdersNicepayV1Enterprise.payMethodChange();
    </script>
@endpush
