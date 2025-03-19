@extends('layouts.main')

@section('title', 'Proposal Masuk')

@section('content')
<div class="container mt-4">
    <br>
    <h3>Proposal Masuk</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Judul Kegiatan</th>
                <th>Proposal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($proposals as $proposal)
                <tr>
                    <td>{{ $proposal->judul_kegiatan }}</td>
                    <td>
                        <a href="{{ asset($proposal->file_proposal) }}" class="btn btn-primary btn-sm" download>Download Proposal</a>
                    </td>
                    <td>
                        <form action="{{ route('pembina.proposal.acc', $proposal->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">ACC</button>
                        </form>
                        <a href="{{ route('pembina.proposal.revisi.form', $proposal->id) }}" class="btn btn-danger btn-sm">Revisi</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Belum ada proposal masuk.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
