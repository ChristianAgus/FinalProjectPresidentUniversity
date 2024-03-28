@extends('layouts.user.app')
@section('title', "Invoice")
@section('content')
<div class="container">
    <div class="row my-3">
        <div class="col-md-12 text-right" id="out1">
            <button type="button" class="btn btn-primary print-window" onclick="printStrukMobile({{ $order->id }})"><i class="fa fa-print"></i> Print Struk Mobile</button><span>
                <span>&nbsp;&nbsp;</span>
            <button type="button" class="btn btn-primary print-window" onclick="printInvoice('{{ $order->oc_number }}')"><i class="fa fa-file-invoice"></i> Print Invoice</button>          
        </div>
        <div class="col-md-6">
            <p class="mb-0">INVOICE TO</p>
            <h5 class="mb-0"><b>{{ $order->customer_name }}</b></h5>
            <p class="mb-0">{{ $order->customer_phone }}</p>
            <p class="mb-0">{{ $order->customer_address }}</p>
        </div>
        <div class="col-md-6">
            <table>
                <tbody>
                    <tr>
                        <td>Invoice No</td>
                        <td class="px-3">:</td>
                        <td>{{ $order->oc_number }}</td>
                    </tr>
                    <tr>
                        <td>Order Date</td>
                        <td class="px-3">:</td>
                        <td>{{ Carbon\Carbon::parse($order->order_date)->formatLocalized("%B, %d %Y") }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td class="px-3">:</td>
                        <td>Paid</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">NO</th>
                            <th scope="col">PRODUCT</th>
                            <th scope="col">QTY</th>
                            <th scope="col">PRICE</th>
                            <th scope="col">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order_detail as $order_details)
                        <tr>
                            <td>1</td>
                            <td>
                                <b>{{ $order_details->products->name }}</b>
                            </td>
                            <td>{{ $order_details->qty }}</td>
                            <td>{{ number_format($order_details->price )}}</td>
                            <td>{{ number_format($order_details->sub_total )}}</td>
                        </tr>
                        @endforeach
                        <tr style="background: #E6E4E7; color: #0099D5;">
                            <td colspan="3"></td>
                            <td><b>GRAND TOTAL</b></td>
                            <td><b>{{ number_format($order->grand_total) }}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-6">
            <h5>Terms and Conditions</h5>
            <p>Invoice was created on a computer and is valid without the signature and seal.</p>
        </div>
        <div class="col-md-6">
            <h5>{{ Auth::user()->name }}</h5>
            <p>Haldinfoods</p>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    @media print {
        #out1,#myHeader,#myFooter {
             box-shadow: none;
             display: none;
        }
        .print:last-child {
            page-break-after: auto;
        }
    }
</style>
@endsection


@section('script')
<script>
    function printStrukMobile(orderId) {
        var url = '/exhibition/master/history/invoice/' + orderId;
        window.location.href = url;
    }
    function printInvoice(ocNumber) {
        var url = '/invadmin/pdf/' + ocNumber;
        window.location.href = url;
    }
    $('.print-window').click(function() {
        window.print();
    });
</script>
@endsection

