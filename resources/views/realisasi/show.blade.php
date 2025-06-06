@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detail Realisasi</h1>

    <div class="mb-3">
        <strong>Indikator:</strong> {{ $realisasi->indikator->nama }}
    </div>

    <div class="mb-3">
        <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($realisasi->tanggal)->format('d-m-Y') }}
    </div>

    <div class="mb-3">
        <strong>Nilai:</strong> {{ $realisasi->nilai }}
    </div>

    <div class="mb-3">
        <strong>Persentase:</strong> {{ $realisasi->persentase ?? '-' }}
    </div>

    <div class="mb-3">
        <strong>Keterangan:</strong> {{ $realisasi->keterangan ?? '-' }}
    </div>

    <div class="mb-3">
        <strong>Diinput oleh:</strong> {{ $realisasi->user->name }}
    </div>

    <a href="{{ route('realisasi.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
