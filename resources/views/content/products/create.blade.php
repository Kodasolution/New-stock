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
    <h4 class="py-3 breadcrumb-wrapper mb-4"><span class="text-muted fw-light"><a href="{{ route('product.index') }}">Liste
                des produits</a></h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Ajouter un produit</h5>
                    {{--  <small class="text-muted float-end">Default label</small>  --}}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('product.store') }}">
                        @csrf
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" id="basic-default-name"
                                    placeholder="Enter category name" value="{{ old('name') }}" />
                                @error('name')
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="multicol-country">Category</label>
                            <div class="col-sm-10">
                                <select id="select2Basic"
                                    class="select2-icons form-select form-select-lg @error('category_id') is-invalid @enderror"
                                    data-allow-clear="true" name="category_id">
                                    <option value="{{ old('category_id') }}">Select category</option>
                                    @foreach ($category as $cat)
                                        <option value="{{ $cat->id }}">
                                            @if ($cat->parent)
                                                @php
                                                    $parent = $cat->parent;
                                                    $parentNames = [];
                                                    while ($parent) {
                                                        $parentNames[] = $parent->name;
                                                        $parent = $parent->parent;
                                                    }
                                                    echo implode(' &rarr; ', array_reverse($parentNames)) . ' &rarr; ';
                                                @endphp
                                            @endif
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('category_id')
                                    <div class="invalid-feedback">{{ $errors->first('category_id') }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Unit mesure</label>
                            <div class="col-sm-10">
                                <input type="text" name="unit_mesure"
                                    class="form-control @error('unit_mesure') is-invalid @enderror" id="basic-default-name"
                                    placeholder="Enter l'unité de mesure" value="{{ old('unit_mesure') }}" />
                                @error('unit_mesure')
                                    <div class="invalid-feedback">{{ $errors->first('unit_mesure') }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="multicol-country">Status</label>
                            <div class="col-sm-10">
                                <select class="form-select @error('status_pro') is-invalid @enderror"
                                    data-allow-clear="true" name="status_pro">
                                    <option value="{{ old('status_pro') }}">Choose status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                @error('status_pro')
                                    <div class="invalid-feedback">{{ $errors->first('status_pro') }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <a href="{{ route('product.index') }}" class="btn btn-dark">Return</a>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
