<?php

namespace App\Livewire\Admin\BeritaAcara;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\BeritaAcaraPemusnahan;
use Illuminate\Support\Facades\Storage;

class UploadBuktiModal extends Component
{
    use WithFileUploads;

    public $file;
    public $path;
    public $beritaAcaraId;

    protected $rules = [
        'file' => 'required|file|max:2048',
    ];

    protected $listeners = ['uploadBukti','deleteFile'];

    public function uploadBukti($id)
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset(['file']);

        $this->beritaAcaraId = $id;
        $beritaAcara = BeritaAcaraPemusnahan::find($id);

        $this->path = $beritaAcara?->file ?? null;
    }

    public function save()
    {
        $this->validate();

        $filename = time() . '-' . uniqid() . '.' . $this->file->getClientOriginalExtension();
        try {
            $path = $this->file->storeAs(
                'uploads/' . (new BeritaAcaraPemusnahan)->getTable() . '/file',
                $filename,
                'public'
            );
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Gagal mengunggah file.']);
            return;
        }

        $beritaAcara = BeritaAcaraPemusnahan::find($this->beritaAcaraId);
        if ($beritaAcara) {
            $beritaAcara->update(['file' => $path]);
            $this->path = $path;
        }

        $this->dispatch('success', 'Bukti pemusnahan berhasil diupload.');
        $this->reset(['file']);
    }

    public function deleteFile($id)
    {
        $data = BeritaAcaraPemusnahan::findOrFail($this->id);
        if(Storage::disk('public')->exists($data->$id))
            Storage::disk('public')->delete($data->$id);
        $data->update([$id => null]);

        $this->dispatch('success', 'File ' . $id . ' Behasil Dihapus!');
    }

    public function render()
    {
        return view('admin.berita-acara.upload-bukti-modal');
    }
}
