@extends('layouts.main')

@section('title', 'Revisi Proposal')

@section('content')
<div class="container mt-4">
    <br>
    <h3>Revisi Proposal: {{ $proposal->judul_kegiatan }}</h3>

    <form action="{{ route('pembina.proposal.revisi', $proposal->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Upload File Revisi</label>
            <input type="file" name="file_revisi" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Catatan Revisi</label>
            <textarea name="catatan_revisi" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-danger mt-3">Kirim Revisi ke Admin</button>
    </form>
</div>
@endsection
