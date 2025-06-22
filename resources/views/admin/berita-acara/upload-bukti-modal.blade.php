<div class="modal fade" id="modalUploadBukti" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-xs modal-simple">
        <div class="modal-content">
            <div class="modal-body">

                {{-- Notifikasi sukses --}}
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

                <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
                <h4 class="text-center mb-4">Upload Bukti</h4>

                <form wire:submit.prevent="save">
                    <x-form.input
                        type="file"
                        label="File Bukti"
                        name="file"
                        id="file"
                        path="{{ $path ?? null }}"
                        wire:model="file" />

                    <x-form.button type="submit" wire:target="save">
                        {{ __('Simpan') }}
                    </x-form.button>
                </form>

            </div>
        </div>
    </div>
</div>
