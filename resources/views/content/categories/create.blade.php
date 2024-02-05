@extends('layouts/layoutMaster')

@section('title', ' Add Category')

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
    <h4 class="py-3 breadcrumb-wrapper mb-4"><span class="text-muted fw-light"><a href="{{ route('category.index') }}">Liste
                des categories</a></h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Ajouter une categorie</h5>
                    {{--  <small class="text-muted float-end">Default label</small>  --}}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('category.store') }}">
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

                        {{-- <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="multicol-country">Parent name</label>
                            <div class="col-sm-10">
                                <select class="form-select @error('parent_id') is-invalid @enderror" data-allow-clear="true"
                                    name="parent_id">
                                    <option value="">Select parent name</option>
                                    @foreach ($category as $cat)
                                        <option value="{{ $cat->id }}">
                                            {{ $cat->parent ? $cat->parent->name : '' }}
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="invalid-feedback">{{ $errors->first('parent_id') }}</div>
                                @enderror
                            </div>
                        </div> --}}

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="multicol-country">Status</label>
                            <div class="col-sm-10">
                                <select class="form-select @error('status_cat') is-invalid @enderror"
                                    data-allow-clear="true" name="status_cat">
                                    <option value="{{ old('status_cat') }}">Choose status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                    @error('status_cat')
                                        <div class="invalid-feedback">{{ $errors->first('status_cat') }}</div>
                                    @enderror
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
