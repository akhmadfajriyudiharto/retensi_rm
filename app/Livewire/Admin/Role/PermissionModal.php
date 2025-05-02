<?php

namespace App\Livewire\Admin\Role;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionModal extends Component
{
    public $role = null, $options = [], $selectedItems = [];
    public $listeners = ['editPermission'];

    public function mount()
    {
        $menuFile = base_path('resources/menu/verticalMenu.json');

        if (file_exists($menuFile)) {
            $verticalMenuJson = file_get_contents($menuFile);
            $verticalMenuData = json_decode($verticalMenuJson);
        }
        $this->options = $verticalMenuData->menu;
    }

    public function toggleItem($itemId)
    {
        if (in_array($itemId, $this->selectedItems)) {
            $this->selectedItems = array_diff($this->selectedItems, [$itemId]);
            $msg = 'menghapus';
        } else {
            $this->selectedItems[] = $itemId;
            $msg = 'menambahkan';
        }
        $permissionIds = Permission::whereIn('name', $this->selectedItems)->pluck('id')->toArray();
        $this->role->permissions()->sync($permissionIds);
        Artisan::call('cache:clear');
        $this->dispatch('success', 'Berhasil ' . $msg . ' permission ' . $itemId . ' ke role!' . $this->role->name);
    }

    public function editPermission($id)
    {
        $this->selectedItems = [];
        $this->role = null;
        $this->role = Role::find($id);
        foreach ($this->role->permissions as $item){
            $this->selectedItems[] = $item['name'];

        }
    }

    public function render()
    {
        return view('admin.role.permission-modal');
    }
}
