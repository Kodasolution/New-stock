@extends('layouts/layoutMaster')
@section('title', 'Dashboard - Stock')
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

@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar">
                                <span class="avatar-initial rounded-circle bg-label-primary"><i
                                        class='bx bx-money-withdraw'></i>
                                </span>
                            </div>

                            <div class="card-info">
                                @if (key_exists('out', $rapports) && $rapports['out'] != null && is_object($rapports['out']))
                                    <h5 class="card-title mb-0 me-2">{{ $rapports['out']->sum('price_tot') ?? 0 }} BIF
                                    </h5>
                                @else
                                    {{-- <h5 class="card-title mb-0 me-2">{{ $rapports['depense'] ?? 0 }} BIF</h5> --}}
                                    <h5 class="card-title mb-0 me-2">{{ $rapports['out'] ?? 0 }} BIF</h5>
                                @endif
                                <small class="text-muted">Sorties</small>
                            </div>
                        </div>
                        <div id="conversationChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar">
                                <span class="avatar-initial rounded-circle bg-label-danger "> <img
                                        style="max-width: 50%; height: auto;"
                                        src="{{ asset('assets/img/icons/dash4.svg') }}" alt="img">
                                </span>
                            </div>
                            <div class="card-info">
                                @if (key_exists('depense', $rapports) && $rapports['depense'] != null && is_object($rapports['depense']))
                                    <h5 class="card-title mb-0 me-2">{{ $rapports['depense']->sum('amount') ?? 0 }} BIF
                                    </h5>
                                @else
                                    <h5 class="card-title mb-0 me-2">{{ $rapports['depense'] ?? 0 }} BIF</h5>
                                @endif
                                <small class="text-muted">Depenses</small>
                            </div>
                        </div>
                        <div id="incomeChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar">
                                <span class="avatar-initial rounded-circle bg-label-success"><i
                                        class='bx bx-transfer-alt'></i></span>
                            </div>
                            <div class="card-info">
                                @if (key_exists('dettes', $rapports) && $rapports['dettes'] != null && is_object($rapports['dettes']))
                                    <h5 class="card-title mb-0 me-2">{{ $rapports['dettes']->sum('montant') ?? 0 }} BIF
                                    </h5>
                                @else
                                    <h5 class="card-title mb-0 me-2">{{ $rapports['dettes'] ?? 0 }} BIF</h5>
                                @endif
                                <small class="text-muted">Dettes</small>
                            </div>
                        </div>
                        <div id="profitChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar">
                                <span class="avatar-initial rounded-circle bg-label-info"><i
                                        class='bx bx-wallet fs-4'></i></span>
                            </div>
                            <div class="card-info">
                                @if (key_exists('salaires', $rapports) && $rapports['salaires'] != null && is_object($rapports['salaires']))
                                    <h5 class="card-title mb-0 me-2">{{ $rapports['salaires']->sum('montant_verse') ?? 0 }}
                                        BIF
                                    </h5>
                                @else
                                    <h5 class="card-title mb-0 me-2">{{ $rapports['salaires'] ?? 0 }} BIF</h5>
                                @endif <small class="text-muted">Salaires</small>
                            </div>
                        </div>
                        <div id="expensesLineChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar">
                                <span class="avatar-initial rounded-circle bg-label-success "> <img
                                        style="max-width: 50%; height: auto;"
                                        src="{{ asset('assets/img/icons/dash3.svg') }}" alt="img">
                                </span>
                            </div>
                            <div class="card-info">
                                @if (key_exists('caisses', $rapports) && $rapports['caisses'] != null && is_object($rapports['caisses']))
                                    <h5 class="card-title mb-0 me-2">{{ $rapports['caisses']->sum('montant') ?? 0 }} BIF
                                    </h5>
                                @else
                                    <h5 class="card-title mb-0 me-2">{{ $rapports['caisses'] ?? 0 }} BIF</h5>
                                @endif
                                <small class="text-muted">caisse</small>
                            </div>
                        </div>
                        <div id="expensesLineChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title">Search Filter</h5>
            {{-- <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0"> --}}
            <form action="{{ route('rapport.index') }}" method="GET" class="d-sm-flex">
                {{-- <div class="row"> --}}
                <div class="col-md-3 col-12 mb-4">
                    <label for="flatpickr-range" class="form-label  @error('date')  is-invalid @enderror">Date
                        Between</label>
                    <input type="text" class="form-control" value="{{ request('date') }}" name="date"
                        placeholder="YYYY-MM-DD to YYYY-MM-DD" id="flatpickr-range" />
                    @error('date')
                        <div class=" invalid-feedback">{{ $errors->first('date') }}</div>
                    @enderror
                </div>
                <div class="col-md-3 col-12 mt-4 m-2">
                    <select name="type" class=" form-select @error('type')  is-invalid @enderror ">
                        <option value="">Select Type</option>
                        <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Sorties</option>
                        <option value="dette"{{ request('type') == 'dette' ? 'selected' : '' }}>Dettes</option>
                        <option value="depense"{{ request('type') == 'depense' ? 'selected' : '' }}>Depenses
                        </option>
                        <option value="salaire"{{ request('type') == 'salaire' ? 'selected' : '' }}>Salaires
                        </option>
                        <option value="caisse"{{ request('type') == 'caisse' ? 'selected' : '' }}>Caisse
                        </option>
                    </select>
                    @error('type')
                        <div class=" invalid-feedback">{{ $errors->first('type') }}</div>
                    @enderror
                </div>
                <div class="col-2 m-4 d-flex justify-between">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1">Search</button>
                    <div class="dropdown">
                        <button type="button"
                            class="btn btn-secondary buttons-collection dropdown-toggle btn-label-primary me-2"
                            data-bs-toggle="dropdown">Export</button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="javascript:void(0);">
                                <i class="bx bxs-file-export me-1"></i> Excel</a>
                            <a class="dropdown-item" href="javascript:void(0);"><i class="bx bxs-file-pdf me-1"></i>
                                PDF</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mt-4">
                    <a href="{{ route('rapport.index') }}"class="btn btn-dark ">Reset</a>

                </div>
                {{-- 
                        <div class="d-flex justify-content-end" >
                        </div> --}}
                {{-- </div> --}}
            </form>
            {{-- </div> --}}
        </div>

    </div>
    @if (!key_exists('status', $rapports) && request('type') != null)
        {{-- Depense List --}}
        @if ($rapports['depense'] != null)
            <div class="card table-responsive mt-4">
                <table class="datatables-users table border-top">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>User</th>
                            <th>Title</th>
                            <th>Solde</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rapports['depense'] as $depense)
                            <tr id="row_19">
                                <td>
                                    <span class="badge-basic-primary-text">{{ $loop->iteration }}</span>
                                </td>
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
                                            <a class="dropdown-item"
                                                href="{{ route('expenses.edit', [$depense->id]) }}"><i
                                                    class="bx bx-edit-alt me-1"></i>
                                                Edit</a>
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Dettes --}}
        @if ($rapports['dettes'] != null)
            <div class="card table-responsive mt-4">
                <table class="datatables-users table border-top">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Type</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Montant</th>
                             <th>Date</th> 
                             <th>Motif</th> 
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @dd($rapports) --}}
                        @foreach ($rapports['dettes'] as $dette)
                            <tr id="row_19">
                                <td>
                                    <span class="badge-basic-primary-text">{{ $loop->iteration }}</span>
                                </td>
                                <td>
                                    <span class="badge-basic-primary-text">{{ Str::ucfirst($dette->type) }}</span> 
                                </td>
                                @if ($dette->type == 'user')
                                    <td>
                                        <span class="badge-basic-primary-text">
                                            {{ $dette->user->firstName ?? '' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge-basic-primary-text">
                                            {{ $dette->user->lastName ?? '' }}
                                        </span>
                                    </td>
                                @else
                                    <td>
                                        <span class="badge-basic-primary-text">
                                            {{ $dette->client->firstName ?? '' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge-basic-primary-text">
                                            {{ $dette->client->lastName ?? '' }}
                                        </span>
                                    </td>
                                @endif
                                <td>
                                    <span class="badge-basic-primary-text">
                                        {{ $dette->montant }} BIF
                                    </span>
                                </td>
                                <td>
                                    {{ $dette->date_creation}} 
                                </td>
                                <td>{{ $dette->motif }}</td>
                                <td>
                                    @if ($dette->status == 1)
                                        <span class="badge rounded-pill bg-danger">Unpaid</span>
                                    @else
                                        <span class="badge rounded-pill bg-success">Paid</span>
                                    @endif
    
                                </td>
                                {{-- <td>
                                    <span class="badge-basic-primary-text">
                                        {{ $dette->status ? 'Unpaid' : 'Paid' }}
                                    </span>
                                </td> --}}
                               

                                <td class="action">
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('expenses.dette.edit', [$dette->id]) }}"><i
                                                    class="bx bx-edit-alt me-1"></i>
                                                Edit</a>

                                            {{--  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i>
                                      Delete</a>  --}}

                                            <form action="{{ route('expenses.dette.destroy', [$dette->id]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Do you want to delete this dette?')">
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
        @endif

        {{-- Out --}}
        @if ($rapports['out'] != null)
            <div class="card table-responsive mt-4">
                <table class="datatables-users table border-top">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Product</th>
                            <th>Flux number</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total amount</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($rapports['out'] as $mouvement)
                            <tr id="row_19">
                                <td>
                                    <span class="badge-basic-primary-text">{{ $loop->iteration }}</span>
                                </td>
                                <td>
                                    <span class="badge-basic-primary-text">
                                        @foreach ($mouvement->productMouvements as $productMouv)
                                            {{ $productMouv->product->name }}
                                        @endforeach
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-basic-primary-text">
                                        {{ $mouvement->referenceMov }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge-basic-primary-text">
                                        @foreach ($mouvement->productMouvements as $productMouv)
                                            {{ $productMouv->quantity }} {{ $productMouv->product->unit_mesure }}
                                        @endforeach
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-basic-primary-text">
                                        @foreach ($mouvement->productMouvements as $productMouv)
                                            {{ $productMouv->price_un }} BIF
                                        @endforeach
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-basic-primary-text">
                                        @foreach ($mouvement->productMouvements as $productMouv)
                                            {{ $productMouv->price_tot }} BIF
                                        @endforeach
                                    </span>
                                </td>
                                {{--  <td>
                          <span class="badge-basic-primary-text">
                            {{ $mouvement->user->firstName }}
                            {{ $mouvement->user->lastName }}
                          </span>
                        </td>  --}}
                                <td>
                                    <span class="badge-basic-primary-text">
                                        {{ $mouvement->date_flux }}
                                    </span>
                                </td>

                                <td class="action">
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('inventory.editOutput', [$mouvement->id]) }}"><i
                                                    class="bx bx-edit-alt me-1"></i>
                                                Edit</a>

                                            {{--  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i>
                                      Delete</a>  --}}

                                            <form action="{{ route('inventory.destroy', [$mouvement->id]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Do you want to delete this flux?')">
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
        @endif

        {{-- caisse --}}
        @if ($rapports['caisses'] != null)
            <div class="card table-responsive mt-4">
                <table class="datatables-users table border-top">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>Montant</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rapports['caisses'] as $caisse)
                            <tr id="row_19">
                                <td>
                                    <span class="badge-basic-primary-text">{{ $loop->iteration }}</span>
                                </td>
                                <td>
                                    <span class="badge-basic-primary-text">
                                        {{ $caisse->user->firstName ?? '' }}
                                        {{ $caisse->user->lastName ?? '' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-basic-primary-text">
                                        {{ $caisse->type }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-basic-primary-text">
                                        {{ $caisse->montant }} BIF
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-basic-primary-text">
                                        {{ $caisse->date_creation }}
                                    </span>
                                </td>
                                <td class="action">
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('caisse.edit', [$caisse->id]) }}"><i
                                                    class="bx bx-edit-alt me-1"></i>
                                                Edit</a>
                                            <form action="{{ route('caisse.destroy', [$caisse->id]) }}" method="POST"
                                                onsubmit="return confirm('Do you want to delete this money mouvement?')">
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
        @endif
        {{-- salaires --}}
        @if ($rapports['salaires'] != null)
            <div class="card table-responsive mt-4">
                <table class="datatables-users table border-top">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Montant brut</th>
                            <th>Montant versé</th>
                            <th>Date de versément</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($rapports['salaires'] as $versement)
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
                                <td>
                                    <span class="badge-basic-primary-text">
                                        {{ $versement->date_verse }}
                                    </span>
                                </td>


                                <td class="action">
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('expenses.versement.edit', [$versement->id]) }}"><i
                                                    class="bx bx-edit-alt me-1"></i>
                                                Edit</a>

                                            {{--  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i>
                                      Delete</a>  --}}

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
            {{-- <div class="">
            {{ $versements->links() }} 
        </div>  --}}
        @endif
    @endif
@endsection
