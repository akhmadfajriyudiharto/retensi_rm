@props(['input' => [], 'isSearch' => false, 'fieldWidth' => 12])

@php
    $state = 0;
    $increase = $fieldWidth;
@endphp
@foreach ($input as $key => $item)
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
                    @isset($item['class'])
                    <div wire:ignore>
                    @endisset
                        <x-form.select
                            label="{{$item['name']}}"
                            name="f_{{$key}}"
                            column-name="{{$key}}"
                            id="f_{{$key}}"
                            class="{{$item['class'] ?? ''}} dt-select"
                            :options="$item['options']"
                            wire:model="fieldDatas.f_{{ $key }}" />
                    @isset($item['class'])
                    </div>
                    @endisset
                    @break
                @case('checkbox')
                    <x-form.checkbox
                        label="{{$item['name']}}"
                        name="f_{{$key}}"
                        column-name="{{$key}}"
                        id="f_{{$key}}"
                        wire:model="fieldDatas.f_{{ $key }}" />

                    @break
                @case('textarea')
                    <x-form.textarea
                        label="{{$item['name']}}"
                        name="f_{{$key}}"
                        column-name="{{$key}}"
                        id="f_{{$key}}"
                        wire:model="fieldDatas.f_{{ $key }}" />
                    @break
                @case('map')
                    <x-form.input
                        type="text"
                        label="{{ __($item['name']) }}"
                        name="f_{{$key}}"
                        id="f_{{$key}}"
                        wire:model="fieldDatas.f_{{$key}}"
                        style="display: none;" />

                    <div class="leaflet-map" id="f_{{$key}}Map" data-field="f_{{$key}}" style="height: 400px; margin-bottom: 20px;" wire:ignore ></div>
                    @break
                @case('ckeditor5')
                    <x-form.label for="f_{{ $key }}" class="form-label" value="{{ __($item['name']) }}" />
                    <div id="f_{{$key}}" style="display: none;">{!!$fieldDatas['f_'.$key]!!}</div>
                    <div class="editor-container editor-container_classic-editor editor-container_include-style" id="editor-container" wire:ignore>
                        <textarea class="editor-ckeditor5" data-field="f_{{$key}}" id="f_{{$key}}Editor"  wire:ignore></textarea>
                    </div>
                    @break
                @default
                    <x-form.input
                        type="{{$item['type']}}"
                        label="{{ __($item['name']) }}"
                        name="f_{{$key}}"
                        column-name="{{$key}}"
                        id="f_{{$key}}"
                        class="dt-input"
                        path="{{($item['type'] == 'file' && isset($fieldDatas['f_'.$key . 'path']) && $fieldDatas['f_'.$key . 'path'])
                            ? $fieldDatas['f_'.$key . 'path'] : null}}"
                        wire:model="fieldDatas.f_{{$key}}" />
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
