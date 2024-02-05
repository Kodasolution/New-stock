<div class="modal fade" id="add-module" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog add-new-module">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="module-title">Add New module</h3>
                </div>
                <form action="{{ route('user.module.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-12 mb-4">
                        <label class="form-label" for="name">module Name</label>
                        <input type="text" id="name" name="name"
                            class="form-control @error('name') is-invalid @enderror" placeholder="Enter a module name"
                            tabindex="-1" />
                        @error('name')
                            <div class=" invalid-feedback">{{ $errors->first('name') }}</div>
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
