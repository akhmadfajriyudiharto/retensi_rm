
<div class="modal fade" id="modalForm" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-{{$formWidth}} modal-simple">
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
            <h4 class="mb-2">{{ $action == 'edit' ? 'Edit' : 'Tambah' }} {{$title}}</h4>
            <p>{{$description}}</p>
        </div>
        <form wire:submit="save">
            <x-form.form-builder
                :input="$fieldActives" :fieldWidth="$fieldWidth" />
            <div id="preview"></div>
            <div class="mt-10 d-flex justify-content-end align-items-baseline">
                <x-form.button wire:target="save">
                    {{ __('Simpan') }}
                </x-form.button>
                <div class="m-2 d-flex justify-content-end align-items-baseline">
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                </div>
            </div>
        </form>
        </div>
    </div>
    </div>
</div>

@push('page-script')
    <x-master.form-modal-script />
@endpush
