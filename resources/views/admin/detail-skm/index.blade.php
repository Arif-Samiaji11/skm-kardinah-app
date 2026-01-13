@extends('layouts.admin')

@section('title', 'Detail SKM')

@section('content')
<h1 class="text-3xl font-bold text-slate-800 mb-4">
    Detail SKM
</h1>

{{-- FILTER BULAN & TAHUN --}}
<form method="GET" action="{{ route('admin.detail-skm.index') }}" class="mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center gap-3">

        {{-- FILTER BAR --}}
        <div class="flex items-center gap-0 bg-slate-50 border border-slate-200 rounded-xl shadow-sm overflow-hidden">

            {{-- BULAN --}}
            <div class="flex items-center gap-2 px-3 py-2">
                <span class="text-sm text-slate-600 whitespace-nowrap">Bulan</span>

                <div class="relative">
                    <select name="bulan"
                        class="appearance-none bg-white border border-slate-200 rounded-lg
                               pl-3 pr-9 py-2 text-sm text-slate-800
                               hover:border-slate-300 focus:border-slate-400
                               focus:outline-none focus:ring-2 focus:ring-slate-200 transition">
                        <option value="">Semua</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ (string)request('bulan') === (string)$i ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                            </option>
                        @endfor
                    </select>

                    {{-- Chevron --}}
                    <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>

            {{-- Separator --}}
            <div class="hidden sm:block w-px self-stretch bg-slate-200"></div>

            {{-- TAHUN --}}
            <div class="flex items-center gap-2 px-3 py-2">
                <span class="text-sm text-slate-600 whitespace-nowrap">Tahun</span>

                <div class="relative">
                    <select name="tahun"
                        class="appearance-none bg-white border border-slate-200 rounded-lg
                               pl-3 pr-9 py-2 text-sm text-slate-800
                               hover:border-slate-300 focus:border-slate-400
                               focus:outline-none focus:ring-2 focus:ring-slate-200 transition">
                        <option value="">Semua</option>
                        @for ($year = now()->year; $year >= 2020; $year--)
                            <option value="{{ $year }}" {{ (string)request('tahun') === (string)$year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>

                    {{-- Chevron --}}
                    <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- ACTION BUTTON --}}
        <div class="flex gap-2">
            <button type="submit"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold
                       rounded-xl border border-slate-200 bg-white text-slate-700
                       hover:bg-slate-50 transition">
                Terapkan
            </button>

            <a href="{{ route('admin.detail-skm.index') }}"
               class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold
                      rounded-xl border border-slate-200 bg-white text-slate-700
                      hover:bg-slate-50 transition">
                Reset
            </a>
        </div>
    </div>
</form>


<div class="space-y-6">
    @forelse($units as $unit)
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-slate-800">
                    {{ $unit->nama_unit }}
                </h2>
                <p class="text-sm text-slate-500">
                    Total data SKM:
                    <span class="font-semibold">{{ $unit->skmPeriode->count() }}</span>
                </p>
            </div>

            <div class="p-6 overflow-x-auto">
                @if($unit->skmPeriode->isEmpty())
                    <p class="text-slate-600">Belum ada data SKM untuk unit ini.</p>
                @else
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-slate-600 border-b">
                                <th class="py-2 pr-4">Nama Ruangan</th>
                                <th class="py-2 pr-4">Bulan</th>
                                <th class="py-2 pr-4">Tahun</th>
                                <th class="py-2 pr-4">IKM</th>
                                <th class="py-2 pr-4">NRR Total</th>
                                <th class="py-2 pr-4">Total Respon</th>
                                <th class="py-2 pr-4">Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unit->skmPeriode as $row)
                                <tr class="border-b last:border-b-0">
                                    <td class="py-2 pr-4 text-slate-800">{{ $row->nama_ruangan ?? '-' }}</td>
                                    <td class="py-2 pr-4 text-slate-700">{{ $row->bulan ?? '-' }}</td>
                                    <td class="py-2 pr-4 text-slate-700">{{ $row->tahun ?? '-' }}</td>
                                    <td class="py-2 pr-4 text-slate-800">
                                        {{ $row->ikm !== null ? number_format($row->ikm, 1) : '-' }}
                                    </td>
                                    <td class="py-2 pr-4 text-slate-700">
                                        {{ $row->nrr_total !== null ? number_format($row->nrr_total, 2) : '-' }}
                                    </td>
                                    <td class="py-2 pr-4 text-slate-700">{{ $row->total_respon ?? '-' }}</td>

                                    <td class="py-2 pr-4 text-slate-700">
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="whitespace-nowrap">{{ $row->kategori_ikm }}</span>

                                            <a href="{{ route('admin.detail-skm.show', $row->id) }}"
                                               class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50">
                                                Detail
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @empty
        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-slate-600">Belum ada data unit ruangan.</p>
        </div>
    @endforelse
</div>
@endsection
