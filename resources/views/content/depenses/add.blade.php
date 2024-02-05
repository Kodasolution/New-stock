@extends('layouts/layoutMaster')

@section('title', 'Expenses')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.css')}}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />

@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/autosize/autosize.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.js')}}"></script>
<script src="{{asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js')}}"></script>
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

@endsection

@section('page-script')
<script src="{{ asset('assets/js/forms-selects.js') }}"></script>
<script src="{{asset('assets/js/forms-extras.js')}}"></script>

@endsection

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4"><span class="text-muted fw-light"></span><a href="{{ route('expenses.index') }}">Liste des dépenses</a></h4>

<div class="row">

  <!-- Form Repeater -->
  <div class="col-12">
    <div class="card">
      <h5 class="card-header">Ajouter une dépense</h5>
      <div class="card-body">
        <form class="form-repeater" method="POST" action="{{ route('expenses.save') }}" >
            @csrf
          
            <div class="row">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="multicol-country">User</label>
                    <div class="col-sm-10">
                        <select id="select2"
                        class="select2 form-select form-select-lg @error('user_id') is-invalid @enderror"
                        data-allow-clear="true" name="user_id">
                        <option value="{{ old('user_id') }}" >Choose user</option>
                        @foreach ($users as $user)
                          <option value="{{ $user->id }}">{{ $user->firstName }} {{ $user->lastName }}</option>
                        @endforeach
                      </select>
                      @error('user_id')
                        <div class="invalid-feedback">{{ $errors->first('user_id') }}</div>
                      @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-default-name">Date</label>
                    <div class="col-sm-10">
                        <input type="date" name="date_creation" class="form-control @error('date_creation') is-invalid @enderror" 
                            id="basic-default-name" placeholder="Enter the date" value="{{ old('date_creation') }}"/>
        
                        @error('date_creation')
                            <div class="invalid-feedback">{{ $errors->first('date_creation') }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div data-repeater-list="group_a">
                <div data-repeater-item>
                    <div class="row">

                        <div class="mb-3 col-lg-6 col-xl-2 col-12 mb-0">
                            <label class="form-label" for="form-repeater-1-4">Ingrédients</label>
                            {{-- <select id="select2Basic"
                            class="select2 form-select form-select-lg @error('product_id') is-invalid @enderror"
                            data-allow-clear="true" name="product_id"> --}}
                            <select id="form-repeater-1-4" class="form-select @error('ingredient_id') is-invalid @enderror" name="ingredient_id">
                                <option value="{{ old('ingredient_id') }}" >Choose ingredient</option>
                                @foreach($ingredients as $ingredient)
                                    <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                                @endforeach
                            </select>
                            @error('ingredient_id')
                                <div class="invalid-feedback">{{ $errors->first('ingredient_id') }}</div>
                            @enderror
                        </div>


                        <div class="mb-3 col-lg-6 col-xl-3 col-12 mb-0">
                            <label class="form-label" for="form-repeater-1-1">Prix unitaire</label>
                            <input type="text" id="form-repeater-1-1" class="form-control @error('prix_unitaire') is-invalid @enderror" 
                                name="prix_unitaire" placeholder="prix unitaire" />
                            @error('prix_unitaire')
                                <div class="invalid-feedback">{{ $errors->first('prix_unitaire') }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0">
                            <button class="btn btn-label-danger mt-4" data-repeater-delete>
                                <i class="bx bx-x me-1"></i>
                                <span class="align-middle">Delete</span>
                        </button>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="mb-0">
                <button class="btn btn-primary" data-repeater-create>
                <i class="bx bx-plus me-1"></i>
                <span class="align-middle">Add</span>
                </button>
            </div>

            <div class="row justify-content-end">
                <div class="col-sm-10">
                    <a href="{{ route('expenses.list') }}" class="btn btn-dark">Return</a>
                    <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
  <!-- /Form Repeater -->
</div>
@endsection
