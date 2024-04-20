@extends('layouts.admin.appadmin')
@section('title', "Category")
@section('content')
<div class="container-xxl flex-grow-1 container-p-y mt-4">
    <div class="card">
        <nav class="breadcrumb push bg-body-extra-light rounded-pill px-4 py-2">
            <a class="breadcrumb-item" href="{{ route('home') }}">Dashboard</a>
            <span class="breadcrumb-item active">Category</span>
        </nav>
        <h5 class="card-header">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                Add Data
            </button>
        </h5>
        <div class="card-body">
                <div class="block-content block-content-full">
                    <!-- DataTables functionality is initialized with .js-dataTable-full class in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive"  id="dataTable">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th style="width: 15%;">Updated</th>
                            <th style="width: 15%;">Created</th>
                            <th style="width: 15%;">Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
        </div>
    </div>
</div>

@include('backend.master.category.form', ['action' => route('category.create'), 'id' => 'tambahModal'])
@include('backend.master.category.form', ['id' => 'editModal', 'type' => 'edit'])
@endsection

@section('css') 
<link rel="stylesheet" href="{{ asset('assets/adminnew/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/adminnew/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/adminnew/js/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/adminnew/js/plugins/sweetalert2/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/adminnew/js/plugins/magnific-popup/magnific-popup.css') }}">
@endsection

@section('script')
<script src="{{ asset('assets/adminnew/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/adminnew/js/plugins/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
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
            drawCallback: function () {
                $('.delete-btn').on('click', function(){
                        var routers = $(this).data("url");
                        swal.fire({
                            title: 'Anda Yakin?',
                            text: 'Data yang dihapus tidak dapat dikembalikan lagi!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d26a5c',
                            confirmButtonText: 'Iya, hapus!',
                            html: false,
                            preConfirm: function() {
                                return new Promise(function (resolve) {
                                    setTimeout(function () {
                                        resolve();
                                    }, 50);
                                });
                            }
                        }).then(function(result){
                            if (result.value) {
                                $.ajax({
                                    url: routers,
                                    type: 'GET',
                                    success: function (data) {
                                        $("#dataTable").DataTable().ajax.reload();
                                    }, error: function(XMLHttpRequest, textStatus, errorThrown) { 
                                        alert(errorThrown);
                                    },    
                                    cache: false,
                                    contentType: false,
                                    processData: false
                                });
                            } else if (result.dismiss === 'cancel') {
                                swal.fire('Cancelled', 'Your data is safe :)', 'error');
                                        }
                                    });
                                });
                $('.popup-image').magnificPopup({
                    type: 'image',
                });
                $('.js-change-status').on('click', function (e) {
                    var routers = $(this).data("url");
                    var status = $(this).data("status");
                    var colors = $(this).data("color");

                    swal.fire({
                        title: 'Anda Yakin?',
                        text: 'Untuk melakukan perubahan active or inactive.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: colors,
                        confirmButtonText: status,
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
                            swal.fire('Cancelled', 'Your data is safe :)', 'error');
                        }
                    });
                });
            },
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: "{{ route('category.index') }}",
            columns: [
                { data: 'name', name: 'ms_categories.name' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
                { data: 'created_at', name: 'ms_categories.created_at' },
                { data: 'updated_at', name: 'ms_categories.updated_at' },
                { data: 'edit', name: 'edit', orderable: false, searchable: false }
            ],
            responsive: true,
        });
    });
</script>
@endsection