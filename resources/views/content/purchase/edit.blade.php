@extends('layouts/layoutMaster')

@section('title', ' Edit purchase')

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
<h4 class="py-3 breadcrumb-wrapper mb-4"><span class="text-muted fw-light"></span> Liste des commandes</h4>

<!-- Basic Layout & Basic with Icons -->
<div class="row">
  <!-- Basic Layout -->
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Modifier une commande</h5> 
        {{--  <small class="text-muted float-end">Default label</small>  --}}
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('purchase.update', $purchases->id) }}" >
            @csrf
            @method('patch')
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="multicol-country">Product</label>
              <div class="col-sm-10">
                <select id="select2Basic"
                class="select2 form-select form-select-lg @error('product_id') is-invalid @enderror"
                data-allow-clear="true" name="product_id">
                  @foreach ($purchases->details as $detail)
                    @foreach ($products as $product)
                        @if ($product->id==$detail->product_id)
                            <option selected value="{{ $product->id }}">
                                {{ $product->name }}
                            </option>
                        @else
                            <option value="{{ $product->id }}">
                                {{ $product->name }}
                            </option>

                        @endif
                    @endforeach
                  @endforeach
                </select>
                @error('product_id')
                  <div class="invalid-feedback">{{ $errors->first('product_id') }}</div>
              @enderror
              </div>
            </div>
  
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Quantity</label>
              <div class="col-sm-10">
                @foreach ($purchases->details as $detail)
                    <input type="text" name="quantity" class="form-control @error('quantity') is-invalid @enderror" 
                      id="basic-default-name" placeholder="Enter quantity" value="{{ $detail->quantity }}"  />
                @endforeach
                @error('quantity')
                  <div class="invalid-feedback">{{ $errors->first('quantity') }}</div>
              @enderror
                    </div>
            </div>
  
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Unit Price</label>
              <div class="col-sm-10">
                @foreach ($purchases->details as $detail)
                    <input type="text" name="unit_price" class="form-control @error('unit_price') is-invalid @enderror" 
                      id="basic-default-name" placeholder="Enter unit price" value="{{ $detail->unit_price }}" />
                @endforeach
                @error('unit_price')
                  <div class="invalid-feedback">{{ $errors->first('unit_price') }}</div>
              @enderror
              </div>
            </div>
  
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="multicol-country">Provider</label>
              <div class="col-sm-10">
                <select class="form-select @error('fournisseur_id') is-invalid @enderror" data-allow-clear="true" name="fournisseur_id" >
                  <option value="" >Choose provider</option>
                  @foreach ($providers as $provider)
                      @if ($provider->id==$purchases->fournisseur_id)
                          <option selected value="{{ $provider->id }}">
                              {{ $provider->firstName }}
                              {{ $provider->lastName }}
                          </option>
                      @else
                          <option value="{{ $provider->id }}">
                              {{ $provider->firstName }}
                              {{ $provider->lastName }}
                          </option>
                      @endif
                  @endforeach
                </select>
                @error('fournisseur_id')
                  <div class="invalid-feedback">{{ $errors->first('fournisseur_id') }}</div>
              @enderror
              </div>
            </div>
  
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Date</label>
              <div class="col-sm-10">
                <input type="date" name="date_commande" class="form-control @error('date_commande') is-invalid @enderror" 
                id="basic-default-name" placeholder="Enter the date" value="{{ $purchases->date_commande }}"  />
                @error('date_commande')
                  <div class="invalid-feedback">{{ $errors->first('date_commande') }}</div>
              @enderror
              </div>
            </div>


            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="multicol-country">Status</label>
              <div class="col-sm-10">
                <select class="form-select @error('status_co') is-invalid @enderror" data-allow-clear="true" name="status_co" >
                  @if($purchases->status_co)
                      <option value="1" selected>Delivered</option>
                      <option value="0">Undelivered</option>
                  @else
                      <option value="1">Delivered</option>
                      <option value="0" selected>Undelivered</option>
                  @endif
                </select>
                @error('status_co')
                  <div class="invalid-feedback">{{ $errors->first('status_co') }}</div>
              @enderror
              </div>
            </div>
            
            <div class="row justify-content-end">
              <div class="col-sm-10">
                <a href="{{ route('purchase.index') }}" class="btn btn-dark">Return</a>
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
