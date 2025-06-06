@extends('layouts.app')

@section('title', 'Realisasi KPI')
@section('page_title', 'REALISASI KPI')

@section('styles')
<style>
    /* Main Container */
    .dashboard-content {
        max-width: 1800px;
        margin: 0 auto;
        padding: 0 15px;
    }

    /* Page Header - Modern UI */
    .page-header {
        background: linear-gradient(135deg, var(--pln-blue), var(--pln-light-blue));
        color: white;
        border-radius: 12px;
        padding: 20px 25px;
        margin-bottom: 25px;
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-header h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }

    .page-header-subtitle {
        margin-top: 5px;
        font-weight: 400;
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .page-header-actions {
        display: flex;
        gap: 10px;
    }

    .page-header-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
    }

    .page-header-badge i {
        margin-right: 5px;
    }

    /* Filter Card */
    .filter-card {
        background: var(--pln-surface);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--pln-border);
        box-shadow: 0 8px 20px var(--pln-shadow);
    }

    .filter-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
    }

    .filter-card h5 {
        font-size: 16px;
        font-weight: 600;
        color: var(--pln-text);
        margin-bottom: 15px;
    }

    /* Table Card */
    .table-card {
        background: var(--pln-surface);
        border-radius: 16px;
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--pln-border);
        box-shadow: 0 8px 20px var(--pln-shadow);
    }

    .table-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
    }

    .table-card .card-header {
        background: linear-gradient(135deg, var(--pln-blue), var(--pln-light-blue));
        color: white;
        border: none;
        border-radius: 16px 16px 0 0;
        padding: 15px 20px;
    }

    .table-card .card-body {
        padding: 20px;
    }

    /* Data Table */
    .data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        color: var(--pln-text);
    }

    .data-table th,
    .data-table td {
        padding: 15px;
        border-bottom: 1px solid var(--pln-border);
    }

    .data-table th {
        background-color: var(--pln-accent-bg);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: var(--pln-text);
    }

    .data-table tbody tr:hover {
        background-color: var(--pln-accent-bg);
    }

    /* Progress bar */
    .progress-wrapper {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .progress {
        height: 8px;
        border-radius: 4px;
        background-color: var(--pln-accent-bg);
        overflow: hidden;
    }

    .progress-value {
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Status badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-badge i {
        margin-right: 5px;
    }

    .status-badge.verified {
        background-color: rgba(40, 167, 69, 0.15);
        color: #28a745;
    }

    .status-badge.pending {
        background-color: rgba(255, 193, 7, 0.15);
        color: #ffc107;
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }

    .action-buttons .btn {
        border-radius: 50px;
        padding: 5px 15px;
        font-size: 0.75rem;
        white-space: nowrap;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .filter-group {
            flex-direction: column;
        }
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .page-header-actions {
            width: 100%;
            justify-content: flex-start;
            margin-top: 10px;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Realisasi KPI</h1>

@if(auth()->user()->role === 'master_admin' || auth()->user()->role === 'pic_' . $bidang)
        <a href="{{ route('realisasi.pilihIndikator') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Tambah Realisasi KPI
        </a>
    @endif
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Indikator KPI - {{ strtoupper($bidang) }}</h6>
        <form method="GET" class="form-inline">
            <label class="mr-2">Periode:</label>
            <select name="tahun" class="form-control mr-2" onchange="this.form.submit()">
                @foreach($daftarTahun as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
            <select name="bulan" class="form-control mr-2" onchange="this.form.submit()">
                @foreach($daftarBulan as $key => $namaBulan)
                    <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>{{ $namaBulan }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Indikator</th>
                        <th>Satuan</th>
                        <th>Target</th>
                        <th>Capaian</th>
                        <th>Persentase</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($indikators as $index => $indikator)
                        @php
                            $realisasi = $indikator->realisasi()
                                ->where('tahun', $tahun)
                                ->where('bulan', $bulan)
                                ->where('periode_tipe', $periodeTipe)
                                ->first();
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $indikator->nama }}</td>
                            <td>{{ $indikator->satuan }}</td>
                            <td>{{ $indikator->target }}</td>
                            <td>{{ $realisasi->capaian ?? '-' }}</td>
                            <td>{{ $realisasi ? number_format($realisasi->persentase, 2) . '%' : '-' }}</td>
                            <td>
                                @if($realisasi)
                                    @if($realisasi->status == 'diverifikasi')
                                        <span class="badge badge-success">Diverifikasi</span>
                                    @elseif($realisasi->status == 'ditolak')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-warning">Menunggu</span>
                                    @endif
                                @else
                                    <span class="badge badge-secondary">Belum Diinput</span>
                                @endif
                            </td>
                            <td>
                                @if($realisasi)
                                    <a href="{{ route('realisasi.show', $realisasi->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($realisasi->status != 'diverifikasi')
                                        <a href="{{ route('realisasi.edit', $realisasi->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('realisasi.create', [
                                        'indikator_id' => $indikator->id,
                                        'tahun' => $tahun,
                                        'bulan' => $bulan,
                                        'periode_tipe' => $periodeTipe
                                    ]) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus"></i> Input Data
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada indikator KPI yang tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
    $(document).ready(function () {
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });
</script>
@endsection
