@extends('layouts/layoutMaster')

@section('title', ' Edit dette employee')

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


<h4 class="py-3 breadcrumb-wrapper mb-4"><span class="text-muted fw-light"></span><a href="{{ route('expenses.dette.index') }}">Retour</a></h4>

<!-- Basic Layout & Basic with Icons -->
<div class="row">
  <!-- Basic Layout -->
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Débiteur : 
          @if ($details->dette->type == 'user')
            {{ $details->dette->user->firstName }} {{ $details->dette->user->lastName }}
          @else
            {{ $details->dette->client->firstName }} {{ $details->dette->client->lastName }}
          @endif
        </h5> 
        {{--  <small class="text-muted float-end">Default label</small>  --}}
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('expenses.dette.update', $details->id) }}" >
            @csrf
            @method('patch')

            
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Montant</label>
              <div class="col-sm-4">
                    <input type="text" name="montant" class="form-control @error('montant') is-invalid @enderror" 
                      id="basic-default-name" placeholder="Enter amount" value="{{ $details->montant }}"  />
                      @error('montant')
                      <div class="invalid-feedback">{{ $errors->first('montant') }}</div>
                    @enderror
                    </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Description</label>
              <div class="col-sm-4">
                    <input type="text" name="motif" class="form-control @error('motif') is-invalid @enderror" 
                      id="basic-default-name" placeholder="Enter description" value="{{ $details->motif }}"  />
                      @error('motif')
                      <div class="invalid-feedback">{{ $errors->first('motif') }}</div>
                    @enderror
                    </div>
            </div>
  
  
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Date</label>
              <div class="col-sm-4">
                <input type="date" name="date_creation" class="form-control @error('date_creation') is-invalid @enderror" 
                id="basic-default-name" placeholder="Enter the date" value="{{ $details->date_creation }}"  />
                @error('date_creation')
                <div class="invalid-feedback">{{ $errors->first('date_creation') }}</div>
              @enderror
              </div>
            </div>


            
            <div class="row justify-content-end">
              <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
    const typeSelect = document.getElementById('typeSelect');
    const userSelectDiv = document.getElementById('userSelectDiv');
    const clientSelectDiv = document.getElementById('clientSelectDiv');

    typeSelect.addEventListener('change', () => {
        if (typeSelect.value === 'user') {
            userSelectDiv.style.display = 'block';
            clientSelectDiv.style.display = 'none';
        } else if (typeSelect.value === 'client') {
            userSelectDiv.style.display = 'none';
            clientSelectDiv.style.display = 'block';
        } else {
            userSelectDiv.style.display = 'none';
            clientSelectDiv.style.display = 'none';
        }
    });

    // Définir l'état initial en fonction de la valeur actuelle de la dette
    if (typeSelect.value === 'user') {
        userSelectDiv.style.display = 'block';
        clientSelectDiv.style.display = 'none';
    } else if (typeSelect.value === 'client') {
        userSelectDiv.style.display = 'none';
        clientSelectDiv.style.display = 'block';
    } else {
        userSelectDiv.style.display = 'none';
        clientSelectDiv.style.display = 'none';
    }
</script>

@endsection
