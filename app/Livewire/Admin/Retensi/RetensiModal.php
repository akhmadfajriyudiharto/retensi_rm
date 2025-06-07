<?php

namespace App\Livewire\Admin\Retensi;

use App\Models\RetensiRecord;
use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class RetensiModal extends Component
{
    public $listeners = ['retensi'];

    public function retensi($id)
    {
        RetensiRecord::create(['rekam_medis_id' => $id, 'status' => RetensiRecord::STATUS_INAKTIF]);

        $this->dispatch('success', 'Data Rekam Medis Behasil Diretensi!');
    }

    public function render()
    {
        return view('admin.retensi.retensi-modal');
    }
}
