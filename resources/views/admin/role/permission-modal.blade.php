<div class="modal fade" id="modalPermission" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-xl modal-simple">
    <div class="modal-content">
        <div class="modal-body">
            <div x-data="{ message: '', shown: false, timeout: null }"
                x-init="@this.on('success', msg => {
                    clearTimeout(timeout);
                    message = msg;
                    shown = true;
                    timeout = setTimeout(() => { shown = false }, 3000);
                })"
                x-show="shown"
                class="alert alert-success"
                style="display: none;">
                <span x-text="message"></span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="text-center mb-6">
                <h4 class="mb-2">Pengaturan Permission Role</h4>
            </div>

            <table class="table table-hover" style="width: 100%;" id="datatable">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Read</th>
                        <th>Create</th>
                        <th>Update</th>
                        <th>Delete</th>
                        <th>Other</th>
                    </tr>
                </thead>
                <tbody>
                    @component('admin.role.permission-form', ['menus' => $options, 'selectedItems' => $selectedItems])

                    @endcomponent
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>
