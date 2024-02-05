@extends('layouts/layoutMaster')

@section('title', ' Ajouter Salaire-Employé')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
@endsection

{{--  @section('page-script')
<script src="{{asset('assets/js/form-layouts.js')}}"></script>
@endsection  --}}

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
<h4 class="py-3 breadcrumb-wrapper mb-4"><span class="text-muted fw-light"></span>Versément des salaires</h4>

<!-- Basic Layout & Basic with Icons -->
<div class="row">
  <!-- Basic Layout -->
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Ajouter un versément</h5> 
        {{--  <small class="text-muted float-end">Default label</small>  --}}
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('expenses.versement.store') }}" >
          @csrf

          {{--  <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="multicol-country">Employé</label>
            <div class="col-sm-10">
              <select class="form-select @error('user_id') is-invalid @enderror" data-allow-clear="true" name="user_id" >
                <option value="{{ old('user_id') }}" >Choose employee</option>
                @foreach ($salaires as $salaire)
                  <option value="{{ $salaire->id }}">{{ $salaire->user->firstName }} {{ $salaire->user->lastName }}</option>
                @endforeach
              </select>

              @error('user_id')
                  <div class="invalid-feedback">{{ $errors->first('user_id') }}</div>
              @enderror
            </div>
          </div>  --}}

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-name">Employé</label>
            <div class="col-sm-10">
              {{-- <select id="select2"
              class="select2 form-select form-select-lg @error('salaire_id') is-invalid @enderror"
              data-allow-clear="true" name="salaire_id"> --}}
              <select class="form-select @error('salaire_id') is-invalid @enderror" data-allow-clear="true" name="salaire_id" id="selectEmployee">
                <option value="{{ old('salaire_id') }}">Choose employee</option>
                @foreach ($salaires as $salaire)
                  <option value="{{ $salaire->id }}">{{ $salaire->user->firstName }} {{ $salaire->user->lastName }}</option>
                @endforeach
              </select>
              @error('salaire_id')
                <div class="invalid-feedback">{{ $errors->first('salaire_id') }}</div>
              @enderror
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-name">Versé le</label>
            <div class="col-sm-10">
              <input type="date" name="date_verse" class="form-control @error('date_verse') is-invalid @enderror"  
                id="basic-default-name" placeholder="Enter the date" value="{{ old('date_verse') }}"/>
              @error('date_verse')
                  <div class="invalid-feedback">{{ $errors->first('date_verse') }}</div>
              @enderror
            </div>
          </div>

          {{--  <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-name">Montant versé</label>
            <div class="col-sm-10">
              <input type="text" name="montant_verse" class="form-control @error('montant_verse') is-invalid @enderror" 
                id="basic-default-name" placeholder="Enter amount" value="{{ old('montant_verse') }}"/>
                @error('montant_verse')
                  <div class="invalid-feedback">{{ $errors->first('montant_verse') }}</div>
              @enderror
            </div>
          </div>  --}}
          
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-name">Montant du salaire</label>
            <div class="col-sm-10">
              <input type="text" name="montant_verse" class="form-control" id="montantSalaire" value="" readonly />
              @error('montant_verse')
                  <div class="invalid-feedback">{{ $errors->first('montant_verse') }}</div>
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

<script>
  document.getElementById('selectEmployee').addEventListener('change', function () {
    const selectedUserId = this.value;
    const montantSalaireInput = document.getElementById('montantSalaire');
    const selectedSalaire = {!! json_encode($salaires) !!}.find(salaire => salaire.id == selectedUserId);
    montantSalaireInput.value = selectedSalaire ? selectedSalaire.montant : '';
  });
</script>

@endsection
