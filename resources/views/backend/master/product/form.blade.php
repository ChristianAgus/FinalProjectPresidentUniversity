@php
$action = $action ?? '';
$type = $type ?? '';
@endphp
<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-popin" role="document">
        <div class="modal-content">
            <form action="{{ $action }}" method="post" id="{{ $type }}Form">
                @if ($type == 'edit')
                {!! method_field('PUT') !!}
                @endif
                {!! csrf_field() !!}
                <div class="block block-rounded shadow-none mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">{{ $type == '' ? 'Add' : ucfirst($type) }} Product</h3>
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
                                    <label class="form-label">Category *</label>
                                    <select name="category" class="form-control" id="{{$type}}category" required>
                                        <option value="" disabled selected>Please select</option>
                                            @foreach($category as $categories)
                                                <option value="{{ $categories->id }}">{{ $categories->name }}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label class="form-label">SKU *</label>
                                    <input type="text" class="form-control" name="sku" placeholder="Enter Product Code ..." id="{{$type}}sku" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label class="form-label">Product Name *</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter Product Name ..." id="{{$type}}product" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Size *</label>
                                    <input type="text" class="form-control" name="size" id="{{$type}}size"  placeholder="Enter Size (Number, Comma, Point) ..."  required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">UoM *</label>
                                    <select name="uom" class="form-control" id="{{$type}}uom" required>
                                        <option value="" disabled selected>Please select</option>
                                        <option value="mL">Milliliter (mL)</option>
                                        <option value="L">Liter (L)</option>
                                        <option value="G">Gram (G)</option>
                                        <option value="KG">Kilogram (KG)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">stock *</label>
                                    <input type="number" class="form-control" name="stock" id="{{$type}}stock"  placeholder="Enter Stock Product (Tidak Boleh Kosong) ..."  required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label class="form-label">Description *</label>
                                    <textarea class="form-control" id="{{$type}}description" required name="description" rows="4" placeholder="Description.."></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label class="form-label">Spesification *</label>
                                    <textarea class="form-control" id="{{$type}}specification" required name="specification" rows="4" placeholder="Specifitaion.."></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label class="form-label">Price *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control" name="price" placeholder="Enter Price (Number only)..." id="{{$type}}price" onkeydown="return ( event.ctrlKey || event.altKey || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) || (95<event.keyCode && event.keyCode<106)|| (event.keyCode==8) || (event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) || (event.keyCode==46) )" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label class="form-label">Image <span class="badge bg-secondary ml-1">Optional</span></label>
                                    <input type="file" class="form-control" name="image" id="{{$type}}imageInput" accept="image/*">
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
                </div>
            </div>
        </form>
    </div>
</div>

 
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
    $("#{{$type}}size").on("keypress keyup blur",function (event) {
        $(this).val($(this).val().replace(/[^0-9\.|\,]/g,''));
        debugger;
        if(event.which == 44)
        {
        return true;
        }
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57  )) {
        
          event.preventDefault();
        }
    });
    $(document).on('keyup', '#{{$type}}price', function(event) {  
        if(event.which >= 37 && event.which <= 40) return;
        $(this).val(function(index, value) {
            return value
            .replace(/\D/g, "")
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });
    });
</script>
<script>
    @if ($type == 'edit')
    function editModal(json) {
        $('#editModal').modal('show');
        $("#{{ $type }}alertNotification").hide();
        $('#{{$type}}Form').attr('action', "{{ url('/exhibition/master/product/update') }}/"+json.id);
        $('#{{$type}}category').val(json.category).trigger('change');
        $('#{{$type}}sku').val(json.sku);

        $('#{{$type}}product').val(json.name);
        $('#{{$type}}size').val(json.size);
        $('#{{$type}}uom').val(json.uom);
        $('#{{$type}}description').val(json.description);
        $('#{{$type}}specification').val(json.specification);
        $('#{{$type}}price').val(json.price);
    }
    @else
    function tambahModal() {
        $('#tambahModal').modal('show');
        $("#{{ $type }}alertNotification").hide();
        $('#{{$type}}category,#{{$type}}uom').val(null).trigger('change');
        $('#{{$type}}product,#{{$type}}sku,#{{$type}}size,#{{$type}}price,#{{$type}}imageInput').val(null);
    }
    @endif

</script>
@endpush