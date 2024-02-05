@extends('layouts/layoutMaster')

@section('title', 'Purchases List')

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
    <script src="{{ asset('assets/js/app-invoice-list.js') }}"></script>
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
    <h4 class="py-3 breadcrumb-wrapper mb-2">Admin / Tous les commandes</h4>

    <!-- Users List Table -->
    <div class="card">
        <div class="card-header ">
            <div class="d-flex justify-content-end">
                <a href="{{ route('purchase.create') }}" class="btn btn-primary"><i class="bx bx-plus me-0 me-lg-2"></i>
                    Ajouter une commande</a>
            </div>
            <div class="row">
                <form action="{{ route('purchase.index') }}" method="GET" class="d-sm-flex  col-9 ">
                    <div class="col-md-3 col-sm-6 m-3">
                        <select name="fournisseur" id="select2" class=" select2  form-select" data-allow-clear="true">
                            <option value="">fournisseur</option>
                            @foreach ($providers as $item)
                                <option value="{{ $item->id }}"
                                    {{ request('fournisseur') == $item->id ? 'selected' : '' }}> {{ $item->firstName }}
                                    {{ $item->lastName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class ="col-md-4 col-sm-6 m-3">
                        <input type="text" class="form-control " name="product" value="{{ request('product') }}"
                            placeholder="product name ">
                    </div>
                    <div class ="col-md-4 col-sm-6 m-3">
                        <select name="status" id="" class="form-select">
                            <option value="">choose status</option>
                            <option value="delivery"{{ request('status') == 'delivery' ? 'selected' : '' }}>Delivery
                            </option>
                            <option value="undelivery"{{ request('status') == 'undelivery' ? 'selected' : '' }}>Undelivery
                            </option>
                        </select>
                    </div>
                    <div class="col-sm-3 m-3">
                        <button type="submit" class="btn btn-info mb-3 text-nowrap ">Search</button>
                        <a href="{{ route('purchase.index') }}" class= "btn btn-dark  mb-3">Reset</a>
                    </div>
                </form>
                
            </div>
            <span class="text-muted col-lg-12 mx-3 mt-n4"><b>{{ $purchases->total() }} result(s)</b></span>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Purchase number</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total amount</th>
                        <th>Provider</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($purchases as $purchase)
                        <tr id="row_19">
                            <td>
                                <span class="badge-basic-primary-text">{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                <span class="badge-basic-primary-text">
                                    {{ $purchase->numero_commande }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-basic-primary-text">
                                    @foreach ($purchase->details as $detail)
                                        {{ $detail->produit->name }}
                                    @endforeach
                                </span>
                            </td>
                            <td>
                                <span class="badge-basic-primary-text">
                                    @foreach ($purchase->details as $detail)
                                        {{ $detail->quantity }}
                                    @endforeach
                                </span>
                            </td>
                            <td>
                                <span class="badge-basic-primary-text">
                                    @foreach ($purchase->details as $detail)
                                        {{ $detail->unit_price }} BIF
                                    @endforeach
                                </span>
                            </td>
                            <td>
                                <span class="badge-basic-primary-text">
                                    @foreach ($purchase->details as $detail)
                                        {{ $detail->total_price }} BIF
                                    @endforeach
                                </span>
                            </td>
                            <td>
                                <span class="badge-basic-primary-text">
                                    {{ $purchase->fournisseur->firstName }}
                                    {{ $purchase->fournisseur->lastName }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-basic-primary-text">
                                    {{ $purchase->date_commande }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-basic-primary-text">
                                    {{ $purchase->status_co ? 'Delivered' : 'Undelivered' }}
                                </span>
                            </td>

                            <td class="action">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('purchase.edit', [$purchase->id]) }}"><i
                                                class="bx bx-edit-alt me-1"></i>
                                            Edit</a>
                                        <form action="{{ route('purchase.destroy', [$purchase->id]) }}" method="POST"
                                            onsubmit="return confirm('Do you want to delete this purchase?')">
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
