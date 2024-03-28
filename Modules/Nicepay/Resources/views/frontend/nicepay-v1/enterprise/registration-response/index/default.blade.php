@extends('layouts::blank')

@section('contents')
    RegistrationResponse
    <ul>
        <li>id: {{ $registrationResponse->id }}</li>
        <li>resultCd: {{ $registrationResponse->resultCd }}</li>
        <li>resultMsg: {{ $registrationResponse->resultMsg }}</li>
        <li>tXid: {{ $registrationResponse->tXid }}</li>
        <li>referenceNo: {{ $registrationResponse->referenceNo }}</li>

        <li>payMethod: {{ $registrationResponse->payMethod }}</li>
        <li>amount: {{ $registrationResponse->amount }}</li>
        <li>currency: {{ $registrationResponse->currency }}</li>
        <li>goodsNm: {{ $registrationResponse->goodsNm }}</li>
        <li>billingNm: {{ $registrationResponse->billingNm }}</li>

        <li>transDt: {{ $registrationResponse->transDt }}</li>
        <li>transTm: {{ $registrationResponse->transTm }}</li>
        <li>description: {{ $registrationResponse->description }}</li>
        <li>callbackUrl: {{ $registrationResponse->callbackUrl }}</li>
        <li>authNo: {{ $registrationResponse->authNo }}</li>

        <li>recurringToken: {{ $registrationResponse->recurringToken }}</li>
        <li>preauthToken: {{ $registrationResponse->preauthToken }}</li>
        <li>ccTransType: {{ $registrationResponse->ccTransType }}</li>
        <li>vat: {{ $registrationResponse->vat }}</li>
        <li>free: {{ $registrationResponse->free }}</li>

        <li>notaxAmt: {{ $registrationResponse->notaxAmt }}</li>
        <li>bankCd: {{ $registrationResponse->bankCd }}</li>
        <li>bankVacctNo: {{ $registrationResponse->bankVacctNo }}</li>
        <li>vacctValidDt: {{ $registrationResponse->vacctValidDt }}</li>
        <li>vacctValidTm: {{ $registrationResponse->vacctValidTm }}</li>

        <li>mitraCd: {{ $registrationResponse->mitraCd }}</li>
        <li>payNo: {{ $registrationResponse->payNo }}</li>
        <li>payValidTm: {{ $registrationResponse->payValidTm }}</li>
        <li>payValidDt: {{ $registrationResponse->payValidDt }}</li>
        <li>receiptCode: {{ $registrationResponse->receiptCode }}</li>

        <li>mRefNo: {{ $registrationResponse->mRefNo }}</li>

        <li>
            data:
            <pre>{{ json_encode(json_decode($registrationResponse->data), JSON_PRETTY_PRINT) }}</pre>
        </li>
        <li>created_at: {{ $registrationResponse->created_at }}</li>
        <li>updated_at: {{ $registrationResponse->updated_at }}</li>
    </ul>
@endsection
