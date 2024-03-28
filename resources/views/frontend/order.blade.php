@extends('layouts.user.app')
@section('title', "Checkout")
@section('content')
<section class="checkout spad">
    <div class="container">
        <div class="checkout__form">
            <h4>Billing Details</h4>
            <form action="{{ route('frontend.create_order') }}" id="OrderForm" method="post">
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-lg-8 col-md-6">
                        <div class="checkout__input">
                            <p>Full Name<span>*</span></p>
                            <input class="form-control" type="text" maxlength="100" name="full_name" value="{{ $order->customer_name ?? '' }}" placeholder="Enter Full Name" required>
                        </div>
                        <div class="checkout__input">
                            <p>Phone Number<span>*</span></p>
                            <input class="form-control" type="text" name="phone_number" maxlength="16" minlength="7" value="{{ $order->customer_phone ?? '' }}" placeholder="Enter Phone Number" onkeydown="return ( event.ctrlKey || event.altKey || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) || (95<event.keyCode && event.keyCode<106)|| (event.keyCode==8) || (event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) || (event.keyCode==46) )" required>
                        </div>
                        <div class="checkout__input">
                            <p>Address<span>*</span></p>
                            <textarea class="form-control" name="address" placeholder="Enter Street Address" required>{{ $order->customer_address ?? "" }}</textarea>
                        </div>
                        <div class="checkout__input">
                            <p>Payment<span>*</span></p>
                            <select name="pay_category" id="payment_select" required>
                                <option value="" disabled selected>Please select</option>
                                <option value="Cash">Cash</option>
                                <option value="Transfer">Transfer</option>
                                <option value="QRIS">QRIS</option>
                            </select>
                        </div><br/><br/>
                        
                        <div class="checkout__input" id="proof_formQRIS" style="display:none;">
                            <div class="checkout__order" id="bank_info" style="display:none;">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Bank Account</h4>
                                        <p class="card-text">PT. Haldin Pacific Semesta</p>
                                        <p class="card-text">Bank Central Asia</p>
                                        <p class="card-text">Bank Account No : 869.11-8888.4</p>
                                    </div>
                                </div>
                            </div>                            
                            <div class="checkout__input" id="qris_form" style="display:none;">
                                <img src="{{ asset('assets/img/QRIS.png') }}" alt="QRIS Image" width="200">
                            </div>
                            <p>Proof of Payment<span>*</span></p>
                            <input type="file" name="proof_of_payment" class="from-control" accept="image/*">
                        </div>
                        <div class="checkout__input">
                            <p>Order notes</p>
                            <input class="form-control" name="order_notes" type="text" placeholder="Notes about your order, e.g. special notes for delivery.">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="checkout__order">
                            <h4>Your Order</h4>
                            <div class="checkout__order__products">Products <span>Total</span></div>
                            <ul>
                                @foreach($order_detail as $order_details)
                                <li>{{ $order_details->products->name }} ({{ $order_details->qty }}) <span>Rp{{ number_format($order_details->sub_total, 0) }}</span></li>
                                @endforeach
                            </ul>
                            <div class="checkout__order__total">Grand Total <span>Rp{{ number_format($orders->grand_total, 0) }}</span></div>
                            <input type="hidden" name="order_id" value="{{ $orders->id }}">
                            <div class="checkout__input__checkbox">
                                <label for="paypal">
                                    Are you sure to checkout?
                                    <input type="checkbox" value="Yes" name="check_confirm" id="paypal">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                      
                            <button type="submit" class="site-btn" id="btnSubmit" style="display:none;">PLACE ORDER</button>
                            <button type="button" class="site-btn" id="btnLoading" style="display:none;"><i class="fa fa-spinner fa-spin"></i></button>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('css')
<link href="{{ asset('assets/toastr/build/toastr.min.css') }}" rel="stylesheet">
<style>
    .nice-select {
        width : 100%;
    }
    #qris_form img {
    display: block;
    margin: 0 auto;
    border: 3px solid #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    max-width: 100%;
    height: auto;
}
</style>
@endsection

@section('script')
<script src="{{ asset('assets/toastr/build/toastr.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });


    $(document).on('change', '[name="check_confirm"]', function() {
        if ($(this).is(':checked')){
            $('#btnSubmit').show();
        } else {
            $('#btnSubmit').hide();
        }
    });
    $(function(){
        $('#payment_select').on('change paste keyup', function(){
            var payment = $(this).val();
            if(payment == "Transfer") {
                $('#proof_formQRIS').show();
                $('#bank_info').show();
                $('#qris_form').hide();
                $('[name="proof_of_payment"]').attr("required", "true");
            } else if (payment == "QRIS") {
                $('#proof_formQRIS').show();
                $('#bank_info').hide();
                $('#qris_form').show();
                $('[name="proof_of_payment"]').attr("required", "true");
            } else {
                $('#proof_formQRIS').hide();
                $('#bank_info').hide();
                $('#qris_form').hide();
                $('[name="proof_of_payment"]').val(null);
                $('[name="proof_of_payment"]').removeAttr("required");;
            }
        });
    });
    $("#OrderForm").submit(function(e){
        e.preventDefault();    
        var formData = new FormData(this);
        $("#btnLoading").show();
        $("#btnSubmit").hide();
         $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function (data) {
                $("#btnSubmit").show();
                $("#btnLoading").hide();
                if (data.success == false) {
                    toastr.warning(data.message);
                } else {
                    toastr.success(data.message, 'Haldinfoods,');
                    window.location.href = '/invoice/'+ data.invoice;
                }
            }, error: function (xhr, ajaxOptions, thrownError) {
                $("#btnSubmit").show();
                $("#btnLoading").hide();
                var err = eval("(" + xhr.responseText + ")");
                toastr.error(err.message);
            },    
            cache: false,
            contentType: false,
            processData: false
        });
    });
</script>
@endsection