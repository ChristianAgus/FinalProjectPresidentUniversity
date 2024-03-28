@php
$action = $action ?? '';
$type = $type ?? '';
@endphp
<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-popin modal-xl" role="document">
        <div class="modal-content">
            <form action="{{ $action }}" method="post" id="{{ $type }}Form">
                @if ($type == 'edit')
                {!! method_field('PUT') !!}
                @endif
                {!! csrf_field() !!}
                <div class="block block-rounded shadow-none mb-0">
                    <div class="block-header block-header-default bg-primary d-flex align-items-center">
                        <h3 class="block-title me-2">
                            <i class="fa fa-user"></i> 
                            {{ $type == '' ? 'Add' : ucfirst($type) }} User
                        </h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="block-content fs-sm">
                        <div class="modal-body">
                            <div id="{{ $type }}alertNotification" style="display:none;"></div>
                            <div class="row">
                                <div class="form-floating col-md-4 mb-3">
                                      <input type="text" class="form-control" name="firstname" placeholder="Enter First Name..." id="{{$type}}firstname" required>
                                      <label class="form-label" for="example-text-input-floating">First Name *</label>
                                </div>
                                <div class="form-floating col-md-4 mb-3">
                                    <input type="text" class="form-control" name="lastname" placeholder="Enter Last Name..." id="{{$type}}lastname" required>
                                    <label class="form-label">Last Name *</label>
                                </div>
                                <div class="form-floating col-md-4 mb-3">
                                    <input type="text" class="form-control" name="fullname" placeholder="Enter Full Name..." id="{{$type}}fullname" required>
                                    <label class="form-label">Full Name *</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-floating col-md-4 mb-3">
                                    <select class="form-select" name="gender" placeholder="Enter Gender..." id="{{$type}}gender" required>
                                      <option value="" disabled selected>Select an option</option>
                                      <option value="Male">Male</option>
                                      <option value="Female">Female</option>
                                      <option value="Other">Other</option>
                                    </select>
                                    <label class="form-label">Gender *</label>
                                  </div>
                                <div class="form-floating col-md-4 mb-3">
                                    <textarea class="form-control" name="address" placeholder="Enter Address..." id="{{$type}}address" required></textarea>
                                    <label class="form-label">Address *</label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" class="js-flatpickr form-control" name="birthdate"  id="{{$type}}birthdate" required>
                                    <small class="form-text text-muted">Please select your birthdate.</small>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="form-floating  col-md-4 mb-3">
                                    <input type="text" class="form-control telp" name="phone" placeholder="Enter Phone Number..." id="{{$type}}phone"  required>
                                    <label class="form-label">Phone Number *</label>
                                </div>
                                <div class="form-floating col-md-4 mb-3">
                                    <select class="form-select" name="role" id="{{$type}}role" required>
                                        <option value="" disabled selected>Please select</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Sales">Sales</option>
                                        <option value="User">User</option>
                                    </select>
                                    <label class="form-label">Role *</label>
                                </div>
                                <div class="form-floating col-md-4 mb-3">
                                    <input type="email" class="form-control" name="email" placeholder="Enter Email..." id="{{$type}}email" required>
                                    <label class="form-label">Email *</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-floating col-md-4 mb-3">
                                    <input type="text" class="form-control" name="username" placeholder="Enter Username..." id="{{$type}}username" required>
                                    <label class="form-label">Username *</label>
                                </div>
                                <div class="form-floating col-md-4 mb-3">
                                    <input type="password" class="form-control" name="password"  id="{{$type}}password" >
                                    <label class="form-label">Password *</label>
                                </div>
                                <div class="form-floating col-md-4 mb-3">
                                    <input type="password" class="form-control" name="repassword"  id="{{$type}}repassword" >
                                    <label class="form-label">Re-enter Password *</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="{{ $type }}btnSubmit" class="btn btn-primary">Submit</button>
                        <button type="button" style="display:none;" class="btn btn-primary" id="{{ $type }}btnLoading">Loading...</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

 
@push('additional-css') 
<link rel="stylesheet" href="{{ asset('assets/adminnew/js/plugins/flatpickr/flatpickr.min.css') }}">
@endpush

@push('additional-js')
<script src="{{ asset('assets/adminnew/js/plugins/flatpickr/flatpickr.min.js') }}"></script>
<script type="module">
    flatpickr("#{{$type}}birthdate", {
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
    });
$("#{{ $type }}Form").submit(function(e){
    e.preventDefault();    
    var formData = new FormData(this);
    $("#{{ $type }}btnLoading").show();
    $("#{{ $type }}btnSubmit").hide();
    $("#{{ $type }}alertNotification").hide();

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function (data) {
            $("#{{ $type }}btnSubmit").show();
            $("#{{ $type }}btnLoading").hide();
            $("#{{ $type }}alertNotification").show();
            
            if (data.success == false) {
                $("#{{ $type }}alertNotification").removeClass('alert alert-success').addClass('alert alert-danger');
                $("#{{ $type }}alertNotification").html(data.message);
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.message,
                }).then((result) => {
                    $("#dataTable").DataTable().ajax.reload();
                    $('#{{ $id }}').modal('hide');
                });
            }
        }, error: function (xhr, ajaxOptions, thrownError) {
            $("#{{ $type }}btnSubmit").show();
            $("#{{ $type }}btnLoading").hide();
            $("#{{ $type }}alertNotification").show();
            $("#{{ $type }}alertNotification").removeClass('alert alert-success').addClass('alert alert-danger');
            $("#{{ $type }}alertNotification").html(xhr.responseJSON.message);
        },    
        cache: false,
        contentType: false,
        processData: false
    });
});

</script>
<script>
    @if ($type == 'edit')
    function editModal(json) {
    $('#editModal').modal('show');
    $("#{{ $type }}alertNotification").hide();
    $('#{{$type}}Form').attr('action', "{{ url('/exhibition/akun/update') }}/"+json.id);
    $('#{{$type}}gender').val(json.gender).trigger('change');
    $('#{{$type}}role').val(json.role).trigger('change');
    $('#{{$type}}birthdate').val(json.birthdate).trigger('change');
    $('#{{$type}}username').val(json.username);
    $('#{{$type}}fullname').val(json.fullname);
    $('#{{$type}}lastname').val(json.last_name);
    $('#{{$type}}firstname').val(json.first_name);
    $('#{{$type}}phone').val(json.phone);
    $('#{{$type}}address').val(json.address);
    $('#{{$type}}email').val(json.email);
    flatpickr("#{{$type}}birthdate", {
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        defaultDate: json.birthdate,  // Menetapkan nilai awal
    });
}
    @else
    function tambahModal() {
        $('#tambahModal').modal('show');
        $("#{{ $type }}alertNotification").hide();
        $('#{{$type}}gender,#{{$type}}birthdate,#{{$type}}role').val(null).trigger('change');
        $('#{{$type}}firstname,#{{$type}}lastname,#{{$type}}fullname,#{{$type}}address,#{{$type}}phone,#{{$type}}email,#{{$type}}username,#{{$type}}password,#{{$type}}repassword').val(null);
    }
    @endif

</script>
@endpush