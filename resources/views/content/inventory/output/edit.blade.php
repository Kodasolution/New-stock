@extends('layouts/layoutMaster')

@section('title', 'Flux de stock')

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
    <h4 class="py-3 breadcrumb-wrapper mb-4"><span class="text-muted fw-light">Liste des sorties</h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Modifier une sortie</h5>
                    {{--  <small class="text-muted float-end">Default label</small>  --}}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('inventory.updateOutput', $mouvements->id) }}">
                        @csrf
                        @method('patch')
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="multicol-country">Product</label>
                            <div class="col-sm-10">
                                <select id="select2Basic"
                                    class="select2 form-select form-select-lg @error('product_id') is-invalid @enderror"
                                    data-allow-clear="true" name="product_id">
                                    @foreach ($mouvements->productMouvements as $productMouvement)
                                        @foreach ($products as $product)
                                            @if ($product->id == $productMouvement->product_id)
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
                                @foreach ($mouvements->productMouvements as $productMouvement)
                                    <input type="text" name="quantity"
                                        class="form-control @error('quantity') is-invalid @enderror" id="basic-default-name"
                                        placeholder="Enter quantity" value="{{ $productMouvement->quantity }}" />
                                @endforeach
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $errors->first('quantity') }}</div>
                                @enderror
                            </div>
                        </div>
                        <input type="hidden" name="price_un" id="price_un" value="{{ $productMouvement->price_un }}">

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="multicol-country">User</label>
                            <div class="col-sm-10">
                                <select id="select2"
                                    class="select2 form-select form-select-lg @error('user_id') is-invalid @enderror"
                                    data-allow-clear="true" name="user_id">
                                    <option value="">Choose user</option>
                                    @foreach ($users as $user)
                                        @if ($user->id == $mouvements->user_id)
                                            <option selected value="{{ $user->id }}">
                                                {{ $user->firstName }}
                                                {{ $user->lastName }}
                                            </option>
                                        @else
                                            <option value="{{ $user->id }}">
                                                {{ $user->firstName }}
                                                {{ $user->lastName }}
                                            </option>
                                        @endif
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
                                    placeholder="Enter the date" value="{{ $mouvements->date_flux }}" />
                                @error('date_flux')
                                    <div class="invalid-feedback">{{ $errors->first('date_flux') }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                              <a href="{{ route('inventory.sorties') }}" class="btn btn-dark">Return</a>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
