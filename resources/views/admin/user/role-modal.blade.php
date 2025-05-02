<div class="modal fade" id="modalRole" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg modal-simple">
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
            <h4 class="mb-2">Pengaturan Role User</h4>
        </div>
        @foreach ($options as $key => $role)
            <x-form.checkbox
                label="{{$role}}"
                name="{{$key}}"
                id="{{$key}}"
                wire:click="toggleItem('{{$key}}')"
                :value="(in_array($key, $selectedItems))" />
        @endforeach
        </div>
    </div>
    </div>
</div>
