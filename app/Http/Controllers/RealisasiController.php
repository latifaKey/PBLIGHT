<?php

namespace App\Http\Controllers;

use App\Models\Realisasi;
use App\Models\Indikator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RealisasiController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $bulan = $request->input('bulan', date('n'));
        $periode_tipe = $request->input('periode_tipe', 'bulanan');

        $user = Auth::user();
        $isMaster = $user->role === 'master_admin';

        $indikators = Indikator::with([
            'pilar', 'bidang',
            'realisasi' => function ($query) use ($tahun, $bulan, $periode_tipe) {
                $query->where('tahun', $tahun)
                      ->where('bulan', $bulan)
                      ->where('periode_tipe', $periode_tipe);
            }
        ]);

        if (!$isMaster) {
            $indikators->where('bidang_id', $user->bidang_id);
        }
    $bidang = null;
    if (!$isMaster) {
        $indikators->where('bidang_id', $user->bidang_id);
        $bidang = optional($user->bidang)->nama; // pastikan relasi bidang ada di model User
    }
        $indikators = $indikators->orderBy('kode')->get();

        return view('realisasi.index', compact('indikators', 'tahun', 'bulan', 'periode_tipe','bidang'));
    }

    public function create($indikatorId)
    {
        $indikator = Indikator::with(['pilar', 'bidang'])->findOrFail($indikatorId);
        $user = Auth::user();

        if ($user->role !== 'master_admin' && $user->bidang_id !== $indikator->bidang_id) {
            abort(403, 'Anda tidak memiliki akses untuk indikator ini.');
        }

        return view('realisasi.create', compact('indikator'));
    }

    public function store(Request $request, $indikatorId)
    {
        $indikator = Indikator::findOrFail($indikatorId);
        $user = Auth::user();

        if ($user->role !== 'master_admin' && $user->bidang_id !== $indikator->bidang_id) {
            abort(403, 'Anda tidak diizinkan menginput realisasi untuk indikator ini.');
        }

        $validated = $request->validate([
            'tahun' => 'required|integer',
            'bulan' => 'required|integer',
            'periode_tipe' => 'required|in:bulanan,semesteran,tahunan',
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        Realisasi::updateOrCreate(
            [
                'indikator_id' => $indikator->id,
                'tahun' => $validated['tahun'],
                'bulan' => $validated['bulan'],
                'periode_tipe' => $validated['periode_tipe'],
            ],
            [
                'nilai' => $validated['nilai'],
                'user_id' => $user->id,
                'verifikator_id' => null,
                'status' => 'draft',
            ]
        );

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

    public function show($id)
    {
        $realisasi = Realisasi::with('indikator', 'user')->findOrFail($id);
        return view('realisasi.show', compact('realisasi'));
    }
}
