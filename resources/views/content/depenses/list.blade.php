@extends('layouts/layoutMaster')

@section('title', ' Expenses List')

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

@endsection

@section('page-script')
    <script src="{{ asset('assets/js/app-invoice-list.js') }}"></script>
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

    <h4 class="py-3 breadcrumb-wrapper mb-2">Admin / Toutes les dépenses</h4>

    <!-- Users List Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-end">
                <a href="{{ route('expenses.create') }}" class="btn btn-primary"><i class="bx bx-plus me-0 me-lg-2"></i>
                    Ajouter une
                    dépense</a>
            </div>
            <div class="row">
                <form action="{{ route('expenses.index') }}" method="GET" class="d-sm-flex col-9 ">
                    <div class="col-md-4 col-sm-6 m-3">
                        <select id="select2" class="select2 form-select form-select-lg" data-allow-clear="true"
                            name="user">
                            <option value="">Choose user</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->firstName }} {{ $user->lastName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class ="col-md-4 col-sm-6 m-3">
                        <input type="text" class="form-control " name="title" value="{{ request('title') }}"
                            placeholder="Title">
                    </div>
                    <div class ="col-md-4 col-sm-6 m-3">
                        <input type="date" class="form-control " name="date" value="{{ request('date') }}"
                            placeholder="product name ">
                    </div>
                    <div class="col-sm-3 m-3">
                        <button type="submit" class="btn btn-info mb-3 text-nowrap ">Search</button>
                        <a href="{{ route('expenses.index') }}" class= "btn btn-dark  mb-3">Reset</a>
                    </div>
                </form>
                <span class="text-muted col-lg-12 mx-3 mt-n4"><b>{{ $depenses->total() }} result(s)</b></span>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                    <tr>
                        {{--  <th>N°</th>  --}}
                        <th>Employé</th>
                        <th>Title</th>
                        <th>Solde</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($depenses as $depense)
                        @if ($depense->type == 'autres')
                            <tr id="row_19">
                                {{--  <td>
                                <span class="badge-basic-primary-text">{{ $depense->id }}</span>
                            </td>  --}}
                                <td>
                                    <span class="badge-basic-primary-text">
                                        {{ $depense->user->firstName ?? '' }}
                                        {{ $depense->user->lastName ?? '' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-basic-primary-text">
                                        {{ $depense->title }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-basic-primary-text">
                                        {{ $depense->amount }} BIF
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-basic-primary-text">
                                        {{ $depense->date_creation }}
                                    </span>
                                </td>



                                <td class="action">
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('expenses.edit', [$depense->id]) }}"><i
                                                    class="bx bx-edit-alt me-1"></i>
                                                Edit</a>

                                            {{--  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i>
                            Delete</a>  --}}

                                            <form action="{{ route('expenses.destroy', [$depense->id]) }}" method="POST"
                                                onsubmit="return confirm('Do you want to delete this expense?')">
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
                        @endif
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
