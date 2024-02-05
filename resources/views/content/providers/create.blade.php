@extends('layouts/layoutMaster')

@section('title', ' Add Provider')

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
<h4 class="py-3 breadcrumb-wrapper mb-4"><span class="text-muted fw-light"><a href="{{ route('provider.index') }}">Liste des fournisseurs</a></h4>

<!-- Basic Layout & Basic with Icons -->
<div class="row">
  <!-- Basic Layout -->
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Ajouter un fournisseur</h5> 
        {{--  <small class="text-muted float-end">Default label</small>  --}}
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('provider.store') }}" >
          @csrf
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-name">First name</label>
            <div class="col-sm-10">
              <input type="text" name="firstName" class="form-control @error('firstName') is-invalid @enderror" 
                id="basic-default-name" placeholder="Enter first name" value="{{ old('firstName') }}"/>
              @error('firstName')
                  <div class="invalid-feedback">{{ $errors->first('firstName') }}</div>
              @enderror
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-name">Last name</label>
            <div class="col-sm-10">
              <input type="text" name="lastName" class="form-control @error('lastName') is-invalid @enderror" 
                id="basic-default-name" placeholder="Enter last name" value="{{ old('lastName') }}"/>
              @error('lastName')
                  <div class="invalid-feedback">{{ $errors->first('lastName') }}</div>
              @enderror
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-name">Phone number</label>
            <div class="col-sm-10">
              <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                id="basic-default-name" placeholder="Enter phone number" value="{{ old('phone') }}"/>
              @error('phone')
                  <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
              @enderror
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-name">Email</label>
            <div class="col-sm-10">
              <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" 
                id="basic-default-name" placeholder="Enter email" value="{{ old('email') }}"/>
              @error('email')
                  <div class="invalid-feedback">{{ $errors->first('email') }}</div>
              @enderror
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-name">Adress</label>
            <div class="col-sm-10">
              <input type="text" name="adress" class="form-control @error('adress') is-invalid @enderror" 
                id="basic-default-name" placeholder="Enter adress" value="{{ old('adress') }}"/>
              @error('adress')
                  <div class="invalid-feedback">{{ $errors->first('adress') }}</div>
              @enderror
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="multicol-country">Status</label>
            <div class="col-sm-10">
              <select class="form-select @error('status') is-invalid @enderror" data-allow-clear="true" name="status" >
                <option value="{{ old('status') }}" >Choose status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
              @error('status')
                  <div class="invalid-feedback">{{ $errors->first('status') }}</div>
              @enderror
            </div>
          </div>
          
          <div class="row justify-content-end">
            <div class="col-sm-10">
              <a href="{{ route('provider.index') }}" class="btn btn-dark">Return</a>
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>
@endsection
