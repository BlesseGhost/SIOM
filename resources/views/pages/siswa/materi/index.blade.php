@extends('layouts.main')

@section('title', 'Buat Proposal Kegiatan')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Buat Proposal Kegiatan</h1>
    </div>

    <div class="section-body">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('proposal.generate') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- FORM DATA PROPOSAL -->
            <h5>Data Proposal Kegiatan</h5><hr>
            <div class="row">
                <div class="col-md-6">
                    @foreach ([
                        'nomor_urut' => 'Nomor Urut',
                        'bulan_romawi' => 'Bulan Romawi',
                        'tahun' => 'Tahun',
                        'ormawa' => 'Ormawa',
                        'dari' => 'Dari',
                        'surat_edaran' => 'Surat Edaran',
                        'tanggal_rapat' => 'Tanggal Rapat',
                        'tanggal_surat_edaran' => 'Tanggal Surat Edaran',
                        'nomor_surat_edaran' => 'Nomor Surat Edaran'
                    ] as $name => $label)
                        <div class="form-group mb-2">
                            <label>{{ $label }}</label>
                            <input type="{{ in_array($name, ['tanggal_rapat', 'tanggal_surat_edaran']) ? 'date' : 'text' }}" name="{{ $name }}" class="form-control" required>
                        </div>
                    @endforeach
                </div>
                <div class="col-md-6">
                    @foreach ([
                        'kode_anggaran' => 'Kode Anggaran',
                        'nama_kegiatan' => 'Nama Kegiatan',
                        'tema_kegiatan' => 'Tema Kegiatan',
                        'latar_belakang' => 'Latar Belakang',
                        'tujuan' => 'Tujuan Kegiatan',
                        'ketua_pelaksana' => 'Ketua Pelaksana',
                        'nim_ketua' => 'NIM Ketua',
                        'jumlah_dana' => 'Jumlah Dana (Rp)',
                        'terbilang_dana' => 'Terbilang Dana',
                        'tanggal_surat' => 'Tanggal Surat'
                    ] as $name => $label)
                        <div class="form-group mb-2">
                            <label>{{ $label }}</label>
                            @if(in_array($name, ['latar_belakang', 'tujuan']))
                                <textarea name="{{ $name }}" class="form-control" rows="3" required></textarea>
                            @elseif($name == 'tanggal_surat')
                                <input type="date" name="{{ $name }}" class="form-control" required>
                            @else
                                <input type="text" name="{{ $name }}" class="form-control" required>
                            @endif
                        </div>
                    @endforeach
                    <div class="form-group mb-3">
                        <label>Upload Tanda Tangan Ketua Pelaksana</label>
                        <input type="file" name="ttd_ketua" class="form-control" accept="image/*" required>
                    </div>
                </div>
            </div>

            <!-- FORM ANGGARAN DANA -->
            <h5 class="mt-4">Rincian Anggaran Dana</h5><hr>
            <table class="table table-bordered" id="tabelAnggaran">
                <thead>
                    <tr class="table-primary text-center">
                        <th>Rincian Kebutuhan</th>
                        <th>Volume</th>
                        <th>Satuan</th>
                        <th>Harga Satuan (Rp)</th>
                        <th>Jumlah Total (Rp)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="anggaran[0][uraian]" class="form-control" required></td>
                        <td><input type="number" name="anggaran[0][volume]" class="form-control volume" required></td>
                        <td><input type="text" name="anggaran[0][satuan]" class="form-control" placeholder="pcs / buah / lusin" required></td>
                        <td><input type="number" name="anggaran[0][harga_satuan]" class="form-control harga_satuan" required></td>
                        <td><input type="number" name="anggaran[0][jumlah_total]" class="form-control jumlah_total" readonly></td>
                        <td><button type="button" class="btn btn-danger btn-sm hapusBaris">Hapus</button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-secondary mb-3" id="tambahBaris">+ Tambah Baris</button><br>

            <!-- SUBMIT -->
            <button type="submit" class="btn btn-primary btn-lg">Generate Proposal</button>
        </form>
    </div>
</section>

{{-- Script Tambah Baris Dinamis --}}
<script>
    let index = 1;

    // Tambah Baris Anggaran
    document.getElementById('tambahBaris').addEventListener('click', function () {
        const table = document.querySelector('#tabelAnggaran tbody');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="text" name="anggaran[${index}][uraian]" class="form-control" required></td>
            <td><input type="number" name="anggaran[${index}][volume]" class="form-control volume" required></td>
            <td><input type="text" name="anggaran[${index}][satuan]" class="form-control" placeholder="pcs / buah / lusin" required></td>
            <td><input type="number" name="anggaran[${index}][harga_satuan]" class="form-control harga_satuan" required></td>
            <td><input type="number" name="anggaran[${index}][jumlah_total]" class="form-control jumlah_total" readonly></td>
            <td><button type="button" class="btn btn-danger btn-sm hapusBaris">Hapus</button></td>
        `;
        table.appendChild(row);
        index++;
    });

    // Hapus Baris Anggaran
    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('hapusBaris')) {
            e.target.closest('tr').remove();
        }
    });

    // Hitung Otomatis Jumlah Total
    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('volume') || e.target.classList.contains('harga_satuan')) {
            const row = e.target.closest('tr');
            const volume = parseFloat(row.querySelector('.volume').value) || 0;
            const hargaSatuan = parseFloat(row.querySelector('.harga_satuan').value) || 0;
            row.querySelector('.jumlah_total').value = volume * hargaSatuan;
        }
    });
</script>
@endsection
