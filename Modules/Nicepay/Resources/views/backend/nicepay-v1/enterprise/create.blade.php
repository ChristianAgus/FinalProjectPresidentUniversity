@extends('layouts::blank')

@section('contents')
    <form action="{{ route('nicepay.backend.nicepay-v1.enterprise.store') }}" class="{{ request()->input('auto_submit', '1') == '1' ? 'hidden' : '' }}" method="post">
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
                    <label>referenceNo *</label>
                    <input class="form-control" name="referenceNo" placeholder="Refe12345" required type="text" value="{{ request()->old('referenceNo', $registration->referenceNo) }}" />
                </div>
                <div class="form-group">
                    <label>goodsNm *</label>
                    <input class="form-control" name="goodsNm" placeholder="product tiket" required type="text" value="{{ request()->old('goodsNm', $registration->goodsNm) }}" />
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
                    <label>billingCity *</label>
                    <input class="form-control" name="billingCity" placeholder="jakarta" required type="text" value="{{ request()->old('billingCity', $registration->billingCity) }}" />
                </div>
                <div class="form-group">
                    <label>billingState *</label>
                    <input class="form-control" name="billingState" placeholder="Jakarta" required type="text" value="{{ request()->old('billingState', $registration->billingState) }}" />
                </div>

                <div class="form-group">
                    <label>billingPostCd *</label>
                    <input class="form-control" name="billingPostCd" placeholder="12345" required type="text" value="{{ request()->old('billingPostCd', $registration->billingPostCd) }}" />
                </div>
                <div class="form-group">
                    <label>billingCountry *</label>
                    <input class="form-control" name="billingCountry" placeholder="Indonesia" required type="text" value="{{ request()->old('billingCountry', $registration->billingCountry) }}" />
                </div>
                <div class="form-group">
                    <label>callBackUrl *</label>
                    <input class="form-control" name="callBackUrl" placeholder="https://merchant.com/callback" required type="text" value="{{ request()->old('callBackUrl', $registration->callBackUrl) }}" />
                </div>
                <div class="form-group">
                    <label>dbProcessUrl *</label>
                    <input class="form-control" name="dbProcessUrl" placeholder="https://merchant.com/dbprocess" required type="text" value="{{ request()->old('dbProcessUrl', $registration->dbProcessUrl) }}" />
                </div>
                <div class="form-group">
                    <label>description *</label>
                    <input class="form-control" name="description" placeholder="order again" required type="text" value="{{ request()->old('description', $registration->description) }}" />
                </div>
                <div class="form-group">
                    <label>merchantToken *</label>
                    <input class="form-control" name="merchantToken" placeholder="c00ce98a2a700ecbe8579227b596683a0117e7f6cdacb6aef09a81e7785868fb" required type="text" value="{{ request()->old('merchantToken', $registration->getMerchantToken()) }}" />
                </div>
                <div class="form-group">
                    <label>userIP *</label>
                    <input class="form-control" name="userIP" placeholder="127.0.0.1" required type="text" value="{{ request()->old('userIP', $registration->userIP) }}" />
                </div>
                <div class="form-group">
                    <label>cartData *</label>
                    <input class="form-control" name="cartData" placeholder="(JSON Format)" required type="text" value="{{ request()->old('cartData', $registration->cartData) }}" />
                </div>

                <div class="credit_card_div form-group hidden">
                    <label>instmntType *</label>
                    <select class="form-control" name="instmntType">
                        <option></option>
                        @foreach ($nicepayCode->getInstallmentTypeOptions() as $installmentTypeCode => $installmentType)
                            <option {{ $installmentTypeCode == request()->old('instmntType', $registration->instmntType) ? 'selected' : '' }} value="{{ $installmentTypeCode }}">{{ $installmentType }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="creditCardDiv form-group hidden">
                    <label>instmntMon *</label>
                    <input class="creditCardInput form-control" name="instmntMon" placeholder="1" type="number" value="{{ request()->old('instmntMon', $registration->instmntMon) }}" />
                </div>
                <div class="creditCardDiv form-group hidden">
                    <label>cardCvv *</label>
                    <input class="creditCardInput form-control" name="cardCvv" placeholder="123" type="number" value="{{ request()->old('cardCvv', $registration->cardCvv) }}" />
                </div>
                <div class="creditCardDiv form-group hidden">
                    <label>onePassToken *</label>
                    <input class="creditCardInput form-control" name="onePassToken" placeholder="9338d54573688ae18e175240b0257de48d89c6ef1c9c7b5c094dc4beed9e435f" type="number" value="{{ request()->old('onePassToken', $registration->onePassToken) }}" />
                </div>
                <div class="creditCardDiv form-group hidden">
                    <label>recurrOpt *</label>
                    <select class="creditCardInput form-control" name="recurrOpt">
                        <option></option>
                        @foreach ($registration->getRecurrOptOptions() as $recurrOpt => $recurrOptName)
                            <option {{ $recurrOpt == request()->old('recurrOpt', $registration->recurrOpt) ? 'selected' : '' }} value="{{ $recurrOpt }}">{{ $recurrOptName }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group hidden virtualAccountDiv">
                    <label>bankCd *</label>
                    <select class="form-control virtualAccountInput" name="bankCd">
                        <option></option>
                        @foreach ($nicepayCode->getBankCodeOptions() as $bankCode => $bank)
                            <option {{ $bankCode == request()->old('bankCd', $registration->bankCd) ? 'selected' : '' }} value="{{ $bankCode }}">{{ $bank }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group hidden virtualAccountDiv">
                    <label>vacctValidDt *</label>
                    <input class="form-control virtualAccountInput" name="vacctValidDt" placeholder="20180429" type="text" value="{{ request()->old('vacctValidDt', $registration->vacctValidDt) }}" />
                </div>
                <div class="form-group hidden virtualAccountDiv">
                    <label>vacctValidTm *</label>
                    <input class="form-control virtualAccountInput" name="vacctValidTm" placeholder="225959" type="text" value="{{ request()->old('vacctValidTm', $registration->vacctValidTm) }}" />
                </div>

                <div class="clickPayDiv convenienceStoreDiv eWalletDiv form-group hidden">
                    <label>mitraCd *</label>
                    <select class="clickPayInput convenienceStoreInput eWalletInput form-control" name="mitraCd">
                        <option></option>
                        @foreach ($nicepayCode->getMitraCodeOptions() as $mitraCodeValue => $mitraCode)
                            <option {{ $mitraCodeValue == request()->old('mitraCd', $registration->mitraCd) ? 'selected' : '' }} value="{{ $mitraCodeValue }}">{{ $mitraCode }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="clickPayDiv form-group hidden">
                    <label>clickPayNo *</label>
                    <input class="clickPayInput form-control" name="clickPayNo" placeholder="Clickpay card number" type="text" value="{{ request()->old('clickPayNo', $registration->clickPayNo) }}" />
                </div>
                <div class="clickPayDiv form-group hidden">
                    <label>dataField3 *</label>
                    <input class="clickPayInput form-control" name="dataField3" placeholder="Token input 3 for Clickpay" type="text" value="{{ request()->old('dataField3', $registration->dataField3) }}" />
                </div>
                <div class="clickPayDiv form-group hidden">
                    <label>clickPayToken *</label>
                    <input class="clickPayInput form-control" name="clickPayToken" placeholder="Code response from token" type="text" value="{{ request()->old('clickPayToken', $registration->clickPayToken) }}" />
                </div>

                <div class="convenienceStoreDiv form-group hidden">
                    <label>payValidDt</label>
                    <input class="convenienceStoreInput form-control" name="payValidDt" placeholder="CVS Expiry Date (YYYYMMDD)" type="text" value="{{ request()->old('payValidDt', $registration->payValidDt) }}" />
                </div>
                <div class="convenienceStoreDiv form-group hidden">
                    <label>payValidTm</label>
                    <input class="convenienceStoreInput form-control" name="payValidTm" placeholder="CVS Expiry Time (HH24MISS)" type="text" value="{{ request()->old('payValidTm', $registration->payValidTm) }}" />
                </div>

                <div class="form-group">
                    <label>billingAddr</label>
                    <input class="form-control" name="billingAddr" placeholder="billing address" type="text" value="{{ request()->old('billingAddr', $registration->billingAddr) }}" />
                </div>
                <div class="form-group">
                    <label>deliveryNm</label>
                    <input class="form-control" name="deliveryNm" placeholder="Delivery name" type="text" value="{{ request()->old('deliveryNm', $registration->deliveryNm) }}" />
                </div>
                <div class="form-group">
                    <label>deliveryPhone</label>
                    <input class="form-control" name="deliveryPhone" placeholder="02112345678" type="text" value="{{ request()->old('deliveryPhone', $registration->deliveryPhone) }}" />
                </div>
                <div class="form-group">
                    <label>deliveryAddr</label>
                    <input class="form-control" name="deliveryAddr" placeholder="Delivery Address" type="text" value="{{ request()->old('deliveryAddr', $registration->deliveryAddr) }}" />
                </div>
                <div class="form-group">
                    <label>deliveryEmail</label>
                    <input class="form-control" name="deliveryEmail" placeholder="buyer@merhcant.com" type="text" value="{{ request()->old('deliveryEmail', $registration->deliveryEmail) }}" />
                </div>
                <div class="form-group">
                    <label>deliveryCity</label>
                    <input class="form-control" name="deliveryCity" placeholder="Jakarta" type="text" value="{{ request()->old('deliveryCity', $registration->deliveryCity) }}" />
                </div>
                <div class="form-group">
                    <label>deliveryState</label>
                    <input class="form-control" name="deliveryState" placeholder="Jakarta" type="text" value="{{ request()->old('deliveryState', $registration->deliveryState) }}" />
                </div>
                <div class="form-group">
                    <label>deliveryPostCd</label>
                    <input class="form-control" name="deliveryPostCd" placeholder="12345" type="text" value="{{ request()->old('deliveryPostCd', $registration->deliveryPostCd) }}" />
                </div>
                <div class="form-group">
                    <label>deliveryCountry</label>
                    <input class="form-control" name="deliveryCountry" placeholder="Indonesia" type="text" value="{{ request()->old('deliveryCountry', $registration->deliveryCountry) }}" />
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
                    <label>reqDt</label>
                    <input class="form-control" name="reqDt" placeholder="20180423" type="text" value="{{ request()->old('reqDt', $registration->reqDt) }}" />
                </div>
                <div class="form-group">
                    <label>reqTm</label>
                    <input class="form-control" name="reqTm" placeholder="235959" type="text" value="{{ request()->old('reqTm', $registration->reqTm) }}" />
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
                    <label>userSessionID</label>
                    <input class="form-control" name="userSessionID" placeholder="SessionUser1234" type="text" value="{{ request()->old('userSessionID', $registration->userSessionID) }}" />
                </div>
                <div class="form-group">
                    <label>userAgent</label>
                    <input class="form-control" name="userAgent" placeholder="Mozilla" type="text" value="{{ request()->old('userAgent', $registration->userAgent) }}" />
                </div>
                <div class="form-group">
                    <label>userLanguage</label>
                    <input class="form-control" name="userLanguage" placeholder="en-US" type="text" value="{{ request()->old('userLanguage', $registration->userLanguage) }}" />
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

    <script>
    class BackendNicepayV1EnterpriseCreate
    {
        constructor()
        {
            this.virtualAccountDiv = document.querySelector('.virtualAccountDiv');
            this.virtualAccountInput = document.querySelector('.virtualAccountInput');
            this.payMethod = document.querySelector('[name=payMethod]');
        }

        payMethodChange()
        {
            this.virtualAccountDiv.classList.add('hidden');
            this.virtualAccountInput.required = false;

            switch (this.payMethod.value) {
                case '02':
                this.virtualAccountDiv.classList.remove('hidden');
                this.virtualAccountInput.required = true;
                    break;
                default:

            }
        }
    }

    var backendNicepayV1EnterpriseCreate = new BackendNicepayV1EnterpriseCreate;
    backendNicepayV1EnterpriseCreate.payMethod.addEventListener('change', function() {
        backendNicepayV1EnterpriseCreate.payMethodChange()
    });
    backendNicepayV1EnterpriseCreate.payMethodChange();
    </script>
@endpush
