<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Detail SKM
        </h2>
    </x-slot>

    @php
        // Yang KETERANGAN NILAI = angka (1-4) dari value option
        // Dibalik sesuai permintaan:
        // 4 = Sangat Baik, 3 = Baik, 2 = Kurang Baik, 1 = Tidak Baik
        $opsiNilai = [
            4 => 'Sangat Baik',
            3 => 'Baik',
            2 => 'Kurang Baik',
            1 => 'Tidak Baik',
        ];
    @endphp

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- Info Parent (data dari model RuanganPeriode -> tabel skm) --}}
                    <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                            <div>
                                <span class="text-xs text-gray-500">Nama Ruangan</span>
                                <div class="font-semibold text-gray-800">{{ $parent->nama_ruangan }}</div>
                            </div>

                            <div>
                                <span class="text-xs text-gray-500">Bulan</span>
                                <div class="font-semibold text-gray-800">{{ $parent->bulan }}</div>
                            </div>

                            <div>
                                <span class="text-xs text-gray-500">Tahun</span>
                                <div class="font-semibold text-gray-800">{{ $parent->tahun }}</div>
                            </div>
                        </div>
                    </div>

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

                    <form method="POST"
                          action="{{ route('ruangan-periode.detail.store', $parent->id) }}"
                          class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            @for ($i=1; $i<=9; $i++)
                                @php
                                    $oldVal = old('u'.$i);
                                @endphp

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        U{{ $i }}
                                    </label>

                                    <select name="u{{ $i }}"
                                            required
                                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">-- Pilih Nilai --</option>

                                        @foreach($opsiNilai as $val => $label)
                                            <option value="{{ $val }}"
                                                {{ (string)$oldVal === (string)$val ? 'selected' : '' }}>
                                                {{ $val }}. {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <p class="mt-1 text-xs text-gray-500">
                                        KETERANGAN NILAI: 4=Sangat Baik, 3=Baik, 2=Kurang Baik, 1=Tidak Baik.
                                    </p>

                                    @error('u'.$i)
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endfor
                        </div>

                        <div class="flex items-center gap-2">
                            {{-- KEMBALI -> DASHBOARD --}}
                            <a href="{{ route('dashboard') }}"
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
