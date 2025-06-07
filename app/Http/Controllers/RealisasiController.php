<?php

namespace App\Http\Controllers;

use App\Models\Realisasi;
use App\Models\Indikator;
use App\Models\TahunPenilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class RealisasiController extends Controller
{
public function index(Request $request)
{
    $user = Auth::user();
    $tahun = $request->tahun ?? Carbon::now()->year;
    $bulan = $request->bulan ?? Carbon::now()->month;
    $periodeTipe = $request->periode_tipe ?? 'bulanan';

    $indikatorsQuery = Indikator::with(['pilar', 'bidang'])
        ->where('aktif', true);

    if ($user->isMasterAdmin()) {
        // Tidak ada filter tambahan
    } elseif ($user->isAdmin()) {
        // Filter berdasarkan bidang admin
        $bidang = $user->getBidang();
        if (!$bidang) {
            return redirect()->route('dashboard')->with('error', 'Bidang tidak ditemukan.');
        }
        $indikatorsQuery->where('bidang_id', $bidang->id);
    } else {
        return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke fitur ini.');
    }

    // Pagination dan query string untuk filter
    $indikators = $indikatorsQuery->orderBy('kode')->paginate(10)->withQueryString();

    // Tambahkan data realisasi ke setiap indikator
    foreach ($indikators as $indikator) {
        $realisasiQuery = Realisasi::where('indikator_id', $indikator->id)
            ->where('user_id', $user->id)
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->where('periode_tipe', $periodeTipe);

        if ($periodeTipe == 'mingguan') {
            $minggu = $request->minggu ?? 1;
            $realisasiQuery->where('minggu', $minggu);
        }

        $realisasi = $realisasiQuery->orderBy('tanggal', 'desc')->first();

        $indikator->realisasi = $realisasi->nilai ?? 0;
        $indikator->persentase = $realisasi->persentase ?? 0;
        $indikator->keterangan = $realisasi->keterangan ?? '';
        $indikator->diverifikasi = $realisasi->diverifikasi ?? false;
        $indikator->nilai_id = $realisasi->id ?? null;
    }

    // ===============================
    // Tambahan untuk data harian
    // ===============================

    $tanggal = $request->input('tanggal', Carbon::now()->toDateString());

    $realisasiHarian = Realisasi::where('user_id', $user->id)
        ->whereDate('tanggal', $tanggal)
        ->with('indikator')
        ->get();

    // Semua indikator aktif untuk referensi input harian
    $indikatorsHarian = Indikator::where('aktif', true)->get();

    return view('realisasi.index', compact(
        'indikators',
        'tahun',
        'bulan',
        'periodeTipe',
        'tanggal',
        'realisasiHarian',
        'indikatorsHarian'
    ));
}

public function create($indikatorId)
{
    $indikator = Indikator::with(['pilar', 'bidang'])->findOrFail($indikatorId);
    $user = Auth::user();

    // Master Admin (asisten_manager) boleh input semua indikator
    if ($user->isMasterAdmin()) {
        // Akses penuh
    }
    // Admin bidang (pic_*) hanya boleh input jika indikator sesuai bidang mereka
    elseif ($user->isAdmin()) {
        $bidang = \App\Models\Bidang::where('role_pic', $user->role)->first();

        if (!$bidang || $bidang->id !== $indikator->bidang_id) {
            abort(403, 'Anda tidak memiliki akses untuk indikator ini.');
        }
    } else {
        abort(403, 'Anda tidak memiliki akses ke fitur ini.');
    }

    return view('realisasi.create', compact('indikator'));
}




public function store(Request $request, Indikator $indikator)
{
    $request->validate([
        'tanggal' => 'required|date',
        'nilai' => 'required|numeric|min:0',
        'keterangan' => 'nullable|string|max:1000',
    ]);

    $tanggal = \Carbon\Carbon::parse($request->tanggal);
    $user = auth()->user();

    // Cek apakah user berwenang menginput indikator ini
    if (!$user->isMasterAdmin() && !$user->isAdmin()) {
        abort(403, 'Anda tidak memiliki hak untuk input realisasi.');
    }

    // Jika admin/pic, cek apakah indikator milik bidang yang sesuai
    if ($user->isAdmin() && $indikator->bidang->role_pic !== $user->role) {
        abort(403, 'Indikator ini tidak termasuk dalam bidang Anda.');
    }


    $realisasi = new Realisasi([
        'indikator_id' => $indikator->id,
        'user_id' => $user->id,
        'tanggal' => $tanggal->toDateString(),
        'tahun' => $tanggal->year,
        'bulan' => $tanggal->month,
        'periode_tipe' => 'harian',
        'nilai' => $request->nilai,
        'persentase' => $indikator->target > 0 ? ($request->nilai / $indikator->target) * 100 : 0,
        'keterangan' => $request->keterangan,
        'diverifikasi' => false,
    ]);

    $realisasi->save();

    return redirect()->route('realisasi.index')->with('success', 'Realisasi berhasil disimpan.');
}




    public function edit(Request $request, $indikatorId)
    {
        $indikator = Indikator::with(['pilar', 'bidang'])->findOrFail($indikatorId);
        $user = Auth::user();

        if ($user->role !== 'master_admin' && $user->bidang_id !== $indikator->bidang_id) {
            abort(403, 'Anda tidak memiliki akses untuk indikator ini.');
        }

        $tahun = $request->input('tahun', date('Y'));
        $bulan = $request->input('bulan', date('n'));
        $periode_tipe = $request->input('periode_tipe', 'bulanan');

        $realisasi = Realisasi::where('indikator_id', $indikator->id)
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->where('periode_tipe', $periode_tipe)
            ->first();

        if (!$realisasi) {
            return redirect()->back()->with('error', 'Data realisasi tidak ditemukan.');
        }

        return view('realisasi.edit', compact('indikator', 'realisasi', 'tahun', 'bulan', 'periode_tipe'));
    }

    public function update(Request $request, $id)
    {
        $realisasi = Realisasi::with('indikator')->findOrFail($id);
        $indikator = $realisasi->indikator;
        $user = Auth::user();

        if ($user->role !== 'master_admin' && $user->bidang_id !== $indikator->bidang_id) {
            abort(403, 'Anda tidak diizinkan mengedit realisasi ini.');
        }

        $validated = $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        $realisasi->update([
            'nilai' => $validated['nilai'],
            'user_id' => $user->id,
            'status' => 'draft',
        ]);

        return redirect()->route('realisasi.index')->with('success', 'Realisasi berhasil diperbarui.');
    }


}
