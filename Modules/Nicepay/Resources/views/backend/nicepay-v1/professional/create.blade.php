@extends('layouts::blank')

@section('contents')
    <form action="{{ route('nicepay.backend.nicepay-v1.professional.store') }}" class="{{ request()->input('auto_submit', '1') == '1' ? 'hidden' : '' }}" method="post">
        {{ csrf_field() }}
        <div class="box">
            <div class="box-header hidden with-border"></div>
            <div class="box-body">
                <div class="form-group">
                    <label>iMid *</label>
                    <input class="form-control" name="iMid" placeholder="IONPAYTEST" required type="text" value="{{ request()->old('iMid', $registration->iMid) }}" />
                </div>
                <div class="form-group hidden">
                    <label>merchantKey *</label>
                    <input class="form-control" name="merchantKey" placeholder="33F49GnCMS1mFYlGXisbUDzVf2ATWCl9k3R++d5hDd3Frmuos/XLx8XhXpe+LDYAbpGKZYSwtlyyLOtS/8aD7A==" required type="text" value="{{ request()->old('merchantKey', $registration->merchantKey) }}" />
                </div>
                <div class="form-group">
                    <label>merchantToken</label>
                    <input class="form-control" name="merchantToken" placeholder="c00ce98a2a700ecbe8579227b596683a0117e7f6cdacb6aef09a81e7785868fb" type="text" value="{{ request()->old('merchantToken', $registration->getMerchantToken()) }}" />
                </div>
                <div class="form-group">
                    <label>payMethod *</label>
                    <select class="form-control" name="payMethod" required>
                        <option></option>
                        @foreach ($nicepayCode->getPaymentMethodOptions() as $paymentMethodCode => $paymentMethod)
                            <option {{ $paymentMethodCode == request()->old('payMethod', $registration->payMethod) ? 'selected' : '' }} value="{{ $paymentMethodCode }}">{{ $paymentMethod }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>currency *</label>
                    <input class="form-control" name="currency" placeholder="IDR" required type="text" value="{{ request()->old('currency', $registration->currency) }}" />
                </div>
                <div class="form-group">
                    <label>amt *</label>
                    <input class="form-control" name="amt" placeholder="10000" required type="number" value="{{ request()->old('amt', $registration->amt) }}" />
                </div>
                <div class="form-group">
                    <label>instmntType</label>
                    <select class="form-control" name="instmntType">
                        <option></option>
                        @foreach ($nicepayCode->getInstallmentTypeOptions() as $installmentTypeCode => $installmentType)
                            <option {{ $installmentTypeCode == request()->old('instmntType', $registration->instmntType) ? 'selected' : '' }} value="{{ $installmentTypeCode }}">{{ $installmentType }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>instmntMon</label>
                    <input class="form-control" name="instmntMon" placeholder="1" type="number" value="{{ request()->old('instmntMon', $registration->instmntMon) }}" />
                </div>
                <div class="form-group">
                    <label>referenceNo *</label>
                    <input class="form-control" name="referenceNo" placeholder="MerchantReferenceNumber1" required type="text" value="{{ request()->old('referenceNo', $registration->referenceNo) }}" />
                </div>
                <div class="form-group">
                    <label>goodsNm *</label>
                    <input class="form-control" name="goodsNm" placeholder="Merchant Goods 1" required type="text" value="{{ request()->old('goodsNm', $registration->goodsNm) }}" />
                </div>
                <div class="form-group">
                    <label>billingNm *</label>
                    <input class="form-control" name="billingNm" placeholder="Buyer Name" required type="text" value="{{ request()->old('billingNm', $registration->billingNm) }}" />
                </div>
                <div class="form-group">
                    <label>billingPhone *</label>
                    <input class="form-control" name="billingPhone" placeholder="02123456789" required type="text" value="{{ request()->old('billingPhone', $registration->billingPhone) }}" />
                </div>
                <div class="form-group">
                    <label>billingEmail *</label>
                    <input class="form-control" name="billingEmail" placeholder="buyer@merchant.com" required type="email" value="{{ request()->old('billingEmail', $registration->billingEmail) }}" />
                </div>
                <div class="form-group">
                    <label>billingAddr</label>
                    <input class="form-control" name="billingAddr" placeholder="Billing Address" type="text" value="{{ request()->old('billingAddr', $registration->billingAddr) }}" />
                </div>
                <div class="form-group">
                    <label>billingCity</label>
                    <input class="form-control" name="billingCity" placeholder="Jakarta Utara" type="text" value="{{ request()->old('billingCity', $registration->billingCity) }}" />
                </div>
                <div class="form-group">
                    <label>billingState</label>
                    <input class="form-control" name="billingState" placeholder="DKI Jakarta" type="text" value="{{ request()->old('billingState', $registration->billingState) }}" />
                </div>

                <div class="form-group">
                    <label>billingPostCd</label>
                    <input class="form-control" name="billingPostCd" placeholder="10160" type="text" value="{{ request()->old('billingPostCd', $registration->billingPostCd) }}" />
                </div>
                <div class="form-group">
                    <label>billingCountry</label>
                    <input class="form-control" name="billingCountry" placeholder="Indonesia" type="text" value="{{ request()->old('billingCountry', $registration->billingCountry) }}" />
                </div>
                <div class="form-group">
                    <label>deliveryNm</label>
                    <input class="form-control" name="deliveryNm" placeholder="Delivery name" type="text" value="{{ request()->old('deliveryNm', $registration->deliveryNm) }}" />
                </div>
                <div class="form-group">
                    <label>deliveryPhone</label>
                    <input class="form-control" name="deliveryPhone" placeholder="02123456789" type="text" value="{{ request()->old('deliveryPhone', $registration->deliveryPhone) }}" />
                </div>
                <div class="form-group">
                    <label>deliveryAddr</label>
                    <input class="form-control" name="deliveryAddr" placeholder="Delivery Address" type="text" value="{{ request()->old('deliveryAddr', $registration->deliveryAddr) }}" />
                </div>
                <div class="form-group">
                    <label>deliveryCity</label>
                    <input class="form-control" name="deliveryCity" placeholder="Jakarta Utara" type="text" value="{{ request()->old('deliveryCity', $registration->deliveryCity) }}" />
                </div>
                <div class="form-group">
                    <label>deliveryState</label>
                    <input class="form-control" name="deliveryState" placeholder="DKI Jakarta" type="text" value="{{ request()->old('deliveryState', $registration->deliveryState) }}" />
                </div>
                <div class="form-group">
                    <label>deliveryPostCd</label>
                    <input class="form-control" name="deliveryPostCd" placeholder="10160" type="text" value="{{ request()->old('deliveryPostCd', $registration->deliveryPostCd) }}" />
                </div>
                <div class="form-group">
                    <label>deliveryCountry</label>
                    <input class="form-control" name="deliveryCountry" placeholder="indonesia" type="text" value="{{ request()->old('deliveryCountry', $registration->deliveryCountry) }}" />
                </div>
                <div class="form-group">
                    <label>callBackUrl *</label>
                    <input class="form-control" name="callBackUrl" placeholder="https://merchant.com/callBackUrl" required type="text" value="{{ request()->old('callBackUrl', $registration->callBackUrl) }}" />
                </div>
                <div class="form-group">
                    <label>dbProcessUrl *</label>
                    <input class="form-control" name="dbProcessUrl" placeholder="https://merchant.com/dbProcessUrl" required type="text" value="{{ request()->old('dbProcessUrl', $registration->dbProcessUrl) }}" />
                </div>
                <div class="form-group">
                    <label>vat</label>
                    <input class="form-control" name="vat" placeholder="0" type="text" value="{{ request()->old('vat', $registration->vat) }}" />
                </div>
                <div class="form-group">
                    <label>fee</label>
                    <input class="form-control" name="fee" placeholder="0" type="text" value="{{ request()->old('fee', $registration->fee) }}" />
                </div>
                <div class="form-group">
                    <label>notaxAmt</label>
                    <input class="form-control" name="notaxAmt" placeholder="0" type="text" value="{{ request()->old('notaxAmt', $registration->notaxAmt) }}" />
                </div>
                <div class="form-group">
                    <label>description *</label>
                    <input class="form-control" name="description" placeholder="this is test order" required type="text" value="{{ request()->old('description', $registration->description) }}" />
                </div>
                <div class="form-group">
                    <label>reqDt</label>
                    <input class="form-control" name="reqDt" placeholder="20180303" type="text" value="{{ request()->old('reqDt', $registration->reqDt) }}" />
                </div>
                <div class="form-group">
                    <label>reqTm</label>
                    <input class="form-control" name="reqTm" placeholder="135959" type="text" value="{{ request()->old('reqTm', $registration->reqTm) }}" />
                </div>
                <div class="form-group">
                    <label>reqDomain</label>
                    <input class="form-control" name="reqDomain" placeholder="merchant.com" type="text" value="{{ request()->old('reqDomain', $registration->reqDomain) }}" />
                </div>
                <div class="form-group">
                    <label>reqServerIP</label>
                    <input class="form-control" name="reqServerIP" placeholder="127.0.0.1" type="text" value="{{ request()->old('reqServerIP', $registration->reqServerIP) }}" />
                </div>
                <div class="form-group">
                    <label>reqClientVer</label>
                    <input class="form-control" name="reqClientVer" placeholder="1.0" type="text" value="{{ request()->old('reqClientVer', $registration->reqClientVer) }}" />
                </div>

                <div class="form-group">
                    <label>userIP</label>
                    <input class="form-control" name="userIP" placeholder="127.0.0.1" type="text" value="{{ request()->old('userIP', $registration->userIP) }}" />
                </div>
                <div class="form-group">
                    <label>userSessionID</label>
                    <input class="form-control" name="userSessionID" placeholder="userSessionID" type="text" value="{{ request()->old('userSessionID', $registration->userSessionID) }}" />
                </div>
                <div class="form-group">
                    <label>userAgent</label>
                    <input class="form-control" name="userAgent" placeholder="Mozilla" type="text" value="{{ request()->old('userAgent', $registration->userAgent) }}" />
                </div>
                <div class="form-group">
                    <label>userLanguage</label>
                    <input class="form-control" name="userLanguage" placeholder="en-US" type="text" value="{{ request()->old('userLanguage', $registration->userLanguage) }}" />
                </div>
                <div class="form-group">
                    <label>recurrOpt</label>
                    <select class="form-control" name="recurrOpt">
                        <option></option>
                        @foreach ($registration->getRecurrOptOptions() as $recurrOpt => $recurrOptName)
                            <option {{ $recurrOpt == request()->old('recurrOpt', $registration->recurrOpt) ? 'selected' : '' }} value="{{ $recurrOpt }}">{{ $recurrOptName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>cartData</label>
                    <input class="form-control" name="cartData" placeholder="(JSON Format)" type="text" value="{{ request()->old('cartData', $registration->cartData) }}" />
                </div>
                <div class="form-group">
                    <label>worker</label>
                    <input class="form-control" name="worker" placeholder="worker" type="text" value="{{ request()->old('worker', $registration->worker) }}" />
                </div>
                <div class="form-group">
                    <label>merFixAcctId</label>
                    <input class="form-control" name="merFixAcctId" placeholder="14015824" type="text" value="{{ request()->old('merFixAcctId', $registration->merFixAcctId) }}" />
                </div>
                <div class="form-group">
                    <label>vacctValidDt</label>
                    <input class="form-control" name="vacctValidDt" placeholder="20180404" type="text" value="{{ request()->old('vacctValidDt', $registration->vacctValidDt) }}" />
                </div>
                <div class="form-group">
                    <label>vacctValidTm</label>
                    <input class="form-control" name="vacctValidTm" placeholder="235959" type="text" value="{{ request()->old('vacctValidTm', $registration->vacctValidTm) }}" />
                </div>
                <div class="form-group">
                    <label>paymentExpDt</label>
                    <input class="form-control" name="paymentExpDt" placeholder="20180404" type="text" value="{{ request()->old('paymentExpDt', $registration->paymentExpDt) }}" />
                </div>
                <div class="form-group">
                    <label>paymentExpTm</label>
                    <input class="form-control" name="paymentExpTm" placeholder="235959" type="text" value="{{ request()->old('paymentExpTm', $registration->paymentExpTm) }}" />
                </div>
                <div class="form-group">
                    <label>payValidDt</label>
                    <input class="form-control" name="payValidDt" placeholder="20180404" type="text" value="{{ request()->old('payValidDt', $registration->payValidDt) }}" />
                </div>
                <div class="form-group">
                    <label>payValidTm</label>
                    <input class="form-control" name="payValidTm" placeholder="235959" type="text" value="{{ request()->old('payValidTm', $registration->payValidTm) }}" />
                </div>

                <div class="form-group">
                    <label>tXid</label>
                    <input class="form-control" name="tXid" placeholder="BM...315" type="text" value="{{ request()->old('tXid', $registration->tXid) }}" />
                </div>
                <div class="form-group">
                    <label>mitraCd</label>
                    <select class="form-control" name="mitraCd">
                        <option></option>
                        @foreach ($nicepayCode->getMitraCodeOptions() as $mitraCodeValue => $mitraCode)
                            <option {{ $mitraCodeValue == request()->old('mitraCd', $registration->mitraCd) ? 'selected' : '' }} value="{{ $mitraCodeValue }}">{{ $mitraCode }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>mRefNo</label>
                    <input class="form-control" name="mRefNo" placeholder="bankcd123456789" type="text" value="{{ request()->old('mRefNo', $registration->mRefNo) }}" />
                </div>
                <div class="form-group">
                    <label>timeStamp</label>
                    <input class="form-control" name="timeStamp" placeholder="20180404165639" type="text" value="{{ request()->old('timeStamp', $registration->timeStamp) }}" />
                </div>
                <div class="form-group">
                    <label>version</label>
                    <input class="form-control" name="version" placeholder="D2" type="text" value="{{ request()->old('version', $registration->version) }}" />
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">NICEPay Payment Page</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>hide optDisplayCB</label>
                            <select class="form-control" name="optDisplayCB">
                                @foreach ($registration->getOptDisplayCBOptions() as $optDisplayCBValue => $optDisplayCB)
                                    <option {{ $optDisplayCBValue == request()->old('optDisplayCB', $registration->getOptDisplayCB()) ? 'selected' : '' }} value="{{ $optDisplayCBValue }}">{{ $optDisplayCB }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>hide optDisplayBL</label>
                            <select class="form-control" name="optDisplayBL">
                                @foreach ($registration->getOptDisplayBLOptions() as $optDisplayBLValue => $optDisplayBL)
                                    <option {{ $optDisplayBLValue == request()->old('optDisplayBL', $registration->optDisplayBL) ? 'selected' : '' }} value="{{ $optDisplayBLValue }}">{{ $optDisplayBL }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>isCheckPaymentExptDt</label>
                            <select class="form-control" name="isCheckPaymentExptDt">
                                @foreach ($registration->getIsCheckPaymentExptDtOptions() as $isCheckPaymentExptDtValue => $isCheckPaymentExptDt)
                                    <option {{ $isCheckPaymentExptDtValue == request()->old('isCheckPaymentExptDt', $registration->isCheckPaymentExptDt) ? 'selected' : '' }} value="{{ $isCheckPaymentExptDtValue }}">{{ $isCheckPaymentExptDt }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <div class="form-group">
                    <input name="auto_submit" type="hidden" value="{{ request()->input('auto_submit', '1') }}" />
                    <input class="btn btn-success" type="submit" value="@lang('cms.save')" />
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
    if ($('[name=auto_submit]').val() == '1') {
        $('form').submit();
    }
    </script>
@endpush
