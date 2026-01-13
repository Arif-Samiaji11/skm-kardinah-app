@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<h1 class="text-3xl font-bold text-slate-800 mb-6">
    Dashboard Admin
</h1>

<div class="bg-white p-6 rounded-xl shadow">
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
        <div>
            <h2 class="text-xl font-semibold text-slate-800">Diagram IKM per Ruangan</h2>
            <p class="text-sm text-slate-500">
                Tahun: <span class="font-semibold">{{ $tahunAktif }}</span>

                @if(!empty($unitAktif))
                    <span class="mx-2">•</span>
                    Unit: <span class="font-semibold">
                        {{ optional($unitList->firstWhere('id', (int)$unitAktif))->nama_unit ?? 'Dipilih' }}
                    </span>
                @endif

                @if(!empty($ruanganAktif))
                    <span class="mx-2">•</span>
                    Ruangan: <span class="font-semibold">{{ $ruanganAktif }}</span>
                @endif
            </p>
        </div>

        @php
            $tahunMax = (int) now()->year;
            $tahunFilter = [];
            for ($i = 0; $i < 5; $i++) {
                $tahunFilter[] = $tahunMax - $i;
            }
        @endphp

        <form method="GET" class="w-full sm:w-auto">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="flex items-center gap-0 bg-slate-50 border border-slate-200 rounded-xl shadow-sm overflow-hidden">

                    {{-- UNIT --}}
                    <div class="flex items-center gap-2 px-3 py-2">
                        <span class="text-sm text-slate-600 whitespace-nowrap">Pilih Unit</span>
                        <div class="relative">
                            <select name="unit"
                                class="appearance-none bg-white border border-slate-200 rounded-lg
                                       pl-3 pr-9 py-2 text-sm text-slate-800
                                       hover:border-slate-300 focus:border-slate-400
                                       focus:outline-none focus:ring-2 focus:ring-slate-200 transition"
                                onchange="this.form.submit()">
                                <option value="">Semua Unit</option>
                                @foreach($unitList as $u)
                                    <option value="{{ $u->id }}" {{ (string)request('unit') === (string)$u->id ? 'selected' : '' }}>
                                        {{ $u->nama_unit }}
                                    </option>
                                @endforeach
                            </select>

                            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500"
                                 viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>

                    <div class="hidden sm:block w-px self-stretch bg-slate-200"></div>

                    {{-- RUANGAN --}}
                    <div class="flex items-center gap-2 px-3 py-2">
                        <span class="text-sm text-slate-600 whitespace-nowrap">Pilih Ruangan</span>
                        <div class="relative">
                            <select name="ruangan"
                                class="appearance-none bg-white border border-slate-200 rounded-lg
                                       pl-3 pr-9 py-2 text-sm text-slate-800
                                       hover:border-slate-300 focus:border-slate-400
                                       focus:outline-none focus:ring-2 focus:ring-slate-200 transition"
                                onchange="this.form.submit()">
                                <option value="">Semua Ruangan</option>
                                @foreach($ruanganList as $r)
                                    <option value="{{ $r }}" {{ (string)request('ruangan') === (string)$r ? 'selected' : '' }}>
                                        {{ $r }}
                                    </option>
                                @endforeach
                            </select>

                            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500"
                                 viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>

                    <div class="hidden sm:block w-px self-stretch bg-slate-200"></div>

                    {{-- TAHUN --}}
                    <div class="flex items-center gap-2 px-3 py-2">
                        <span class="text-sm text-slate-600 whitespace-nowrap">Pilih Tahun</span>
                        <div class="relative">
                            <select name="tahun"
                                class="appearance-none bg-white border border-slate-200 rounded-lg
                                       pl-3 pr-9 py-2 text-sm text-slate-800
                                       hover:border-slate-300 focus:border-slate-400
                                       focus:outline-none focus:ring-2 focus:ring-slate-200 transition"
                                onchange="this.form.submit()">
                                @foreach($tahunFilter as $t)
                                    <option value="{{ $t }}" {{ (int)$t === (int)$tahunAktif ? 'selected' : '' }}>
                                        {{ $t }}
                                    </option>
                                @endforeach
                            </select>

                            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500"
                                 viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold
                          rounded-xl border border-slate-200 bg-white text-slate-700
                          hover:bg-slate-50 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Ringkasan --}}
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-slate-50 p-6 rounded-xl border border-slate-100">
            <p class="text-sm text-slate-500">Total Unit</p>
            <h2 class="text-2xl font-semibold text-slate-800">{{ $totalUnit }}</h2>
        </div>

        <div class="bg-slate-50 p-6 rounded-xl border border-slate-100">
            <p class="text-sm text-slate-500">Total Ruangan</p>
            <h2 class="text-2xl font-semibold text-slate-800">{{ $totalRuangan }}</h2>
        </div>

        <div class="bg-slate-50 p-6 rounded-xl border border-slate-100">
            <p class="text-sm text-slate-500">Responden</p>
            <h2 class="text-2xl font-semibold text-slate-800">{{ $totalResponden }}</h2>
        </div>
    </div>
</div>

{{-- Charts --}}
<div class="mt-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
    @forelse($chartData as $item)
        @php $canvasId = 'chart_' . $item['hash']; @endphp

        <div class="group relative bg-white p-4 rounded-xl shadow hover:shadow-md transition">
            <div class="mb-2">
                <h3 class="text-base font-semibold text-slate-800 truncate" title="{{ $item['nama_ruangan'] }}">
                    {{ $item['nama_ruangan'] }}
                </h3>
                <p class="text-xs text-slate-500">IKM per bulan ({{ $tahunAktif }})</p>
            </div>

            <div class="w-full">
                <canvas id="{{ $canvasId }}" height="70"></canvas>
            </div>

            {{-- ✅ Tabel bawah tetap tampilkan kategori --}}
            <div class="mt-3 overflow-x-auto">
                <table class="min-w-full text-xs">
                    <thead>
                        <tr class="text-left text-slate-600 border-b">
                            <th class="py-1 pr-2">Bulan</th>
                            <th class="py-1 pr-2">IKM</th>
                            <th class="py-1 pr-2">Kategori</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($item['labels'] as $i => $bulanLabel)
                            <tr class="border-b last:border-b-0">
                                <td class="py-1 pr-2 text-slate-700">{{ $bulanLabel }}</td>
                                <td class="py-1 pr-2 font-semibold text-slate-800">
                                    {{ $item['values'][$i] !== null ? number_format($item['values'][$i], 1) : '-' }}
                                </td>
                                <td class="py-1 pr-2 text-slate-700">
                                    {{ $item['categories'][$i] ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="absolute inset-0 bg-white/70 opacity-0 group-hover:opacity-100 transition flex items-center justify-center rounded-xl">
                <button
                    type="button"
                    class="px-4 py-2 text-sm font-semibold rounded-lg bg-slate-800 text-white hover:bg-slate-700"
                    onclick='openDetailModal(@json($item), {{ (int)$tahunAktif }})'>
                    Lihat Detail
                </button>
            </div>
        </div>
    @empty
        <div class="bg-white p-6 rounded-xl shadow col-span-1 md:col-span-2 xl:col-span-4">
            <p class="text-slate-600">Belum ada data IKM untuk tahun {{ $tahunAktif }}.</p>
        </div>
    @endforelse
</div>

{{-- MODAL DETAIL --}}
<div id="detailModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeDetailModal()"></div>

    <div class="relative h-full w-full flex items-center justify-center p-4">
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="flex items-start justify-between px-5 py-4 border-b">
                <div>
                    <h3 id="modalTitle" class="text-lg font-semibold text-slate-800">Detail</h3>
                    <p id="modalSubTitle" class="text-sm text-slate-500"></p>
                </div>
                <button type="button"
                    class="ml-3 px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100"
                    onclick="closeDetailModal()">
                    ✕
                </button>
            </div>

            <div class="p-5 overflow-y-auto" style="max-height: calc(80vh - 64px);">
                <div class="w-full border border-slate-100 p-3 rounded-xl">
                    <div class="relative" style="height: 240px;">
                        <canvas id="modalChart"></canvas>
                    </div>
                </div>

                <div class="mt-5 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-slate-600 border-b">
                                <th class="py-2 pr-4">Bulan</th>
                                <th class="py-2 pr-4">IKM</th>
                                <th class="py-2 pr-4">Kategori</th>
                            </tr>
                        </thead>
                        <tbody id="modalTableBody"></tbody>
                    </table>
                </div>
            </div>

            <div class="px-5 py-4 border-t flex justify-end">
                <button type="button"
                    class="px-4 py-2 text-sm font-semibold rounded-lg bg-slate-800 text-white hover:bg-slate-700"
                    onclick="closeDetailModal()">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js + DataLabels --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
    const chartsPayload = @json($chartData);
    let modalChartInstance = null;

    // Register plugin datalabels
    if (typeof ChartDataLabels !== 'undefined') {
        Chart.register(ChartDataLabels);
    }

    function formatIKM(val) {
        if (val === null || val === undefined) return null;
        const n = Number(val);
        return Number.isFinite(n) ? n.toFixed(1) : null;
    }

    // =========================
    // CHART KECIL (CARD)
    // ✅ TIDAK tampilkan angka IKM / kategori di atas batang
    // =========================
    chartsPayload.forEach((item) => {
        const canvasId = 'chart_' + item.hash;
        const ctx = document.getElementById(canvasId);
        if (!ctx) return;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: item.labels,
                datasets: [{
                    label: 'IKM',
                    data: item.values,
                    borderWidth: 1,
                    borderRadius: 6,
                    barPercentage: 0.7,
                    categoryPercentage: 0.8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            // tooltip boleh tetap tampil kategori (opsional)
                            label: (context) => {
                                const idx = context.dataIndex;
                                const v = context.raw;
                                const shown = formatIKM(v);
                                const cat = (item.categories && item.categories[idx]) ? item.categories[idx] : '-';
                                return shown === null ? `IKM: - | Kategori: ${cat}` : `IKM: ${shown} | Kategori: ${cat}`;
                            }
                        }
                    },
                    datalabels: {
                        display: false // ✅ MATIKAN label di chart kecil
                    }
                },
                scales: {
                    x: { ticks: { maxRotation: 0, minRotation: 0, font: { size: 10 } } },
                    y: { beginAtZero: true, suggestedMax: 100, ticks: { font: { size: 10 } } }
                }
            }
        });
    });

    // =========================
    // MODAL DETAIL
    // ✅ tetap tampil kategori di label (IKM + kategori)
    // =========================
    function openDetailModal(item, tahunAktif) {
        document.body.style.overflow = 'hidden';

        document.getElementById('modalTitle').textContent = item.nama_ruangan;
        document.getElementById('modalSubTitle').textContent = `Detail IKM per bulan (${tahunAktif})`;

        const tbody = document.getElementById('modalTableBody');
        tbody.innerHTML = '';
        item.labels.forEach((label, idx) => {
            const val = item.values[idx];
            const shown = (val === null || val === undefined) ? '-' : Number(val).toFixed(1);
            const cat = (item.categories && item.categories[idx]) ? item.categories[idx] : '-';

            const tr = document.createElement('tr');
            tr.className = 'border-b last:border-b-0';
            tr.innerHTML = `
                <td class="py-2 pr-4 text-slate-700">${label}</td>
                <td class="py-2 pr-4 font-semibold text-slate-800">${shown}</td>
                <td class="py-2 pr-4 text-slate-700">${cat}</td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('detailModal').classList.remove('hidden');

        const ctx = document.getElementById('modalChart');
        if (modalChartInstance) modalChartInstance.destroy();

        modalChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: item.labels,
                datasets: [{
                    label: 'IKM',
                    data: item.values,
                    borderWidth: 1,
                    borderRadius: 10,
                    barPercentage: 0.8,
                    categoryPercentage: 0.9
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                const idx = context.dataIndex;
                                const v = context.raw;
                                const cat = (item.categories && item.categories[idx]) ? item.categories[idx] : '-';
                                const shown = formatIKM(v);
                                return shown === null ? `IKM: - | Kategori: ${cat}` : `IKM: ${shown} | Kategori: ${cat}`;
                            }
                        }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        offset: 2,
                        clamp: true,
                        color: '#334155',
                        font: { size: 11, weight: '700' },
                        formatter: (value, context) => {
                            const idx = context.dataIndex;
                            const shown = formatIKM(value);
                            if (shown === null) return '';
                            const cat = (item.categories && item.categories[idx]) ? item.categories[idx] : '-';
                            return `${shown} (${cat})`; // ✅ modal tetap tampil kategori
                        }
                    }
                },
                scales: {
                    x: { ticks: { maxRotation: 0, minRotation: 0 } },
                    y: { beginAtZero: true, suggestedMax: 100 }
                }
            }
        });

        document.addEventListener('keydown', escClose);
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
        document.body.style.overflow = '';

        if (modalChartInstance) {
            modalChartInstance.destroy();
            modalChartInstance = null;
        }
        document.removeEventListener('keydown', escClose);
    }

    function escClose(e) {
        if (e.key === 'Escape') closeDetailModal();
    }
</script>
@endsection
