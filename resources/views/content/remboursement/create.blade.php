@extends('layouts/layoutMaster')

@section('title', ' Remboursement')

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

    <h4 class="py-3 breadcrumb-wrapper mb-4"><span class="text-muted fw-light"></span><a
            href="{{ route('expenses.remboursement.index') }}">Liste des remboursements</a></h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Rembourser une dette</h5>
                    {{--  <small class="text-muted float-end">Default label</small>  --}}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('expenses.remboursement.store') }}">
                        @csrf
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="multicol-country">Débiteur</label>
                            <div class="col-sm-6">
                                <select id="select2Basic"
                                    class="select2 form-select form-select-lg @error('dette_id') is-invalid @enderror"
                                    data-allow-clear="true" name="dette_id">

                              {{-- <select class="form-select  @error('dette_id') is-invalid @enderror" data-allow-clear="true" name="dette_id" > --}}
                                    <option value="{{ old('dette_id') }}">Choose debiteur</option>
                                    @foreach ($dettes as $dette)
                                        <option value="{{ $dette->id }}">
                                            @if ($dette->type == 'user' && $dette->user)
                                                {{ $dette->user->firstName }} {{ $dette->user->lastName }}
                                            @elseif ($dette->type == 'client' && $dette->client)
                                                {{ $dette->client->firstName }} {{ $dette->client->lastName }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('dette_id')
                                    <div class="invalid-feedback">{{ $errors->first('dette_id') }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Montant</label>
                            <div class="col-sm-6">
                                <input type="text" name="montant_rembourse"
                                    class="form-control @error('montant_rembourse') is-invalid @enderror"
                                    id="basic-default-name" placeholder="Enter amount"
                                    value="{{ old('montant_rembourse') }}" />
                                @error('montant_rembourse')
                                    <div class="invalid-feedback">{{ $errors->first('montant_rembourse') }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Date</label>
                            <div class="col-sm-6">
                                <input type="date" name="date_rembourse"
                                    class="form-control @error('date_rembourse') is-invalid @enderror"
                                    id="basic-default-name" placeholder="Enter the date"
                                    value="{{ old('date_rembourse') }}" />
                                @error('date_rembourse')
                                    <div class="invalid-feedback">{{ $errors->first('date_rembourse') }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <a href="{{ route('expenses.remboursement.index') }}" class="btn btn-dark">Return</a>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
