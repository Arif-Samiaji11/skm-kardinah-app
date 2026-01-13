<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin SKM')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>

<body class="bg-slate-100">

<div class="flex">

    <!-- SIDEBAR (FIXED, NO SCROLL) -->
    <aside class="fixed top-0 left-0 w-64 h-screen bg-slate-900 text-white flex flex-col overflow-hidden">
        <!-- LOGO -->
        <div class="px-6 py-6 border-b border-slate-700 shrink-0">
            <h1 class="text-xl font-bold">SKM ADMIN</h1>
            <p class="text-xs text-slate-400">RSUD Kardinah</p>
        </div>

        <!-- MENU (TIDAK SCROLL) -->
        <nav class="px-4 py-6 space-y-2">
            <a href="{{ route('admin.dashboard') }}"
               class="block px-4 py-3 rounded-lg transition
               {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 font-semibold' : 'hover:bg-slate-800' }}">
                📊 Dashboard
            </a>

            <a href="{{ route('admin.detail-skm.index') }}"
               class="block px-4 py-3 rounded-lg transition
               {{ request()->routeIs('admin.detail-skm.*') ? 'bg-slate-800 font-semibold' : 'hover:bg-slate-800' }}">
                📋 Detail SKM (Data)
            </a>

            
        </nav>

        <!-- FOOTER (DI BAWAH, FIXED) -->
        <div class="mt-auto px-6 py-4 border-t border-slate-700 text-sm shrink-0">
            <p class="font-semibold">{{ auth()->user()->name }}</p>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-red-400 hover:text-red-500 text-xs mt-2">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- CONTENT (SCROLL DI SINI SAJA) -->
    <main class="ml-64 flex-1 min-h-screen overflow-y-auto p-8">
        @yield('content')
    </main>

</div>

</body>
</html>
