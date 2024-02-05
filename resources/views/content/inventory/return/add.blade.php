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

    <h4 class="py-3 breadcrumb-wrapper mb-4"><span class="text-muted fw-light"></span> Liste des retours</h4>


    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Ajouter un retour</h5>
                    {{--  <small class="text-muted float-end">Default label</small>  --}}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('inventory.storeRetour') }}">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="multicol-country">Product</label>
                            <div class="col-sm-10">
                                <select id="select2Basic"
                                    class="select2 form-select form-select-lg @error('product_id') is-invalid @enderror"
                                    data-allow-clear="true" name="product_id">
                                    <option value="{{ old('product_id') }}">Choose product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            data-price-un="{{ $product->getLastPrice() }}">{{ $product->name }}</option>
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
                                    placeholder="Enter the date" />
                                @error('date_flux')
                                    <div class="invalid-feedback">{{ $errors->first('date_flux') }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <a href="{{ route('inventory.return') }}" class="btn btn-dark">Return</a>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Écoutez le changement dans le menu déroulant de produits
        $(document).ready(function() {
            $('select[name="product_id"]').change(function() {
                // Obtenez la valeur de l'attribut data-price-un de l'option sélectionnée
                var selectedOption = $('select[name="product_id"] option:selected');
                var unitPrice = selectedOption.data('price-un');

                // Mettez à jour la valeur du champ caché
                $('#price_un').val(unitPrice);

                console.log('Unit Price:', unitPrice);
            });

        });
    </script>


@endsection
