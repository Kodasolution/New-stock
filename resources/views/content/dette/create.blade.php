@extends('layouts/layoutMaster')

@section('title', ' Add dette')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

 {{-- @section('page-script')
    <script src="{{ asset('assets/js/forms-selects.js') }}"></script>
@endsection  --}}

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

    <h4 class="py-3 breadcrumb-wrapper mb-4"><span class="text-muted fw-light"><a
                href="{{ route('expenses.dette.index') }}">Liste des dettes</a></h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Donner une dette</h5>
                    {{--  <small class="text-muted float-end">Default label</small>  --}}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('expenses.dette.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="multicol-country">Type</label>
                            <div class="col-sm-10">
                                <select class="form-select @error('type') is-invalid @enderror" data-allow-clear="true"
                                    name="type">
                                    <option value="{{ old('type') }}">Choose Type</option>
                                    <option value="user">Employés</option>
                                    <option value="client">Clients</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="multicol-country">Employé</label>
                            <div class="col-sm-10">
                              {{-- <select id="select2"
                              class="select2 form-select form-select-lg @error('user_id') is-invalid @enderror"
                              data-allow-clear="true" name="user_id"> --}}

                                <select class=" form-select  @error('user_id') is-invalid @enderror" data-allow-clear="true"
                                    name="user_id">
                                    <option value="{{ old('user_id') }}">Choose employee</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->firstName }} {{ $user->lastName }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $errors->first('user_id') }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="multicol-country">Client</label>
                            <div class="col-sm-10">
                                <select class="form-select @error('client_id') is-invalid @enderror" data-allow-clear="true"
                                    name="client_id">
                                    <option value="{{ old('client_id') }}">Choose client</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->firstName }}
                                            {{ $client->lastName }}</option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <div class="invalid-feedback">{{ $errors->first('client_id') }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Montant</label>
                            <div class="col-sm-10">
                                <input type="text" name="montant"
                                    class="form-control @error('montant') is-invalid @enderror" id="basic-default-name"
                                    placeholder="Enter amount" value="{{ old('montant') }}" />
                                @error('montant')
                                    <div class="invalid-feedback">{{ $errors->first('montant') }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Motif</label>
                            <div class="col-sm-10">
                                <input type="text" name="motif"
                                    class="form-control @error('motif') is-invalid @enderror" id="basic-default-name"
                                    placeholder="Enter the motif" value="{{ old('motif') }}" />
                                @error('motif')
                                    <div class="invalid-feedback">{{ $errors->first('motif') }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Date</label>
                            <div class="col-sm-10">
                                <input type="date" name="date_creation"
                                    class="form-control @error('date_creation') is-invalid @enderror"
                                    id="basic-default-name" placeholder="Enter the date"
                                    value="{{ old('date_creation') }}" />
                                @error('date_creation')
                                    <div class="invalid-feedback">{{ $errors->first('date_creation') }}</div>
                                @enderror
                            </div>
                        </div>

                        {{--  <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="multicol-country">Status</label>
            <div class="col-sm-10">
              <select class="form-select @error('status') is-invalid @enderror" data-allow-clear="true" name="status" >
                <option value="{{ old('status') }}" >Choose status</option>
                <option value="1">Unpaid</option>
                <option value="0">Paid</option>
              </select>
              @error('status')
              <div class="invalid-feedback">{{ $errors->first('status') }}</div>
            @enderror
            </div>
          </div>  --}}

                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Récupérez les éléments de sélection et les champs d'employé et de client
            const typeSelect = document.querySelector('select[name="type"]');
            const employeSelect = document.querySelector('select[name="user_id"]');
            const clientSelect = document.querySelector('select[name="client_id"]');

            // Fonction pour masquer les champs d'employé et de client
            function hideFields() {
                employeSelect.style.display = "none";
                clientSelect.style.display = "none";
            }

            // Fonction pour afficher les champs d'employé
            function showEmployeField() {
                employeSelect.style.display = "block";
                clientSelect.style.display = "none";
            }

            // Fonction pour afficher les champs de client
            function showClientField() {
                employeSelect.style.display = "none";
                clientSelect.style.display = "block";
            }

            // Ajoutez un écouteur d'événements pour le champ "Type"
            typeSelect.addEventListener("change", function() {
                const selectedType = typeSelect.value;

                // Masquez d'abord tous les champs
                hideFields();

                // En fonction du type sélectionné, affichez les champs appropriés
                if (selectedType === "user") {
                    showEmployeField();
                } else if (selectedType === "client") {
                    showClientField();
                }
            });

            // Assurez-vous que les champs sont masqués au chargement de la page
            hideFields();
        });
    </script>

@endsection
