@extends('layouts/layoutMaster')

@section('title', ' Edit client credit')

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
        
<h4 class="py-3 breadcrumb-wrapper mb-4"><span class="text-muted fw-light">Forms/</span> Horizontal Layouts</h4>

<!-- Basic Layout & Basic with Icons -->
<div class="row">
  <!-- Basic Layout -->
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Basic Layout</h5> 
        {{--  <small class="text-muted float-end">Default label</small>  --}}
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('client-credit.update', $credits->id) }}" >
            @csrf
            @method('patch')
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="multicol-country">Clients</label>
              <div class="col-sm-10">
                <select class="form-select @error('client_id') is-invalid @enderror" data-allow-clear="true" name="client_id" >
                    @foreach ($clients as $client)
                        @if ($client->id==$credits->client_id)
                            <option selected value="{{ $client->id }}">
                                {{ $client->firtName }} {{ $client->lastName }}
                            </option>
                        @else
                            <option value="{{ $client->id }}">
                              {{ $client->firtName }} {{ $client->lastName }}
                            </option>

                        @endif
                    @endforeach
                </select>
                @error('client_id')
                <div class="invalid-feedback">{{ $errors->first('client_id') }}</div>
              @enderror
              </div>
            </div>
  
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Montant</label>
              <div class="col-sm-10">
                    <input type="text" name="montant" class="form-control @error('montant') is-invalid @enderror" 
                      id="basic-default-name" placeholder="Enter amount" value="{{ $credits->montant }}"  />
                    </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Description</label>
              <div class="col-sm-10">
                    <input type="text" name="description" class="form-control" 
                      id="basic-default-name" placeholder="Enter description" value="{{ $credits->description }}"  />
                    </div>
            </div>
  
  
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Date</label>
              <div class="col-sm-10">
                <input type="date" name="date_credit" class="form-control" 
                id="basic-default-name" placeholder="Enter the date" value="{{ $credits->date_credit }}"  />
              </div>
            </div>


            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="multicol-country">Status</label>
              <div class="col-sm-10">
                <select class="form-select" data-allow-clear="true" name="status" >
                  @if($credits->status)
                      <option value="1" selected>Unpaid</option>
                      <option value="0">Paid</option>
                  @else
                      <option value="1">Unpaid</option>
                      <option value="0" selected>Paid</option>
                  @endif
                </select>
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
@endsection
