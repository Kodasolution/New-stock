<div class="modal fade" id="edit-module-{{ $module->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog add-new-module">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="module-title">Edit module - {{ Str::ucfirst($module->name) }}</h3>
                </div>
                <form action="{{ route('user.module.update', ['module' => $module]) }}" method="POST"
                    class="row g-3">
                    @csrf
                    @method('patch')
                    @php
                        $err = 'err_' . $module->id;
                    @endphp
                    <div class="col-12 mb-4">
                        <label class="form-label" for="name">Module name</label>
                        <input type="text" id="name" name="name"
                            class="form-control @error('name', $err) is-invalid @enderror"
                            placeholder="Enter a module name" tabindex="-1"
                            value="{{ old('name') ?? $module->name }}" />
                        @error('name', $err)
                            <div class=" invalid-feedback">{{ $errors->$err->first('name') }}</div>
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
