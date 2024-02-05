<div class="modal fade" id="add-user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog add-new-user">
        <div class="modal-content p-2">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="user-title">Add New user</h3>
                </div>
                <form action="{{ route('user.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-12 mb-0">
                        <label class="form-label" for="name">First Name</label>
                        <input type="text" id="name" name="firstName"
                            class="form-control @error('firstName') is-invalid @enderror" placeholder="First name" />
                        @error('firstName')
                            <div class=" invalid-feedback">{{ $errors->first('firstName') }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-0">
                        <label class="form-label" for="name">Last Name</label>
                        <input type="text" id="name" name="lastName"
                            class="form-control @error('lastName') is-invalid @enderror" placeholder="Last name" />
                        @error('lastName')
                            <div class=" invalid-feedback">{{ $errors->first('lastName') }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-0">
                        <label class="form-label" for="email">Email</label>
                        <input type="text" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror" placeholder="Email" />
                        @error('email')
                            <div class=" invalid-feedback">{{ $errors->first('email') }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-0">
                        <label class="form-label">Telephone</label>
                        <input type="text" id="telephone" name="phone"
                            class="form-control @error('phone') is-invalid @enderror" placeholder="Telephone" />
                        @error('phone')
                            <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-0">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Enter a user password" />
                        @error('password')
                            <div class=" invalid-feedback">{{ $errors->first('password') }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-0">
                        <label class="form-label" for="role">Role</label>
                        <select name="role" id="" class="form-control @error('role') is-invalid @enderror">
                            {{-- @dd($roles) --}}
                            @foreach ($roles as $role )
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class=" invalid-feedback">{{ $errors->first('role') }}</div>
                        @enderror
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Save</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
