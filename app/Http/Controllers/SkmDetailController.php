<?php

namespace App\Http\Controllers;

use App\Models\RuanganPeriode;
use App\Models\SkmDetail;
use App\Models\SkmDetailRevision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkmDetailController extends Controller
{
    public function index(RuanganPeriode $ruangan_periode)
    {
        $parent = $ruangan_periode;

        $details = SkmDetail::where('skm_id', $parent->id)
            ->orderBy('no_urut')     // ✅ tampil berurutan 1..n
            ->orderBy('id')          // fallback bila ada no_urut sama/null
            ->paginate(10);

        return view('skm_detail.index', compact('parent', 'details'));
    }

    public function create(RuanganPeriode $ruangan_periode)
    {
        $parent = $ruangan_periode;

        return view('skm_detail.create', compact('parent'));
    }

    public function store(Request $request, RuanganPeriode $ruangan_periode)
    {
        $parent = $ruangan_periode;

        $validated = $request->validate([
            'u1' => ['required', 'integer', 'in:1,2,3,4'],
            'u2' => ['required', 'integer', 'in:1,2,3,4'],
            'u3' => ['required', 'integer', 'in:1,2,3,4'],
            'u4' => ['required', 'integer', 'in:1,2,3,4'],
            'u5' => ['required', 'integer', 'in:1,2,3,4'],
            'u6' => ['required', 'integer', 'in:1,2,3,4'],
            'u7' => ['required', 'integer', 'in:1,2,3,4'],
            'u8' => ['required', 'integer', 'in:1,2,3,4'],
            'u9' => ['required', 'integer', 'in:1,2,3,4'],
        ]);

        $validated['skm_id'] = $parent->id;

        // ✅ auto no_urut (aman dengan transaction + lock)
        DB::transaction(function () use (&$validated, $parent) {
            $nextNo = SkmDetail::where('skm_id', $parent->id)
                ->lockForUpdate()
                ->max('no_urut');

            $validated['no_urut'] = $nextNo ? ($nextNo + 1) : 1;

            SkmDetail::create($validated);
        });

        // ✅ Redirect setelah simpan:
        // - kalau dari preview/modal dan mengirim redirect_to => balik ke halaman itu
        // - kalau tidak ada => tetap ke dashboard (sesuai behavior awal kamu)
        $redirectTo = $request->input('redirect_to');
        if ($redirectTo) {
            return redirect($redirectTo)->with('success', 'Detail berhasil ditambahkan.');
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'Detail berhasil ditambahkan.');
    }

    public function edit(RuanganPeriode $ruangan_periode, SkmDetail $detail)
    {
        $parent = $ruangan_periode;

        // Guard: pastikan detail milik parent
        abort_unless((int) $detail->skm_id === (int) $parent->id, 404);

        return view('skm_detail.edit', compact('parent', 'detail'));
    }

    public function update(Request $request, RuanganPeriode $ruangan_periode, SkmDetail $detail)
    {
        $parent = $ruangan_periode;

        abort_unless((int) $detail->skm_id === (int) $parent->id, 404);

        $validated = $request->validate([
            'u1' => ['required', 'integer', 'in:1,2,3,4'],
            'u2' => ['required', 'integer', 'in:1,2,3,4'],
            'u3' => ['required', 'integer', 'in:1,2,3,4'],
            'u4' => ['required', 'integer', 'in:1,2,3,4'],
            'u5' => ['required', 'integer', 'in:1,2,3,4'],
            'u6' => ['required', 'integer', 'in:1,2,3,4'],
            'u7' => ['required', 'integer', 'in:1,2,3,4'],
            'u8' => ['required', 'integer', 'in:1,2,3,4'],
            'u9' => ['required', 'integer', 'in:1,2,3,4'],
        ]);

        // ✅ SIMPAN snapshot nilai lama sebelum update (untuk Undo setelah save)
        DB::transaction(function () use ($detail, $validated) {
            SkmDetailRevision::create([
                'skm_detail_id' => $detail->id,
                'u1' => (int) $detail->u1,
                'u2' => (int) $detail->u2,
                'u3' => (int) $detail->u3,
                'u4' => (int) $detail->u4,
                'u5' => (int) $detail->u5,
                'u6' => (int) $detail->u6,
                'u7' => (int) $detail->u7,
                'u8' => (int) $detail->u8,
                'u9' => (int) $detail->u9,
            ]);

            $detail->update($validated);
        });

        // ✅ BALIK KE HALAMAN SEMULA (preview) kalau ada redirect_to dari form modal
        // default fallback: tetap ke index detail seperti sebelumnya (biar tidak merusak alur lama)
        $redirectTo = $request->input('redirect_to');
        if ($redirectTo) {
            return redirect($redirectTo)->with('success', 'Detail berhasil diupdate.');
        }

        return redirect()
            ->route('ruangan-periode.detail.index', $parent->id)
            ->with('success', 'Detail berhasil diupdate.');
    }

    /**
     * ✅ UNDO (REVERT) setelah save:
     * mengembalikan data ke nilai sebelumnya yang tersimpan di DB (revision terakhir).
     */
    public function undo(Request $request, RuanganPeriode $ruangan_periode, SkmDetail $detail)
    {
        $parent = $ruangan_periode;

        abort_unless((int) $detail->skm_id === (int) $parent->id, 404);

        $redirectTo = $request->input('redirect_to');
        if (!$redirectTo) {
            $redirectTo = route('ruangan-periode.detail.index', $parent->id);
        }

        $lastRevision = SkmDetailRevision::where('skm_detail_id', $detail->id)
            ->orderByDesc('id')
            ->first();

        if (!$lastRevision) {
            return redirect($redirectTo)->with('success', 'Tidak ada data sebelumnya untuk di-undo.');
        }

        DB::transaction(function () use ($detail, $lastRevision) {
            // kembalikan nilai detail ke snapshot terakhir
            $detail->update([
                'u1' => (int) $lastRevision->u1,
                'u2' => (int) $lastRevision->u2,
                'u3' => (int) $lastRevision->u3,
                'u4' => (int) $lastRevision->u4,
                'u5' => (int) $lastRevision->u5,
                'u6' => (int) $lastRevision->u6,
                'u7' => (int) $lastRevision->u7,
                'u8' => (int) $lastRevision->u8,
                'u9' => (int) $lastRevision->u9,
            ]);

            // ✅ hapus revision terakhir agar undo bisa bertahap (stack)
            $lastRevision->delete();
        });

        return redirect($redirectTo)->with('success', 'Undo berhasil. Data dikembalikan ke nilai sebelumnya.');
    }

    public function destroy(Request $request, RuanganPeriode $ruangan_periode, SkmDetail $detail)
    {
        $parent = $ruangan_periode;

        abort_unless((int) $detail->skm_id === (int) $parent->id, 404);

        DB::transaction(function () use ($parent, $detail) {
            $detail->delete();

            // ✅ resequence no_urut => 1..n lagi setelah delete
            $rows = SkmDetail::where('skm_id', $parent->id)
                ->orderBy('no_urut')
                ->orderBy('id')
                ->lockForUpdate()
                ->get(['id', 'no_urut']);

            $i = 1;
            foreach ($rows as $row) {
                if ((int) $row->no_urut !== $i) {
                    SkmDetail::where('id', $row->id)->update(['no_urut' => $i]);
                }
                $i++;
            }
        });

        // ✅ BALIK KE HALAMAN SEMULA (preview) kalau delete dipanggil dari preview dan ada redirect_to
        // default fallback: tetap ke index detail seperti sebelumnya
        $redirectTo = $request->input('redirect_to');
        if ($redirectTo) {
            return redirect($redirectTo)->with('success', 'Detail berhasil dihapus.');
        }

        return redirect()
            ->route('ruangan-periode.detail.index', $parent->id)
            ->with('success', 'Detail berhasil dihapus.');
    }

    // Tidak dipakai
    public function show() {}
}
