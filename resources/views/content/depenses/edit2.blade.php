@extends('layouts/layoutMaster')

@section('title', ' Add Expense')

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
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
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


    <h4 class="py-3 breadcrumb-wrapper mb-4"><span class="text-muted fw-light"><a href="{{ route('expenses.index') }}">Liste
                des dépenses</a></h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Modifier une dépense</h5>
                    {{--  <small class="text-muted float-end">Default label</small>  --}}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('expenses.update2', $depenseIngredients->id) }}">
                        @csrf
                        @method('PATCH')


                        <div class="row mb-3" id="selectIngredientsIngredients">
                            <label for="basic-default-name" class="col-sm-2 col-form-label">Ingrédients</label>
                            <div class="col-sm-10">
                                <select id="select2"
                                    class="select2 form-select form-select-lg @error('ingredient_id') is-invalid @enderror"
                                    data-allow-clear="true" name="ingredient_id">
                                    <option value="{{ old('ingredient_id', $depenseIngredients->ingredient_id) }}">Choose
                                        ingredient</option>
                                    @foreach ($ingredients as $ingredient)
                                        <option value="{{ $ingredient->id }}"
                                            {{ $depenseIngredients->ingredient_id === $ingredient->id ? 'selected' : '' }}>
                                            {{ $ingredient->name }}</option>
                                    @endforeach
                                </select>
                                @error('ingredient_id')
                                    <div class="invalid-feedback">{{ $errors->first('ingredient_id') }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Montant</label>
                            <div class="col-sm-10">
                                <input type="text" name="prix_unitaire"
                                    class="form-control @error('prix_unitaire') is-invalid @enderror"
                                    id="basic-default-name" placeholder="Enter amount"
                                    value="{{ old('prix_unitaire', $depenseIngredients->prix_unitaire) }}" />
                                @error('prix_unitaire')
                                    <div class="invalid-feedback">{{ $errors->first('prix_unitaire') }}</div>
                                @enderror
                            </div>
                        </div>



                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <a href="{{ route('expenses.list') }}" class="btn btn-dark">Return</a>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection
