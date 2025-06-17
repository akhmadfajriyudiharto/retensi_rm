<div class="modal fade" id="modalSaksi" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-xl modal-simple">
        <div class="modal-content">
            <div class="modal-body">
                <div x-data="{ message: '', shown: false, timeout: null }"
                     x-init="@this.on('success', msg => {
                         clearTimeout(timeout);
                         message = msg;
                         shown = true;
                         timeout = setTimeout(() => { shown = false }, 3000);
                     })"
                     x-show="shown"
                     class="alert alert-success"
                     style="display: none;">
                    <span x-text="message"></span>
                </div>

                <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
                <h4 class="text-center mb-4">Pengaturan Saksi</h4>

                @if($beritaAcara)
                <div class="mb-4">
                    <strong>Berita Acara:</strong> {{ $beritaAcara->nama_ketua }} - {{ $beritaAcara->tanggal }}
                </div>

                <!-- Form Input -->
                @if ($beritaAcara->status == 'proses')
                <form wire:submit.prevent="save">
                    <div class="row">
                        <div class="col-md-3">
                            <label>NIK</label>
                            <input wire:model="nik" class="form-control">
                            @error('nik') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-3">
                            <label>NIP</label>
                            <input wire:model="nip" class="form-control">
                            @error('nip') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-3">
                            <label>Nama</label>
                            <input wire:model="nama" class="form-control">
                            @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-3">
                            <label>Jabatan</label>
                            <input wire:model="jabatan" class="form-control">
                            @error('jabatan') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-12 mt-2">
                            <label>Alamat</label>
                            <textarea wire:model="alamat" class="form-control"></textarea>
                            @error('alamat') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
                @endif

                <!-- Tabel -->
                <h5 class="mt-4">Daftar Saksi</h5>
                <table class="table table-bordered mt-2">
                    <thead>
                        <tr>
                            <th>NIK</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Alamat</th>
                            @if ($beritaAcara->status == 'proses')
                            <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($saksies as $saksi)
                            <tr>
                                <td>{{ $saksi->nik }}</td>
                                <td>{{ $saksi->nip }}</td>
                                <td>{{ $saksi->nama }}</td>
                                <td>{{ $saksi->jabatan }}</td>
                                <td>{{ $saksi->alamat }}</td>
                                @if ($beritaAcara->status == 'proses')
                                <td>
                                    <button wire:click="edit({{ $saksi->id }})" class="btn btn-sm btn-warning"><i class="tf-icons ti ti-edit scaleX-n1-rtl ti-xs"></i></button>
                                    <button class="btn btn-sm btn-danger" data-action="delete_saksi" data-id="{{ $saksi->id }}">
                                        <i class="tf-icons ti ti-trash scaleX-n1-rtl ti-xs"></i>
                                    </button>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">Belum ada saksi</td></tr>
                        @endforelse
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>

