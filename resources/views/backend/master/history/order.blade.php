@extends('layouts.admin.appadmin')
@section('title', "Order History")
@section('content')
<div class="container-xxl flex-grow-1 container-p-y mt-4">
    <div class="card">
       <nav class="breadcrumb push bg-body-extra-light rounded-pill px-4 py-2">
        <a class="breadcrumb-item" href="{{ route('home') }}">Dashboard</a>
        <span class="breadcrumb-item active">Order</span>
       </nav>
      <div class="row">
        <div class="col-12 col-xs-3">
            <div class="block block-content">
                <div class="nav nav-pills push">
                    <li class="nav-item">
                        <a href="{{ route('order.index') }}" class="nav-link {{ request()->is('/exhibition/master/history/order') ? 'active' : '' }}">
                          <i class="fas fa-clock fa-fw mr-2"></i>Orders
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="{{ route('report.excel') }}" class="nav-link {{ request()->is('/exhibition/master/history/order-report') ? 'active' : '' }}">
                          <i class="fas fa-file-excel fa-fw mr-2"></i>Report
                        </a>
                      </li>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mb-4">
                <div class="card-body">
                    <div class="block-content block-content-full">
                            <!-- DataTables functionality is initialized with .js-dataTable-full class in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive"  id="dataTable">
                        <thead>
                          <tr>
                            <th>Oc Number</th>
                            <th>Payment Action</th>
                            <th>Name Customer</th>
                            <th>Grand Total</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('css') 
<link rel="stylesheet" href="{{ asset('assets/adminnew/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/adminnew/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/adminnew/js/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/adminnew/js/sweetalert2/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/adminnew/js/magnific-popup/magnific-popup.css') }}">
@endsection

@section('script')
<script src="{{ asset('assets/adminnew/js/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/adminnew/js/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('assets/adminnew/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/adminnew/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/adminnew/js/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/adminnew/js/plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

<script type="module">
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("#dataTable").DataTable({
            drawCallback: function(){
                $('.popup-image').magnificPopup({
                    type: 'image',
                });
                $('.js-change-status').on('click', function (e) {
                var routers = $(this).data("url");
                var status = $(this).data("status");
                var colors = $(this).data("color");

                swal.fire({
                    title: 'Anda Yakin?',
                    text: 'Untuk melakukan perubahan ' + status + '.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: colors,
                    confirmButtonText: status,
                    cancelButtonText: 'Cancel Orders', 
                    html: false,
                    preConfirm: function () {
                        return new Promise(function (resolve) {
                            setTimeout(function () {
                                resolve();
                            }, 50);
                        });
                    }
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: routers,
                            type: 'GET',
                            success: function (data) {
                                $("#dataTable").DataTable().ajax.reload();
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                alert(errorThrown);
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    } else if (result.dismiss === 'cancel') {
                        $.ajax({
                            url: routers + '/cancel', 
                            type: 'GET',
                            success: function (data) {
                                $("#dataTable").DataTable().ajax.reload();
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                alert(errorThrown);
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }
                });
            });
            },
            processing: true,
            serverSide: true,
            ajax: "{{ route('order.index') }}",
            columns: [
                {data: 'oc_number', name: 'orders.oc_number'},
                {data: 'image', name: 'orders.image', orderable: false, searchable: false},
                {data: 'customer_name', name: 'orders.customer_name'},
                {data: 'grand_total', name: 'orders.grand_total'},
                {data: 'status', name: 'orders.status'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            responsive: true,
        });
    });
</script>
@endsection