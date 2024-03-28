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
            <div class="col-md-3 col-xl-3 col-6 custom-col mix {{ $products->categories->name }}" id="col_prod" data-toggle="appear">
                <div class="block block-rounded">
                    <div class="block-content p-0 overflow-hidden">
                        <a class="img-link detail" href="javascript:void(0)"
                                 data-id1="{{ $products->id }}" 
                                 data-name="{{ $products->name }}"
                                 data-description="{{ $products->description }}"
                                 data-specification="{{ $products->specification }}"
                                 data-price="{{ $products->price }}" 
                                 data-image="{{ !empty($products->image) && file_exists(public_path('uploads/master/product/image/'.$products->image)) ? asset('uploads/master/product/image/'.$products->image) : asset('assets/img/loading.png') }}" 
                                 >
                            <img class="img-fluid rounded-top" 
                                 src="{{ !empty($products->image) && file_exists(public_path('uploads/master/product/image/'.$products->image)) ? asset('uploads/master/product/image/'.$products->image) : asset('assets/img/loading.png') }}" 
                                 alt="" 
                                 style="width: 220px; height: 220px;"
                                 >
                        </a>
                    </div>
                        <h4 class="font-size-h5 mb-10 clamp-text" data-toggle="tooltip" title="{{ $products->name }}">{{ $products->name }}</h4>
                        <i class="font-size-h1 font-w300 mb-5 text-success">Rp{{ number_format($products->price, 0) }}</i><br><br>
                    <div class="rating-shop-container">
                        <div class="rating-container">
                            <span class="in-stock" style="color: #28a745;">In Stock <i class="fa fa-check-circle"></i></span>  <br>
                            <span class="rating-count">(50)</span>
                        </div>
                            <i class="fa fa-cart-plus shopping-cart-icon cart{{ $products->id }}" id="addcart" href="javascript:void(0)" data-id="{{ $products->id }}"></i>
                    </div>
                </div>
            </div>
            @endforeach
      </div>
      
    </div>
</section>
<div class="modal fade" id="modaldetail" tabindex="-1" role="dialog" aria-labelledby="modal-message" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            <div class="block">
                <div>
                    <div>
                        <img class="img-fluid" id="productImage" alt="" style="498px; height: 498px;">
                    </div>
                </div>
            </div>
            <div class="block-content block-content-full block-content-sm bg-body font-size-sm">
                <h4 class="titlemodal" data-toggle="tooltip" id="name"></h4>
                <i class="pricemodal font-w300 mb-5 text-success" id="price" style="font-size: 18px;"></i><br>
            </div>
            <div class="block-content">
                <div class="accordion">
                    Description
                    <i class="fa fa-plus"></i>
                </div>
                <div class="panel">
                    <p id="descript"></p>
                </div>
            
                <div class="accordion">
                    Spesification
                    <i class="fa fa-plus"></i>
                </div>
                <div class="panel">
                    <p id="specific"></p>
                </div>
                <input type="hidden" name="id" class="detailid" value="">
            </div>
            <div class="block-content text-center">
                <button class="add-to-cart-btn" data-id=""> <i class="fa fa-cart-plus"></i> Add to Cart</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('css')
<link href="{{ asset('assets/toastr/build/toastr.min.css') }}" rel="stylesheet">
<style>
    .custom-col {
        margin-bottom: 15px;
    }
    @media (max-width: 767px) {
        .custom-col {
            margin-bottom: 15px; 
        }
    }
    .rating-shop-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    }
    .rating-container {
        display: flex;
        align-items: center;
    }
    .js-rating {
        margin-right: 5px; 
    }
    .js-rating i {
        font-size: 14px; 
    }
    .rating-count {
        font-size: 12px;
        color: #888; 
    }
    .shop-button {
        padding: 8px 16px; 
        font-size: 14px; 
        background-color: #3498db; 
        color: #fff; 
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .shop-button:hover {
        background-color: #2980b9;
    }
    .shopping-cart-icon {
        font-size: 25px; 
        margin-right: 5px; 
        margin-left: -10px; 
        color: #000; 
        transition: color 0.3s ease; 
    }
    .shopping-cart-icon:hover {
        color: #3498db; 
    }
    .shopping-cart-icon:active {
        color: #2980b9; 
    }
    .clamp-text {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden;
        -webkit-line-clamp: 1;
    } 
    .font-size-h5 {
            font-size: 20px;
        }
    @media (max-width: 767px) {
    .font-size-h5 {
            font-size: 16px;
        }
    }
    .font-size-h1 {
        font-size: 18px;
    }
    @media (max-width: 767px) {
    .font-size-h1 {
        font-size: 18px;
         }
    }
    .in-stock {
        color: #28a745;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
    }
    .in-stock.animated {
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-5px); }
        40%, 80% { transform: translateX(5px); }
    }
    .block-content {
        padding: 15px;
        font-size: 14px;
        color: #333;
    }
    .block-content-full {
        background-color: #f8f8f8;
    }
    .modal-content {
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
    }
    #modaldetail .block-header {
        background-color: #3498db;
        color: #fff;
        padding: 15px;
        border-radius: 5px;
    }
    #modaldetail .block-title {
        margin-bottom: 0;
    }
    #modaldetail .block-options {
        color: #fff;
    }
    #modaldetail .block-options button {
        color: #fff;
    }
    #modaldetail .block-options button:hover {
        color: #eee;
    }
    #modaldetail .block-content {
        padding: 15px;
        font-size: 14px;
        color: #333;
    }
    #modaldetail .block-content-full {
        background-color: #f8f8f8;
    }
    #modaldetail.modal-content {
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2); 
    }
    .modal-content .close {
    position: absolute;
    top: 0;
    right: 0;
    padding:10px;
    font-size: 25px;
    font-weight: bold;
    background-color: transparent;
    border: none;
    color: #fff;
    cursor: pointer;
    }
    .modal-content .close:hover {
    opacity: 0.7;
    }
    .accordion 
    {
        cursor: pointer;
        padding: 18px;
        width: 100%;
        text-align: left;
        outline: none;
        font-size: 16px;
        transition: 0.4s;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #f1f1f1;
    }
    .accordion:hover 
    {
        background-color: #ddd;
    }
    .accordion-btn 
    {
        font-size: 20px;
        margin-left: 10px;
    }
    .add-to-cart-btn {
    padding: 10px 15px;
    font-size: 16px;
    font-weight: bold;
    background-color: #3498db;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .add-to-cart-btn:hover {
        background-color: #2980b9;
        transform: scale(1.05);
    }

    .add-to-cart-btn:active {
        background-color: #2980b9;
        transform: scale(0.95);
    }
    .modal-content .close {
        position: absolute;
        top: 0;
        right: 0;
        padding: 10px;
        font-size: 20px;
        font-weight: bold;
        background-color: #e74c3c;
        color: #fff;
        cursor: pointer;
        transition: background-color 0.3s ease, opacity 0.3s ease;
    }

    .modal-content .close:hover {
        background-color: #c0392b;
        opacity: 0.7;
    }

    #modaldetail .block-content {
        background-color: #f8f8f8;
        padding: 15px;
        border-bottom: 1px solid #ddd;
    }

    #modaldetail .block-content-full {
        background-color: #f8f8f8;
        padding: 15px;
    }

    #modaldetail .titlemodal {
        font-size: 18px;
        margin-bottom: 10px;
        color: #333;
    }

    #modaldetail .pricemodal {
        font-size: 24px;
        color: #28a745;
        font-weight: bold;
    }

    .block-rounded {
        background-color: #f8f8f8;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 15px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 767px) {
    .block-rounded {
        width: auoto;
        padding: 10px;
        height: auto;
        border-radius: 15px;
        margin-left: auto;
        margin-right: auto;
    }
    }

</style>
@endsection

@section('script')
<script src="{{ asset('assets/toastr/build/toastr.min.js') }}"></script>
<script src="{{ asset('assets/jquery-raty/jquery.raty.js') }}"></script>
<script>
$(document).ready(function () {
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('click', '.detail', function () {
        var DetId = $(this).data('id1');
        var name = $(this).data('name');
        var desc = $(this).data('description');
        var spec = $(this).data('specification');
        var priceValue = $(this).data('price');
        var img = $(this).data('image');
        var formattedPrice = 'Rp. ' + parseFloat(priceValue).toLocaleString();
        $('.add-to-cart-btn').attr('data-id', DetId);
        $('#modaldetail #name').html(name);
        $('#modaldetail #descript').html(desc);
        $('#modaldetail #specific').html(spec);
        $('#modaldetail #price').html(formattedPrice);
        $('#modaldetail #productImage').attr('src', img);
        $('#modaldetail').modal('show');
    });
        $(".accordion").click(function(){
            $(this).next(".panel").slideToggle("fast");
        });
    });



    $(document).on('click','#addcart, .add-to-cart-btn',function(){
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
   $(document).on('click', '.featured__item__pic.custom_thumb', function(e) {
  e.preventDefault();
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
    function table_post_row(res) {
    var htmlView = '';
    var baseImageUrl = '{{ asset('uploads/master/product/image/') }}';
    for (let i = 0; i < res.products.length; i++) {
        var imagePath = res.products[i].image ? baseImageUrl + '/' + res.products[i].image : '{{ asset('assets/img/loading.png') }}';
        console.log('Image Path:', imagePath);
        htmlView += '<div class="col-md-3 col-xl-3 col-6 custom-col mix ' + res.products[i].cat_name + '" id="col_prod" data-toggle="appear">' +
            '<div class="block block-rounded">' +
                '<div class="block-content p-0 overflow-hidden">' +
                    '<a class="img-link detail" href="javascript:void(0)" data-id1="' + res.products[i].id + '" data-name="' + res.products[i].name + '" data-description="' + res.products[i].description +'" data-specification="' + res.products[i].specification +'" data-price="' + res.products[i].price + '">' +
                        '<img class="img-fluid rounded-top" src="' + imagePath + '" alt="" style="width: 220px; height: 220px;">' +
                    '</a>' +
                '</div>' +
                '<h4 class="font-size-h5 mb-10 clamp-text" data-toggle="tooltip" title="' + res.products[i].name + '">' + res.products[i].name + '</h4>' +
                '<i class="font-size-h1 font-w300 mb-5 text-success">Rp' + parseFloat(res.products[i].price).toLocaleString(window.document.documentElement.lang) + '</i><br><br>' +
                '<div class="rating-shop-container">' +
                    '<div class="rating-container">' +
                        '<span class="in-stock" style="color: #28a745;">In Stock <i class="fa fa-check-circle"></i></span>  <br>' +
                        '<span class="rating-count">(50)</span>' +
                    '</div>' +
                    '<i class="fa fa-cart-plus shopping-cart-icon cart' + res.products[i].id + '" id="addcart" href="javascript:void(0)" data-id="' + res.products[i].id + '"></i>' +
                '</div>' +
            '</div>' +
        '</div>';
    }
    $('#products').html(htmlView);
}

</script>
@endsection