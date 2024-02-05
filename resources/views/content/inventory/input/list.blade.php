@extends('layouts/layoutMaster')

@section('title', 'Inventory List')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />

@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
@endsection

@section('page-script')
    {{--  <script src="{{asset('assets/js/app-user-list.js')}}"></script>  --}}
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
    <h4 class="py-3 breadcrumb-wrapper mb-2">Admin / Flux de stock</h4>

    <!-- Users List Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-end">
                <a href="{{ route('inventory.create') }}" class="btn btn-primary mx-2 my-1"><i
                    class="bx bx-plus me-2"></i>Ajouter une entrée</a>
            </div>
            <div class="row">
                <form action="{{ route('inventory.index') }}" method="GET" class="d-sm-flex  col-9 ">
                    <div class="col-md-3 col-sm-6 m-3">
                        <input type="text" class="form-control " name="flux_number" value="{{ request('flux_number') }}"
                        placeholder="Flux number ">
                    </div>
                    <div class ="col-md-4 col-sm-6 m-3">
                        <input type="text" class="form-control " name="product" value="{{ request('product') }}"
                            placeholder="product name ">
                    </div>
                    <div class ="col-md-4 col-sm-6 m-3">
                        <input type="date" class="form-control " name="date" value="{{ request('date') }}"
                        placeholder="product name ">
                    </div>
                    <div class="col-sm-3 m-3">
                        <button type="submit" class="btn btn-info mb-3 text-nowrap ">Search</button>
                        <a href="{{ route('inventory.index') }}" class= "btn btn-dark  mb-3">Reset</a>
                    </div>
                </form>
                <span class="text-muted col-lg-12 mx-3 mt-n4"><b>{{ $mouvements->total() }} result(s)</b></span>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Product</th>
                        <th>Flux number</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total amount</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($mouvements as $mouvement)
                        <tr id="row_19">
                            <td>
                                <span class="badge-basic-primary-text">{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                <span class="badge-basic-primary-text">
                                    @foreach ($mouvement->productMouvements as $productMouv)
                                        {{ $productMouv->product->name }}
                                    @endforeach
                                </span>
                            </td>
                            <td>
                                <span class="badge-basic-primary-text">
                                    {{ $mouvement->referenceMov }}
                                </span>
                            </td>

                            <td>
                                <span class="badge-basic-primary-text">
                                    @foreach ($mouvement->productMouvements as $productMouv)
                                        {{ $productMouv->quantity }} {{ $productMouv->product->unit_mesure }}
                                    @endforeach
                                </span>
                            </td>
                            <td>
                                <span class="badge-basic-primary-text">
                                    @foreach ($mouvement->productMouvements as $productMouv)
                                        {{ $productMouv->price_un }} BIF
                                    @endforeach
                                </span>
                            </td>
                            <td>
                                <span class="badge-basic-primary-text">
                                    @foreach ($mouvement->productMouvements as $productMouv)
                                        {{ $productMouv->price_tot }} BIF
                                    @endforeach
                                </span>
                            </td>
                            {{--  <td>
                  <span class="badge-basic-primary-text">
                    {{ $mouvement->user->firstName }}
                    {{ $mouvement->user->lastName }}
                  </span>
                </td>  --}}
                            <td>
                                <span class="badge-basic-primary-text">
                                    {{ $mouvement->date_flux }}
                                </span>
                            </td>

                            <td class="action">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('inventory.edit', [$mouvement->id]) }}"><i
                                                class="bx bx-edit-alt me-1"></i>
                                            Edit</a>

                                        {{--  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i>
                              Delete</a>  --}}

                                        <form action="{{ route('inventory.destroy', [$mouvement->id]) }}" method="POST"
                                            onsubmit="return confirm('Do you want to delete this flux?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item">
                                                <i class="bx bx-trash me-1"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between">
                <div class="col-lg-4">
                    {!! $pagination->perPageForm() !!}
                </div>
                <div class="">
                    {!! $pagination->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
