@extends('layouts/layoutMaster')

@section('title', ' Add Product')

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
    <h4 class="py-3 breadcrumb-wrapper mb-4"><span class="text-muted fw-light"></span>Liste des entrées</h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Ajouter une entrée</h5>
                    {{--  <small class="text-muted float-end">Default label</small>  --}}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('inventory.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="multicol-country">Product</label>
                            <div class="col-sm-10">
                                <select id="select2Basic"
                                    class="select2 form-select form-select-lg @error('product_id') is-invalid @enderror"
                                    data-allow-clear="true" name="product_id">
                                    <option value="{{ old('product_id') }}">Choose product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
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
                                <input type="text" name="quantity"
                                    class="form-control @error('quantity') is-invalid @enderror" id="basic-default-name"
                                    placeholder="Enter quantity" value="{{ old('quantity') }}" />
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $errors->first('quantity') }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Unit Price</label>
                            <div class="col-sm-10">
                                <input type="text" name="price_un"
                                    class="form-control @error('price_un') is-invalid @enderror" id="basic-default-name"
                                    placeholder="Enter unit price" value="{{ old('price_un') }}" />
                                @error('price_un')
                                    <div class="invalid-feedback">{{ $errors->first('price_un') }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="multicol-country">Users</label>
                            <div class="col-sm-10">
                              <select id="select2"
                              class="select2 form-select form-select-lg @error('user_id') is-invalid @enderror"
                              data-allow-clear="true" name="user_id">
                               
                                    <option value="{{ old('user_id') }}">Choose User</option>
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
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Date</label>
                            <div class="col-sm-10">
                                <input type="date" name="date_flux"
                                    class="form-control @error('date_flux') is-invalid @enderror" id="basic-default-name"
                                    placeholder="Enter the date" value="{{ old('date_flux') }}" />
                                @error('date_flux')
                                    <div class="invalid-feedback">{{ $errors->first('date_flux') }}</div>
                                @enderror
                            </div>
                        </div>

                        {{--  <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="multicol-country">Mouvement Type</label>
            <div class="col-sm-10">
              <select class="form-select" data-allow-clear="true" name="typeMouv" >
                <option value="" >Choose Type</option>
                <option value="input">Entrées</option>
                <option value="output">Sorties</option>
                <option value="return">Retour</option>
              </select>
            </div>
          </div>  --}}

                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                              <a href="{{ route('inventory.index') }}" class="btn btn-dark">Return</a>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
