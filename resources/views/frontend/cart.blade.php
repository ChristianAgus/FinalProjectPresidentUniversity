@extends('layouts.user.app')
@section('title', "Cart")
@section('content')

<section class="shoping-cart spad">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="shoping__cart__table">

                    <div class="wrap_cart">
                        @foreach($order_detail as $key => $order_details)
                            <div class="item_cart" id="tr_cart{{ $order_details->id }}">
                                <div class="row">
                                    <div class="col-12">
                                        <h5>{{ $order_details->products->name }}</h5>
                                        <h6>{{ $order_details->products->sku }}</h6>

                                    </div>
                                    <div class="col-4 shoping__cart__item">
                                        <img src="{{ asset('uploads/master/product/image/'.$order_details->products->image) }}" alt="{{ $order_details->products->name }}">
                                    </div>
                                    <div class="col-8 shoping__cart__quantity">
                                        <div class="quantity">
                                            <div class="pro-qty">
                                                <button class="dec{{ $order_details->id }} qtybtn btn btn-danger" data-orderID="{{ $order_details->order_id }}" data-orderDetailID="{{ $order_details->id }}" {{ $order_details->qty <= 1 ? 'disabled' : '' }}>-</button>
                                                <input type="text" data-id value="{{ $order_details->qty }}" min="1">
                                                <button class="inc{{ $order_details->id }} qtybtn btn btn-success" data-orderID="{{ $order_details->order_id }}" data-orderDetailID="{{ $order_details->id }}">+</button>
                                            </div>
                                        </div>
                                        <h6 class="shoping__cart__price">Rp{{ number_format($order_details->products->price, 0) }}</h6>
                                        <h3 class="shoping__cart__total" id="sub_total{{ $order_details->id }}">Rp{{ number_format($order_details->sub_total, 0) }}</h3>
                                    </div>

                                    <div class="wrap_btn shoping__cart__item__close" data-orderID="{{ $order_details->order_id }}" data-orderDetailID="{{ $order_details->id }}">
                                        <span class="icon_close"></span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
            <div class="col-lg-6">
                <!-- <div class="shoping__continue">
                    <div class="shoping__discount">
                        <h5>Discount Codes</h5>
                        <form action="#">
                            <input type="text" placeholder="Enter your coupon code">
                            <button type="submit" class="site-btn">APPLY COUPON</button>
                        </form>
                    </div>
                </div> -->
            </div>
            <div class="col-lg-6">
                <div class="shoping__checkout">
                    <h5>Cart Total</h5>
                    <ul>
                        <!-- <li>Subtotal <span>$454.98</span></li> -->
                        <li>Total <span id="grand_total">Rp{{ number_format($grand_cart, 0) }}</span></li>
                    </ul>
                    <a href="{{ route('frontend.get_order') }}" class="primary-btn stock">PROCEED TO CHECKOUT</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('css')
<link href="{{ asset('assets/toastr/build/toastr.min.css') }}" rel="stylesheet">
@endsection
@section('script')
<script src="{{ asset('assets/toastr/build/toastr.min.js') }}"></script>

<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    var proQty = $('.pro-qty');
    proQty.on('click', '.qtybtn', function () {
        var orderID         = $(this).attr("data-orderID");
        var orderDetailID   = $(this).attr("data-orderDetailID");

        var $button = $(this);
        var oldValue = $button.parent().find('input').val();
        if ($button.hasClass('inc'+orderDetailID)) {
            var qty = parseFloat(oldValue) + 1;
            $('.dec'+orderDetailID).attr('disabled', false);
        } else if($button.hasClass('dec'+orderDetailID))  {
            console.log(oldValue);
            if (oldValue >= 1) {
                if(parseFloat(oldValue) - 1 == 1) {
                    $(this).attr('disabled', true);
                }
                var qty = parseFloat(oldValue) - 1;
            } else {
                qty = 1;
            }
        } else {
            alert('Data tidak ditemukan!');
        }
        $.ajax({
            url: "{{ route('frontend.set_cart') }}",
            type: 'POST',
            data:{orderDetID:orderDetailID, qty:qty, orderID:orderID},
            success: function (json) {
                if (json.success == true) {
            $button.parent().find('input').val(qty);
            $('#sub_total' + orderDetailID).html(json.sub_total);
            $('#grand_total').html(json.grand_total);
            $('#grand_cart,#grand_cart_m').html(json.grand_total);
        } else {
            if (json.hasOwnProperty('stock_exceeded') && json.stock_exceeded == true) {
                toastr.error("Jumlah yang diminta melebihi stok yang tersedia.");
            } else {
                toastr.error(json.message);
            }
            $button.parent().find('input').val(oldValue);
        }
    }, error: function (xhr, ajaxOptions, thrownError) {
                var err = eval("(" + xhr.responseText + ")");
                alert(err.message);
                $button.parent().find('input').val(oldValue);
            },
        });
    });



    $('.shoping__cart__item__close').bind('click', function () {
        var orderID         = $(this).attr("data-orderID");
        var orderDetailID   = $(this).attr("data-orderDetailID");
        $.ajax({
            url: "{{ route('frontend.remove_cart') }}",
            type: 'POST',
            data:{orderDetID:orderDetailID, orderID:orderID},
            success: function (json) {
                if (json.success == true ){
                    $('#tr_cart'+orderDetailID).remove();
                    $('#grand_total').html(json.grand_total);
                    $('#grand_cart,#grand_cart_m').html(json.grand_total);
                } else {
                    alert(json.message);
                }
            }, error: function (xhr, ajaxOptions, thrownError) {
                var err = eval("(" + xhr.responseText + ")");
                alert(err.message);
            },
        });
    });
</script>
@endsection
