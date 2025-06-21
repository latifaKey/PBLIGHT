<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Indikator;
use App\Models\Realisasi;
use App\Models\Pilar;
use App\Models\Bidang;
use Carbon\Carbon;

echo "Script perbaikan data dimulai...\n";

// 1. Tandai beberapa indikator sebagai indikator utama
echo "Menandai indikator utama...\n";
$indikators = Indikator::orderBy('id')->take(5)->get();
foreach($indikators as $i => $indikator) {
    $indikator->is_utama = true;
    $indikator->prioritas = 5 - ($i % 5);
    $indikator->save();
    echo "- Indikator {$indikator->kode} ({$indikator->nama}) ditandai sebagai utama dengan prioritas {$indikator->prioritas}\n";
}

// 2. Pastikan semua indikator memiliki data realisasi untuk tahun dan bulan saat ini
echo "\nMemastikan data realisasi tersedia...\n";
$tahun = Carbon::now()->year;
$bulan = Carbon::now()->month;

$indikators = Indikator::where('aktif', true)->get();
$count = 0;

foreach($indikators as $indikator) {
    $realisasi = Realisasi::where('indikator_id', $indikator->id)
        ->where('tahun', $tahun)
        ->where('bulan', $bulan)
        ->where('periode_tipe', 'bulanan')
        ->first();

    if (!$realisasi) {
        // Buat data realisasi dummy jika belum ada
        $realisasi = new Realisasi();
        $realisasi->indikator_id = $indikator->id;
        $realisasi->user_id = 1; // Admin user
        $realisasi->tanggal = Carbon::now()->format('Y-m-d');
        $realisasi->nilai = rand(70, 100); // Nilai random antara 70-100
        $realisasi->persentase = rand(70, 100); // Persentase random antara 70-100
        $realisasi->keterangan = "Data otomatis dibuat oleh sistem";
        $realisasi->tahun = $tahun;
        $realisasi->bulan = $bulan;
        $realisasi->periode_tipe = 'bulanan';
        $realisasi->diverifikasi = true;
        $realisasi->verifikasi_oleh = 1; // Admin user
        $realisasi->verifikasi_pada = Carbon::now();
        $realisasi->save();

        $count++;
        echo "- Realisasi untuk indikator {$indikator->kode} ({$indikator->nama}) dibuat\n";
    }
}

echo "\nTotal {$count} data realisasi baru dibuat\n";

// 3. Pastikan semua pilar memiliki urutan
echo "\nMemastikan urutan pilar...\n";
$pilars = Pilar::all();
foreach($pilars as $i => $pilar) {
    if (!$pilar->urutan) {
        $pilar->urutan = $i + 1;
        $pilar->save();
        echo "- Pilar {$pilar->kode} ({$pilar->nama}) diatur urutan ke-{$pilar->urutan}\n";
    }
}

echo "\nScript perbaikan data selesai!\n";
