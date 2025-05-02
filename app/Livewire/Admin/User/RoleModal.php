<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleModal extends Component
{
    public $user = null, $options = [], $selectedItems = [];
    public $listeners = ['editRole'];

    public function mount()
    {
        $this->options = Role::pluck('name', 'id')->all();
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
        $this->user->roles()->sync($this->selectedItems);
        $this->dispatch('success', 'Berhasil ' . $msg . ' role ' . $this->options[$itemId] . ' ke user!');
    }

    public function editRole($id)
    {
        $this->selectedItems = [];
        $this->user = null;
        $this->user = User::find($id);
        foreach ($this->user->roles as $item)
            $this->selectedItems[] = $item['id'];
    }

    public function render()
    {
        return view('admin.user.role-modal');
    }
}
