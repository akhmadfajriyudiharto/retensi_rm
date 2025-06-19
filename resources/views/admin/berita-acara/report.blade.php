<x-report.report :title="$title" format="html">
    @section('content')
    @php
        $tanggal = \Carbon\Carbon::parse($beritaAcara->tanggal)->locale('id');
    @endphp
    <div class="mt-5 mb-3" style="text-align: justify;">
        Pada hari ini {{ $tanggal->translatedFormat('l') }} tanggal {{ $tanggal->format('d') }}
        bulan {{ $tanggal->translatedFormat('F') }} tahun {{ $tanggal->format('Y') }} sesuai dengan
        Peraturan Penteri Kesehatan Republik Indonesia Nomor 269/MENKES/PER/III/2008 tentang Rekam
        Medis, kami yang bertanda tangan di bawah ini:
        <table class="mt-3" width="100%">
            <tr>
                <td width="30%">Nama Ketua Komite Rekam Medis</td>
                <td>: {{$beritaAcara->nama_ketua}}</td>
            </tr>
            <tr>
                <td width="30%">NIK</td>
                <td>: {{$beritaAcara->nik_ketua}}</td>
            </tr>
            <tr>
                <td width="30%">Alamat</td>
                <td>: {{$beritaAcara->alamat_ketua}}</td>
            </tr>
        </table>
        <div class="mt-3">
            Dengan disaksikan oleh:
        </div>
        <table width="100%">
            @foreach ($beritaAcara->saksi as $key => $item)
            <tr>
                <td width="3%"></td>
                <td width="3%">{{$key+1}}.</td>
                <td width="15%">Nama</td>
                <td>: {{$item->nama}}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>NIK</td>
                <td>: {{$item->nik}}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>Alamat</td>
                <td>: {{$item->alamat}}</td>
            </tr>
            @endforeach
        </table>
        <div class="mt-3">
            Telah melakukan pemusnahan berkas rekam medis sebagaimana tercantum dalam daftar terlampir.
            Tempat dilakukan pemusnahan: {{$beritaAcara->tempat_pemusnahan}}, {{$beritaAcara->alamat_pemusnahan}}
        </div>
        <div class="mt-3">
            Demikian berita acara ini kami buat sesungguhnya dengan penuh tanggung jawab.
        </div>

        <table class="mt-5" width="100%">
            <tr>
                <td width=35%>{{$beritaAcara->kota_pemusnahan}}, {{$tanggal->translatedFormat('d F Y')}}</td>
                <td width=30%></td>
                <td width=35%></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">Saksi-saksi</td>
                <td></td>
                <td style="text-align: center;">Yang menerima berita acara</td>
            </tr>
            @foreach ($beritaAcara->saksi as $key => $item)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>{{$key+1}}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="text-align: center;">{{$item->nama}}</td>
                    <td></td>
                    <td style="text-align: center;">{{$key == 0 ? $beritaAcara->nama_ketua : ''}}</td>
                </tr>
            @endforeach
        </table>
    </div>
    <div style="page-break-before: always;">
        @if (!isset($format) || $format == 'html')
            <x-report.header-report></x-report.header-report>
        @endif
        <u><h5 style="font-size: 13pt;">Lampiran: Daftar Rekam Medis yang Dimusnahkan</h5></u>

        <table class="mt-5" width="100%" cellspacing="0" cellpadding="5"
           style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px;">

        <thead>
            <tr>
                <th style="border: 1px solid #000;">No</th>
                <th style="border: 1px solid #000;">No. RM</th>
                <th style="border: 1px solid #000;">Nama Pasien</th>
                <th style="border: 1px solid #000;">Tanggal Kunjungan</th>
                <th style="border: 1px solid #000;">Dokter</th>
                <th style="border: 1px solid #000;">Layanan</th>
                <th style="border: 1px solid #000;">Diagnosa</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($beritaAcara->rekamMedis as $index => $rekamMedis)
                <tr>
                    <td style="border: 1px solid #000;">{{ $index + 1 }}</td>
                    <td style="border: 1px solid #000;">{{ $rekamMedis->pasien->no_rm }}</td>
                    <td style="border: 1px solid #000;">{{ $rekamMedis->pasien->nama }}</td>
                    <td style="border: 1px solid #000;">
                        {{ \Carbon\Carbon::parse($rekamMedis->tanggal_kunjungan)->translatedFormat('d F Y') }}
                    </td>
                    <td style="border: 1px solid #000;">{{ $rekamMedis->dokter->nama ?? '-' }}</td>
                    <td style="border: 1px solid #000;">{{ $rekamMedis->layanan->nama ?? '-' }}</td>
                    <td style="border: 1px solid #000;">{{ $rekamMedis->diagnosa ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>


    @endsection
</x-report.report>
