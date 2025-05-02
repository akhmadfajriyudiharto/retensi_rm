@props(['menus' => [], 'level' => 0, 'selectedItems' => []])

@foreach ($menus as $key => $menu)
    <tr>
        <td>
            <label style="margin-left: {{ $level * 20 }}px;">{{$menu->name}}</label>
        </td>
        @if (!isset($menu->submenu))
            <td>
                <x-form.checkbox
                    label="View"
                    name="{{$menu->slug}}.view"
                    id="{{$menu->slug}}.view"
                    wire:click="toggleItem('{{$menu->slug}}.view')"
                    :value="(in_array($menu->slug.'.view', $selectedItems))" />
            </td>
            <td>
                <x-form.checkbox
                    label="Create"
                    name="{{$menu->slug}}.create"
                    id="{{$menu->slug}}.create"
                    wire:click="toggleItem('{{$menu->slug}}.create')"
                    :value="(in_array($menu->slug.'.create', $selectedItems))" />
            </td>
            <td>
                <x-form.checkbox
                    label="Update"
                    name="{{$menu->slug}}.update"
                    id="{{$menu->slug}}.update"
                    wire:click="toggleItem('{{$menu->slug}}.update')"
                    :value="(in_array($menu->slug.'.update', $selectedItems))" />
            </td>
            <td>
                <x-form.checkbox
                    label="Delete"
                    name="{{$menu->slug}}.delete"
                    id="{{$menu->slug}}.delete"
                    wire:click="toggleItem('{{$menu->slug}}.delete')"
                    :value="(in_array($menu->slug.'.delete', $selectedItems))" />
            </td>
            <td>
                @isset($menu->permissions)
                    @foreach ($menu->permissions as $item)
                        <x-form.checkbox
                            label="{{ltrim(strrchr($item, '.'), '.')}}"
                            name="{{$item}}"
                            id="{{$item}}"
                            wire:click="toggleItem('{{$item}}')"
                            :value="(in_array($item, $selectedItems))" />
                    @endforeach
                @endisset
            </td>
        @else
            <td colspan="5">
            </td>
        @endif
    </tr>
    @if (isset($menu->submenu))
        @component('admin.role.permission-form', ['menus' => $menu->submenu, 'level' => $level+1, 'selectedItems' => $selectedItems])

        @endcomponent
    @endif
@endforeach
