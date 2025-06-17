<div class="modal fade" id="modalPilihRM" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Rekam Medis untuk Dimusnahkan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <input type="text" wire:change="searchChanged($event.target.value)" class="form-control mb-3" placeholder="Cari pasien...">

                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            @if ($beritaAcara?->status == 'proses')
                            <th>
                                <input type="checkbox" wire:click="toggleAll"
                                    {{ count($selectedRekamMedis) === $rekamMedisList->count() ? 'checked' : '' }}>
                            </th>
                            @endif
                            <th wire:click="sortBy('pasien_id')" style="cursor:pointer">Pasien</th>
                            <th wire:click="sortBy('layanan_id')" style="cursor:pointer">Layanan</th>
                            <th wire:click="sortBy('kasus_id')" style="cursor:pointer">Kasus</th>
                            <th wire:click="sortBy('dokter_id')" style="cursor:pointer">Dokter</th>
                            <th wire:click="sortBy('tanggal_kunjungan')" style="cursor:pointer">Kunjungan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rekamMedisList as $rm)
                            <tr>
                                @if ($beritaAcara?->status == 'proses')
                                <td>
                                    <input type="checkbox" wire:click="toggleItem({{ $rm->id }})"
                                        {{ in_array($rm->id, $selectedRekamMedis) ? 'checked' : '' }}>
                                </td>
                                @endif
                                <td>{{ $rm->pasien->no_rm }} <br/> {{ $rm->pasien->nama }}</td>
                                <td>{{ $rm->layanan->nama ?? '-' }}</td>
                                <td>{{ $rm->kasus->nama ?? '-' }}</td>
                                <td>{{ $rm->dokter->nama ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($rm->tanggal_kunjungan)->translatedFormat('d F Y') }}</td>
                                <td>
                                    @php
                                    $status = $rm->latestRetensi->status;
                                    if (in_array($status, [\App\Models\RetensiRecord::STATUS_AKTIF, \App\Models\RetensiRecord::STATUS_INAKTIF])) {
                                        $tanggalBatas = $status === \App\Models\RetensiRecord::STATUS_AKTIF
                                            ? $rm->batas_aktif
                                            : $rm->batas_inaktif;

                                        if ($tanggalBatas) {
                                            $tanggalBatas = \Carbon\Carbon::parse($tanggalBatas);
                                            if ($tanggalBatas->isPast()) {
                                                $status = $status === \App\Models\RetensiRecord::STATUS_AKTIF
                                                    ? \App\Models\RetensiRecord::STATUS_BELUM_INAKTIF
                                                    : \App\Models\RetensiRecord::STATUS_BELUM_MUSNAH;
                                            }
                                        }
                                    }
                                    echo '<span class="badge bg-' . \App\Models\RetensiRecord::getStatusBadge($status) . '"  style="white-space: normal;">' . \App\Models\RetensiRecord::getStatus($status) . '</span>';
                                    @endphp
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">Tidak ada data rekam medis ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($beritaAcara?->status == 'proses')
                <div class="mt-3">
                    <button class="btn btn-primary" wire:click="simpanPilihan">Simpan</button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
