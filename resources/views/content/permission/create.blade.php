<div class="modal fade" id="add-permission" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md add-new-permission">
        <div class="modal-content p-2">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="permission-title">Add New permission</h3>
                </div>
                <form action="{{ route('user.permission.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-12">
                        <label for="modle" class="form-label">Modules</label>
                        <select class="form-select @error('module') is-invalid @enderror" id="modle" name="module">
                            <option value="">Select module</option>
                            @foreach ($modules as $id => $name)
                                <option value="{{ $id }}" @selected((int) old('module') === $id)>
                                    {{ Str::ucfirst($name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('module')
                            <div class=" invalid-feedback">{{ $errors->first('module') }}</div>
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
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Save</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
