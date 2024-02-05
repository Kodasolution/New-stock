
@extends('layouts/layoutMaster')

@section('title', 'Dashboard - Stock')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/forms-pickers.js') }}"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar">
                                <span class="avatar-initial rounded-circle bg-label-primary"><i
                                        class='bx bx-user fs-4'></i></span>
                            </div>
                            <div class="card-info">
                                <h5 class="card-title mb-0 me-2">$38,566</h5>
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
                                <span class="avatar-initial rounded-circle bg-label-warning"><i
                                        class='bx bx-dollar fs-4'></i></span>
                            </div>
                            <div class="card-info">
                                <h5 class="card-title mb-0 me-2">$53,659</h5>
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
                                        class='bx bx-wallet fs-4'></i></span>
                            </div>
                            <div class="card-info">
                                <h5 class="card-title mb-0 me-2">$12,452</h5>
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
                                <span class="avatar-initial rounded-circle bg-label-danger"><i
                                        class='bx bx-cart fs-4'></i></span>
                            </div>
                            <div class="card-info">
                                <h5 class="card-title mb-0 me-2">$8,125</h5>
                                <small class="text-muted">Salaires</small>
                            </div>
                        </div>
                        <div id="expensesLineChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title">Search Filter</h5>
            <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">
                <form action="" method="GET">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 col-12 mb-4">
                            <label for="bs-rangepicker-basic" class="form-label">Basic</label>
                            <input type="text" id="bs-rangepicker-basic" class="form-control" />
                        </div>
                        <div class="col-4 mt-4">
                            <select id="country" class="select2 form-select">
                                <option value="">Select Type</option>
                                <option value="United Arab Emirates">Sorties</option>
                                <option value="United Kingdom">Dettes</option>
                                <option value="United States">Depenses</option>
                                <option value="United States">salaires</option>
                            </select>
                            @error('name')
                                <div class=" invalid-feedback">{{ $errors->first('name') }}</div>
                            @enderror
                        </div>
                        <div class="col-2 m-4">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">Search</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
