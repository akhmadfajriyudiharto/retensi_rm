
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
            @php
                $state = 0;
                $increase = $fieldWidth;
            @endphp
            @foreach ($fieldActives as $key => $item)
                @if ($state%12 === 0)
                <div class="row">
                @endif
                @if (isset($item['isMerged']) && $item['isMerged'])
                </div>
                <div class="row">
                @endif
                    <div class="col-md-{{isset($item['isMerged']) && $item['isMerged'] ? 12 : $fieldWidth}}">
                        @switch($item['type'])
                            @case('select')
                                <x-form.select
                                    label="{{$item['name']}}"
                                    name="{{$key}}"
                                    id="{{$key}}"
                                    class="{{$item['class'] ?? ''}}"
                                    :options="$item['options']"
                                    wire:model="fieldDatas.{{ $key }}" />
                                @break
                            @case('checkbox')
                                <x-form.checkbox
                                    label="{{$item['name']}}"
                                    name="{{$key}}"
                                    id="{{$key}}"
                                    wire:model="fieldDatas.{{ $key }}" />

                                @break
                            @case('textarea')
                                <x-form.textarea
                                    label="{{$item['name']}}"
                                    name="{{$key}}"
                                    id="{{$key}}"
                                    wire:model="fieldDatas.{{ $key }}" />
                                @break
                            @case('map')
                                <x-form.input
                                    type="text"
                                    label="{{ __($item['name']) }}"
                                    name="{{$key}}"
                                    id="{{$key}}"
                                    wire:model="fieldDatas.{{$key}}"
                                    style="display: none;" />

                                <div class="leaflet-map" id="{{$key}}Map" data-field="{{$key}}" style="height: 400px; margin-bottom: 20px;" wire:ignore ></div>
                                @break
                            @case('ckeditor5')
                                <x-form.label for="{{ $key }}" class="form-label" value="{{ __($item['name']) }}" />
                                <div id="{{$key}}" style="display: none;">{!!$fieldDatas[$key]!!}</div>
                                <div class="editor-container editor-container_classic-editor editor-container_include-style" id="editor-container" wire:ignore>
                                    <textarea class="editor-ckeditor5" data-field="{{$key}}" id="{{$key}}Editor"  wire:ignore></textarea>
                                </div>
                                @break
                            @default
                                <x-form.input
                                    type="{{$item['type']}}"
                                    label="{{ __($item['name']) }}"
                                    name="{{$key}}"
                                    id="{{$key}}"
                                    path="{{($item['type'] == 'file' && isset($fieldDatas[$key . 'path']) && $fieldDatas[$key . 'path'])
                                        ? $fieldDatas[$key . 'path'] : null}}"
                                    wire:model="fieldDatas.{{$key}}" />
                        @endswitch
                    </div>
                @php
                    $state += $increase;
                    if(isset($item['isMerged']) && $item['isMerged'])
                        $state = 0;
                @endphp
                @if ($state%12 === 0)
                </div>
                @endif
            @endforeach
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
