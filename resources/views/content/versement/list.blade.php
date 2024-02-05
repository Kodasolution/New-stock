@php
    use Carbon\Carbon;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Salaire-employé List')

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
    <!-- Users List Table -->
    <h4 class="py-3 breadcrumb-wrapper mb-2">Admin / Liste des verséments</h4>

    <div class="card">
        <div class="card-header ">
            <div class="d-flex justify-content-end">
                {{-- <a href="{{ route('expenses.versement.create') }}" class="btn btn-primary"><i
                        class="bx bx-plus me-0 me-lg-2"></i> Versé un
                    salaire</a> --}}
            </div>
            <div class="row">
                <form action="{{ route('expenses.versement.index') }}" method="GET" class="d-sm-flex col-9 ">
                    <div class="col-md-4 col-sm-6 m-3">
                        <select id="select2" class="select2 form-select form-select-lg" data-allow-clear="true"
                            name="user">
                            <option value="">Choose user</option>
                            @foreach ($salaires as $salaire)
                                <option value="{{ $salaire->user_id }}">{{ $salaire->user?->firstName }}
                                    {{ $salaire->user?->lastName }}</option>
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
                        <a href="{{ route('expenses.versement.index') }}" class= "btn btn-dark  mb-3">Reset</a>
                    </div>
                </form>
                <span class="text-muted col-lg-12 mx-3 mt-n4"><b>{{ $versements->total() }} result(s)</b></span>
            </div>

        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Montant brut</th>
                        <th>Montant versé</th>
                        <th>Endette</th>
                        <th>Mois</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($versements as $versement)
                        <tr id="row_19">
                            <td>
                                <span class="badge-basic-primary-text">{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                <span class="badge-basic-primary-text">
                                    {{ $versement->salaire->user->firstName }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-basic-primary-text">
                                    {{ $versement->salaire->user->lastName }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-basic-primary-text">
                                    {{ $versement->salaire->montant }} BIF
                                </span>
                            </td>
                            <td>
                                <span class="badge-basic-primary-text">
                                    {{ $versement->montant_verse }} BIF
                                </span>
                            </td>
                            @if ($versement->has_dette == 0)
                                <td><span class="badge bg-primary">NON</span> <i
                                        class="fas fa-check-circle text-primary "></i>
                                @else
                                <td><mark>OUI</mark>
                                    <span
                                        class="badge rounded-pill bg-label-danger d-flex align-items-center">{{ $versement->dette_montant }}
                                        BIF</span>
                                </td>
                            @endif
                            <td>
                                <span class="badge-basic-primary-text">

                                    @switch($versement->mois)
                                        @case('1')
                                            Janvier
                                        @break

                                        @case('2')
                                            Fevrier
                                        @break

                                        @case('3')
                                            Mars
                                        @break

                                        @case('4')
                                            Avril
                                        @break

                                        @case('5')
                                            Mai
                                        @break

                                        @case('6')
                                            Juin
                                        @break

                                        @case('7')
                                            Juillet
                                        @break

                                        @case('8')
                                            Aout
                                        @break

                                        @case('9')
                                            Septembre
                                        @break

                                        @case('10')
                                            Octobre
                                        @break

                                        @case('11')
                                            Novembre
                                        @break

                                        @case('12')
                                            Decembre 
                                        @break

                                        @default
                                    @endswitch
                                </span>
                            </td>
                            @if ($versement->status == 0)
                                <td>
                                    <span class="badge rounded-pill bg-label-danger d-flex align-items-center">Unpaid</span>
                                </td>
                            @else
                                <td>
                                    <span class="badge rounded-pill bg-label-success d-flex align-items-center">Paid</span>
                                </td>
                            @endif
                            <td class="action">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{ route('expenses.versement.show', ['versement' => $versement->id]) }}"><i
                                                class="bx bx-edit-alt me-1"></i>
                                            Edit</a>
                                        <form action="{{ route('expenses.versement.destroy', [$versement->id]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Do you want to delete this employee salary?')">
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
                {{-- {{ $versements->links() }} --}}
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
