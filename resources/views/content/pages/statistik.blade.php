@extends('layouts/layoutMaster')

@section('title', 'Dashboard - Analytics')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/apex-charts/apex-charts.scss',
  'resources/assets/vendor/libs/swiper/swiper.scss',
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss'
])
@endsection

@section('page-style')
<!-- Page -->
@vite(['resources/assets/vendor/scss/pages/cards-advance.scss'])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/apex-charts/apexcharts.js',
  'resources/assets/vendor/libs/swiper/swiper.js',
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  ])
@endsection

@section('page-script')
@vite([
  'resources/assets/js/dashboards-analytics.js'
])
<script type="module">
'use strict';
(function () {
    const rekamMedisTahunanEl = document.querySelector('#rekamMedisTahunan'),
    rekamMedisTahunanConfig = {
      chart: {
        height: 300,
        stacked: true,
        type: 'bar'
      },
      plotOptions: {
        bar: {
          columnWidth: '45%',
          borderRadius: 4
        }
      },
      dataLabels: {
        enabled: false
      },
      series: {!! json_encode($data['seriesRM']) !!},
      xaxis: {
        categories: {!! json_encode($data['categoriesRM']) !!},
        labels: {
          style: {
            fontSize: '13px',
            fontFamily: 'Public Sans'
          }
        }
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return val + " rekam medis";
          }
        }
      },
      legend: {
        position: 'top'
      },
      responsive: [
        {
          breakpoint: 1025,
          options: {
            chart: {
              height: 350
            }
          }
        }
      ]
    };
  if (typeof rekamMedisTahunanEl !== undefined && rekamMedisTahunanEl !== null) {
    const rekamMedisTahunan = new ApexCharts(rekamMedisTahunanEl, rekamMedisTahunanConfig);
    rekamMedisTahunan.render();
  }
})();
</script>
@endsection

@section('content')

<div class="row g-6">
  <!-- Website Analytics -->
  <div class="col-lg-6">
    <div class="swiper-container swiper-container-horizontal swiper swiper-card-advance-bg" id="swiper-with-pagination-cards">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <div class="row">
            <div class="col-12">
              <h5 class="text-white mb-0">Jumlah Rekam Medis</h5>
              <small>Total {{$data['totalRekamMedis']}}</small>
            </div>
            <div class="row">
              <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1 pt-md-9">
                <h6 class="text-white mt-0 mt-md-3 mb-4">Status</h6>
                <div class="row">
                  <div class="col-12">
                    <ul class="list-unstyled mb-0">
                        @foreach ($data['rekamMedisPerStatus'] as $key => $item)
                        <li class="d-flex mb-4 align-items-center">
                          <p class="mb-0 fw-medium me-2 website-analytics-text-bg">{{$key}}</p>
                          <p class="mb-0">{{$item}}</p>
                        </li>
                        @endforeach
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                <img src="{{asset('assets/img/illustrations/card-website-analytics-1.png')}}" alt="Website Analytics" height="150" class="card-website-analytics-img">
              </div>
            </div>
            <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
              <img src="{{asset('assets/img/illustrations/card-website-analytics-3.png')}}" alt="Website Analytics" height="150" class="card-website-analytics-img">
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div class="row">
            <div class="col-12">
              <h5 class="text-white mb-0">Jumlah Dokter</h5>
              <small>Total {{$data['totalDokter']}}</small>
            </div>
            <div class="row">
              <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1 pt-md-9">
                <h6 class="text-white mt-0 mt-md-3 mb-4">Jenis Kelamin</h6>
                <div class="row">
                  <div class="col-12">
                    <ul class="list-unstyled mb-0">
                        @foreach ($data['dokterPerJK'] as $key => $item)
                        <li class="d-flex mb-4 align-items-center">
                          <p class="mb-0 fw-medium me-2 website-analytics-text-bg">{{$key == 'L' ? 'Laki-laki' : 'Perempuan'}}</p>
                          <p class="mb-0">{{$item}}</p>
                        </li>
                        @endforeach
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                <img src="{{asset('assets/img/illustrations/card-website-analytics-1.png')}}" alt="Website Analytics" height="150" class="card-website-analytics-img">
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div class="row">
            <div class="col-12">
              <h5 class="text-white mb-0">Jumlah Pasien</h5>
              <small>Total {{$data['totalPasien']}}</small>
            </div>
            <div class="row">
              <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1 pt-md-9">
                <h6 class="text-white mt-0 mt-md-3 mb-4">Jenis Kelamin</h6>
                <div class="row">
                  <div class="col-12">
                    <ul class="list-unstyled mb-0">
                        @foreach ($data['pasienPerJK'] as $key => $item)
                        <li class="d-flex mb-4 align-items-center">
                          <p class="mb-0 fw-medium me-2 website-analytics-text-bg">{{$key == 'L' ? 'Laki-laki' : 'Perempuan'}}</p>
                          <p class="mb-0">{{$item}}</p>
                        </li>
                        @endforeach
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                <img src="{{asset('assets/img/illustrations/card-website-analytics-1.png')}}" alt="Website Analytics" height="150" class="card-website-analytics-img">
              </div>
            </div>
            <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
              <img src="{{asset('assets/img/illustrations/card-website-analytics-2.png')}}" alt="Website Analytics" height="150" class="card-website-analytics-img">
            </div>
          </div>
        </div>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>
  <!--/ Website Analytics -->

  <!-- Sales Overview -->
  <div class="col-lg-6 col-sm-12">
    <div class="card h-100">
      <div class="card-header">
        <div class="d-flex justify-content-between">
          <p class="mb-0 text-body">Jumlah Pasien</p>
        </div>
        <h4 class="card-title mb-1">{{$data['totalPasien']}}</h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-4">
            <div class="d-flex gap-2 align-items-center mb-2">
              <span class="badge bg-label-info p-1 rounded"><i class="ti ti-gender-male ti-sm"></i></span>
              <p class="mb-0">Laki-laki</p>
            </div>
            <h5 class="mb-0 pt-1">{{ number_format($data['pasienPerJK']['L'] / $data['totalPasien'] * 100, 2) }}%</h5>
            <small class="text-muted">{{$data['pasienPerJK']['L']}}</small>
          </div>
          <div class="col-4">
            <div class="divider divider-vertical">
              <div class="divider-text">
                <span class="badge-divider-bg bg-label-secondary">VS</span>
              </div>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="d-flex gap-2 justify-content-end align-items-center mb-2">
              <p class="mb-0">Perempuan</p>
              <span class="badge bg-label-primary p-1 rounded"><i class="ti ti-gender-female ti-sm"></i></span>
            </div>
            <h5 class="mb-0 pt-1">{{ number_format($data['pasienPerJK']['P'] / $data['totalPasien'] * 100, 2) }}%</h5>
            <small class="text-muted">{{$data['pasienPerJK']['P']}}</small>
          </div>
        </div>
        <div class="d-flex align-items-center mt-6">
          <div class="progress w-100" style="height: 10px;">
            <div class="progress-bar bg-info" style="width: {{ number_format($data['pasienPerJK']['L'] / $data['totalPasien'] * 100, 2) }}%" role="progressbar" aria-valuenow="{{ number_format($data['pasienPerJK']['L'] / $data['totalPasien'] * 100, 2) }}" aria-valuemin="0" aria-valuemax="100"></div>
            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ number_format($data['pasienPerJK']['L'] / $data['totalPasien'] * 100, 2) }}%" aria-valuenow="{{ number_format($data['pasienPerJK']['L'] / $data['totalPasien'] * 100, 2) }}" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Sales Overview -->

  <!-- Earning Reports -->
  <div class="col-lg-12">
    <div class="card h-100">
      <div class="card-header pb-0 d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-1">Data Rekam Medis</h5>
          <p class="card-subtitle">10 Tahun Terakhir</p>
        </div>
        <!-- </div> -->
      </div>
      <div class="card-body">
        <div class="row align-items-center g-md-8">
          <div class="col-12">
            <div id="rekamMedisTahunan"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Earning Reports -->
</div>

@endsection
