@extends('layouts/layoutMaster')

@section('title', 'Dette List')

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

    @if (session()->has('success'))
        <div class=" alert alert-success ">
            {{ session()->get('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif
    <h4 class="py-3 breadcrumb-wrapper mb-2">Admin / Toutes les dettes</h4>

    <!-- Users List Table -->
    <div class="card">
        <div class="card-header ">
            <div class="d-flex justify-content-end">
                <a href="{{ route('expenses.dette.create') }}" class="btn btn-primary"><i
                        class="bx bx-plus me-0 me-lg-2"></i>
                    Donner une
                    dette</a>
            </div>
            <div class="row">
                <form action="{{ route('expenses.dette.index') }}" method="GET" class="d-sm-flex col-9 ">
                    <div class="col-md-2 col-sm-6 m-3">
                        <select name="type" id="" class="form-select">
                            <option value="">user type</option>
                            <option value="user" {{ request('type') == 'user' ? 'selected' : '' }}> Employee</option>
                            <option value="client" {{ request('type') == 'client' ? 'selected' : '' }}>Client</option>
                        </select>
                    </div>
                    <div class ="col-md-3 col-sm-6 m-3">
                        <input type="text" class="form-control " name="user" value="{{ request('user') }}"
                            placeholder="user name or email ">
                    </div>
                    {{-- <div class ="col-md-3 col-sm-6 m-3">
                        <input type="date" class="form-control " name="date" value="{{ request('date') }}"
                            placeholder="product name ">
                    </div> --}}
                    {{-- <div class="col-md-3 col-12 m-3">
         
                        <input type="text" class="form-control" value="{{ request('date') }}" name="date"
                            placeholder="YYYY-MM-DD to YYYY-MM-DD" id="flatpickr-range" />
                        @error('date')
                            <div class=" invalid-feedback">{{ $errors->first('date') }}</div>
                        @enderror
                    </div> --}}
                    <div class ="col-md-3 col-sm-6 m-3">
                        <select name="status" id="" class="form-select">
                            <option value="">choose status</option>
                            <option value="paid"{{ request('status') == 'paid' ? 'selected' : '' }}>Paid
                            </option>
                            <option value="unpaid"{{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid
                            </option>
                        </select>
                    </div>
                    <div class="col-sm-3 m-3">
                        <button type="submit" class="btn btn-info mb-3 text-nowrap ">Search</button>
                        <a href="{{ route('expenses.dette.index') }}" class= "btn btn-dark  mb-3">Reset</a>
                    </div>
                </form>
                <span class="text-muted col-lg-12 mx-3 mt-n4"><b>{{ $dettes->total() }} result(s)</b></span>
            </div>

        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Type</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Montant</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dettes as $dette)
                        <tr id="row_19">
                            <td>
                                <span class="badge-basic-primary-text">{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                <span class="badge-basic-primary-text">{{ Str::ucfirst($dette->type) }}</span>
                            </td>
                            @if ($dette->type == 'user')
                                <td>
                                    <span class="badge-basic-primary-text">
                                        {{ $dette->user->firstName ?? '' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-basic-primary-text">
                                        {{ $dette->user->lastName ?? '' }}
                                    </span>
                                </td>
                            @else
                                <td>
                                    <span class="badge-basic-primary-text">
                                        {{ $dette->client->firstName ?? '' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-basic-primary-text">
                                        {{ $dette->client->lastName ?? '' }}
                                    </span>
                                </td>
                            @endif
                            <td>
                                <span class="badge-basic-primary-text">
                                    {{ $dette->montant_total }} BIF
                                </span>
                            </td>
                            <td>
                                @if ($dette->status == 1)
                                    <span class="badge rounded-pill bg-danger">Unpaid</span>
                                @else
                                    <span class="badge rounded-pill bg-success">Paid</span>
                                @endif

                            </td>
                            <td>{{ $dette->updated_at }}</td>
                            <td class="action">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                    <div class="dropdown-menu">

                                        <a class="dropdown-item" href="{{ route('expenses.dette.show', [$dette->id]) }}"><i
                                                class="bx bx-detail me-1"></i>
                                            Details</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between">
                <div class="col-lg-4">
                    {!! $pagination->perPageForm() !!}
                </div>
                <div class="">
                    {!! $pagination->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
