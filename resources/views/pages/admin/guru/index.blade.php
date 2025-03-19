@extends('layouts.main')

@section('title', 'Pengaturan Dana Proposal')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Pengaturan Dana Proposal</h1>
    </div>

    <div class="section-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="card-header">
                <h4>Daftar Proposal yang Disetujui (ACC)</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>Judul Kegiatan</th>
                                <th>Total Dana (Rp)</th>
                                <th>Sisa Dana (Rp)</th>
                                <th>Pengusul</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($proposals as $proposal)
                                <tr>
                                    <td>{{ $proposal->judul_kegiatan }}</td>
                                    <td class="text-end">Rp {{ number_format($proposal->total_dana, 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        <b>Rp {{ number_format($proposal->sisa_dana, 0, ',', '.') }}</b>
                                    </td>
                                    <td>{{ $proposal->user->name ?? '-' }}</td>
                                    <td class="text-center">
                                        <!-- Tombol Modal Edit -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalDana{{ $proposal->id }}">
                                            Edit Dana
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal Edit Dana -->
                                <div class="modal fade" id="modalDana{{ $proposal->id }}" tabindex="-1" aria-labelledby="modalDanaLabel{{ $proposal->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.updateDana', $proposal->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalDanaLabel{{ $proposal->id }}">Atur Sisa Dana</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label>Total Dana (Rp)</label>
                                                        <input type="text" value="Rp {{ number_format($proposal->total_dana, 0, ',', '.') }}" class="form-control" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Input Sisa Dana (Rp)</label>
                                                        <input type="number" name="sisa_dana" value="{{ $proposal->sisa_dana }}" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal -->
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada proposal yang disetujui.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
