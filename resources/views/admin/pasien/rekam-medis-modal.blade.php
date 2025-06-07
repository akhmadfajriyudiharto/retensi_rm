<div class="modal fade" id="modalRekamMedis" tabindex="-1" aria-hidden="true" wire:ignore.self>
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
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-6">
            <h4 class="mb-2">Riwayat Rekam Medis</h4>
        </div>
        <div class="container mb-5">
            <div class="row mb-2">
                <div class="col-md-2 fw-bold">No Rekam Medis</div>
                <div class="col-md-4">: {{$pasien?->no_rm}}</div>
                <div class="col-md-2 fw-bold">Nama</div>
                <div class="col-md-4">: {{$pasien?->nama}}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-2 fw-bold">NIK</div>
                <div class="col-md-4">: {{$pasien?->nik}}</div>
                <div class="col-md-2 fw-bold">Jenis Kelamin</div>
                <div class="col-md-4">: {{$pasien?->jenis_kelamin}}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-2 fw-bold">Tanggal Lahir</div>
                <div class="col-md-4">: {{$pasien?->tanggal_lahir}}</div>
                <div class="col-md-2 fw-bold">Alamat</div>
                <div class="col-md-4">: {{$pasien?->alamat}}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-2 fw-bold">Telepon</div>
                <div class="col-md-4">: {{$pasien?->telepon}}</div>
                <div class="col-md-2 fw-bold">Email</div>
                <div class="col-md-4">: {{$pasien?->email}}</div>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
            <thead>
                <tr>
                    <th>Tgl. Kunjungan</th>
                    <th>Kasus</th>
                    <th>Dokter</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @if ($rekam_medis)
                @foreach ($rekam_medis as $item)
                    <tr>
                        <td>{{$item['tanggal_kunjungan']}}</td>
                        <td>{{$item['kasus']}}</td>
                        <td>{{$item['dokter']}}</td>
                        <td>{{$item['status']}}</td>
                    </tr>
                @endforeach
                @endif
            </tbody>
            </table>
        </div>
    </div>
    </div>
</div>
