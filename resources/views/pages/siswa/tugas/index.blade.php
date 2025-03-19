@extends('layouts.main')

@section('title', 'Proposal Saya')

@section('content')
<div class="container mt-4">
    <br>
    <h3>Proposal yang Saya Ajukan</h3>

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Judul Kegiatan</th>
                <th>Status</th>
                <th>Proposal</th>
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
                        @elseif ($proposal->status == 'revisi_kemahasiswaan')
                            <span class="badge bg-danger">Revisi Kemahasiswaan</span>
                        @elseif ($proposal->status == 'acc')
                            <span class="badge bg-success">Disetujui</span>
                        @endif
                    </td>
                    
                    <td class="text-center">
                        <a href="{{ route('proposal.download', $proposal->id) }}" class="btn btn-primary btn-sm" download>
                            Download
                        </a>
                    </td>

                    <td>
                        @if (!in_array($proposal->status, ['revisi_pembina', 'revisi_kemahasiswaan']))
                            @if ($proposal->file_revisi)
                                <a href="{{ asset($proposal->file_revisi) }}" class="btn btn-warning btn-sm" target="_blank" download>Download Revisi</a>
                            @else
                                <span class="text-muted">Belum Ada</span>
                            @endif
                        @else
                            <span class="text-muted">-</span> {{-- Tidak ditampilkan jika revisi pembina/kemahasiswaan --}}
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
                        @if (!in_array($proposal->status, ['revisi_pembina', 'revisi_kemahasiswaan']))
                            <div class="d-flex gap-2">
                                <!-- Tombol Hapus Proposal -->
                                <form action="{{ route('proposal.hapus', $proposal->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proposal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>

                                <!-- Tombol Ajukan Ulang (Modal) jika status revisi -->
                                @if ($proposal->status == 'revisi_admin')
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalResubmit{{ $proposal->id }}">
                                        Ulang
                                    </button>
                                @endif
                            </div>
                        @else
                            <span class="text-muted">-</span> {{-- Tidak ada aksi untuk revisi pembina/kemahasiswaan --}}
                        @endif
                    </td>
                </tr>

                <!-- Modal Ajukan Ulang -->
                <div class="modal fade" id="modalResubmit{{ $proposal->id }}" tabindex="-1" aria-labelledby="modalResubmitLabel{{ $proposal->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('proposal.resubmit', $proposal->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalResubmitLabel{{ $proposal->id }}">Ajukan Ulang Proposal</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="filePerbaikan{{ $proposal->id }}" class="form-label">Upload File Perbaikan</label>
                                        <input type="file" name="file_perbaikan" id="filePerbaikan{{ $proposal->id }}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success">Kirim Proposal Ulang</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Modal -->
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada proposal yang diajukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
