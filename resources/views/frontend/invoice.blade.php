@extends('layouts.user.app')
@section('title', "Invoice")
@section('content')
<div class="container">
    <div class="row my-3">
        @if($order->payment_status == 1 && $order->status == "Closed")
        <div class="col-md-12 text-right mb-2">
            <a href="{{ route('frontend.download_invoice', $order->oc_number) }}" class="btn btn-primary"><i class="fa fa-download"></i></a>
        </div>
        @endif
        @if($order->payment_status == 0 && $order->status == "Order")
        <div class="col-md-12">
            <div class="alert alert-warning">
                Your transaction is pending confirmation from the admin.
                <br>
                Please present this to the sales representative.
                <b id="order-number">
                    {{ $order->oc_number }} 
                </b>
                <button type="button" class="btn btn-sm btn-secondary" id="copy-button" onclick="copyToClipboard()" style="padding: 0.2rem .5rem;font-size: 0.7rem;line-height: 1;border-radius: .2rem;">Copy</button>
            </div>            
        </div>
        @endif

        @if($order->payment_status == 1 && $order->status == "Closed")
        <div class="col-md-12">
            <div class="alert alert-success">
                Your payment has been verified.
            </div>
        </div>
        @endif
        
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
                    @if($order->order_notes != null)
                    <tr>
                        <td>Notes</td>
                        <td class="px-3">:</td>
                        <td>{{ $order->order_notes }}</td>
                    </tr>
                    @endif
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
            <h5>Sales Support</h5>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>

function copyToClipboard() {
        var orderNumber = document.getElementById("order-number");
        var range = document.createRange();
        range.selectNode(orderNumber);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
        document.execCommand("copy");
        window.getSelection().removeAllRanges();
    }

</script>
@endsection



