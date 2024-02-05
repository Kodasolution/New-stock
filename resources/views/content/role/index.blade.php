@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Roles - Apps')

@section('content')
    @include('admin.includes.show-msg')
    <h4 class="py-3 breadcrumb-wrapper mb-2">Roles List</h4>
    <div class="row g-4">
        <div class="col-xl-12">
            <div class="card h-100">
                <div class="row h-100">
                    <div class="col-sm-5">
                        <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-3">
                            <img src="{{ asset('assets/img/illustrations/boy-with-laptop-' . $configData['style'] . '.png') }}"
                                class="img-fluid" alt="Image" width="100"
                                data-app-light-img="illustrations/boy-with-laptop-light.png"
                                data-app-dark-img="illustrations/boy-with-laptop-dark.png">
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="card-body text-sm-end text-center ps-sm-0">
                            <button data-bs-target="#add-role" data-bs-toggle="modal"
                                class="btn btn-primary mb-3 text-nowrap add-new-role">Add New Role</button>
                            <p class="mb-0">Add role, if it does not exist</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('content.role.create')
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($roles as $item)
                                    <tr>
                                        <th>{{ $item->id }}</th>
                                        <td>{{ Str::ucfirst($item->name) }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <button data-bs-target="#edit-role-{{ $item->id }}"
                                                        data-bs-toggle="modal" class="dropdown-item">
                                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                                    </button>
                                                    <form action="{{ route('user.role.destroy', ['role' => $item]) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="dropdown-item deleteAll"
                                                            data-msg="role - {{ $item->name }}">
                                                            <i class="bx bx-trash me-1"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                                @include('content.role.edit', ['role' => $item])
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    @if (count($errors) > 0)
        <script>
            $(document).ready(function() {
                $("#add-role").modal('show');
            });
        </script>
    @endif

    @if (session()->has('err'))
        <script>
            $(document).ready(function() {
                $("#edit-role-" + {{ session()->get('err') }}).modal('show');
            });
        </script>
    @endif
    <script src="{{ asset('assets/js/app-access-roles.js') }}"></script>
@endsection
