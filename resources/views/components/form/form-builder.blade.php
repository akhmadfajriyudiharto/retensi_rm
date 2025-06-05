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
                            name="{{$key}}"
                            id="{{$key}}"
                            class="{{$item['class'] ?? ''}}"
                            :options="$item['options']"
                            wire:model="fieldDatas.{{ $key }}" />
                    @isset($item['class'])
                    </div>
                    @endisset
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
