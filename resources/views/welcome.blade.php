<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SKM RSUD Kardinah</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-50 text-slate-800">

<!-- NAVBAR -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">
                RS
            </div>
            <div>
                <h1 class="font-semibold text-lg">RSUD Kardinah</h1>
                <p class="text-xs text-slate-500">Survei Kepuasan Masyarakat</p>
            </div>
        </div>

        <nav class="hidden md:flex gap-6 text-sm font-medium">
            <a href="#" class="hover:text-blue-600">Beranda</a>
            <a href="#" class="hover:text-blue-600">Layanan</a>
            <a href="#" class="hover:text-blue-600">Kuisioner SKM</a>
            <a href="#" class="hover:text-blue-600">Kontak</a>
        </nav>
    </div>
</header>

<!-- HERO -->
<section class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white">
    <div class="max-w-7xl mx-auto px-6 py-20 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <h2 class="text-3xl md:text-4xl font-bold mb-4 leading-tight">
                Survei Kepuasan Masyarakat<br>
                RSUD Kardinah
            </h2>
            <p class="text-blue-100 mb-6">
                Pendapat Anda sangat berarti bagi peningkatan mutu pelayanan
                kesehatan kami.
            </p>
            <a href="{{ route('login') }}"

"
               class="inline-block bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold shadow hover:bg-blue-50 transition">
                Isi Kuisioner Sekarang
            </a>
        </div>

        <div class="hidden md:block">
            <img src="https://img.icons8.com/fluency/480/hospital.png" alt="Rumah Sakit">
        </div>
    </div>
</section>

<!-- LAYANAN -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-6">
        <h3 class="text-2xl font-bold text-center mb-10">
            Unit Pelayanan Rumah Sakit
        </h3>

        <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-6">
            @php
                $layanan = [
                    'Intensive',
                    'Rawat Inap',
                    'Rawat Jalan',
                    'Pelayanan Umum'
                ];
            @endphp

            @foreach ($layanan as $item)
                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4 font-bold">
                        +
                    </div>
                    <h4 class="font-semibold mb-2">{{ $item }}</h4>
                    <p class="text-sm text-slate-600">
                        Pelayanan {{ strtolower($item) }} RSUD Kardinah.
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA -->
<section class="bg-blue-600 text-white py-16">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <h3 class="text-2xl font-bold mb-4">
            Bantu Kami Menjadi Lebih Baik
        </h3>
        <p class="text-blue-100 mb-6">
            Isi Survei Kepuasan Masyarakat untuk meningkatkan kualitas layanan RSUD Kardinah.
        </p>
        <a href="{{ route('login') }}"
           class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold shadow hover:bg-blue-50 transition">
            Mulai Isi SKM
        </a>
    </div>
</section>

<!-- FOOTER -->
<footer class="bg-slate-900 text-slate-300 py-6">
    <div class="max-w-7xl mx-auto px-6 text-center text-sm">
        © {{ date('Y') }} RSUD Kardinah — Sistem Survei Kepuasan Masyarakat
    </div>
</footer>

</body>
</html>
