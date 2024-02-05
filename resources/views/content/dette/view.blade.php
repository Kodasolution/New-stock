@extends('layouts/layoutMaster')

@section('title', 'Dette List')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/forms-pickers.js') }}"></script>
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

    <h4 class="py-3 breadcrumb-wrapper mb-4"><span class="text-muted fw-light"></span><a
            href="{{ route('expenses.dette.index') }}">Retour</a></h4>
    <div class="card">
        <div class="card-header ">
            <div class="d-flex ">
                <h5>Débiteur :
                    @if ($dettes->type == 'user')
                        {{ $dettes->user->firstName }} {{ $dettes->user->lastName }}
                    @else
                        {{ $dettes->client->firstName }} {{ $dettes->client->lastName }}
                    @endif
                </h5>
                {{-- <div class="card mx-5">
                  <div class="card-body">
                      <div class="d-flex justify-content-between">
                          <div class="d-flex align-items-center gap-3">
                              <div class="avatar">
                                  <span class="avatar-initial rounded-circle bg-label-danger "> <img
                                          style="max-width: 50%; height: auto;"
                                          src="{{ asset('assets/img/icons/dash4.svg') }}" alt="img">
                                  </span>
                              </div> --}}
                <div class="card mx-5">
                    <div class="card-info">
                        <h5 class="card-title mb-0 me-2">
                            @if (request('date') == null)
                                {{ $dettes->montant_total }}
                            @else
                                {{ $detailDettes->sum('montant') }}
                            @endif

                            BIF       
                        </h5>
                        <small class="text-muted">Total par periode de recherche</small>
                    </div>

                </div>
                {{-- </div>
                          <div id="incomeChart"></div>
                      </div>
                  </div>
              </div> --}}

            </div>
            <div class="row">
                <form action="{{ route('expenses.dette.show', [$dettes->id]) }}" method="GET" class="d-sm-flex col-9 ">
                    <div class="col-md-5 col-12 m-3">
                        <input type="text" class="form-control" value="{{ request('date') }}" name="date"
                            placeholder="YYYY-MM-DD to YYYY-MM-DD" id="flatpickr-range" />
                        @error('date')
                            <div class=" invalid-feedback">{{ $errors->first('date') }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-3 m-3">
                        <button type="submit" class="btn btn-info mb-3 text-nowrap ">Search</button>
                        <a href="{{ route('expenses.dette.show', [$dettes->id]) }}" class= "btn btn-dark  mb-3">Reset</a>
                    </div>
                </form>
                <span class="text-muted col-lg-12 mx-3 mt-n4"><b>{{ $detailDettes->total() }} result(s)</b></span>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Montant</th>
                        <th>Motif</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($detailDettes as $details)
                        <tr id="row_19">
                            <td>
                                <span class="badge-basic-primary-text">{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                <span class="badge-basic-primary-text">
                                    {{ $details->montant }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-basic-primary-text">
                                    {{ $details->motif }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-basic-primary-text">
                                    {{ $details->date_creation }}
                                </span>
                            </td>


                            <td class="action">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{ route('expenses.dette.edit', [$details->id]) }}"><i
                                                class="bx bx-edit-alt me-1"></i>
                                            Edit</a>

                                        {{--  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i>
                        Delete</a>  --}}

                                        <form action="{{ route('expenses.dette.destroy', [$details->id]) }}" method="POST"
                                            onsubmit="return confirm('Do you want to delete this debt?')">
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
