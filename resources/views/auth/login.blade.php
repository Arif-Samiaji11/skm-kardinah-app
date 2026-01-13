<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin | SKM RSUD Kardinah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')
</head>

<body class="min-h-screen flex items-center justify-center px-4
             bg-gradient-to-br from-sky-500 via-blue-600 to-cyan-500">

    <!-- WRAPPER -->
    <div class="relative w-full max-w-6xl">

        <!-- BLUR BACKDROP -->
        <div class="absolute inset-0 bg-white/30 backdrop-blur-xl rounded-3xl"></div>

        <!-- CARD -->
        <div class="relative grid grid-cols-1 md:grid-cols-2
                    bg-white/90 rounded-3xl overflow-hidden
                    shadow-[0_40px_100px_rgba(0,0,0,0.25)]">

            <!-- LEFT : LOGIN -->
            <div class="flex flex-col justify-center px-8 sm:px-12 py-14">

                <!-- LOGO -->
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-10 h-10 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold">
                    RS
                </div>
                    <div>
                        <p class="font-semibold text-slate-800 leading-none">
                            RSUD Kardinah
                        </p>
                        <span class="text-xs text-slate-500">
                            Sistem SKM
                        </span>
                    </div>
                </div>

                <!-- TITLE -->
                <h1 class="text-3xl font-bold text-slate-800 mb-2">
                    Login Administrator
                </h1>
                <p class="text-slate-500 text-sm mb-8">
                    Silakan masuk untuk mengelola Survei Kepuasan Masyarakat
                </p>

                <!-- STATUS -->
                <x-auth-session-status
                    class="mb-4 text-sm text-emerald-600"
                    :status="session('status')" />

                <!-- FORM -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- EMAIL -->
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">
                            Email
                        </label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="w-full rounded-xl border border-slate-300
                                   px-4 py-3 text-sm
                                   focus:border-blue-600 focus:ring-4
                                   focus:ring-blue-600/20 transition"
                            placeholder="admin@rsudkardinah.go.id">
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">
                            Password
                        </label>
                        <input
                            type="password"
                            name="password"
                            required
                            class="w-full rounded-xl border border-slate-300
                                   px-4 py-3 text-sm
                                   focus:border-blue-600 focus:ring-4
                                   focus:ring-blue-600/20 transition"
                            placeholder="Minimal 8 karakter">
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
                    </div>

                    <!-- OPTIONS -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 text-slate-600">
                            <input
                                type="checkbox"
                                name="remember"
                                class="rounded border-slate-300
                                       text-blue-600 focus:ring-blue-600">
                            Ingat saya
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-blue-600 hover:underline">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <!-- BUTTON -->
                    <button
                        type="submit"
                        class="w-full py-3 rounded-xl font-semibold text-white
                               bg-gradient-to-r from-blue-600 to-cyan-500
                               hover:opacity-90 transition
                               shadow-lg shadow-blue-600/40">
                        Masuk ke Dashboard
                    </button>
                </form>

                <!-- FOOTER -->
                <p class="mt-10 text-xs text-slate-400">
                    © {{ date('Y') }} RSUD Kardinah — Sistem SKM
                </p>
            </div>

            <!-- RIGHT : VISUAL -->
            <div class="hidden md:flex flex-col items-center justify-center
                        bg-gradient-to-br from-blue-50 to-cyan-50
                        px-10 py-14 text-center relative">

                <!-- BADGE -->
                

                <h2 class="text-4xl font-bold text-blue-700 mb-4">
                    Welcome Back 👋
                </h2>

                <p class="text-slate-500 mb-8 max-w-sm">
                    Kelola data SKM RSUD Kardinah secara cepat, aman, dan terintegrasi.
                </p>

                <img
                    src="{{ asset('images/login.png') }}"
                    alt="Pelayanan Kesehatan"
                    class="w-80 max-w-full drop-shadow-2xl
                           transition duration-300 hover:scale-105"
                />
            </div>

        </div>
    </div>

</body>
</html>
