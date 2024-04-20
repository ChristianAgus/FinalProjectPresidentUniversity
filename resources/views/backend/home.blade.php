@extends('layouts.admin.appadmin')
@section('title', "Dashboard")
@section('content')
<div class="content">
    <div class="row">
    <div class="col-md-6 col-xl-3">
      <a class="block block-rounded block-transparent bg-pulse-light" href="{{ route('order.index') }}">
        <div class="block-content block-content-full block-sticky-options">
          <div class="block-options">
            <div class="block-options-item">
              <i class="fa fa-spinner fa-spin text-white-75"></i>
            </div>
          </div>
          <div class="py-3 text-center">
            <div class="fs-2 fw-bold mb-0 text-white">{{ $pendingOrdersCount }}</div>
            <div class="fs-sm fw-semibold text-uppercase text-white-75">Pending Orders</div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-6 col-xl-3">
      <a class="block block-rounded block-transparent bg-gd-cherry" href="{{ route('order.index') }}">
        <div class="block-content block-content-full block-sticky-options">
          <div class="block-options">
            <div class="block-options-item">
              <i class="fa fa-times text-white-75"></i>
            </div>
          </div>
          <div class="py-3 text-center">
            <div class="fs-2 fw-bold mb-0 text-white">{{ $canceledOrdersCount }}</div>
            <div class="fs-sm fw-semibold text-uppercase text-white-75">Canceled Orders</div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-6 col-xl-3">
      <a class="block block-rounded block-transparent bg-gd-lake" href="{{ route('order.index') }}">
        <div class="block-content block-content-full block-sticky-options">
          <div class="block-options">
            <div class="block-options-item">
              <i class="fa fa-check text-white-75"></i>
            </div>
          </div>
          <div class="py-3 text-center">
            <div class="fs-2 fw-bold mb-0 text-white">{{ $closedOrdersCount }}</div>
            <div class="fs-sm fw-semibold text-uppercase text-white-75">Completed</div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-6 col-xl-3">
      <a class="block block-rounded block-transparent bg-gd-dusk" href="javascript:void(0)">
        <div class="block-content block-content-full block-sticky-options">
          <div class="block-options">
            <div class="block-options-item">
              <i class="fa fa-archive text-white-75"></i>
            </div>
          </div>
          <div class="py-3 text-center">
            <div class="fs-2 fw-bold mb-0 text-white">{{ $totalOrdersCount }}</div>
            <div class="fs-sm fw-semibold text-uppercase text-white-75">All</div>
          </div>
        </div>
      </a>
    </div>
      <!-- END Row #1 -->
    </div>
    <div class="row">
      <!-- Row #2 -->
      <div class="col-md-6">
        <div class="block block-rounded block-fx-shadow">
          <div class="block-header block-header-default">
            <h3 class="block-title">Sales <small>Grafik Date</small><span> {{ Carbon\Carbon::now()->format('F Y') }}</span></h3>
            <div class="block-options">
              <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                <i class="si si-refresh"></i>
              </button>
            </div>
          </div>
          <div class="block-content border-bottom">
            <div class="row items-push text-center">
              <div class="col-6 col-sm-6">
                <div class="fs-4 fw-semibold">{{$totalQtyThisMonth}} PCS</div>
                <div class="fs-sm fw-semibold text-uppercase text-muted">This Month</div>
              </div>
              <div class="col-6 col-sm-6">
                <div class="fs-4 fw-semibold">{{$totalQtyThisWeek}} PCS</div>
                <div class="fs-sm fw-semibold text-uppercase text-muted">This Week</div>
              </div>
            </div>
          </div>
          <div class="block-content block-content-full">
            <!-- Lines Chart Container functionality is initialized in js/pages/db_pop.min.js which was auto compiled from _js/pages/db_pop.js -->
            <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
            <canvas id="js-chartjs-pop-line"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="block block-rounded block-fx-shadow">
          <div class="block-header block-header-default">
            <h3 class="block-title">Earning <small>Grafik Date</small><span> {{ Carbon\Carbon::now()->format('F Y') }}</span></h3>
            <div class="block-options">
              <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                <i class="si si-refresh"></i>
              </button>
            </div>
          </div>
          <div class="block-content border-bottom">
            <div class="row items-push text-center">
              <div class="col-6 col-sm-">
                <div class="fs-4 fw-semibold">Rp {{ number_format($totalGrandThisMonth, 0, ',', '.') }}</div>
                <div class="fs-sm fw-semibold text-uppercase text-muted">This Month</div>
              </div>
              <div class="col-6 col-sm-6">
                <div class="fs-4 fw-semibold">Rp {{ number_format($totalGrandThisWeek, 0, ',', '.') }}</div>
                <div class="fs-sm fw-semibold text-uppercase text-muted">This Week</div>
              </div>
            </div>
          </div>
          <div class="block-content block-content-full">
            <!-- Lines Chart Container functionality is initialized in js/pages/db_pop.min.js which was auto compiled from _js/pages/db_pop.js -->
            <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
            <canvas id="js-chartjs-pop-lines1"></canvas>
          </div>
        </div>
      </div>
      <!-- END Row #2 -->
    </div>
  </div>
@endsection

@section('script')
<script src="{{ asset('assets/js/plugins/chart.js/chart.min.js') }}"></script>

<script>
    $(document).ready(function() {
        var ctx = document.getElementById('js-chartjs-pop-line').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Total Quantity',
                    data: {!! json_encode($data) !!},
                    borderWidth: 1,
                    fill: true,
                    backgroundColor: 'rgba(56,56,56,.4)',
                    borderColor: 'rgba(56,56,56,.9)',
                    pointBackgroundColor: 'rgba(56,56,56,.9)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(56,56,56,.9)',
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        var ctx1 = document.getElementById('js-chartjs-pop-lines1').getContext('2d');
        var myChart1 = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: {!! json_encode($dtorder) !!},
                datasets: [{
                    label: 'Total Price Rp. ',
                    data: {!! json_encode($ttlgrd) !!},
                    borderWidth: 1,
                    fill: true,
                    backgroundColor: 'rgba(230,76,60,.4)',
                    borderColor: 'rgba(230,76,60,.9)',
                    pointBackgroundColor: 'rgba(230,76,60,.9)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(230,76,60,.9)',
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection

