@extends('layouts.app')

@section('title', 'Edit Realisasi')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Realisasi KPI</h1>

    <form action="{{ route('realisasi.update', $realisasi) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="indikator_id" class="form-label">Indikator</label>
            <select name="indikator_id" id="indikator_id" class="form-select" required>
                @foreach ($indikators as $indikator)
                    <option value="{{ $indikator->id }}" {{ $realisasi->indikator_id == $indikator->id ? 'selected' : '' }}>
                        {{ $indikator->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $realisasi->tanggal->format('Y-m-d') }}" required>
        </div>

        <div class="mb-3">
            <label for="nilai" class="form-label">Nilai</label>
            <input type="number" name="nilai" id="nilai" class="form-control" value="{{ $realisasi->nilai }}" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="persentase" class="form-label">Persentase</label>
            <input type="number" name="persentase" id="persentase" class="form-control" value="{{ $realisasi->persentase }}" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control" rows="3">{{ $realisasi->keterangan }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('realisasi.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
