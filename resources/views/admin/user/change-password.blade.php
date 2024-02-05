<div class="modal fade" id="change-password-{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog add-new-user">
        <div class="modal-content p-2">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="user-title">Change password for - {{ Str::ucfirst($user->name) }}</h3>
                </div>
                <form action="{{ route('user.change-password', ['user' => $user]) }}" method="POST" class="row g-3">
                    @csrf
                    @php
                        $errPswd = 'err_pswd_' . $user->id;
                    @endphp
                    <div class="col-12 mb-3">
                        <label class="form-label" for="pswd">New password</label>
                        <input type="text" id="pswd" name="password"
                            class="form-control @error('password', $errPswd) is-invalid @enderror"
                            placeholder="New password" value="{{ old('password') }}" />
                        @error('password', $errPswd)
                            <div class="invalid-feedback">{{ $errors->$errPswd->first('password') }}</div>
                        @enderror
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Edit</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
