@extends('layouts/layoutMaster')

@section('title', 'Category List')

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
    <h4 class="py-3 breadcrumb-wrapper mb-2">Admin / Toutes les Categories</h4>

    <!-- Users List Table -->
    <div class="card">
        <div class="card-header">
            <div class="row">
                <form action="{{ route('category.index') }}" method="GET" class="d-sm-flex  col-9 ">
                    <div class ="col-md-4 col-sm-6 m-3">
                        <input type="text" class="form-control " name="name" value="{{ request('name') }}"
                            placeholder="search by name">
                    </div>
                    <div class="col-sm-2 m-3">
                        <button type="submit" class="btn btn-primary mb-3 text-nowrap ">Search</button>
                        <a href="{{ route('category.index') }}" class="text-dark mx-3">Reset</a>
                    </div>
                </form>
                <div class="col-sm-3">
                    <a href="{{ route('category.create') }}" class="btn btn-primary"><i
                            class="bx bx-plus me-0 me-lg-2"></i>
                        Ajouter
                        categorie</a>
                </div>
                
            </div>
            <span class="text-muted col-lg-12 mx-3 mt-n4"><b>{{ $category->total() }} result(s)</b></span>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Name</th>
                        {{--  <th>Parent Name</th>  --}}
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($category as $categorie)
                        <tr id="row_19">
                            <td>
                                <span class="badge-basic-primary-text">{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                <span class="badge-basic-primary-text">
                                    {{ $categorie->name }}
                                </span>
                            </td>
                            {{--  <td>
                    <span class="badge-basic-primary-text">
                      @if ($categorie->parent)
                        @php
                          $parent = $categorie->parent;
                          while ($parent->parent) {
                              echo  $parent->parent->name;
                              $parent = $parent->parent;
                          }
                        @endphp
                        <span class="badge-basic-primary-text">
                          &rarr; {{ $categorie->parent->name }}
                        </span>
                      @else
                          Null
                      @endif
                    </span> 
                </td>  --}}
                            <td>
                                @if ($categorie->status_cat == 1)
                                    <span class="badge rounded-pill bg-success">Active</span>
                                @else
                                    <span class="badge rounded-pill bg-waring">Inactive</span>
                                @endif
                                {{-- <span class="badge-basic-primary-text">
                    {{ $categorie->status_cat ? 'Active' : 'Inactive'}}
                  </span> --}}
                            </td>

                            <td class="action">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('category.edit', [$categorie->id]) }}"><i
                                                class="bx bx-edit-alt me-1"></i>
                                            Edit</a>

                                        {{--  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i>
                              Delete</a>  --}}

                                        <form action="{{ route('category.destroy', [$categorie->id]) }}" method="POST"
                                            onsubmit="return confirm('Do you want to delete this category?')">
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
