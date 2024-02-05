@extends('layouts/layoutMaster')

@section('title', 'Dette List')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />

@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
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
        
<!-- Users List Table -->

<h4 class="py-3 breadcrumb-wrapper mb-4"><span class="text-muted fw-light"></span><a href="{{ route('expenses.dette.index') }}">Retour</a></h4>
<div class="card">
  <div class="card-header d-flex justify-content-between">
    <h5>Montant total  : 
        
        {{ $depenses->amount }} BIF
    </h5>
    {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editUser"> <i
            class="bx bx-plus me-0 me-lg-2"></i> Add Station</button>
    @include('admin.station.create') --}}
    {{--  <a href="{{ route('expenses.dette.create') }}" class="btn btn-primary"><i class="bx bx-plus me-0 me-lg-2"></i> Donner une
        dette</a>  --}}
</div>
  <div class="card-datatable table-responsive">
    <table class="datatables-users table border-top">
      <thead>
        <tr>
          <th>N°</th>
          <th>Ingrédient</th>
          <th>Prix</th>
          <th>Actions</th>
        </tr>
      </thead>

      <tbody>
        @foreach ($depenses->details as $details)
            <tr id="row_19">
                <td>
                    <span class="badge-basic-primary-text">{{ $loop->iteration }}</span>
                </td>
                <td>
                  <span class="badge-basic-primary-text">
                      {{ $details->ingredient->name }}
                  </span>
                </td>
                <td>
                  <span class="badge-basic-primary-text">
                      {{ $details->prix_unitaire }} BIF
                  </span>
                </td>
                

                <td class="action">
                  <div class="dropdown">
                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                          data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('expenses.editDepenseIngredient', [$details->id]) }}"><i
                            class="bx bx-edit-alt me-1"></i>
                        Edit</a>

                    {{--  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i>
                        Delete</a>  --}}

                        <form action="{{ route('expenses.destroy2', [$details->id]) }}" method="POST" onsubmit="return confirm('Do you want to delete this ingredient?')">
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

</div>
@endsection
