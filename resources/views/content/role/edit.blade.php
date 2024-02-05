<div class="modal fade" id="edit-role-{{ $role->id }}"tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title">Add New Role</h3>
                    <p>Set role permissions</p>
                </div>
                <!-- Add role form -->
                <form class="row g-3" action="{{ route('user.role.update', ['role' => $role]) }}" method="POST">
                    @csrf
                    @method('patch')
                    @php
                        $err = 'err_' . $role->id;
                    @endphp
                    <div class="col-12 mb-4">
                        <label class="form-label" for="name">Role Name</label>
                        <input type="text" id="name"
                            class="form-control @error('name', $err) is-invalid @enderror"
                            placeholder="Enter a role name" name="name" value="{{ old('name') ?? $role->name }}" />
                        @error('name', $err)
                            <div class="invalid-feedback">{{ $errors->$err->first('name') }}</div>
                        @enderror
                    </div>
                    @error('permission')
                        <div class="alert alert-solid-danger" role="alert">
                            <h6 class="alert-heading mb-1">Error !</h6>
                            <span>{{ $errors->$err->first('permission') }}</span>
                        </div>
                    @enderror
                    <div class="col-12">
                        <h5>Role Permissions</h5>
                        <div class="table-responsive">
                            <table class="table table-flush-spacing">
                                <tbody>
                                    <tr>
                                        <td class="text-nowrap">Module Access <i class="bx bx-info-circle bx-xs"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Allows a full access to the system"></i>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll" />
                                                <label class="form-check-label" for="selectAll">
                                                    Select All
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    @php
                                        $arr_flip = array_flip($permission);
                                    @endphp
                                    @foreach ($modules as $module)
                                        <tr>
                                            <td class="text-nowrap">{{ Str::ucfirst($module) }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @foreach (config('permission.action') as $act)
                                                        @php
                                                            $perm_id = $arr_flip[$act . ' ' . $module] ?? '';
                                                        @endphp
                                                        @if (in_array($act . ' ' . $module, $permission))
                                                            <div class="form-check me-3 me-lg-5">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="{{ $perm_id . '-' . $act }}" name="permission[]"
                                                                    value="{{ $perm_id }}"
                                                                    @checked(in_array($perm_id, old('permission') ?? $role->permissions->pluck('id')->toArray())) />
                                                                <label class="form-check-label"
                                                                    for="{{ $perm_id . '-' . $act }}">
                                                                    {{ Str::ucfirst($act) }}
                                                                </label>
                                                            </div>
                                                            @error('permission', $err)
                                                                <div class="invalid-feedback">
                                                                    {{ $errors->$err->first('permission') }}</div>
                                                            @enderror
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
