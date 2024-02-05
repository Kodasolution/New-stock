@extends('layouts/layoutMaster')

@section('title', 'Remboursement')

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
    <h4 class="py-3 breadcrumb-wrapper mb-2">Admin / Toutes les remboursements</h4>

    <!-- Users List Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-end">
                <a href="{{ route('expenses.remboursement.create') }}" class="btn btn-primary"><i
                        class="bx bx-plus me-0 me-lg-2"></i> Rembourser une
                    dette</a>
            </div>
            <div class="row">
                <form action="{{ route('expenses.remboursement.index') }}" method="GET" class="d-sm-flex col-9 ">
                    <div class="col-md-2 col-sm-6 m-3">
                        <select name="type" id="" class="form-select">
                            <option value="">user type</option>
                            <option value="user" {{ request('type') == 'user' ? 'selected' : '' }}> Employee</option>
                            <option value="client" {{ request('type') == 'client' ? 'selected' : '' }}>Client</option>
                        </select>
                    </div>
                    <div class ="col-md-3 col-sm-6 m-3">
                        <input type="text" class="form-control " name="user" value="{{ request('user') }}"
                            placeholder="user name or email ">
                    </div>
                    <div class ="col-md-3 col-sm-6 m-3">
                        <input type="date" class="form-control " name="date" value="{{ request('date') }}"
                            placeholder="product name ">
                    </div>
                    {{-- <div class ="col-md-3 col-sm-6 m-3">
                        <select name="status" id="" class="form-select">
                            <option value="">choose status</option>
                            <option value="paid"{{ request('status') == 'paid' ? 'selected' : '' }}>Paid
                            </option>
                            <option value="unpaid"{{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid
                            </option>
                        </select>
                    </div> --}}
                    <div class="col-sm-3 m-3">
                        <button type="submit" class="btn btn-info mb-3 text-nowrap ">Search</button>
                        <a href="{{ route('expenses.remboursement.index') }}" class= "btn btn-dark  mb-3">Reset</a>
                    </div>
                </form>
                <span class="text-muted col-lg-12 mx-3 mt-n4"><b>{{ $remboursements->total() }} result(s)</b></span>
            </div>

        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Montant</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($remboursements as $remboursement)
                        <tr id="row_19">
                            <td>
                                <span class="badge-basic-primary-text">{{ $loop->iteration }}</span>
                            </td>
                            @if ($remboursement->dette->type === 'user')
                                <td>
                                    <span class="badge-basic-primary-text">
                                        {{ $remboursement->dette->user->firstName }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-basic-primary-text">
                                        {{ $remboursement->dette->user->lastName }}
                                    </span>
                                </td>
                            @else
                                <td>
                                    <span class="badge-basic-primary-text">
                                        {{ $remboursement->dette->client->firstName }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-basic-primary-text">
                                        {{ $remboursement->dette->client->lastName }}
                                    </span>
                                </td>
                            @endif
                            <td>
                                <span class="badge-basic-primary-text">
                                    {{ $remboursement->montant_rembourse }} BIF
                                </span>
                            </td>

                            <td>
                                <span class="badge-basic-primary-text">
                                    {{ $remboursement->date_rembourse }}
                                </span>
                            </td>

                            <td class="action">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{ route('expenses.remboursement.edit', [$remboursement->id]) }}"><i
                                                class="bx bx-edit-alt me-1"></i>
                                            Edit</a>

                                        {{--  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i>
                      Delete</a>  --}}

                                        <form action="{{ route('expenses.remboursement.destroy', [$remboursement->id]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Do you want to delete this remboursement?')">
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
