@extends('layouts/layoutMaster')

@section('title', ' Versément des salaires')

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
    {{-- <h4 class="py-3 breadcrumb-wrapper mb-2">Admin / Liste des verséments</h4> --}}

    <h4 class="py-3 breadcrumb-wrapper mb-4">Admin / <a
                href="{{ route('expenses.versement.index') }}">Liste des verséments</a> / Modification versement</h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Modifier le versément</h5>
                    {{--  <small class="text-muted float-end">Default label</small>  --}}
                </div>
                <div class="card-body">
                    <form method="POST"
                        action="{{ route('expenses.versement.update', ['versement' => $versement->id]) }}">
                        @csrf
                        @method('patch')
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="multicol-country">Employé</label>
                            <div class="col-sm-10">
                                <select id="select2"
                                    class="select2 form-select form-select-lg @error('salaire_id') is-invalid @enderror"
                                    data-allow-clear="true" name="salaire_id">
                                    {{-- <select class="form-select @error('salaire_id') is-invalid @enderror" data-allow-clear="true" 
                  name="salaire_id" > --}}
                                    @foreach ($salaires as $salaire)
                                        @if ($salaire->id == $versement->salaire_id)
                                            <option selected value="{{ $salaire->id }}">
                                                {{ $salaire->user->firstName }} {{ $salaire->user->lastName }}
                                            </option>
                                        @else
                                            <option value="{{ $salaire->id }}">
                                                {{ $salaire->user->firstName }} {{ $salaire->user->lastName }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('salaire_id')
                                    <div class="invalid-feedback">{{ $errors->first('salaire_id') }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Date</label>
                            <div class="col-sm-10">
                                <input type="date" name="date_verse"
                                    class="form-control @error('date_verse') is-invalid @enderror" id="basic-default-name"
                                    placeholder="Enter the date" value="{{ $versement->date_verse }}" />
                                @error('date_verse')
                                    <div class="invalid-feedback">{{ $errors->first('date_verse') }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Montant</label>
                            <div class="col-sm-10">
                                <input type="text" name="montant_verse"
                                    class="form-control @error('montant_verse') is-invalid @enderror"
                                    id="basic-default-name" placeholder="Enter amount"
                                    value="{{ $versement->montant_verse }}" />
                                @error('montant_verse')
                                    <div class="invalid-feedback">{{ $errors->first('montant_verse') }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Mois</label>
                            <div class="col-sm-10">
                                <select name="mois" class="form-control @error('mois') is-invalid @enderror"
                                    id=""> 
                                    <option value="1"{{ $versement->mois == 1 ? 'selected' : '' }}>Janvier</option>
                                    <option value="2"{{ $versement->mois == 2 ? 'selected' : '' }}>Fevrier</option>
                                    <option value="3"{{ $versement->mois == 3 ? 'selected' : '' }}>Mars</option>
                                    <option value="4"{{ $versement->mois == 4 ? 'selected' : '' }}>Avril</option>
                                    <option value="5"{{ $versement->mois == 5 ? 'selected' : '' }}>Mai</option>
                                    <option value="6"{{ $versement->mois == 6 ? 'selected' : '' }}>Juin</option>
                                    <option value="7"{{ $versement->mois == 7 ? 'selected' : '' }}>Juillet</option>
                                    <option value="8"{{ $versement->mois == 8 ? 'selected' : '' }}>Auot</option>
                                    <option value="9"{{ $versement->mois == 9 ? 'selected' : '' }}>Septembre</option>
                                    <option value="10"{{ $versement->mois == 10 ? 'selected' : '' }}>Octobre</option>
                                    <option value="11"{{ $versement->mois == 11 ? 'selected' : '' }}>Novembre</option>
                                    <option value="12"{{ $versement->mois == 12 ? 'selected' : '' }}>Decembre</option>
                                </select>
                                @error('mois')
                                    <div class="invalid-feedback">{{ $errors->first('mois') }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Status</label>
                            <div class="col-sm-10">
                                <select name="status" class="form-control @error('status') is-invalid @enderror"
                                    id="">
                                    <option value="1"{{ $versement->status == 1 ? 'selected' : '' }}>Paid</option>
                                    <option value="0"{{ $versement->status == 0 ? 'selected' : '' }}>Unpaid</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name"></label>
                            <div class="col-sm-4">
                                <label class="switch switch-success">
                                    <input type="checkbox" name="has_dette" class="switch-input" {{ $versement->has_dette == 1 ? 'checked':'' }}  />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label">Has Dette</span>
                                </label>
                            </div>
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Montant En Dette</label>
                            <div class="col-sm-4">
                                <input type="text" name="dette_montant"
                                    class="form-control @error('dette_montant')  is-invalid @enderror" disabled
                                    id="basic-default-name" placeholder="Enter amount"
                                    value="{{ $versement->dette_montant }}" />
                                @error('dette_montant')
                                    <div class="invalid-feedback">{{ $errors->first('dette_montant') }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <a href="{{ route('expenses.versement.index') }}" class="btn btn-dark">Return</a>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
