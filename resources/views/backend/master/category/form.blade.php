@php
$action = $action ?? '';
$type = $type ?? '';
@endphp
<div class="modal fade modal-popin" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-popin" role="document">
        <div class="modal-content">
            <form action="{{ $action }}" method="post" id="{{ $type }}Form">
                @if ($type == 'edit')
                {!! method_field('PUT') !!}
                @endif
                {!! csrf_field() !!}
            <div class="block block-rounded shadow-none mb-0">
                <div class="block-header block-header-default">
                    <h3 class="block-title">{{ $type == '' ? 'Add' : ucfirst($type) }} Category</h3>
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
                            <div class="col mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter Category Name ..." id="{{$type}}name" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label class="form-label">Image <span class="badge bg-secondary ml-1">Optional</span></label>
                                <input type="file" class="form-control" name="image" id="imageInput" accept="image/*">
                                <code>Max. size : 2 MB</code>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="{{ $type }}btnSubmit" class="btn btn-primary">Submit</button>
                    <button type="button" style="display:none;" class="btn btn-primary" id="{{ $type }}btnLoading">Loading...</button>

                </div>
            </form>
            </div>
        </div>
    </div>
</div>
@push('additional-css')
<style>
  </style>
@endpush
 
@push('additional-js')
<script type="module">
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
                    $("#{{ $type }}alertNotification").removeClass('alert alert-danger').addClass('alert alert-success');
                    $("#{{ $type }}alertNotification").html(data.message);
                    $("#dataTable").DataTable().ajax.reload();
                    $('#{{ $id }}').modal('hide');
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
        $('#{{$type}}Form').attr('action', "{{ url('/exhibition/master/category/update') }}/"+json.id);
        $('#{{$type}}name').val(json.name);
    }
    @endif
</script>
@endpush