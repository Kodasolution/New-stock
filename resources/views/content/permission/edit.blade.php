<div class="modal fade" id="edit-permission-{{ $permission->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog add-new-permission">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="permission-title">Edit permission - {{ Str::ucfirst($permission->name) }}</h3>
                </div>
                <form action="{{ route('user.permission.update', ['permission' => $permission]) }}" method="POST"
                    class="row g-3">
                    @csrf
                    @method('patch')
                    @php
                        $err = 'err_' . $permission->id;
                    @endphp
                    <div class="col-12 mb-4">
                        <label class="form-label" for="name">Module name</label>
                        <input type="text" id="name" name="name"
                            class="form-control @error('name', $err) is-invalid @enderror"
                            placeholder="Enter a permission name" tabindex="-1"
                            value="{{ old('name') ?? $permission->name }}" />
                        @error('name', $err)
                            <div class=" invalid-feedback">{{ $errors->$err->first('name') }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <small class="text-light fw-semibold d-block">Can do</small>
                        @foreach ($actions as $key => $act)
                            <div class="form-check form-check-inline form-check-primary mt-3">
                                <input class="form-check-input" type="checkbox" value="{{ $act }}"
                                    id="{{ $act }}" name="can_do[]">
                                <label class="form-check-label" for="{{ $act }}">Can
                                    {{ $act }}</label>
                            </div>
                        @endforeach
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
