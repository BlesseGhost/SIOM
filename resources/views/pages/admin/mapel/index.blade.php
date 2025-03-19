@extends('layouts.main')

@section('title', 'Daftar Proposal Masuk')

@section('content')
<div class="container mt-4"> <br>
    <h3>Daftar Proposal Menunggu Persetujuan Admin</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Judul Kegiatan</th>
                <th>Status</th>
                <th>File Proposal</th>
                <th>File Revisi (Jika Ada)</th>
                <th>Catatan Revisi (Jika Ada)</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($proposals as $proposal)
                <tr>
                    <td>{{ $proposal->judul_kegiatan }}</td>
                    <td>
                        @if ($proposal->status == 'pending_admin')
                            <span class="badge bg-warning text-dark">Menunggu Admin</span>
                        @elseif ($proposal->status == 'pending_pembina')
                            <span class="badge bg-info text-dark">Diproses Pembina</span>
                        @elseif ($proposal->status == 'pending_kemahasiswaan')
                            <span class="badge bg-primary">Diproses Kemahasiswaan</span>
                        @elseif ($proposal->status == 'revisi_admin')
                            <span class="badge bg-danger">Perlu Revisi</span>
                        @elseif ($proposal->status == 'revisi_pembina')
                            <span class="badge bg-danger">Revisi Pembina</span>
                        @elseif ($proposal->status == 'acc')
                            <span class="badge bg-success">Disetujui</span>
                        @endif
                    </td>
                    <td>
    <a href="{{ route('proposal.download', $proposal->id) }}" class="btn btn-primary text-nowrap">
        Download Proposal
    </a>
</td>
                    <td>
                        @if ($proposal->file_revisi)
                            <a href="{{ asset($proposal->file_revisi) }}" class="btn btn-warning btn-sm text-nowrap" target="_blank" download>Download Revisi</a>
                        @else
                            <span class="text-muted">Belum Ada</span>
                        @endif
                    </td>
                    <td>
                        @if ($proposal->catatan_revisi)
                            {{ $proposal->catatan_revisi }}
                        @else
                            <span class="text-muted">Tidak Ada</span>
                        @endif
                    </td>
                    <td>
    <div class="d-flex gap-2">
        <!-- Tombol ACC -->
        <a href="{{ route('proposal.acc.admin', $proposal->id) }}" 
           class="btn btn-success btn-sm me-2"
           onclick="return confirm('Yakin ingin ACC proposal ini?');">
            ACC
        </a>

        <!-- Tombol Revisi untuk buka modal -->
        <button type="button" 
                class="btn btn-warning btn-sm me-2"
                data-bs-toggle="modal" 
                data-bs-target="#modalRevisi{{ $proposal->id }}">
            Revisi
        </button>

        <!-- Tombol Hapus -->
        <form action="{{ route('proposal.hapus', $proposal->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proposal ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
        </form>
    </div>
</td>

                </tr>

                <!-- Modal Revisi -->
<div class="modal fade" id="modalRevisi{{ $proposal->id }}" tabindex="-1" aria-labelledby="modalRevisiLabel{{ $proposal->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('proposal.revisi.admin', $proposal->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRevisiLabel{{ $proposal->id }}">Kirim Revisi Proposal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="catatanRevisi{{ $proposal->id }}" class="form-label">Catatan Revisi</label>
                        <textarea name="catatan_revisi" id="catatanRevisi{{ $proposal->id }}" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="fileRevisi{{ $proposal->id }}" class="form-label">Upload File Revisi</label>
                        <input type="file" name="file_revisi" id="fileRevisi{{ $proposal->id }}" class="form-control" accept=".docx,.pdf" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Kirim Revisi</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal -->
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada proposal masuk.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
