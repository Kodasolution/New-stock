@extends('layouts/layoutMaster')

@section('title', 'Credit List')

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

<!-- Users List Table -->
<div class="card">
  <div class="card-header d-flex justify-content-between">
    <h5>All Purchases</h5>
    {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editUser"> <i
            class="bx bx-plus me-0 me-lg-2"></i> Add Station</button>
    @include('admin.station.create') --}}
    <a href="{{ route('client-credit.create') }}" class="btn btn-primary"><i class="bx bx-plus me-0 me-lg-2"></i> Add
        credit</a>
</div>
  <div class="card-datatable table-responsive">
    <table class="datatables-users table border-top">
      <thead>
        <tr>
          <th>N°</th>
          <th>Nom</th>
          <th>Prénom</th>
          <th>Montant</th>
          <th>Description</th>
          <th>Date</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>

      <tbody>
        @foreach ($credits as $credit)
            <tr id="row_19">
                <td>
                    <span class="badge-basic-primary-text">{{ $loop->iteration }}</span>
                </td>
                <td>
                  <span class="badge-basic-primary-text">
                      {{ $credit->client->firstName }}
                  </span>
                </td>
                <td>
                  <span class="badge-basic-primary-text">
                     {{ $credit->client->lastName }}
                  </span>
                </td>
                <td>
                  <span class="badge-basic-primary-text">
                      {{ $credit->montant }}
                  </span>
                </td>
                <td>
                  <span class="badge-basic-primary-text">
                      {{ $credit->description }}
                  </span>
                </td>
                <td>
                  <span class="badge-basic-primary-text">
                    {{ $credit->date_credit }}
                  </span>
                </td>
                <td>
                  <span class="badge-basic-primary-text">
                    {{ $credit->status ? 'Unpaid' : 'Paid'}}
                  </span>
              </td>

                <td class="action">
                  <div class="dropdown">
                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                          data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                      <div class="dropdown-menu">
                          <a class="dropdown-item" href="{{ route('client-credit.edit', [$credit->id]) }}"><i
                                  class="bx bx-edit-alt me-1"></i>
                              Edit</a>

                          {{--  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i>
                              Delete</a>  --}}

                              <form action="{{ route('client-credit.destroy', [$credit->id]) }}" method="POST" onsubmit="return confirm('Do you want to delete this credit?')">
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

  {{--  <!-- Offcanvas to add new user -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
    <div class="offcanvas-header border-bottom">
      <h6 id="offcanvasAddUserLabel" class="offcanvas-title">Add Category</h6>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0">
      <form method="POST" action="{{ route('category.store') }}" class="add-new-user pt-0" id="addNewUserForm" onsubmit="return false">
        @csrf
        <div class="mb-3">
          <label class="form-label" for="add-user-fullname">Name</label>
          <input type="text" class="form-control" id="add-user-fullname" name="name" placeholder="Category name" name="userFullname" aria-label="John Doe" />
        </div>
        
        <div class="mb-3">
          <label class="form-label" for="country">Parent name</label>
          <select id="country" class="form-select" name="parent_id">
            <option value="">Select parent name</option>
            @foreach ($category as $cat)
              <option value="{{ $cat->id}}">{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="mb-4">
          <label class="form-label" for="user-plan">Status</label>
          <select id="user-plan" class="form-select" name="status_cat">
            <option value="">Choose status</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>

        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>

    </div>
  </div>  --}}
</div>
@endsection
