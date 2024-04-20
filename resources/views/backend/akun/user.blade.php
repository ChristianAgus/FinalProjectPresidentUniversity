@extends('layouts.admin.appadmin')
@section('title', "Profil")
@section('content')
<div class="container-xxl flex-grow-1 container-p-y mt-4">
    <div class="card">
        <nav class="breadcrumb push bg-body-extra-light rounded-pill px-4 py-2">
            <a class="breadcrumb-item" href="{{ route('home') }}">Dashboard</a>
            <span class="breadcrumb-item active">User</span>
          </nav>
        <h5 class="card-header">
            <button type="button" onclick="tambahModal()" class="btn btn-primary">
                Add Data
            </button>
        </h5>
        <div class="card-body">
            <div class="block-content block-content-full">
                    <!-- DataTables functionality is initialized with .js-dataTable-full class in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
            <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive"  id="dataTable">
                <thead>
					<tr>
						<th>id</th>
						<th>fullname</th>
						<th>email</th>
						<th>username</th>
						<th style="width: 15%;">role</th>
						<th style="width: 15%;">gender</th>
						<th style="width: 15%;">action</th>
					</tr>
				</thead>
			</table>
		    </div>
		</div>
	</div>
</div>

@include('backend.akun.form', ['action' => route('akun.create'), 'id' => 'tambahModal'])
@include('backend.akun.form', ['id' => 'editModal', 'type' => 'edit'])
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
        $('.telp').on('input', function() {
            $(this).val($(this).val().replace(/\D/g, ''));
        });
        $('.telp').on('keyup', function() {
            var maxLength = 13;
            if ($(this).val().length > maxLength) {
                $(this).val($(this).val().slice(0, maxLength));
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
            },
            processing: true,
            // serverSide: true,
            ajax: "{{ route('akun.user') }}",
            columns: [
                { data: 'id', name: 'users.id' },
                { data: 'name', name: 'users.name' },
                { data: 'email', name: 'users.email' },
				{ data: 'username', name: 'users.username' },
				{ data: 'role', name: 'users.role' },
				{ data: 'gender', name: 'users.gender' },
                { data: 'edit', name: 'edit', orderable: false, searchable: false }
            ],
            responsive: true,
        });
    });
</script>
@endsection

