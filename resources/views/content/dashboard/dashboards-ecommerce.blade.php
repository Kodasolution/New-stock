@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Paradise - SMS')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
@endsection
@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/dashboards-ecommerce.js') }}"></script>
@endsection
@section('content')
    <div class="row">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-primary"><i
                                            class='bx bx-money-withdraw'></i>
                                        </span>
                                </div>  
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2">
                                        {{ $outputProduct }} BIF</h5>
                                    <small class="text-muted">Sorties</small>
                                </div>
                            </div>
                            <div id="conversationChart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-danger "> <img
                                            style="max-width: 50%; height: auto;"
                                            src="{{ asset('assets/img/icons/dash4.svg') }}" alt="img">
                                    </span>

                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2">{{ $depense }} BIF</h5>
                                    <small class="text-muted">Depenses</small>
                                </div>
                            </div>
                            <div id="incomeChart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-success"><i
                                            class='bx bx-transfer-alt'></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2">{{ $dettes }} BIF</h5>
                                    <small class="text-muted">Dettes</small>
                                </div>
                            </div>
                            <div id="profitChart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-info"><i
                                        class='bx bx-wallet fs-4'></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2">{{ $salaires }} BIF</h5>
                                    <small class="text-muted">Salaires</small>
                                </div>
                            </div>
                            <div id="expensesLineChart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-success "> <img
                                            style="max-width: 50%; height: auto;"
                                            src="{{ asset('assets/img/icons/dash3.svg') }}" alt="img">
                                    </span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2">{{ $caisses }} BIF</h5>
                                    <small class="text-muted">caisse</small>
                                </div>
                            </div>
                            <div id="expensesLineChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Weekly Order Summary -->
        <!-- All Users -->
        <div class="col-md-6 col-lg-6 col-xl-4 mb-4 mb-xl-0">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-2"> Recently Added Products</h5>
                    {{-- <h1 class="display-6 fw-normal mb-0">8,634,820</h1> --}}
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        @foreach ($products as $product)
                            <li class="mb-3 d-flex justify-content-between">
                                <div class="d-flex align-items-center lh-1 me-3">
                                    <span class="badge badge-dot bg-success me-2"></span>
                                    @foreach ($product->productMouvements as $productMouv)
                                        {{ $productMouv->product->name }}
                                    @endforeach
                                </div>
                                <div class="d-flex gap-3">
                                    <span>
                                        @foreach ($product->productMouvements as $productMouv)
                                            {{ $productMouv->product->quantity }} {{ $productMouv->product->unit_mesure }}
                                        @endforeach
                                    </span>
                                    <span class="fw-semibold">
                                        @foreach ($product->productMouvements as $productMouv)
                                            {{ $productMouv->price_un }} BIF / {{ $productMouv->product->unit_mesure }}
                                        @endforeach
                                    </span>
                                </div>
                            </li>
                        @endforeach

                    </ul>
                </div>
            </div>
        </div>
        <!--/ All Users -->
        <!-- Marketing Campaigns -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Expired Products </h5>
                </div>
                <div class="table-responsive">
                    <table class="table border-top">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($productFinish as $pro)
                                <tr>
                                    <td class="text-nowrap"> {{ $pro->name }}</td>
                                    <td class="text-nowrap"> {{ $pro->category->name }}</td>
                                    <td>{{ $pro->updated_at }}</td>
                                    <td><span class="text-danger">Finish</span></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn p-0" type="button" id="action1" data-bs-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="action1">
                                                <a class="dropdown-item" href="#">Details</a>
                                                <a class="dropdown-item" href="#">Write a Review</a>
                                                <a class="dropdown-item" href="#">Download Invoice</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- Statistics cards & Revenue Growth Chart -->
        {{-- <div class="col-lg-4 col-12 mt-4">
            <div class="row">
                <!-- Statistics Cards -->
                <div class="col-6 col-md-3 col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-success"><i
                                        class="bx bx-purchase-tag fs-4"></i></span>
                            </div>
                            <span class="d-block text-nowrap">Purchase</span>
                            <h2 class="mb-0">65</h2>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-danger"><i
                                        class="bx bx-cart fs-4"></i></span>
                            </div>
                            <span class="d-block text-nowrap">Order</span>
                            <h2 class="mb-0">40</h2>
                        </div>
                    </div>
                </div>
                <!--/ Statistics Cards -->
                <!-- Revenue Growth Chart -->
                <div class="col-12 col-md-6 col-lg-12 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center pb-0">
                            <h5 class="card-title mb-0">Revenue Growth</h5>
                            <span>$25,980</span>
                        </div>
                        <div class="card-body pb-0">
                            <div id="revenueGrowthChart"></div>
                        </div>
                    </div>
                </div>
                <!--/ Revenue Growth Chart -->
            </div>
        </div> --}}
        <!--/ Statistics cards & Revenue Growth Chart -->
        <!-- Weekly Order Summary -->
        {{-- <div class="col-xl-8 col-12 mt-4">
            <div class="card">
                <div class="row row-bordered m-0">
                    <!-- Order Summary -->
                    <div class="col-md-8 col-12 px-0">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Weekly Order Summary</h5>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="orderSummaryOptions"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orderSummaryOptions">
                                    <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div id="orderSummaryChart"></div>
                        </div>
                    </div>
                    <!-- Sales History -->
                    <div class="col-md-4 col-12 px-0">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Sales Overview</h5>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="salesOverviewOptions"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="salesOverviewOptions">
                                    <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="mt-1">Last Week</h6>
                            <p class="mb-4">Performance 45% 🤩 better compare to last month</p>
                            <ul class="list-unstyled m-0 pt-0">
                                <li class="mb-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="avatar avatar-sm flex-shrink-0 me-2">
                                            <span class="avatar-initial rounded bg-label-primary"><i
                                                    class="bx bx-trending-up"></i></span>
                                        </div>
                                        <div>
                                            <p class="mb-0 lh-1 text-muted text-nowrap">Earnings This Month</p>
                                            <small class="fw-semibold text-nowrap">$84,789</small>
                                        </div>
                                    </div>
                                    <div class="progress" style="height:6px;">
                                        <div class="progress-bar bg-primary" style="width: 75%" role="progressbar"
                                            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="avatar avatar-sm flex-shrink-0 me-2">
                                            <span class="avatar-initial rounded bg-label-success"><i
                                                    class="bx bx-dollar"></i></span>
                                        </div>
                                        <div>
                                            <p class="mb-0 lh-1 text-muted text-nowrap">Average Daily Sales</p>
                                            <small class="fw-semibold text-nowrap">$12,398</small>
                                        </div>
                                    </div>
                                    <div class="progress" style="height:6px;">
                                        <div class="progress-bar bg-success" style="width: 75%" role="progressbar"
                                            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
@endsection
