@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Realisasi</h1>

    <form action="{{ route('realisasi.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="indikator_id" class="form-label">Indikator</label>
            <select name="indikator_id" class="form-select" required>
                @foreach ($indikator as $i)
                    <option value="{{ $i->id }}">{{ $i->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="nilai" class="form-label">Nilai</label>
            <input type="number" step="0.01" name="nilai" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="persentase" class="form-label">Persentase</label>
            <input type="number" step="0.01" name="persentase" class="form-control">
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('realisasi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
