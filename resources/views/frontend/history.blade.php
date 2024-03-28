@extends('layouts.user.app')
@section('title', "History")
@section('content')

<section class="shoping-cart spad">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @foreach($orders as $order)
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $order->oc_number }}</h5>
                                        <p class="card-text">Price: Rp{{ number_format($order->grand_total, 0) }}</p>
                                        @if($order->payment_status == 0 && $order->status == "Order")
                                            <div class="alert alert-warning" role="alert">
                                                Your transaction is pending confirmation from the admin. Please present this to the sales representative.
                                            </div>
                                        @endif
                                        @if($order->payment_status == 1 && $order->status == "Closed")
                                            <div class="alert alert-success" role="alert">
                                                Your payment has been verified.
                                            </div>
                                        @endif
                                        <div class="text-center mt-2">
                                            <div class="text-center mt-2">
                                                <a href="{{ route('frontend.invoice_detail', ['oc_number' => $order->oc_number]) }}" class="btn btn-primary rounded-pill">
                                                    <i class="fas fa-arrow-down"></i> View  
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-12">
                <div class="shoping__cart__btns">
                    <a href="{{ route('frontend.home') }}" class="primary-btn cart-btn cart-btn-right">CONTINUE SHOPPING</a>
                    <!-- <a href="#" class="primary-btn cart-btn cart-btn-right"><span class="icon_loading"></span>
                        Upadate Cart
                    </a> -->
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('css')
<style>
.card {
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.card-title {
    font-size: 1.25rem;
    font-weight: bold;
}

.card-text {
    color: #555;
}

.alert {
    padding: 1rem;
    border-radius: 8px;
}

.alert-warning {
    background-color: #fff3cd;
    border-color: #ffeeba;
    color: #856404;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.btn {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    color: #fff;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    color: #fff;
}

.btn-primary:hover,
.btn-secondary:hover {
    opacity: 0.8;
}
</style>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });


</script>
@endsection
