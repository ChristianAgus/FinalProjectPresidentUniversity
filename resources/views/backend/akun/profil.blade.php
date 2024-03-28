@extends('layouts.admin.appadmin')
@section('title', "User Profil")
@section('content')
<div class="container-xxl flex-grow-1 container-p-y mt-4">
    <div class="card">
        <nav class="breadcrumb push bg-body-extra-light rounded-pill px-4 py-2">
            <a class="breadcrumb-item" href="{{ route('home') }}">Dashboard</a>
            <span class="breadcrumb-item active">User Profil</span>
          </nav>
        <div class="block-content tab-content">
            <div class="content">
                <!-- User Profile -->
                <div class="block block-bordered block-rounded mb-2">
                    <div class="block-header" role="tab" id="accordion_h1">
                        <a class="fw-semibold" data-bs-toggle="collapse" data-bs-parent="#accordion" href="#accordion_q1" aria-expanded="true" aria-controls="accordion_q1">User Profile</a>
                    </div>
                    <div id="accordion_q1" class="collapse show" role="tabpanel" aria-labelledby="accordion_h1" data-bs-parent="#accordion">
                        <div class="block-content">
                        <form method="POST" action="{{ route('update.profile') }}" id="editprofil" enctype="multipart/form-data">
                                @csrf
                                <div class="row items-push">
                                    <div class="col-lg-7 offset-lg-1">
										<input type="hidden" name="id" value="{{ auth()->user()->id }}">
										<div class="form-floating mb-4">
											<input type="text" class="form-control form-control-lg" id="first_name" name="first_name" value="{{ auth()->user()->first_name }}" required>
											<label class="form-label" for="profile-settings-username">First Name</label>
										</div>
										
										<div class="form-floating mb-4">
											<input type="text" class="form-control" name="last_name" id="last_name" value="{{ auth()->user()->last_name }}" required>
											<label class="form-label">Last Name *</label>
										</div>
										
										<div class="form-floating mb-4">
											<input type="text" class="form-control" name="name" id="name" value="{{ auth()->user()->name }}" required>
											<label class="form-label">Name *</label>
										</div>
										<div class="form-floating mb-4">
											<input type="text" class="form-control" name="username" id="username" value="{{ auth()->user()->username }}" required>
											<label class="form-label">User Name *</label>
										</div>
										<div class="form-floating mb-4">
											<select class="form-select" name="gender" id="gender"  required>
												<option value="" disabled selected>Select an option</option>
												<option value="Male" @if(auth()->user()->gender == 'Male') selected @endif>Male</option>
												<option value="Female" @if(auth()->user()->gender == 'Female') selected @endif>Female</option>
												<option value="Other" @if(auth()->user()->gender == 'Other') selected @endif>Other</option>
											</select>
											<label class="form-label">Gender *</label>
										</div>
										
										<div class="form-floating mb-4">
											<textarea class="form-control" name="address" id="address" required>{{ auth()->user()->address }}</textarea>
											<label class="form-label">Address *</label>
										</div>
										
										<div class="form-floating mb-4">
											<input type="text" class="js-flatpickr form-control" id="birth_date" name="birth_date" value="{{ date('Y-m-d', strtotime(auth()->user()->birth_date)) }}" required>
											<small class="form-text text-muted">Please select your birthdate.</small>
										</div>
										
										<div class="form-floating mb-4">
											<input type="text" class="form-control telp" name="phone_number" id="phone_number" value="{{ auth()->user()->phone_number }}" required>
											<label class="form-label">Phone Number *</label>
										</div>
										
										<div class="form-floating mb-4">
											<select class="form-select" name="role" id="role" required>
												<option value="" disabled selected>Please select</option>
												<option value="Admin" @if(auth()->user()->role == 'Admin') selected @endif>Admin</option>
												<option value="Sales" @if(auth()->user()->role == 'Sales') selected @endif>Sales</option>
												<option value="User" @if(auth()->user()->role == 'User') selected @endif>User</option>
											</select>
											<label class="form-label">Role *</label>
										</div>
										
										<div class="form-floating mb-4">
											<input type="email" class="form-control" name="email" id="email" value="{{ auth()->user()->email }}" required>
											<label class="form-label">Email *</label>
										</div>										
									</div>
										<div class="mb-4">
											<button type="submit" class="btn btn-alt-primary">Update</button>
										</div>
								</div>
							</div>
						</form>
					</div>
				</div>
				<!-- END User Profile -->
				<!-- Change Password -->
				<div class="block block-bordered block-rounded mb-2">
                    <div class="block-header" role="tab" id="accordion_h2">
                        <a class="fw-semibold" data-bs-toggle="collapse" data-bs-parent="#accordion" href="#accordion_q2" aria-expanded="true" aria-controls="accordion_q2">Change Password</a>
                    </div>
                    <div id="accordion_q2" class="collapse" role="tabpanel" aria-labelledby="accordion_h2" data-bs-parent="#accordion">
                        <div class="block-content">
							<form id="changePasswordForm" method="POST" action="{{ route('change.password') }}" onsubmit="return false;">
								@csrf
								<div class="row items-push">
									<div class="col-lg-7 offset-lg-1">
										<div class="form-floating mb-4">
											<input type="password" class="form-control form-control-lg" id="current_password" name="current_password" required>
											<label class="form-label" for="current_password">Current Password</label>
										</div>
										<input type="hidden" name="id" value="{{ auth()->user()->id }}">
										<div class="form-floating mb-4">
											<input type="password" class="form-control form-control-lg" id="new_password" name="new_password" required>
											<label class="form-label" for="new_password">New Password</label>
										</div>
										<div class="form-floating mb-4">
											<input type="password" class="form-control form-control-lg" id="new_password_confirmation" name="new_password_confirmation" required>
											<label class="form-label" for="new_password_confirmation">Confirm New Password</label>
										</div>
										<div class="mb-4">
											<button type="button" id="changePasswordBtn" class="btn btn-alt-primary">Update</button>
										</div>
									</div>
								</div>
							</form>
                        </div>
                    </div>
                </div>
                <!-- END Change Password -->
        </div>
    </div>
</div>
@endsection

@section('css') 
<link rel="stylesheet" href="{{ asset('assets/adminnew/js/plugins/sweetalert2/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/adminnew/js/plugins/magnific-popup/magnific-popup.css') }}">
<link rel="stylesheet" href="{{ asset('assets/adminnew/js/plugins/flatpickr/flatpickr.min.css') }}">
@endsection

@section('script')
<script src="{{ asset('assets/adminnew/js/plugins/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/adminnew/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/adminnew/js/plugins/magnific-popup/jquery.magnific-popup.min.js') }}"></script>


<script type="module">
	    flatpickr("#birth_date", {
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
    });	
	const formData = new FormData(document.getElementById("editprofil"));
	editprofil.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(editprofil);
            $.ajax({
                type: 'POST',
                url: '/exhibition/akun/update-profile',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        }).then((result) => {
                            if (result.isConfirmed || result.isDismissed) {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                        });
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong. Please try again later.',
                    });
                },
            });
        });

$(document).ready(function () {
    $('#changePasswordBtn').click(function () {
        var formData = $('#changePasswordForm').serialize();
        $.ajax({
            type: 'POST',
            url: '{{ route("change.password") }}',
            data: formData,
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
					}).then(function () {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                    });
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid',
                    text: 'Password yang anda masukan salah atau tidak sesuai.',
                });
            },
        });
    });
});

</script>
@endsection

