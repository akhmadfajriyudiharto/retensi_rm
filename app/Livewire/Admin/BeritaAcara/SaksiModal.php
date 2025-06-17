<?php

namespace App\Livewire\Admin\BeritaAcara;

use App\Models\BeritaAcaraPemusnahan;
use App\Models\SaksiPemusnahan;
use Livewire\Component;

class SaksiModal extends Component
{
    public $beritaAcara;
    public $saksies = [];

    public $saksi_id;
    public $nik, $nip, $nama, $jabatan, $alamat;

    protected $listeners = ['editSaksi','deleteSaksi','kunciBA','bukaBA'];

    protected function rules()
    {
        return [
            'nik' => 'required|digits_between:16,17',
            'nip' => 'nullable|digits_between:18,18',
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:50',
            'alamat' => 'required|string|max:255',
        ];
    }

    public function editSaksi($id)
    {
        $this->resetInput();
        $this->beritaAcara = BeritaAcaraPemusnahan::find($id);
        $this->loadSaksies();
    }

    public function loadSaksies()
    {
        $this->saksies = SaksiPemusnahan::where('berita_acara_pemusnahan_id', $this->beritaAcara->id)->get();
    }

    public function resetInput()
    {
        $this->reset(['saksi_id', 'nik', 'nip', 'nama', 'jabatan', 'alamat']);
    }

    public function edit($id)
    {
        $saksi = SaksiPemusnahan::findOrFail($id);
        $this->saksi_id = $saksi->id;
        $this->nik = $saksi->nik;
        $this->nip = $saksi->nip;
        $this->nama = $saksi->nama;
        $this->jabatan = $saksi->jabatan;
        $this->alamat = $saksi->alamat;
    }

    public function save()
    {
        $this->validate();

        if ($this->saksi_id) {
            $saksi = SaksiPemusnahan::findOrFail($this->saksi_id);
        } else {
            $saksi = new SaksiPemusnahan();
            $saksi->berita_acara_pemusnahan_id = $this->beritaAcara->id;
        }

        $saksi->nik = $this->nik;
        $saksi->nip = $this->nip;
        $saksi->nama = $this->nama;
        $saksi->jabatan = $this->jabatan;
        $saksi->alamat = $this->alamat;
        $saksi->save();

        $this->dispatch('success', $this->saksi_id ? 'Saksi diperbarui.' : 'Saksi ditambahkan.');
        $this->resetInput();
        $this->loadSaksies();
    }

    public function deleteSaksi($id)
    {
        $saksi = SaksiPemusnahan::findOrFail($id);
        $saksi->delete();

        $this->dispatch('success', 'Saksi berhasil dihapus.');
        $this->loadSaksies();
    }

    public function kunciBA($id)
    {
        $beritaAcara = BeritaAcaraPemusnahan::findOrFail($id);
        if ($beritaAcara->status !== 'proses') {
            $this->dispatch('error', 'Hanya bisa mengunci berita acara yang masih dalam status proses.');
            return;
        }

        // Cek apakah ada saksi
        if ($beritaAcara->saksi->isEmpty()) {
            $this->dispatch('error', 'Berita acara belum memiliki saksi.');
            return;
        }

        // Cek apakah ada rekam medis
        if ($beritaAcara->rekamMedis->isEmpty()) {
            $this->dispatch('error', 'Berita acara belum memiliki data rekam medis yang dipilih.');
            return;
        }

        $beritaAcara->status = 'dikunci';
        $beritaAcara->save();

        $this->dispatch('success', 'Berhasil mengunci berita acara.');
    }

    public function bukaBA($id)
    {
        $beritaAcara = BeritaAcaraPemusnahan::findOrFail($id);
        if ($beritaAcara->status !== 'dikunci') {
            $this->dispatch('error', 'Hanya bisa mengunci berita acara yang masih dalam status dikunci.');
            return;
        }

        $beritaAcara->status = 'proses';
        $beritaAcara->save();

        $this->dispatch('success', 'Berhasil membuka kunci berita acara.');
    }

    public function render()
    {
        return view('admin.berita-acara.saksi-modal');
    }
}
