@props(['fieldActives' => [], 'fieldWidth' => 12, 'saveButton' => false, 'submit' => 'save', 'keyData' => null, 'isShow' => false, 'fieldDatas' => []])
<form wire:submit="{{$submit}}" x-data x-init="Alpine.nextTick(() => initForm())">
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
                        @if ($isShow)
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <span class="h6">{{$item['name']}}</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span>: {!!$keyData ? $item['options'][$fieldDatas[$keyData][$key]] : $item['options'][$fieldDatas[$key]]!!}</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <x-form.select
                                label="{{$item['name']}}"
                                name="{{$key}}"
                                id="{{$key}}"
                                class="{{$item['class'] ?? ''}}"
                                :options="$item['options']"
                                wire:model="fieldDatas.{{$keyData?$keyData.'.':''}}{{ $key }}" />
                        @endif
                        @break
                    @case('checkbox')
                        <x-form.checkbox
                            label="{{$item['name']}}"
                            name="{{$key}}"
                            id="{{$key}}"
                            wire:model="fieldDatas.{{$keyData?$keyData.'.':''}}{{ $key }}" />

                        @break
                    @case('textarea')
                        @if ($isShow)
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <span class="h6">{{$item['name']}}</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span>: {{$keyData ? $fieldDatas[$keyData][$key] : $fieldDatas[$key]}}</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <x-form.textarea
                                label="{{$item['name']}}"
                                name="{{$key}}"
                                id="{{$key}}"
                                wire:model="fieldDatas.{{$keyData?$keyData.'.':''}}{{ $key }}" />
                        @endif
                        @break
                    @case('map')
                        @if ($isShow)
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <span class="h6">{{$item['name']}}</span>
                                    </div>
                                    <div class="col-md-8">
                                        <x-form.input
                                            type="text"
                                            name="{{$key}}"
                                            id="{{$key}}Show"
                                            wire:model="fieldDatas.{{$keyData?$keyData.'.':''}}{{$key}}"
                                            style="display: none;" />

                                        <div class="leaflet-map" id="{{$key}}MapShow" data-field="{{$key}}Show" data-isedit="false" style="height: 400px; margin-bottom: 20px;" wire:ignore ></div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <x-form.input
                                type="text"
                                label="{{ __($item['name']) }}"
                                name="{{$key}}"
                                id="{{$key}}"
                                wire:model="fieldDatas.{{$keyData?$keyData.'.':''}}{{$key}}"
                                style="display: none;" />

                            <div class="leaflet-map" id="{{$key}}Map" data-field="{{$key}}" data-isedit="{{$isShow ? 'false' : 'true'}}" style="height: 400px; margin-bottom: 20px;" wire:ignore ></div>
                        @endif
                        @break
                    @case('ckeditor5')
                        <x-form.label for="{{ $key }}" class="form-label" value="{{ __($item['name']) }}" />
                        <div id="{{$key}}" style="display: none;">{!!data_get($fieldDatas, ($keyData?$keyData.'.'.$key:$key))!!}</div>
                        <div class="editor-container editor-container_classic-editor editor-container_include-style" id="editor-container" wire:ignore>
                            <textarea class="editor-ckeditor5" data-field="{{$key}}" id="{{$key}}Editor"  wire:ignore></textarea>
                        </div>
                        @break
                    @default
                        @if ($isShow)
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <span class="h6">{{$item['name']}}</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span>: {{$keyData ? $fieldDatas[$keyData][$key] : $fieldDatas[$key]}}</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <x-form.input
                                type="{{$item['type']}}"
                                label="{{ __($item['name']) }}"
                                name="{{$key}}"
                                id="{{$key}}"
                                path="{{($item['type'] == 'file' && data_get($fieldDatas, ($keyData?$keyData.'.'.$key.'path':$key.'path')))
                                    ? data_get($fieldDatas, ($keyData?$keyData.'.'.$key.'path':$key.'path')) : null}}"
                                wire:model="fieldDatas.{{$keyData?$keyData.'.':''}}{{$key}}" />
                        @endif
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
    @if ($saveButton)
        <div class="mt-10 d-flex justify-content-end align-items-baseline">
            <x-form.button wire:target="save">
                {{ __('Simpan') }}
            </x-form.button>
        </div>
    @endif
</form>

