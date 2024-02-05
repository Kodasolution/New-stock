@extends('layouts/layoutMaster')

@section('title', ' Add caisse')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    <script src="{{ asset('assets/js/forms-selects.js') }}"></script>

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

    <h4 class="py-3 breadcrumb-wrapper mb-4"><span class="text-muted fw-light"><a href="{{ route('caisse.index') }}">La
                caisse</a></h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Créer un mouvement de caisse</h5>
                    {{--  <small class="text-muted float-end">Default label</small>  --}}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('caisse.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="multicol-country">User</label>
                            <div class="col-sm-10">
                                <select id="select2"
                                    class="select2 form-select form-select-lg @error('user_id') is-invalid @enderror"
                                    data-allow-clear="true" name="user_id">
                                    <option value="{{ old('user_id') }}">Choose user</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->firstName }} {{ $user->lastName }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $errors->first('user_id') }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Type</label>
                            <div class="col-sm-10">
                                <select class="form-select @error('type') is-invalid @enderror" data-allow-clear="true"
                                    name="type">
                                    <option value="">Choose Type</option>
                                    <option value="approvisionnement">Approvisionnement</option>
                                    <option value="encaissement">Encaissement</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Montant</label>
                            <div class="col-sm-10">
                                <input type="text" name="montant"
                                    class="form-control @error('montant') is-invalid @enderror" id="basic-default-name"
                                    placeholder="Enter amount" />
                                @error('montant')
                                    <div class="invalid-feedback">{{ $errors->first('montant') }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Date</label>
                            <div class="col-sm-10">
                                <input type="date" name="date_creation"
                                    class="form-control @error('date_creation') is-invalid @enderror"
                                    id="basic-default-name" placeholder="Enter the date" />
                                @error('date_creation')
                                    <div class="invalid-feedback">{{ $errors->first('date_creation') }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                              <a href="{{ route('caisse.index')}}" class="btn btn-dark">Return</a>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
