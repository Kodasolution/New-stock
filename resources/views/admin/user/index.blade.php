@php
    $configData = Helper::appClasses();
    $color = ['primary', 'warning', 'success', 'danger'];
@endphp

@extends('layouts/layoutMaster')

@section('title', 'User')

@section('content')
    @include('admin.includes.show-msg')
    <h4 class="py-3 breadcrumb-wrapper mb-2">Admin / Users List</h4>
    <!-- Role cards -->
    <div class="row g-4">
        <!-- Role Table -->
        <div class="col-md-12">
            <div class="card">
                <div class="row">
                    {{-- <div > --}}
                    <form action="{{ route('user.index') }}" method="GET" class="d-sm-flex  col-9 ">  
                        {{-- <h5 class="card-header">Users List</h5> --}}
                        <div class="col-md-2 col-sm-6 m-3">
                            <select name="type" id="" class="form-select">
                                <option value="">search by</option>
                                <option value="name" {{ request('type') == 'name' ? 'selected' : '' }}> Name</option>
                                <option value="role" {{ request('type') == 'role' ? 'selected' : '' }}>Role</option>
                            </select>
                        </div>
                        <div class ="col-md-4 col-sm-6 m-3">
                            <input type="text" class="form-control " name="value" value="{{ request('value') }}"
                                placeholder="search by name or Role ">
                        </div>
                        <div class="col-sm-2 m-3">
                            <button type="submit" class="btn btn-primary mb-3 text-nowrap ">Search</button> 
                            <a href="{{ route('user.index') }}" class="text-dark mx-3">Reset</a>
                        </div>
                    </form>
                    {{-- </div> --}}
                    <div class="col-sm-3">
                        <div class="card-body text-sm-end text-center ps-sm-0">
                            <button data-bs-target="#add-user" data-bs-toggle="modal"
                                class="btn btn-primary mb-3 text-nowrap add-new-user">Add New User</button>
                        </div>
                    </div>
                    <span class="text-muted col-lg-12 mx-3 mt-n4"><b>{{ $users->total() }} result(s)</b></span>

                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Telephone</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($users as $item)
                                <tr>
                                    <th>{{ $item->id }}</th>
                                    <td>{{ Str::ucfirst($item->firstName) }} {{ Str::ucfirst($item->lastName) }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->phone }}</td>
                                    <td>
                                        @if ($item->roles->pluck('name')[0] == 'admin')
                                            <span
                                                class="badge rounded-pill bg-success">{{ $item->roles->pluck('name')[0] }}</span>
                                        @else
                                            <span
                                                class="badge rounded-pill bg-warning">{{ $item->roles->pluck('name')[0] ?? '' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <button data-bs-target="#edit-user-{{ $item->id }}"
                                                    data-bs-toggle="modal" class="dropdown-item">
                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                </button>
                                                <button data-bs-target="#change-password-{{ $item->id }}"
                                                    data-bs-toggle="modal" class="dropdown-item">
                                                    <i class="bx bx-key me-1"></i> Modify password
                                                </button>
                                                <form action="{{ route('user.destroy', ['user' => $item]) }}"
                                                    onsubmit="return confirm('Do you want to delete this user?')"
                                                    method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="dropdown-item ">
                                                        <i class="bx bx-trash me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @include('admin.user.edit', ['user' => $item])
                                @include('admin.user.change-password', ['user' => $item])
                            @empty
                                <tr>
                                    <th>No record found.</th>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer mt-3 ">
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
    </div>
    @include('admin.user.create')
@endsection
@section('page-script')
    @if (count($errors) > 0)
        @dump($errors)
        <script>
            $(document).ready(function() {
                $("#add-user").modal('show');
            });
        </script>
    @endif

    @if (session()->has('err'))
        <script>
            $(document).ready(function() {
                $("#edit-user-" + {{ session()->get('err') }}).modal('show');
            });
        </script>
    @endif

    @if (session()->has('err_pwsd'))
        <script>
            $(document).ready(function() {
                $("#change-password-" + {{ session()->get('err_pwsd') }}).modal('show');
            });
        </script>
    @endif
@endsection
