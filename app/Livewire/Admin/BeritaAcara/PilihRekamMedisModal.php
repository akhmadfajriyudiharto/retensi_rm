<?php

namespace App\Livewire\Admin\BeritaAcara;

use App\Models\BeritaAcaraPemusnahan;
use App\Models\BeritaAcaraRekamMedis;
use App\Models\RekamMedis;
use App\Models\RetensiRecord;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PilihRekamMedisModal extends Component
{
    public $beritaAcaraId;
    public $beritaAcara;
    public $search = '';
    public $sortField = 'tanggal_kunjungan';
    public $sortDirection = 'asc';
    public $selectedRekamMedis = [];

    protected $listeners = ['bukaModalPilihRM','musnahkan'];

    public function bukaModalPilihRM($id)
    {
        $this->beritaAcaraId = $id;
        $this->beritaAcara = BeritaAcaraPemusnahan::find($id);
        $this->selectedRekamMedis = BeritaAcaraRekamMedis::where('berita_acara_pemusnahan_id', $id)
                                    ->pluck('rekam_medis_id')
                                    ->toArray();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function toggleAll()
    {
        if (count($this->selectedRekamMedis) === $this->getRekamMedis()->count()) {
            $this->selectedRekamMedis = [];
        } else {
            $this->selectedRekamMedis = $this->getRekamMedis()->pluck('id')->toArray();
        }
    }

    public function toggleItem($id)
    {
        if (in_array($id, $this->selectedRekamMedis)) {
            $this->selectedRekamMedis = array_diff($this->selectedRekamMedis, [$id]);
        } else {
            $this->selectedRekamMedis[] = $id;
        }
    }

    public function rekamMedisYangSudahDipilihDiBeritaAcaraLain()
    {
        return DB::table('berita_acara_rekam_medis')
            ->where('berita_acara_pemusnahan_id', '!=', $this->beritaAcaraId)
            ->pluck('rekam_medis_id')
            ->toArray();
    }

    public function getRekamMedis()
    {
        if ($this->beritaAcara?->status !== 'proses') {
            return RekamMedis::with(['pasien', 'layanan', 'kasus', 'dokter', 'latestRetensi'])
                ->whereHas('pasien', function ($q) {
                    $q->where('nama', 'ilike', '%' . $this->search . '%')->orWhere('no_rm', 'ilike', '%' . $this->search . '%');
                })
                ->whereIn('id', $this->selectedRekamMedis)
                ->orderBy($this->sortField, $this->sortDirection)
                ->get();
        }

        return RekamMedis::with(['pasien', 'layanan', 'kasus', 'dokter', 'latestRetensi'])
            ->whereHas('pasien', function ($q) {
                $q->where('nama', 'ilike', '%' . $this->search . '%')->orWhere('no_rm', 'ilike', '%' . $this->search . '%');
            })
            ->whereHas('latestRetensi', function ($q) {
                $q->where(function ($sub) {
                    $sub->where(function ($q1) {
                        $q1->where('status', RetensiRecord::STATUS_INAKTIF)
                            ->whereDate('rekam_medis.batas_inaktif', '<', now());
                    });
                });
            })
            ->whereNotIn('id', $this->rekamMedisYangSudahDipilihDiBeritaAcaraLain())
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();
    }

    public function searchChanged($value)
    {
        $this->search = $value;
    }

    public function simpanPilihan()
    {
        // Ambil semua RM yang sebelumnya sudah tersimpan untuk berita acara ini
        $existing = BeritaAcaraRekamMedis::where('berita_acara_pemusnahan_id', $this->beritaAcaraId)
            ->pluck('rekam_medis_id')
            ->toArray();

        // Data yang baru ditambahkan
        $toAdd = array_diff($this->selectedRekamMedis, $existing);

        // Data yang dihapus (tidak dicentang lagi)
        $toDelete = array_diff($existing, $this->selectedRekamMedis);

        // Tambahkan yang baru
        foreach ($toAdd as $rekamMedisId) {
            BeritaAcaraRekamMedis::create([
                'rekam_medis_id' => $rekamMedisId,
                'berita_acara_pemusnahan_id' => $this->beritaAcaraId,
            ]);
        }

        // Hapus yang tidak dicentang lagi
        if (!empty($toDelete)) {
            BeritaAcaraRekamMedis::where('berita_acara_pemusnahan_id', $this->beritaAcaraId)
                ->whereIn('rekam_medis_id', $toDelete)
                ->delete();
        }

        $this->dispatch('success', 'Data rekam medis berhasil disimpan.');
        $this->dispatch('closeModal');
    }

    public function musnahkan($id)
    {
        $beritaAcara = BeritaAcaraPemusnahan::findOrFail($id);

        // Ambil semua ID rekam medis yang terkait dengan berita acara ini
        $selectedRekamMedis = BeritaAcaraRekamMedis::where('berita_acara_pemusnahan_id', $id)
                                    ->pluck('rekam_medis_id')
                                    ->toArray();

        // Validasi jika tidak ada yang dipilih
        if (empty($selectedRekamMedis)) {
            $this->dispatch('error', 'Tidak ada rekam medis yang dipilih untuk dimusnahkan.');
            return;
        }

        DB::beginTransaction();
        try {
            foreach ($selectedRekamMedis as $rekamMedisId) {
                // Hindari duplikat jika sudah ada status MUSNAH sebelumnya
                $sudahMusnah = RetensiRecord::where('rekam_medis_id', $rekamMedisId)
                    ->where('status', RetensiRecord::STATUS_MUSNAH)
                    ->exists();

                if (!$sudahMusnah) {
                    RetensiRecord::create([
                        'rekam_medis_id' => $rekamMedisId,
                        'status' => RetensiRecord::STATUS_MUSNAH,
                        'tanggal' => now(),
                    ]);
                }
            }

            // Ubah status berita acara menjadi "dilaksanakan"
            $beritaAcara->update(['status' => 'dilaksanakan']);

            DB::commit();
            $this->dispatch('success', 'Semua rekam medis berhasil dimusnahkan.');
            $this->dispatch('closeModal');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', 'Gagal memusnahkan rekam medis: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('admin.berita-acara.pilih-rekam-medis-modal', [
            'rekamMedisList' => $this->getRekamMedis()
        ]);
    }
}
