@extends('layouts::blank')

@section('contents')
    CallBackUrl
    <ul>
        <li>id: {{ $callBackUrl->id }}</li>
        <li>resultCd: {{ $callBackUrl->resultCd }}</li>
        <li>resultMsg: {{ $callBackUrl->resultMsg }}</li>
        <li>tXid: {{ $callBackUrl->tXid }}</li>
        <li>referenceNo: {{ $callBackUrl->referenceNo }}</li>

        <li>amount: {{ $callBackUrl->amount }}</li>
        <li>transDt: {{ $callBackUrl->transDt }}</li>
        <li>transTm: {{ $callBackUrl->transTm }}</li>
        <li>description: {{ $callBackUrl->description }}</li>
        <li>receiptCode: {{ $callBackUrl->receiptCode }}</li>

        <li>payNo: {{ $callBackUrl->payNo }}</li>
        <li>mitraCd: {{ $callBackUrl->mitraCd }}</li>
        <li>authNo: {{ $callBackUrl->authNo }}</li>
        <li>bankVacctNo: {{ $callBackUrl->bankVacctNo }}</li>
        <li>bankCd: {{ $callBackUrl->bankCd }}</li>

        <li>
            data:
            <pre>{{ json_encode(json_decode($callBackUrl->data), JSON_PRETTY_PRINT) }}</pre>
        </li>
        <li>created_at: {{ $callBackUrl->created_at }}</li>
        <li>updated_at: {{ $callBackUrl->updated_at }}</li>
    </ul>
@endsection
