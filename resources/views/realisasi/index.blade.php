@extends('layouts.app')

@section('title', 'Realisasi KPI')
@section('page_title', 'REALISASI KPI')

@section('styles')
<style>
/* --- Styles mirip sebelumnya --- */
.dashboard-content {
    max-width: 1800px;
    margin: 0 auto;
    padding: 0 15px;
}
.page-header {
    background: linear-gradient(135deg, var(--pln-blue), var(--pln-light-blue));
    color: white;
    border-radius: 12px;
    padding: 20px 25px;
    margin-bottom: 25px;
    box-shadow: 0 5px 15px rgba(0,123,255,0.2);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.page-header h2 { font-size: 1.5rem; font-weight: 600; margin: 0; }
.page-header-subtitle { margin-top: 5px; font-weight: 400; font-size: 0.9rem; opacity: 0.9; }
.page-header-actions { display: flex; gap: 10px; }
.page-header-badge {
    background: rgba(255,255,255,0.2);
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 0.8rem;
    display: flex; align-items: center;
}
.page-header-badge i { margin-right: 5px; }
.filter-card, .table-card {
    background: var(--pln-surface);
    border-radius: 16px;
    margin-bottom: 25px;
    position: relative;
    overflow: hidden;
    border: 1px solid var(--pln-border);
    box-shadow: 0 8px 20px var(--pln-shadow);
    padding: 20px;
}
.filter-card::before, .table-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 4px;
    background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
}
.table-card .card-header {
    background: linear-gradient(135deg, var(--pln-blue), var(--pln-light-blue));
    color: white;
    border: none;
    border-radius: 16px 16px 0 0;
    padding: 15px 20px;
    margin: -20px -20px 15px -20px;
    font-weight: 600;
    font-size: 1rem;
}
.data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    color: var(--pln-text);
}
.data-table th, .data-table td {
    padding: 15px;
    border-bottom: 1px solid var(--pln-border);
    vertical-align: middle;
}
.data-table th {
    background-color: var(--pln-accent-bg);
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
}
.data-table tbody tr:hover {
    background-color: var(--pln-accent-bg);
}
.progress-wrapper { display: flex; flex-direction: column; gap: 5px; }
.progress {
    height: 8px;
    border-radius: 4px;
    background-color: var(--pln-accent-bg);
    overflow: hidden;
}
.progress-bar.bg-danger { background-color: #dc3545; }
.progress-bar.bg-warning { background-color: #ffc107; }
.progress-bar.bg-success { background-color: #28a745; }
.progress-value {
    font-weight: 600;
    font-size: 0.9rem;
}
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 10px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
}
.status-badge i { margin-right: 5px; }
.status-badge.verified {
    background-color: rgba(40,167,69,0.15);
    color: #28a745;
}
.status-badge.pending {
    background-color: rgba(255,193,7,0.15);
    color: #ffc107;
}
.status-badge.not-input {
    background-color: rgba(220,53,69,0.15);
    color: #dc3545;
}
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
@media (max-width: 992px) {
    .filter-group { flex-direction: column; }
}
@media (max-width: 768px) {
    .page-header { flex-direction: column; align-items: flex-start; gap: 10px; }
    .page-header-actions { width: 100%; justify-content: flex-start; margin-top: 10px; }
}
</style>
@endsection

@section('content')
<div class="dashboard-content">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h2><i class="fas fa-chart-line me-2"></i>Realisasi KPI</h2>
            <div class="page-header-subtitle">Pengelolaan data realisasi kinerja bidang</div>
        </div>
        <div class="page-header-actions">
            @if(isset($indikators) && $indikators->count())
                <div class="page-header-badge">
                    <i class="fas fa-tasks"></i> Total Indikator: {{ $indikators->count() }}
                </div>
            @endif
        </div>
    </div>

    @include('components.alert')

    <!-- Filter Form -->
    <div class="filter-card">
        <h5><i class="fas fa-filter me-2"></i>Filter Data Realisasi</h5>
        <form method="GET" action="{{ route('realisasi.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tahun</label>
                    <select name="tahun" class="form-select">
                        @for($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-select">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('bulan', date('n')) == $i ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Periode</label>
                    <select name="periode_tipe" class="form-select">
                        <option value="bulanan" {{ request('periode_tipe', 'bulanan') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                        <option value="mingguan" {{ request('periode_tipe') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                    </select>
                </div>
            </div>
            <div class="mt-3 text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-1"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>

    <!-- Realisasi Harian -->
    <div class="mt-4">
        <h4>Realisasi Tanggal: {{ $tanggal ?? 'Tidak tersedia' }}</h4>

        @if(isset($realisasiHarian) && $realisasiHarian->isEmpty())
            <p>Belum ada data realisasi untuk tanggal ini. Silakan input data.</p>
            <form method="POST" action="{{ route('realisasi.store') }}">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Indikator</th>
                            <th>Nilai</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($indikators as $indikator)
                            <tr>
                                <td>{{ $indikator->nama }}</td>
                                <td>
                                    <input type="number" name="nilai[{{ $indikator->id }}]" class="form-control" required>
                                </td>
                                <td>
                                    <input type="text" name="keterangan[{{ $indikator->id }}]" class="form-control">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-end">
                    <button type="submit" class="btn btn-success">Simpan Realisasi</button>
                </div>
            </form>
        @elseif(isset($realisasiHarian))
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Indikator</th>
                        <th>Nilai</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($realisasiHarian as $realisasi)
                        <tr>
                            <td>{{ $realisasi->indikator->nama }}</td>
                            <td>{{ $realisasi->nilai }}</td>
                            <td>{{ $realisasi->keterangan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Data Table -->
    <div class="table-card mt-5">
        <div class="card-header">
            Data Realisasi KPI
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:5%">No</th>
                            <th style="width:15%">Bidang</th>
                            <th style="width:25%">Indikator</th>
                            <th style="width:10%">Target</th>
                            <th style="width:10%">Realisasi</th>
                            <th style="width:15%">Capaian</th>
                            <th style="width:10%">Status</th>
                            <th style="width:10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($indikators as $index => $indikator)
                            @php
                                $realisasi = $indikator->realisasi ?? null;
                                $nilai = $realisasi->nilai ?? null;
                                $diverifikasi = $realisasi->diverifikasi ?? null;
                                $idRealisasi = $realisasi->id ?? null;

                                $persentase = $indikator->persentase ?? 0;
                                $progressClass = 'bg-danger';
                                if ($persentase >= 90) {
                                    $progressClass = 'bg-success';
                                } elseif ($persentase >= 70) {
                                    $progressClass = 'bg-warning';
                                }

                                $user = auth()->user();
                                $isMaster = method_exists($user, 'hasRole') ? $user->hasRole('master_admin') : ($user->role === 'master_admin');
                                $isAdminBidang = $indikator->bidang_id == $user->bidang_id;
                            @endphp
                            <tr>
                                <td>{{ $indikators->firstItem() + $index }}</td>
                                <td>{{ $indikator->bidang->nama }}</td>
                                <td>
                                    <strong>{{ $indikator->kode }}</strong> - {{ $indikator->nama }}
                                    @if($indikator->deskripsi)
                                        <div class="small text-muted">{{ Str::limit($indikator->deskripsi, 60) }}</div>
                                    @endif
                                </td>
                                <td>{{ number_format($indikator->target, 2) }}</td>
                                <td>
                                    @if($nilai !== null)
                                        {{ number_format($nilai, 2) }}
                                    @else
                                        <span class="text-muted fst-italic">Belum input</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="progress-wrapper">
                                        <div class="progress" role="progressbar" aria-valuenow="{{ $persentase }}" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar {{ $progressClass }}" style="width: {{ $persentase }}%;"></div>
                                        </div>
                                        <div class="progress-value">{{ $persentase }}%</div>
                                    </div>
                                </td>
                                <td>
                                    @if($diverifikasi)
                                        <span class="status-badge verified"><i class="fas fa-check-circle"></i> Diverifikasi</span>
                                    @elseif($nilai !== null)
                                        <span class="status-badge pending"><i class="fas fa-hourglass-half"></i> Pending</span>
                                    @else
                                        <span class="status-badge not-input"><i class="fas fa-times-circle"></i> Belum Input</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        @php
                                            $queryParams = [
                                                'tahun' => request('tahun', date('Y')),
                                                'bulan' => request('bulan', date('m')),
                                                'periode_tipe' => request('periode_tipe', 'bulanan'),
                                            ];
                                        @endphp

                                        @if($indikator->nilai_id)
                                            <a href="{{ route('realisasi.edit', ['indikator' => $indikator->id] + $queryParams) }}" class="btn btn-warning">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>

                                            <a href="{{ route('realisasi.show', ['id' => $indikator->nilai_id]) }}" class="btn btn-info">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        @else
                                            <a href="{{ route('realisasi.create', ['indikator' => $indikator->id] + $queryParams) }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Input Realisasi
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center fst-italic text-muted">Data indikator tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                {{ $indikators->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

