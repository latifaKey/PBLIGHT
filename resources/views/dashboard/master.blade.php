{{-- resources/views/dashboard/master.blade.php --}}
@extends('layouts.app')

@section('title', 'Master Admin Dashboard')

@section('styles')
<style>
  /* Dashboard Layout System */
  .dashboard-grid {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 20px;
    margin-bottom: 25px;
    width: 100%;
  }

  .grid-span-3 {
    grid-column: span 3;
  }

  .grid-span-4 {
    grid-column: span 4;
  }

  .grid-span-6 {
    grid-column: span 6;
  }

  .grid-span-8 {
    grid-column: span 8;
  }

  .grid-span-12 {
    grid-column: span 12;
  }

  /* Card Styling yang Konsisten untuk tema terang dan gelap */
  .card {
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
  }

  .card:hover {
    transform: translateY(-5px);
  }

  [data-theme="light"] .card {
    background: #ffffff;
    border: 1px solid #dee2e6;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  }

  [data-theme="dark"] .card {
    background: var(--pln-surface);
    border: 1px solid var(--pln-border);
    box-shadow: 0 4px 15px var(--pln-shadow);
  }

  .card-header {
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  [data-theme="light"] .card-header {
    border-bottom: 1px solid #dee2e6;
  }

  [data-theme="dark"] .card-header {
    border-bottom: 1px solid var(--pln-border);
  }

  .card-body {
    padding: 20px;
  }

  .card-header.bg-success {
    background: linear-gradient(45deg, #28a745, #20c997);
  }

  .card-header.bg-warning {
    background: linear-gradient(45deg, #ffc107, #fd7e14);
    color: #212529 !important; /* Warna teks yang lebih gelap untuk kontras dengan background kuning */
  }

  .card-header.bg-primary {
    background: linear-gradient(45deg, var(--pln-blue), var(--pln-light-blue));
  }

  .card-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
  }

  .card-title i {
    margin-right: 8px;
    font-size: 0.9em;
  }

  /* Stat Card Styling */
  .stat-card {
    padding: 20px;
    border-radius: 12px;
    height: 100%;
    display: flex;
    flex-direction: column;
  }

  .stat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
  }

  .stat-title {
    font-size: 1rem;
    font-weight: 500;
    color: var(--pln-text-secondary);
    margin: 0;
  }

  .stat-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: var(--pln-accent-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--pln-light-blue);
    font-size: 1.2rem;
  }

  .stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 5px 0;
    color: var(--pln-text);
  }

  .stat-description {
    font-size: 0.85rem;
    color: var(--pln-text-secondary);
    margin: 0;
  }

  /* Chart Card Styling */
  .chart-card {
    padding: 20px;
    height: 100%;
  }

  .chart-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 15px 0;
    color: var(--pln-text);
    display: flex;
    align-items: center;
  }

  .chart-title i {
    margin-right: 8px;
    color: var(--pln-light-blue);
  }

  .chart-container {
    position: relative;
    height: 300px;
    margin: 0 auto;
    transition: all 0.3s ease;
  }

  .chart-container.medium {
    height: 300px;
  }

  .chart-container.large {
    height: 400px;
  }

  /* Section Divider */
  .section-divider {
    margin: 30px 0 20px;
    border-bottom: 1px solid var(--pln-border);
    padding-bottom: 10px;
  }

  .section-divider h2 {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--pln-text);
    margin: 0;
    display: flex;
    align-items: center;
  }

  .section-divider h2 i {
    margin-right: 10px;
    color: var(--pln-light-blue);
  }

  /* Table Styling */
  .table-responsive {
    overflow-x: auto;
    border-radius: 8px;
  }

  .data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
  }

  .data-table thead th {
    background: var(--pln-surface-2);
    color: var(--pln-text);
    font-weight: 600;
    text-align: left;
    padding: 12px 15px;
    font-size: 0.9rem;
    border-bottom: 2px solid var(--pln-border);
  }

  .data-table tbody td {
    padding: 12px 15px;
    border-bottom: 1px solid var(--pln-border);
    font-size: 0.9rem;
    color: var(--pln-text);
    vertical-align: middle;
  }

  .data-table tbody tr:hover {
    background: var(--pln-accent-bg);
  }

  /* Pengaturan untuk tabel di Bootstrap */
  .table-hover tbody tr:hover {
    background-color: var(--pln-accent-bg);
  }

  .table thead th {
    color: var(--pln-text);
    border-bottom-color: var(--pln-border);
  }

  .table tbody td {
    color: var(--pln-text);
    border-color: var(--pln-border);
  }

  .table-responsive {
    margin-bottom: 1rem;
  }

  .thead-light th {
    background-color: var(--pln-surface-2);
    color: var(--pln-text);
    border-color: var(--pln-border);
  }

  /* Empty State */
  .empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 30px;
    color: var(--pln-text-secondary);
  }

  /* Text colors yang kontras dan terlihat untuk light dan dark */
  [data-theme="light"] .text-success {
    color: #1e7e34 !important;
    font-weight: 600;
  }

  [data-theme="light"] .text-warning {
    color: #d39e00 !important;
    font-weight: 600;
  }

  [data-theme="light"] .text-primary {
    color: #0078b0 !important;
    font-weight: 600;
  }

  [data-theme="light"] .text-muted {
    color: #6c757d !important;
  }

  [data-theme="dark"] .text-success {
    color: #34eb52 !important;
    font-weight: 600;
  }

  [data-theme="dark"] .text-warning {
    color: #ffdf4f !important;
    font-weight: 600;
  }

  [data-theme="dark"] .text-primary {
    color: #4db5ff !important;
    font-weight: 600;
  }

  [data-theme="dark"] .text-muted {
    color: rgba(248, 250, 252, 0.7) !important;
  }

  /* Indikator performa untuk tema light dan dark */
  .performance-indicator {
    display: inline-flex;
    align-items: center;
    padding: 3px 8px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    margin-left: 8px;
  }

  .performance-indicator i {
    margin-right: 5px;
  }

  [data-theme="light"] .performance-indicator.high {
    color: #1e7e34;
    background-color: rgba(40, 167, 69, 0.15);
    border: 1px solid rgba(40, 167, 69, 0.3);
  }

  [data-theme="light"] .performance-indicator.medium {
    color: #d39e00;
    background-color: rgba(255, 193, 7, 0.15);
    border: 1px solid rgba(255, 193, 7, 0.3);
  }

  [data-theme="light"] .performance-indicator.low {
    color: #bd2130;
    background-color: rgba(220, 53, 69, 0.15);
    border: 1px solid rgba(220, 53, 69, 0.3);
  }

  [data-theme="dark"] .performance-indicator.high {
    color: #34eb52;
    background-color: rgba(40, 167, 69, 0.15);
    border: 1px solid rgba(40, 167, 69, 0.3);
  }

  [data-theme="dark"] .performance-indicator.medium {
    color: #ffdf4f;
    background-color: rgba(255, 193, 7, 0.15);
    border: 1px solid rgba(255, 193, 7, 0.3);
  }

  [data-theme="dark"] .performance-indicator.low {
    color: #ff5a6a;
    background-color: rgba(220, 53, 69, 0.15);
    border: 1px solid rgba(220, 53, 69, 0.3);
  }

  /* Form styling yang terintegrasi untuk light dan dark mode */
  .form-select,
  .form-control {
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  }

  [data-theme="light"] .form-select,
  [data-theme="light"] .form-control {
    background-color: #ffffff;
    border-color: #dee2e6;
    color: #333333;
  }

  [data-theme="light"] .form-select:focus,
  [data-theme="light"] .form-control:focus {
    background-color: #ffffff;
    border-color: #0078b0;
    color: #333333;
    box-shadow: 0 0 0 0.25rem rgba(0, 120, 176, 0.25);
  }

  [data-theme="light"] .form-label {
    color: #333333;
    font-weight: 500;
  }

  [data-theme="dark"] .form-select,
  [data-theme="dark"] .form-control {
    background-color: #1e293b;
    border-color: rgba(248, 250, 252, 0.1);
    color: #f8fafc;
  }

  [data-theme="dark"] .form-select:focus,
  [data-theme="dark"] .form-control:focus {
    background-color: #1e293b;
    border-color: #0091d1;
    color: #f8fafc;
    box-shadow: 0 0 0 0.25rem rgba(0, 156, 222, 0.25);
  }

  [data-theme="dark"] .form-label {
    color: #f8fafc;
    font-weight: 500;
  }

  /* Variabel untuk dark/light mode chart */
  :root {
    --chart-grid-color: rgba(255, 255, 255, 0.1);
    --chart-success-color: rgba(40, 167, 69, 0.7);
    --chart-success-border: #28a745;
    --chart-warning-color: rgba(255, 193, 7, 0.7);
    --chart-warning-border: #ffc107;
    --chart-primary-color: rgba(0, 156, 222, 0.3);
    --chart-primary-border: #009cde;
    --chart-text-color: #f8fafc;
    --chart-background: rgba(0, 0, 0, 0.05);
  }

  [data-theme="light"] {
    --chart-grid-color: rgba(0, 0, 0, 0.1);
    --chart-text-color: #333333;
    --chart-background: rgba(255, 255, 255, 0.9);
  }

  /* Button dan form styling dipindahkan ke variabel tema */
  .btn-primary {
    color: #fff;
    transition: all 0.3s ease;
  }

  .btn-primary:hover {
    transform: translateY(-2px);
  }

  [data-theme="light"] .btn-primary {
    background-color: #0078b0;
    border-color: #0078b0;
  }

  [data-theme="light"] .btn-primary:hover {
    background-color: #005d8a;
    border-color: #005d8a;
  }

  [data-theme="dark"] .btn-primary {
    background-color: #0091d1;
    border-color: #0091d1;
  }

  [data-theme="dark"] .btn-primary:hover {
    background-color: #00a7f5;
    border-color: #00a7f5;
  }

  /* Tab styling yang lebih konsisten */
  .nav-tabs {
    border-bottom: 2px solid rgba(0, 156, 222, 0.1);
    margin-bottom: 25px;
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
  }

  .nav-tabs .nav-item {
    margin-bottom: -2px;
  }

  .nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    background: transparent;
    color: var(--pln-text-secondary);
    font-weight: 500;
    padding: 12px 20px;
    border-radius: 8px 8px 0 0;
    transition: all 0.3s ease;
    font-size: 15px;
    position: relative;
    cursor: pointer;
  }

  .nav-tabs .nav-link:hover {
    color: var(--pln-light-blue);
    border-color: rgba(0, 156, 222, 0.5);
    background: var(--pln-accent-bg);
  }

  .nav-tabs .nav-link.active {
    color: var(--pln-light-blue);
    border-color: var(--pln-light-blue);
    background: var(--pln-accent-bg);
    font-weight: 600;
  }

  .nav-tabs .nav-link .badge {
    padding: 5px 8px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 11px;
    transition: all 0.3s ease;
    margin-left: 8px;
    box-shadow: 0 2px 5px var(--pln-shadow);
  }

  .tab-content > .tab-pane {
    display: none;
  }

  .tab-content > .active {
    display: block;
    animation: fadeIn 0.3s ease;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }

  /* Badge styling untuk light dan dark mode */
  .badge {
    padding: 5px 10px;
    border-radius: 30px;
    font-weight: 500;
    font-size: 0.75rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .badge i {
    margin-right: 3px;
  }

  /* Light mode badges */
  [data-theme="light"] .badge-secondary {
    background-color: #6c757d;
    color: #fff;
  }

  [data-theme="light"] .badge-success {
    background-color: #28a745;
    color: #fff;
  }

  [data-theme="light"] .badge-primary {
    background-color: #0078b0;
    color: #fff;
  }

  [data-theme="light"] .badge-warning {
    background-color: #ffc107;
    color: #212529; /* Warna teks gelap untuk kontras dengan background kuning */
  }

  /* Dark mode badges */
  [data-theme="dark"] .badge-warning {
    background-color: #ffc107;
    color: #212529; /* Tetap gelap agar terlihat kontras */
  }

  [data-theme="dark"] .badge-success {
    background-color: #28a745;
    color: #fff;
  }

  [data-theme="dark"] .badge-secondary {
    background-color: #6c757d;
    color: #fff;
  }

  [data-theme="dark"] .badge-primary {
    background-color: #0078b0;
    color: #fff;
  }

  /* Table Styling untuk light dan dark mode */
  [data-theme="light"] .data-table thead th {
    background-color: #f0f2f5;
    color: #333333;
    border-bottom-color: #dee2e6;
  }

  [data-theme="light"] .data-table tbody td {
    color: #333333;
    border-bottom-color: #dee2e6;
  }

  [data-theme="light"] .data-table tbody tr:hover {
    background-color: rgba(0, 120, 176, 0.05);
  }

  [data-theme="dark"] .data-table thead th {
    background-color: #1e293b;
    color: #f8fafc;
    border-bottom-color: rgba(248, 250, 252, 0.1);
  }

  [data-theme="dark"] .data-table tbody td {
    color: #f8fafc;
    border-bottom-color: rgba(248, 250, 252, 0.1);
  }

  [data-theme="dark"] .data-table tbody tr:hover {
    background-color: rgba(0, 156, 222, 0.15);
  }
</style>
@endsection

@section('content')
<div class="container-xl px-4 py-4">
  <div class="row mb-4">
    <div class="col-12">
      <div class="section-divider">
        <h2><i class="fas fa-tachometer-alt"></i> Dashboard Master Admin</h2>
      </div>
      <p class="text-muted">Selamat datang di dashboard master admin. Silahkan pilih tab untuk melihat data.</p>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header bg-primary text-white">
          <h5 class="card-title mb-0">
            <i class="fas fa-filter mr-2"></i> Filter Data
          </h5>
        </div>
        <div class="card-body">
          <form action="{{ route('dashboard.master') }}" method="GET" class="d-flex flex-wrap gap-2 align-items-center">
            <div class="form-group mb-0 mr-2">
              <label for="tahun" class="form-label small mb-1">Tahun:</label>
              <select name="tahun" id="tahun" class="form-select form-select-sm">
                @foreach(range(date('Y') - 5, date('Y') + 1) as $year)
                  <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group mb-0 mr-2">
              <label for="bulan" class="form-label small mb-1">Bulan:</label>
              <select name="bulan" id="bulan" class="form-select form-select-sm">
                @foreach(range(1, 12) as $month)
                  <option value="{{ $month }}" {{ $bulan == $month ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $month, 1)) }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <label class="form-label opacity-0 d-block small mb-1">Action:</label>
              <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-filter fa-sm mr-1"></i> Filter
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Statistik Ringkasan -->
  <div class="dashboard-grid">
    <div class="grid-span-3">
      <div class="card stat-card">
        <div class="stat-header">
          <h3 class="stat-title">NKO Score</h3>
          <div class="stat-icon">
            <i class="fas fa-chart-line"></i>
          </div>
        </div>
        <div class="stat-value">{{ $data['nko'] ?? 0 }}%</div>
        <p class="stat-description">Nilai Kinerja Organisasi</p>
      </div>
    </div>

    <div class="grid-span-3">
      <div class="card stat-card">
        <div class="stat-header">
          <h3 class="stat-title">Pilar</h3>
          <div class="stat-icon">
            <i class="fas fa-layer-group"></i>
          </div>
        </div>
        <div class="stat-value">{{ count($data['pilar'] ?? []) }}</div>
        <p class="stat-description">Total Pilar Kinerja</p>
      </div>
    </div>

    <div class="grid-span-3">
      <div class="card stat-card">
        <div class="stat-header">
          <h3 class="stat-title">Kinerja Tinggi</h3>
          <div class="stat-icon">
            <i class="fas fa-arrow-up"></i>
          </div>
        </div>
        <div class="stat-value">{{ collect($data['pilar'] ?? [])->where('nilai', '>=', 80)->count() }}</div>
        <p class="stat-description">Pilar dengan Kinerja ≥ 80%</p>
      </div>
    </div>

    <div class="grid-span-3">
      <div class="card stat-card">
        <div class="stat-header">
          <h3 class="stat-title">Perlu Perhatian</h3>
          <div class="stat-icon">
            <i class="fas fa-exclamation-triangle"></i>
          </div>
        </div>
        <div class="stat-value">{{ collect($data['pilar'] ?? [])->where('nilai', '<', 70)->count() }}</div>
        <p class="stat-description">Pilar dengan Kinerja < 70%</p>
      </div>
    </div>
  </div>

  <!-- Dashboard Overview -->
  <div class="dashboard-grid">
    <div class="grid-span-6">
      <div class="card chart-card">
        <h3 class="chart-title"><i class="fas fa-chart-pie"></i> Gauge NKO</h3>
        <div class="d-flex flex-column align-items-center justify-content-center">
          <div style="position: relative; height: 200px; width: 300px;">
            <canvas id="gaugeChart"></canvas>
            <div style="position: absolute; top: 60%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
              <h3 class="mb-0 fw-bold">{{ $data['nko'] }}%</h3>
              <p class="mb-0 small text-muted">NKO Score</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="grid-span-6">
      <div class="card chart-card">
        <h3 class="chart-title"><i class="fas fa-chart-line"></i> Trend Kinerja {{ $tahun }}</h3>
        <div class="chart-container medium">
          <canvas id="trendChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Tab Content -->
  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="semua-tab" data-bs-toggle="tab" data-bs-target="#semua" type="button" role="tab" aria-controls="semua" aria-selected="true">
        <i class="fas fa-table me-2"></i>Semua Pilar
        <span class="badge badge-secondary">{{ count($data['pilar'] ?? []) }}</span>
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="tinggi-tab" data-bs-toggle="tab" data-bs-target="#kinerja-tinggi" type="button" role="tab" aria-controls="kinerja-tinggi" aria-selected="false">
        <i class="fas fa-arrow-up me-2"></i>Kinerja Tinggi
        <span class="badge badge-success">{{ collect($data['pilar'] ?? [])->where('nilai', '>=', 80)->count() }}</span>
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="rendah-tab" data-bs-toggle="tab" data-bs-target="#kinerja-rendah" type="button" role="tab" aria-controls="kinerja-rendah" aria-selected="false">
        <i class="fas fa-arrow-down me-2"></i>Perlu Perhatian
        <span class="badge badge-warning">{{ collect($data['pilar'] ?? [])->where('nilai', '<', 70)->count() }}</span>
      </button>
    </li>
  </ul>

  <div class="tab-content" id="myTabContent">
    <!-- Tab Semua Pilar -->
    <div class="tab-pane fade show active" id="semua" role="tabpanel" aria-labelledby="semua-tab">
      <div class="dashboard-grid">
        <div class="grid-span-6">
          <div class="card chart-card">
            <h3 class="chart-title"><i class="fas fa-chart-radar"></i> Perbandingan Antar Pilar</h3>
            <div class="chart-container medium">
              <canvas id="radarChart"></canvas>
            </div>
          </div>
        </div>

        <div class="grid-span-6">
          <div class="card chart-card">
            <h3 class="chart-title"><i class="fas fa-table"></i> Daftar Semua Pilar</h3>
            <div class="table-responsive">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Nama Pilar</th>
                    <th>Nilai (%)</th>
                    <th>Status</th>
                    <th>Tindakan</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($data['pilar'] ?? [] as $pilar)
                    <tr class="data-row">
                      <td>{{ $pilar['nama'] }}</td>
                      <td>
                        @if($pilar['nilai'] >= 80)
                          <strong class="text-success">{{ $pilar['nilai'] }}%</strong>
                          <span class="performance-indicator high">
                            <i class="fas fa-arrow-up"></i> {{ $pilar['nilai'] }}
                          </span>
                        @elseif($pilar['nilai'] >= 70)
                          <strong class="text-primary">{{ $pilar['nilai'] }}%</strong>
                          <span class="performance-indicator medium">
                            <i class="fas fa-minus"></i> {{ $pilar['nilai'] }}
                          </span>
                        @else
                          <strong class="text-warning">{{ $pilar['nilai'] }}%</strong>
                          <span class="performance-indicator low">
                            <i class="fas fa-arrow-down"></i> {{ $pilar['nilai'] }}
                          </span>
                        @endif
                      </td>
                      <td>
                        @if($pilar['nilai'] >= 80)
                          <span class="badge badge-success">
                            <i class="fas fa-check-circle mr-1"></i> Kinerja Tinggi
                          </span>
                        @elseif($pilar['nilai'] >= 70)
                          <span class="badge badge-primary">
                            <i class="fas fa-info-circle mr-1"></i> Kinerja Baik
                          </span>
                        @else
                          <span class="badge badge-warning">
                            <i class="fas fa-exclamation-circle mr-1"></i> Perlu Perhatian
                          </span>
                        @endif
                      </td>
                      <td>
                        <a href="{{ route('dataKinerja.pilar', $loop->iteration) }}" class="btn btn-sm btn-primary">
                          <i class="fas fa-eye mr-1"></i> Detail
                        </a>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" class="text-center py-4">
                        <div class="empty-state">
                          <i class="fas fa-database text-muted mb-3" style="font-size: 2.5rem;"></i>
                          <p class="text-muted">Tidak ada data pilar untuk ditampilkan.</p>
                        </div>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tab Kinerja Tinggi -->
    <div class="tab-pane fade" id="kinerja-tinggi" role="tabpanel" aria-labelledby="tinggi-tab">
      <div class="dashboard-grid">
        <div class="grid-span-12">
          <div class="card chart-card">
            <h3 class="chart-title"><i class="fas fa-chart-bar"></i> Pilar dengan Kinerja Tinggi</h3>
            <div class="chart-container medium">
              <canvas id="highPerformanceChart"></canvas>
            </div>
            <div class="table-responsive mt-4">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Nama Pilar</th>
                    <th>Nilai (%)</th>
                    <th>Status</th>
                    <th>Tindakan</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse(collect($data['pilar'] ?? [])->where('nilai', '>=', 80) as $pilar)
                  <tr class="data-row">
                    <td>{{ $pilar['nama'] }}</td>
                    <td>
                      <strong class="text-success">{{ $pilar['nilai'] }}%</strong>
                      <span class="performance-indicator high">
                        <i class="fas fa-arrow-up"></i> {{ $pilar['nilai'] }}
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-success">
                        <i class="fas fa-check-circle mr-1"></i> Kinerja Tinggi
                      </span>
                    </td>
                    <td>
                      <a href="{{ route('dataKinerja.pilar', collect($data['pilar'])->search(function($p) use ($pilar) { return $p['nama'] == $pilar['nama']; }) + 1) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye mr-1"></i> Detail
                      </a>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="4" class="text-center py-4">
                      <div class="empty-state">
                        <i class="fas fa-chart-line text-muted mb-3" style="font-size: 2.5rem;"></i>
                        <p class="text-muted">Tidak ada pilar dengan kinerja tinggi saat ini.</p>
                      </div>
                    </td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tab Perlu Perhatian -->
    <div class="tab-pane fade" id="kinerja-rendah" role="tabpanel" aria-labelledby="rendah-tab">
      <div class="dashboard-grid">
        <div class="grid-span-12">
          <div class="card chart-card">
            <h3 class="chart-title"><i class="fas fa-exclamation-triangle"></i> Pilar yang Perlu Perhatian</h3>
            <div class="chart-container medium">
              <canvas id="lowPerformanceChart"></canvas>
            </div>
            <div class="table-responsive mt-4">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Nama Pilar</th>
                    <th>Nilai (%)</th>
                    <th>Status</th>
                    <th>Tindakan</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse(collect($data['pilar'] ?? [])->where('nilai', '<', 70) as $pilar)
                  <tr class="data-row">
                    <td>{{ $pilar['nama'] }}</td>
                    <td>
                      <strong class="text-warning">{{ $pilar['nilai'] }}%</strong>
                      <span class="performance-indicator low">
                        <i class="fas fa-arrow-down"></i> {{ $pilar['nilai'] }}
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-warning">
                        <i class="fas fa-exclamation-circle mr-1"></i> Perlu Perhatian
                      </span>
                    </td>
                    <td>
                      <a href="{{ route('dataKinerja.pilar', collect($data['pilar'])->search(function($p) use ($pilar) { return $p['nama'] == $pilar['nama']; }) + 1) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye mr-1"></i> Detail
                      </a>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="4" class="text-center py-4">
                      <div class="empty-state">
                        <i class="fas fa-check-circle text-muted mb-3" style="font-size: 2.5rem;"></i>
                        <p class="text-muted">Tidak ada pilar yang perlu perhatian saat ini.</p>
                      </div>
                    </td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  // Helper function untuk mengambil variabel CSS
  function getCSSVariable(variable, fallback) {
    const computedStyle = getComputedStyle(document.documentElement);
    return computedStyle.getPropertyValue(variable).trim() || fallback;
  }

  // Chart configuration yang support dark/light mode
  function getChartConfig() {
    return {
      gridColor: getCSSVariable('--chart-grid-color', 'rgba(255, 255, 255, 0.1)'),
      textColor: getCSSVariable('--chart-text-color', 'var(--pln-text)'),
      successColor: getCSSVariable('--chart-success-color', 'rgba(40, 167, 69, 0.7)'),
      successBorder: getCSSVariable('--chart-success-border', '#28a745'),
      warningColor: getCSSVariable('--chart-warning-color', 'rgba(255, 193, 7, 0.7)'),
      warningBorder: getCSSVariable('--chart-warning-border', '#ffc107'),
      primaryColor: getCSSVariable('--chart-primary-color', 'rgba(0, 156, 222, 0.3)'),
      primaryBorder: getCSSVariable('--chart-primary-border', '#009cde'),
      background: getCSSVariable('--chart-background', 'rgba(0, 0, 0, 0.05)')
    };
  }

  // Fungsi untuk update semua chart ketika tema berubah
  function updateChartsForTheme() {
    // Akan digunakan jika ada pemicu perubahan tema
    window.dispatchEvent(new Event('resize'));
  }

  // Deteksi perubahan tema
  const themeToggle = document.querySelector('.theme-switch input');
  if (themeToggle) {
    themeToggle.addEventListener('change', function() {
      setTimeout(updateChartsForTheme, 200); // Berikan waktu CSS untuk berubah
    });
  }

  // Tab functionality
  $(document).ready(function() {
    console.log('Document ready, setting up tabs');

    // Manual tab handling
    $('#semua-tab').on('click', function(e) {
      e.preventDefault();
      console.log('Semua tab clicked');
      $('#semua').addClass('show active');
      $('#kinerja-tinggi').removeClass('show active');
      $('#kinerja-rendah').removeClass('show active');

      $('#semua-tab').addClass('active');
      $('#tinggi-tab').removeClass('active');
      $('#rendah-tab').removeClass('active');
    });

    $('#tinggi-tab').on('click', function(e) {
      e.preventDefault();
      console.log('Tinggi tab clicked');
      $('#kinerja-tinggi').addClass('show active');
      $('#semua').removeClass('show active');
      $('#kinerja-rendah').removeClass('show active');

      $('#tinggi-tab').addClass('active');
      $('#semua-tab').removeClass('active');
      $('#rendah-tab').removeClass('active');

      // Trigger resize untuk memastikan chart ditampilkan dengan benar
      setTimeout(function() {
        window.dispatchEvent(new Event('resize'));
      }, 50);
    });

    $('#rendah-tab').on('click', function(e) {
      e.preventDefault();
      console.log('Rendah tab clicked');
      $('#kinerja-rendah').addClass('show active');
      $('#semua').removeClass('show active');
      $('#kinerja-tinggi').removeClass('show active');

      $('#rendah-tab').addClass('active');
      $('#semua-tab').removeClass('active');
      $('#tinggi-tab').removeClass('active');

      // Trigger resize untuk memastikan chart ditampilkan dengan benar
      setTimeout(function() {
        window.dispatchEvent(new Event('resize'));
      }, 50);
    });
  });

  document.addEventListener('DOMContentLoaded', function() {
    // Mengambil konfigurasi untuk chart
    const chartConfig = getChartConfig();

    // Gauge Chart
    const gaugeCtx = document.getElementById('gaugeChart').getContext('2d');
    const nkoValue = {{ $data['nko'] }};

    // Determine color based on value
    let gaugeColor = '#F44336'; // Red for low values
    if (nkoValue >= 70) {
      gaugeColor = chartConfig.successBorder; // Green for high values
    } else if (nkoValue >= 50) {
      gaugeColor = chartConfig.warningBorder; // Yellow for medium values
    }

    new Chart(gaugeCtx, {
      type: 'doughnut',
      data: {
        datasets: [{
          data: [nkoValue, 100 - nkoValue],
          backgroundColor: [
            gaugeColor,
            'rgba(200, 200, 200, 0.1)'
          ],
          borderWidth: 0,
          circumference: 180,
          rotation: 270,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            enabled: false
          }
        }
      }
    });

    // Pilar Charts
    const pilarValues = @json(array_column($data['pilar'], 'nilai'));
    const pilarColors = pilarValues.map(value => {
      if (value >= 70) return chartConfig.successBorder;  // Green for high values
      else if (value >= 50) return chartConfig.warningBorder;  // Yellow for medium values
      else return '#F44336';  // Red for low values
    });

    for (let i = 1; i <= pilarValues.length; i++) {
      const pilarCtx = document.getElementById(`pilar${i}Chart`);
      if (!pilarCtx) continue;

      const pilarValue = pilarValues[i-1];
      const pilarColor = pilarColors[i-1];

      new Chart(pilarCtx.getContext('2d'), {
        type: 'doughnut',
        data: {
          datasets: [{
            data: [pilarValue, 100 - pilarValue],
            backgroundColor: [
              pilarColor,
              'rgba(200, 200, 200, 0.1)'
            ],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '75%',
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              enabled: false
            }
          },
          animation: {
            animateRotate: true,
            animateScale: true
          }
        }
      });
    }

    // Trend Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    // Simulasikan data tren (ini dapat diganti dengan data aktual)
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const currentMonth = {{ $bulan }};
    const trendData = [];

    // Generate trend data up to current month
    for (let i = 1; i <= 12; i++) {
      if (i <= currentMonth) {
        // Generate random values within ±10% of final NKO value for past months
        const min = Math.max({{ $data['nko'] }} - 10, 0);
        const max = Math.min({{ $data['nko'] }} + 5, 100);
        const randomValue = (i < currentMonth)
          ? (Math.random() * (max - min) + min).toFixed(1)
          : {{ $data['nko'] }};
        trendData.push(randomValue);
      } else {
        trendData.push(null); // Future months have null values
      }
    }

    new Chart(trendCtx, {
      type: 'line',
      data: {
        labels: months,
        datasets: [{
          label: 'Nilai Kinerja',
          data: trendData,
          backgroundColor: chartConfig.primaryColor,
          borderColor: chartConfig.primaryBorder,
          borderWidth: 2,
          pointBackgroundColor: '#0a4d85',
          pointBorderColor: '#fff',
          pointBorderWidth: 2,
          pointRadius: 5,
          tension: 0.3,
          fill: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleColor: '#fff',
            bodyColor: '#fff',
            titleFont: {
              size: 14,
              weight: 'bold'
            },
            bodyFont: {
              size: 13
            },
            padding: 12,
            displayColors: false
          }
        },
        scales: {
          x: {
            grid: {
              color: chartConfig.gridColor
            },
            ticks: {
              color: chartConfig.textColor
            }
          },
          y: {
            beginAtZero: true,
            max: 100,
            grid: {
              color: chartConfig.gridColor
            },
            ticks: {
              color: chartConfig.textColor,
              callback: function(value) {
                return value + '%';
              }
            }
          }
        }
      }
    });

    // Radar Chart untuk perbandingan antar pilar
    const radarCtx = document.getElementById('radarChart');
    if (radarCtx) {
      const pilars = @json($data['pilar']);

      if (pilars.length > 0) {
        const labels = pilars.map(pilar => pilar.nama);
        const values = pilars.map(pilar => pilar.nilai);

        // Data untuk radar chart
        new Chart(radarCtx, {
          type: 'radar',
          data: {
            labels: labels,
            datasets: [{
              label: 'Nilai Kinerja (%)',
              data: values,
              backgroundColor: chartConfig.primaryColor || 'rgba(0, 156, 222, 0.3)',
              borderColor: chartConfig.primaryBorder || '#009cde',
              borderWidth: 2,
              pointBackgroundColor: '#0a4d85',
              pointBorderColor: '#fff',
              pointRadius: 5,
              pointHoverRadius: 8
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              r: {
                beginAtZero: true,
                max: 100,
                ticks: {
                  stepSize: 20,
                  backdropColor: 'transparent',
                  color: chartConfig.textColor || 'var(--pln-text-secondary)'
                },
                grid: {
                  color: chartConfig.gridColor || 'rgba(255, 255, 255, 0.1)'
                },
                angleLines: {
                  color: chartConfig.gridColor || 'rgba(255, 255, 255, 0.1)'
                },
                pointLabels: {
                  color: chartConfig.textColor || 'var(--pln-text)',
                  font: {
                    size: 12
                  }
                }
              }
            },
            plugins: {
              legend: {
                display: false
              },
              tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#fff',
                bodyColor: '#fff',
                titleFont: {
                  size: 14,
                  weight: 'bold'
                },
                bodyFont: {
                  size: 13
                },
                padding: 12
              }
            },
            elements: {
              line: {
                tension: 0.1
              }
            }
          }
        });
      } else {
        // Jika tidak ada data, tampilkan pesan kosong
        const noDataDiv = document.createElement('div');
        noDataDiv.className = 'empty-state';
        noDataDiv.innerHTML = `
          <i class="fas fa-chart-pie text-muted mb-3" style="font-size: 2.5rem;"></i>
          <p class="text-muted">Tidak ada data pilar untuk ditampilkan.</p>
        `;
        radarCtx.parentNode.replaceChild(noDataDiv, radarCtx);
      }
    }

    // Kinerja Tinggi Chart - Bar Chart
    const highPerformanceCtx = document.getElementById('highPerformanceChart');
    if (highPerformanceCtx) {
      const highPilar = @json(collect($data['pilar'])->where('nilai', '>=', 80)->values()->all());

      if (highPilar.length > 0) {
        const labels = highPilar.map(pilar => pilar.nama);
        const values = highPilar.map(pilar => pilar.nilai);
        const targetValues = highPilar.map(() => 80); // Target 80%

        new Chart(highPerformanceCtx, {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [
              {
                label: 'Nilai Kinerja (%)',
                data: values,
                backgroundColor: chartConfig.successColor,
                borderColor: chartConfig.successBorder,
                borderWidth: 1,
                borderRadius: 5,
                barPercentage: 0.6,
                categoryPercentage: 0.7
              },
              {
                label: 'Target Minimum Kinerja Tinggi',
                data: targetValues,
                type: 'line',
                backgroundColor: 'transparent',
                borderColor: 'rgba(0, 0, 0, 0.2)',
                borderWidth: 2,
                borderDash: [5, 5],
                pointStyle: false
              }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: true,
                max: 100,
                grid: {
                  color: chartConfig.gridColor
                },
                ticks: {
                  color: chartConfig.textColor,
                  callback: function(value) {
                    return value + '%';
                  }
                }
              },
              x: {
                grid: {
                  display: false
                },
                ticks: {
                  color: chartConfig.textColor
                }
              }
            },
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  color: chartConfig.textColor,
                  usePointStyle: true,
                  padding: 20,
                  font: {
                    size: 12
                  }
                }
              },
              tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#fff',
                bodyColor: '#fff',
                padding: 12,
                mode: 'index',
                intersect: false
              }
            }
          }
        });
      } else {
        // Jika tidak ada data, tampilkan pesan kosong
        const noDataDiv = document.createElement('div');
        noDataDiv.className = 'empty-state';
        noDataDiv.innerHTML = `
          <i class="fas fa-chart-bar text-muted mb-3" style="font-size: 2.5rem;"></i>
          <p class="text-muted">Tidak ada data kinerja tinggi untuk ditampilkan.</p>
        `;
        highPerformanceCtx.parentNode.replaceChild(noDataDiv, highPerformanceCtx);
      }
    }

    // Perlu Perhatian Chart - Horizontal Bar Chart
    const lowPerformanceCtx = document.getElementById('lowPerformanceChart');
    if (lowPerformanceCtx) {
      const lowPilar = @json(collect($data['pilar'])->where('nilai', '<', 70)->values()->all());

      if (lowPilar.length > 0) {
        const labels = lowPilar.map(pilar => pilar.nama);
        const values = lowPilar.map(pilar => pilar.nilai);
        const targetValues = lowPilar.map(() => 70); // Target 70%

        new Chart(lowPerformanceCtx, {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [
              {
                label: 'Nilai Kinerja (%)',
                data: values,
                backgroundColor: chartConfig.warningColor,
                borderColor: chartConfig.warningBorder,
                borderWidth: 1,
                borderRadius: 5,
                barPercentage: 0.6,
                categoryPercentage: 0.7
              },
              {
                label: 'Target Minimum',
                data: targetValues,
                type: 'line',
                backgroundColor: 'transparent',
                borderColor: 'rgba(0, 0, 0, 0.2)',
                borderWidth: 2,
                borderDash: [5, 5],
                pointStyle: false
              }
            ]
          },
          options: {
            indexAxis: 'y', // Horizontal bar
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              x: {
                beginAtZero: true,
                max: 100,
                grid: {
                  color: chartConfig.gridColor
                },
                ticks: {
                  color: chartConfig.textColor,
                  callback: function(value) {
                    return value + '%';
                  }
                }
              },
              y: {
                grid: {
                  display: false
                },
                ticks: {
                  color: chartConfig.textColor
                }
              }
            },
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  color: chartConfig.textColor,
                  usePointStyle: true,
                  padding: 20,
                  font: {
                    size: 12
                  }
                }
              },
              tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#fff',
                bodyColor: '#fff',
                padding: 12,
                mode: 'index',
                intersect: false
              }
            }
          }
        });
      } else {
        // Jika tidak ada data, tampilkan pesan kosong
        const noDataDiv = document.createElement('div');
        noDataDiv.className = 'empty-state';
        noDataDiv.innerHTML = `
          <i class="fas fa-check-circle text-muted mb-3" style="font-size: 2.5rem;"></i>
          <p class="text-muted">Tidak ada pilar yang perlu perhatian. Semua kinerja baik!</p>
        `;
        lowPerformanceCtx.parentNode.replaceChild(noDataDiv, lowPerformanceCtx);
      }
    }
  });
</script>
@endsection
