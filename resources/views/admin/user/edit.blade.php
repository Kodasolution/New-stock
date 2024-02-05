<div class="modal fade" id="edit-user-{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog add-new-user">
        <div class="modal-content p-2">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="user-title">Edit user - {{ Str::ucfirst($user->firstName) }}
                        {{ Str::ucfirst($user->lastName) }}</h3>
                </div>
                <form action="{{ route('user.update', ['user' => $user]) }}" method="POST" class="row g-3">
                    @csrf
                    @method('patch')
                    @php
                        $err = 'err_' . $user->id;
                    @endphp
                    <div class="col-12 mb-0">
                        <label class="form-label" for="name">First name</label>
                        <input type="text" id="name" name="firstName"
                            class="form-control @error('firstName', $err) is-invalid @enderror" placeholder="First Name"
                            tabindex="-1" value="{{ old('firstName') ?? $user->firstName }}" />
                        @error('firstName', $err)
                            <div class=" invalid-feedback">{{ $errors->$err->first('firstName') }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-0">
                        <label class="form-label" for="name">Last name</label>
                        <input type="text" id="name" name="lastName"
                            class="form-control @error('lastName', $err) is-invalid @enderror" placeholder="Last Name"
                            tabindex="-1" value="{{ old('lastName') ?? $user->lastName }}" />
                        @error('lastName', $err)
                            <div class=" invalid-feedback">{{ $errors->$err->first('lastName') }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-0">
                        <label class="form-label" for="email">Email</label>
                        <input type="text" id="email" name="email"
                            class="form-control @error('email', $err) is-invalid @enderror" placeholder="Email"
                            value="{{ old('email') ?? $user->email }}" />
                        @error('email', $err)
                            <div class=" invalid-feedback">{{ $errors->$err->first('email') }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-0">
                        <label class="form-label" for="telephone">Phone</label>
                        <input type="text" id="telephone" name="phone"
                            class="form-control @error('phone', $err) is-invalid @enderror" placeholder="phone"
                            value="{{ old('phone') ?? $user->phone }}" />
                        @error('phone', $err)
                            <div class="invalid-feedback">{{ $errors->$err->first('phone') }}</div>
                        @enderror
                    </div>
                    {{-- @dd($user->roles->pluck('name')[0]) --}}
                    <div class="col-12 mb-0">
                        <label class="form-label" for="role">Role</label>
                        <select name="role" id="" class="form-control @error('role') is-invalid @enderror">
                            {{-- @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach --}}
                            <option value="admin" {{ $user->roles->pluck('name')[0]  == config('module.roles.admin') ? 'selected' : '' }}>
                                Admin</option>
                            <option value="cuissinier"
                                {{ $user->roles->pluck('name')[0] == config('module.roles.cuissinier')  ? 'selected' : '' }}>Cuissinier</option>
                            <option value="caissier"
                                {{ $user->roles->pluck('name')[0] == config('module.roles.caissier')   ? 'selected' : '' }}>Caissier</option>
                        </select>
                        @error('role')
                            <div class=" invalid-feedback">{{ $errors->first('role') }}</div>
                        @enderror
                    </div>
                    <div class="col-12 text-center mt-3">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Edit</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
