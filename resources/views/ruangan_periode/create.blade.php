<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Data Ruangan
        </h2>
    </x-slot>

    @php
        $tahunSekarang = now()->year; // contoh: 2026
        $tahunList = [$tahunSekarang, $tahunSekarang - 1, $tahunSekarang - 2];
        $tahunSelected = old('tahun', $tahunSekarang);
    @endphp

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- Error global --}}
                    @if ($errors->any())
                        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <div class="font-semibold mb-1">Ada input yang belum benar:</div>
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('ruangan-periode.store') }}" class="space-y-4">
                        @csrf

                        {{-- Nama Ruangan: ambil dari tabel nama_ruangan sesuai login (unit_ruangan_id) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Ruangan</label>

                            <select name="nama_ruangan"
                                    required
                                    class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Pilih Nama Ruangan --</option>

                                @foreach($namaRuangan as $nr)
                                    <option value="{{ $nr->nama_ruangan }}"
                                        {{ old('nama_ruangan') == $nr->nama_ruangan ? 'selected' : '' }}>
                                        {{ $nr->nama_ruangan }}
                                    </option>
                                @endforeach
                            </select>

                            <p class="mt-1 text-xs text-gray-500">
                                List ruangan ini otomatis sesuai akun yang login.
                            </p>

                            @error('nama_ruangan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            {{-- Bulan --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Bulan</label>

                                <select name="bulan"
                                        required
                                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-- Pilih Bulan --</option>
                                    @for ($b = 1; $b <= 12; $b++)
                                        <option value="{{ $b }}" {{ (int) old('bulan') === $b ? 'selected' : '' }}>
                                            {{ $b }}
                                        </option>
                                    @endfor
                                </select>

                                @error('bulan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tahun (dropdown: sekarang s/d mundur 2 tahun) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tahun</label>

                                <select name="tahun"
                                        required
                                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-- Pilih Tahun --</option>

                                    @foreach($tahunList as $t)
                                        <option value="{{ $t }}" {{ (int) $tahunSelected === (int) $t ? 'selected' : '' }}>
                                            {{ $t }}
                                        </option>
                                    @endforeach
                                </select>

                                <p class="mt-1 text-xs text-gray-500">
                                    Tahun hanya tersedia: {{ $tahunSekarang }}, {{ $tahunSekarang - 1 }}, {{ $tahunSekarang - 2 }}.
                                </p>

                                @error('tahun')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('ruangan-periode.index') }}"
                               class="rounded-lg border px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Kembali
                            </a>

                            <button type="submit"
                                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                                Simpan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
