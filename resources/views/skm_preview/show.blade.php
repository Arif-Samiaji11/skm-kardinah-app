<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Preview SKM
            </h2>

            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <style>[x-cloak]{display:none !important;}</style>

    @php
        $count = $details->count();
        $bobot = 1 / 9;

        // ===== Perhitungan yang lebih akurat (hindari rounding bertingkat) =====
        $sumU = [];
        $nrrU_raw = [];
        $nrrU = [];
        $nrrTertimbangU_raw = [];
        $nrrTertimbangU = [];
        $nilaiKonversiU = [];

        $sumNrrTertimbang_raw = 0;

        for ($i = 1; $i <= 9; $i++) {
            $col = 'u'.$i;

            $sumU[$col] = (float) ($details->sum($col) ?? 0);

            $nrrU_raw[$col] = $count > 0 ? ($sumU[$col] / $count) : null;

            $nrrU[$col] = $nrrU_raw[$col] !== null ? round($nrrU_raw[$col], 2) : null;

            $nrrTertimbangU_raw[$col] = $nrrU_raw[$col] !== null ? ($nrrU_raw[$col] * $bobot) : null;

            if ($nrrTertimbangU_raw[$col] !== null) {
                $sumNrrTertimbang_raw += $nrrTertimbangU_raw[$col];
            }

            $nrrTertimbangU[$col] = $nrrTertimbangU_raw[$col] !== null ? round($nrrTertimbangU_raw[$col], 4) : null;

            $nilaiKonversiU[$col] = $nrrU_raw[$col] !== null ? round($nrrU_raw[$col] * 25, 2) : null;
        }

        $sumNrrTertimbang = $count > 0 ? round($sumNrrTertimbang_raw, 4) : null;
        $ikmUnitPelayanan = ($sumNrrTertimbang !== null) ? round($sumNrrTertimbang_raw * 25, 2) : null;

        $kategoriNilai = function ($nilai) {
            if ($nilai === null) return '-';
            if ($nilai >= 88.31 && $nilai <= 100.00) return 'SANGAT BAIK';
            if ($nilai >= 76.61 && $nilai <= 88.30)  return 'BAIK';
            if ($nilai >= 65.00 && $nilai <= 76.60)  return 'KURANG BAIK';
            if ($nilai >= 25.00 && $nilai <= 64.99)  return 'TIDAK BAIK';
            return '-';
        };

        $kategoriIkmUnit = $kategoriNilai($ikmUnitPelayanan);

        // ✅ URL halaman saat ini (preview) untuk redirect balik setelah update/delete/undo
        $redirectTo = url()->current();

        $flashUndoDetailId = session('undo_detail_id');

        /**
         * ==========================================================
         * ✅ SIMPAN IKM & KATEGORI KE DATABASE (PARENT)
         * ==========================================================
         * Aman:
         * - Cek kolom ada / tidak
         * - Hanya update jika berubah
         * - Tidak bikin halaman crash
         */
        try {
            // ✅ Jangan simpan jika belum ada responden
            if ($ikmUnitPelayanan !== null) {

                // Cek kolom ada di table skm
                $columns = \Illuminate\Support\Facades\Schema::getColumnListing($parent->getTable());

                $canSaveIkm = in_array('ikm', $columns, true);
                $canSaveKategori = in_array('kategori_ikm', $columns, true);
                $canSaveTotalRespon = in_array('total_respon', $columns, true);

                // Ambil nilai DB yang sekarang
                $ikmDB = $parent->ikm;
                $kategoriDB = $parent->kategori_ikm;

                // Compare perubahan
                $ikmChanged = $canSaveIkm && (($ikmDB === null) || (abs((float)$ikmDB - (float)$ikmUnitPelayanan) > 0.0001));
                $kategoriChanged = $canSaveKategori && ((string)($kategoriDB ?? '') !== (string)($kategoriIkmUnit ?? ''));

                $payloadUpdate = [];

                if ($ikmChanged) {
                    $payloadUpdate['ikm'] = $ikmUnitPelayanan;
                }
                if ($kategoriChanged) {
                    $payloadUpdate['kategori_ikm'] = $kategoriIkmUnit;
                }
                if ($canSaveTotalRespon) {
                    // optional biar konsisten
                    $payloadUpdate['total_respon'] = $count;
                }

                // Update hanya jika ada yang berubah
                if (!empty($payloadUpdate)) {
                    $parent->forceFill($payloadUpdate)->save();
                }
            }
        } catch (\Throwable $e) {
            // Jangan bikin halaman crash; kalau mau debug:
            // \Log::error($e->getMessage());
        }
    @endphp

    <div class="py-12"
         x-data="{
            openEdit: false,
            editAction: '',

            form: { u1: 1,u2: 1,u3: 1,u4: 1,u5: 1,u6: 1,u7: 1,u8: 1,u9: 1 },
            original: { u1: 1,u2: 1,u3: 1,u4: 1,u5: 1,u6: 1,u7: 1,u8: 1,u9: 1 },

            openModal(detail) {
                const initial = {
                    u1: Number(detail.u1), u2: Number(detail.u2), u3: Number(detail.u3),
                    u4: Number(detail.u4), u5: Number(detail.u5), u6: Number(detail.u6),
                    u7: Number(detail.u7), u8: Number(detail.u8), u9: Number(detail.u9),
                };

                this.original = JSON.parse(JSON.stringify(initial));
                this.form     = JSON.parse(JSON.stringify(initial));

                this.editAction = detail.update_url;
                this.openEdit = true;
            },

            closeModal() { this.openEdit = false; },

            isDirty() {
                return JSON.stringify(this.form) !== JSON.stringify(this.original);
            },

            undo() {
                this.form = JSON.parse(JSON.stringify(this.original));
            },

            submitIfDirty(e) {
                if (!this.isDirty()) {
                    e.preventDefault();
                    this.closeModal();
                    return false;
                }
                return true;
            },

            init() {
                @if($errors->any() && old('from_edit') === '1')
                    this.openEdit = true;
                    this.editAction = @js(old('edit_action', ''));

                    const oldVals = {
                        u1: Number(@js(old('u1', 1))),
                        u2: Number(@js(old('u2', 1))),
                        u3: Number(@js(old('u3', 1))),
                        u4: Number(@js(old('u4', 1))),
                        u5: Number(@js(old('u5', 1))),
                        u6: Number(@js(old('u6', 1))),
                        u7: Number(@js(old('u7', 1))),
                        u8: Number(@js(old('u8', 1))),
                        u9: Number(@js(old('u9', 1))),
                    };

                    this.original = JSON.parse(JSON.stringify(oldVals));
                    this.form     = JSON.parse(JSON.stringify(oldVals));
                @endif
            }
         }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any() && old('from_edit') !== '1')
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <div class="font-semibold">Terjadi error:</div>
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- INFO PARENT (ID SKM DIHAPUS) --}}
                    <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                            <div class="rounded-lg border border-gray-200 bg-white p-3 text-center">
                                <span class="text-xs text-gray-500">Nama Ruangan</span>
                                <div class="mt-1 font-semibold text-gray-800">{{ $parent->nama_ruangan }}</div>
                            </div>
                            <div class="rounded-lg border border-gray-200 bg-white p-3 text-center">
                                <span class="text-xs text-gray-500">Bulan</span>
                                <div class="mt-1 font-semibold text-gray-800">{{ $parent->bulan }}</div>
                            </div>
                            <div class="rounded-lg border border-gray-200 bg-white p-3 text-center">
                                <span class="text-xs text-gray-500">Tahun</span>
                                <div class="mt-1 font-semibold text-gray-800">{{ $parent->tahun }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- RINGKASAN --}}
                    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-4">
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-4">
                            <div>
                                <span class="text-xs text-gray-500">Total Respon</span>
                                <div class="font-semibold text-gray-800">{{ $count }}</div>
                            </div>

                            <div>
                                <span class="text-xs text-gray-500">SUM NRR Tertimbang</span>
                                <div class="font-semibold text-gray-800">{{ $sumNrrTertimbang !== null ? $sumNrrTertimbang : '-' }}</div>
                            </div>

                            <div>
                                <span class="text-xs text-gray-500">IKM Unit Pelayanan (N41×25)</span>
                                <div class="font-semibold text-gray-800">{{ $ikmUnitPelayanan !== null ? $ikmUnitPelayanan : '-' }}</div>
                            </div>

                            <div>
                                <span class="text-xs text-gray-500">Kategori</span>
                                <div class="font-semibold text-gray-800">{{ $kategoriIkmUnit }}</div>
                            </div>
                        </div>

                        <div class="mt-2 text-xs text-gray-500">
                            <div><b>NILAI/UNSUR</b> = TOTAL (SUM) per kolom.</div>
                            <div><b>NRR/UNSUR</b> = SUM/COUNT.</div>
                            <div><b>NRR tertimbang/unsur</b> = NRR × bobot ({{ round($bobot, 6) }}).</div>
                            <div><b>IKM Unit Pelayanan</b> = (SUM NRR tertimbang) × 25 (padanan <code>N41*25</code>).</div>
                        </div>
                    </div>

                    {{-- TABLE DETAIL --}}
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        No. Urut
                                    </th>
                                    @for ($i=1; $i<=9; $i++)
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            U{{ $i }}
                                        </th>
                                    @endfor
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Created
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($details as $d)
                                    @php
                                        $payload = [
                                            'u1' => (int) $d->u1, 'u2' => (int) $d->u2, 'u3' => (int) $d->u3,
                                            'u4' => (int) $d->u4, 'u5' => (int) $d->u5, 'u6' => (int) $d->u6,
                                            'u7' => (int) $d->u7, 'u8' => (int) $d->u8, 'u9' => (int) $d->u9,
                                            'update_url' => route('ruangan-periode.detail.update', [$parent->id, $d->id]),
                                        ];

                                        $canUndoBackend = (bool)($d->can_undo ?? $d->has_undo ?? false) || ($flashUndoDetailId == $d->id);
                                    @endphp

                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm font-semibold text-gray-800">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td class="px-4 py-3 text-sm text-gray-800">{{ $d->u1 }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-800">{{ $d->u2 }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-800">{{ $d->u3 }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-800">{{ $d->u4 }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-800">{{ $d->u5 }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-800">{{ $d->u6 }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-800">{{ $d->u7 }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-800">{{ $d->u8 }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-800">{{ $d->u9 }}</td>

                                        <td class="px-4 py-3 text-sm text-gray-500">
                                            {{ $d->created_at?->format('Y-m-d H:i') }}
                                        </td>

                                        <td class="px-4 py-3 text-sm">
                                            <div class="flex items-center gap-2">
                                                @if($canUndoBackend)
                                                    <form method="POST"
                                                          action="{{ route('ruangan-periode.detail.undo', [$parent->id, $d->id]) }}"
                                                          onsubmit="return confirm('Undo perubahan terakhir untuk baris ini?');">
                                                        @csrf
                                                        <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">
                                                        <button type="submit"
                                                                class="rounded-lg border px-2.5 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                                                                title="Undo (kembalikan ke data tersimpan sebelumnya)">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                                 fill="none" stroke="currentColor" stroke-width="2"
                                                                 stroke-linecap="round" stroke-linejoin="round"
                                                                 class="h-4 w-4">
                                                                <path d="M3 7v6h6"></path>
                                                                <path d="M21 17a9 9 0 0 0-15-6l-3 2"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif

                                                <button
                                                    type="button"
                                                    class="rounded-lg border px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                                                    data-detail='@json($payload)'
                                                    @click="openModal(JSON.parse($el.dataset.detail))"
                                                >
                                                    Edit
                                                </button>

                                                <form method="POST"
                                                      action="{{ route('ruangan-periode.detail.destroy', [$parent->id, $d->id]) }}"
                                                      onsubmit="return confirm('Yakin ingin menghapus baris ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">
                                                    <button type="submit"
                                                            class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100">
                                                        Hapus
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="px-4 py-8 text-center text-sm text-gray-500">
                                            Belum ada detail untuk SKM ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                            {{-- FOOTER --}}
                            @if($count > 0)
                                <tfoot class="bg-gray-50 border-t border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700">
                                            NILAI/UNSUR
                                        </th>
                                        @for($i=1; $i<=9; $i++)
                                            @php $col = 'u'.$i; @endphp
                                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">
                                                {{ (floor($sumU[$col]) == $sumU[$col]) ? (int)$sumU[$col] : $sumU[$col] }}
                                            </td>
                                        @endfor
                                        <td class="px-4 py-3 text-sm text-gray-500">-</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">-</td>
                                    </tr>

                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700">
                                            NRR/UNSUR
                                        </th>
                                        @for($i=1; $i<=9; $i++)
                                            @php $col = 'u'.$i; @endphp
                                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">
                                                {{ $nrrU[$col] !== null ? $nrrU[$col] : '-' }}
                                            </td>
                                        @endfor
                                        <td class="px-4 py-3 text-sm text-gray-500">-</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">-</td>
                                    </tr>

                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700">
                                            NRR tertimbang per unsur
                                        </th>
                                        @for($i=1; $i<=9; $i++)
                                            @php $col = 'u'.$i; @endphp
                                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">
                                                {{ $nrrTertimbangU[$col] !== null ? $nrrTertimbangU[$col] : '-' }}
                                            </td>
                                        @endfor
                                        <td class="px-4 py-3 text-sm font-bold text-gray-900">
                                            Total: {{ $sumNrrTertimbang !== null ? $sumNrrTertimbang : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">-</td>
                                    </tr>

                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700">
                                            IKM Unit Pelayanan
                                        </th>
                                        @for($i=1; $i<=9; $i++)
                                            <td class="px-4 py-3 text-sm text-gray-400">-</td>
                                        @endfor
                                        <td class="px-4 py-3 text-sm font-extrabold text-gray-900">
                                            {{ $ikmUnitPelayanan !== null ? $ikmUnitPelayanan : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">-</td>
                                    </tr>

                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700">
                                            NILAI (NRR×25)
                                        </th>
                                        @for($i=1; $i<=9; $i++)
                                            @php $col = 'u'.$i; @endphp
                                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">
                                                {{ $nilaiKonversiU[$col] !== null ? $nilaiKonversiU[$col] : '-' }}
                                            </td>
                                        @endfor
                                        <td class="px-4 py-3 text-sm text-gray-500">-</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">-</td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>

                    {{-- MODAL EDIT --}}
                    <div x-show="openEdit" x-cloak
                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
                         @keydown.escape.window="closeModal()"
                         @click.self="closeModal()">
                        <div class="w-full max-w-2xl rounded-xl bg-white shadow-lg">
                            <div class="flex items-center justify-between border-b px-5 py-4">
                                <div class="font-semibold text-gray-800">Edit Detail</div>
                                <button type="button"
                                        class="rounded-lg border px-3 py-1.5 text-sm hover:bg-gray-50"
                                        @click="closeModal()">
                                    Tutup
                                </button>
                            </div>

                            <form method="POST" :action="editAction" class="p-5" @submit="submitIfDirty($event)">
                                @csrf
                                @method('PATCH')

                                <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">
                                <input type="hidden" name="from_edit" value="1">
                                <input type="hidden" name="edit_action" :value="editAction">

                                <div class="mb-4 flex items-center justify-between gap-2">
                                    <div class="text-xs text-gray-500">
                                        Undo di sini hanya membatalkan perubahan yang belum disimpan (di modal).
                                    </div>

                                    <button type="button"
                                            x-show="isDirty()"
                                            x-cloak
                                            class="inline-flex items-center gap-2 rounded-lg border px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                                            @click="undo()"
                                            title="Undo (kembalikan nilai select ke awal saat modal dibuka)">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="h-4 w-4">
                                            <path d="M3 7v6h6"></path>
                                            <path d="M21 17a9 9 0 0 0-15-6l-3 2"></path>
                                        </svg>
                                        Undo
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                    @for($i=1; $i<=9; $i++)
                                        <div>
                                            <label class="mb-1 block text-xs font-semibold text-gray-600">U{{ $i }}</label>
                                            <select class="w-full rounded-lg border-gray-300 text-sm"
                                                    x-model.number="form.u{{ $i }}"
                                                    name="u{{ $i }}">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                            </select>
                                        </div>
                                    @endfor
                                </div>

                                @if($errors->any() && old('from_edit') === '1')
                                    <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                                        <div class="font-semibold">Terjadi error:</div>
                                        <ul class="list-disc pl-5">
                                            @foreach($errors->all() as $e)
                                                <li>{{ $e }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="mt-5 flex items-center justify-end gap-2">
                                    <button type="button"
                                            class="rounded-lg border px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                                            @click="closeModal()">
                                        Batal
                                    </button>

                                    <button type="submit"
                                            :disabled="!isDirty()"
                                            :class="!isDirty()
                                                ? 'opacity-50 cursor-not-allowed bg-gray-700'
                                                : 'bg-gray-900 hover:bg-gray-800'"
                                            class="rounded-lg px-4 py-2 text-sm font-semibold text-white">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- /MODAL EDIT --}}

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
