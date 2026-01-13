<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Header tabel + tombol tambah --}}
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Data SKM</h3>

                        <a href="{{ route('ruangan-periode.create') }}"
                           class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                            <span class="text-lg leading-none">+</span>
                            <span>Tambah Data</span>
                        </a>
                    </div>

                    {{-- Notifikasi sukses --}}
                    @if (session('success'))
                        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Table --}}
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">No. Urut</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Nama Ruangan</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Bulan</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Tahun</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Action</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 bg-white">
                                @php
                                    $dataRows = $rows ?? collect();
                                    // Nomor awal untuk pagination (kalau bukan paginate, default 1)
                                    $startNo = method_exists($dataRows, 'firstItem') ? ($dataRows->firstItem() ?? 1) : 1;
                                @endphp

                                @forelse($dataRows as $row)
                                    <tr class="hover:bg-gray-50">
                                        {{-- ✅ No urut mengikuti urutan data hasil query (dan konsisten saat pagination) --}}
                                        <td class="px-4 py-3 text-sm text-gray-800">{{ $startNo + $loop->index }}</td>

                                        <td class="px-4 py-3 text-sm text-gray-800">{{ $row->nama_ruangan }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-800">{{ $row->bulan }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-800">{{ $row->tahun }}</td>

                                        <td class="px-4 py-3 text-sm">
                                            <div class="flex items-center gap-2">

                                                {{-- Detail --}}
                                                <a href="{{ route('ruangan-periode.detail.index', $row->id) }}"
                                                   class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">
                                                    Tambah Detail
                                                </a>

                                                {{-- Hapus --}}
                                                <form action="{{ route('ruangan-periode.destroy', $row->id) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Yakin mau hapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700">
                                                        Hapus
                                                    </button>
                                                </form>

                                                {{-- Preview (di sebelah kanan Delete) --}}
                                                <a href="{{ route('ruangan-periode.preview', $row->id) }}"
                                                   class="rounded-lg bg-slate-700 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800">
                                                    Preview
                                                </a>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">
                                            Belum ada data.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if(isset($rows) && method_exists($rows, 'links'))
                        <div class="mt-4">
                            {{ $rows->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
