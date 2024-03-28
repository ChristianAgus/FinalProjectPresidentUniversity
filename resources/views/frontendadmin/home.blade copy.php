@extends('layouts.user.app')
@section('title', "Home")
@section('content')


<section class="featured spad">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form>
                    <div class="form-group search_field">
                        <label for="search_item">Search Products</label>
                        <input type="text" class="form-control" id="search_item" placeholder="keywords...">
                        <i class="fas fa-search"></i>
                      </div>
                </form>
            </div>
            <div class="col-12">
                {{-- <div class="section-title">
                    <h2>Item</h2>
                </div> --}}
                <div class="featured__controls">
                    <ul>
                        <li class="active" data-filter="*">All</li>
                        @foreach($category as $categories)
                        <li data-filter=".{{ $categories->name }}">{{ $categories->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="row featured__filter" id="products">
            @foreach($product as $products)

            <div class="col-12 mix {{ $products->categories->name }}" id="col_prod">
                <div class="featured__item">
                    <a class="cart{{ $products->id }}" id="addcart" href="javascript:void(0)" data-id="{{ $products->id }}">
                        <div class="d-flex">
                            <div class="featured__item__pic set-bg custom_thumb" data-setbg="{{ asset('uploads/master/product/image/'.$products->image) }}"></div>
                            <div class="featured__item__text">
                                <h6>{{ $products->name }}</h6>
                                <h6>{{ $products->sku }}</h6>

                                <h5>Rp{{ number_format($products->price, 0) }}</h5>
                            </div>

                        </div>
                    </a>
                </div>
            </div>

            @endforeach
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

    $(document).on('click','#addcart',function(){
        var produkID = $(this).attr("data-id");
        $.ajax({
            url: "{{ route('frontendadmin.add_cart') }}",
            type: 'POST',
            data:{product_id:produkID},
            success: function (json) {
                if (json.success == false ){
                    toastr.warning(json.message);
                } else {
                    $('#count_cart,#count_cart_m').html(json.count_cart);
                    $('#grand_cart,#grand_cart_m').html(json.grand_cart);
                    toastr.success('<a href="{{ route("frontendadmin.get_cart") }}">'+json.message, 'Haldinfoods,</a>');
                }
            }, error: function (xhr, ajaxOptions, thrownError) {
                var err = eval("(" + xhr.responseText + ")");
                toastr.error(err.message);
            },
        });
   });

    $('#search_item').on('keyup', function(){
        var keyword = $('#search_item').val();
        $('#col_prod').remove();
        $.post('{{ route("frontendadmin.search_product") }}',
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            keyword:keyword
        },
        function(data){
            table_post_row(data);
        });
    });
    function table_post_row(res){
        var htmlView = '';
        for(let i = 0; i < res.products.length; i++){
            htmlView += '<div class="col-12 mix '+res.products[i].cat_name+'" id="col_prod">'+
                '<div class="featured__item">'+
                    '<a class="cart'+res.products[i].id+'" id="addcart" href="javascript:void(0)" data-id='+res.products[i].id+'>'+
                        '<div class="d-flex">'+
                            '<div class="featured__item__pic set-bg custom_thumb" data-setbg="{{asset("/uploads/master/product/image")}}'+'/'+res.products[i].image+'"></div>'+
                            '<div class="featured__item__text">'+
                                '<h6>'+res.products[i].name+'</h6>'+
                                '<h6>'+res.products[i].sku+'</h6>'+
                                '<h5>Rp'+parseFloat(res.products[i].price).toLocaleString(window.document.documentElement.lang)+'</h5>'+
                            '</div>'+
                        '</div>'+
                    '</a>'+
                '</div>'+
            '</div>';
        }
        $('#products').html(htmlView);
    }
</script>
@endsection
