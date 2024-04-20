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
                <div class="block-content block-content-full">
                    <form  method="post" id="filter" autocomplete="off">
                        {!! csrf_field() !!}
                        <div class="input-daterange input-group" data-date-format="yyyy-mm-dd" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                            <input type="text" class="form-control mb-2 mb-sm-0" id="example-daterange1" name="from" placeholder="Date From *" data-week-start="1" data-autoclose="true" data-today-highlight="true" required>
                            <div class="input-group-prepend input-group-append">
                                <span class="input-group-text font-w600">to</span>
                            </div>
                            <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="example-daterange2" name="to" placeholder="Date To *" data-week-start="1" data-autoclose="true" data-today-highlight="true" required>
                        </div>
                    </form>
                    <form method="post" action="{{ route('reports.excel') }}" autocomplete="off">
                        {!! csrf_field() !!}
                        <input type="hidden" id="fromExcel" name="fromExcel">
                        <input type="hidden" id="toExcel" name="toExcel">
                        <br>
                        <button type="submit" class="btn btn-alt-success mr-2" id="btnExcel"><i class="fa fa-cloud-download mr-2"></i>Excel</button>
                        <button type="button" style="display:none;" class="btn btn-alt-success" id="btnLoading"><i class="fa fa-spinner fa-spin"></i></button>
                        <input type="button" id="filterReset" class="btn btn-alt-secondary" value="Reset"/>
                    </form>
                </div>
    </div>

@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">

<style>
.mfp-no-margins img.mfp-img {
	padding: 0;
}
.mfp-no-margins .mfp-figure:after {
	top: 0;
	bottom: 0;
}
.mfp-no-margins .mfp-container {
	padding: 0;
}

.mfp-with-zoom .mfp-container,
.mfp-with-zoom.mfp-bg {
	opacity: 0;
	-webkit-backface-visibility: hidden;
	-webkit-transition: all 0.3s ease-out; 
	-moz-transition: all 0.3s ease-out; 
	-o-transition: all 0.3s ease-out; 
	transition: all 0.3s ease-out;
}

.mfp-with-zoom.mfp-ready .mfp-container {
		opacity: 1;
}
.mfp-with-zoom.mfp-ready.mfp-bg {
		opacity: 0.8;
}

.mfp-with-zoom.mfp-removing .mfp-container, 
.mfp-with-zoom.mfp-removing.mfp-bg {
	opacity: 0;
}

</style>
@endsection

@section('script')
<script src="{{ asset('assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script>Codebase.helpersOnLoad(['js-flatpickr', 'jq-datepicker', 'jq-colorpicker', 'jq-maxlength', 'jq-select2', 'jq-rangeslider', 'jq-masked-inputs', 'jq-pw-strength']);</script>
    <script>
        $(document).ready(function() { 
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
        $("#example-daterange1").change(function(){
            var from = $('#example-daterange1').val();
            $('#fromExcel').val(from);
        });
        $("#example-daterange2").change(function(){
            var to = $('#example-daterange2').val();
            $('#toExcel').val(to);
        });

        $("#filterReset").click(function () {
            $('#example-daterange1').val(null);
            $('#example-daterange2').val(null);
        });
    </script>
@endsection