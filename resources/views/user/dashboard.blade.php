<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User dashboard</title>
    <!-- menyintukan api key dari env -->
    <script>
        const TIMEZONE_DB_API_KEY = "{{ config('app.timezone_db_api_key') }}";
    </script>
    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- import Tailwind CSS Module -->
    <style>
        @tailwind base;
        @tailwind components;
        @tailwind utilities;
    </style>
    <script>
    tailwind.config = {
        darkMode: 'class'
    }
    </script>
</head>
<body class="dark:bg-gray-900 dark:text-white">
    <!-- navbar -->
    <nav class="flex bg-orange-950 w-full h-16 justify-between items-center border-b-4 border-black mb-4">
        <div class="flex items-center px-4">
            <button id="sidebarToggle" class="p-2 hover:bg-orange-700 rounded-lg border-2 border-white text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="ml-4 text-white">Halo {{ auth()->user()->name }}</span>
        </div>
        <h1 class="text-xl mr-4 text-white">Aplikasi Perpustakaan</h1>
    </nav>

    <!-- side bar -->
    <div class="flex">
        <aside id="sidebar" class="bg-orange-950 max-h-50 text-white transition-transform duration-300 -translate-x-full">
            <div class="p-4">
                <ul class="space-y-2">
                    <li>
                        <span id="darkModeToogle" class="block p-2 text-white text-center hover:text-orange-700">Mode</span>
                    </li>
                    <li>
                        <a href="{{ route('user.dashboard') }}" id="menuHome" class="block p-2 text-white text-center hover:text-orange-700">Home</a>
                    </li>
                    <li>
                        <a href="#" id="menuHistory" class="block p-2 text-white text-center hover:text-orange-700">History Pinjaman</a>
                    </li>
                    <li>
                        <a href="#" id="menuSearch" class="block p-2 text-white text-center hover:text-orange-700">Cari Buku</a>
                    </li>
                    <li>
                        <form action="{{ route('logout')}}" method="post" class="block">
                            @csrf
                            <button type="submit" class="w-full text-center pb-2 hover:bg-rose-700 rounded-lg">
                                logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- main content -->
        <main class="flex-1 p-6 mt-20">
            <!-- homecontent -->
            <div id="homeContent" class="content-section">
                <h1 class="text-4xl font-bold text-center items-center justify-center mt-20">
                    SELAMAT DATANG DI APLIKASI PERPUSTAKAAN
                </h1>
                <div id="world-time" class="flex text-center items-center justify-center ">
                    <h2 id="time" class="text-center mt-20 font-bold text-6xl">Loading..</h2>
                </div>
            </div>

            <!-- search content -->
            <div id="searchContent" class="content-section hidden">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-2xl font-semibold mb-4">Cari Buku</h2>
                    <form action="{{ route('export.pdf') }}" method="post" class="flex">
                        @csrf
                        <p class="text-black inline">Export Semua Data Buku Ke PDF</p>
                        <button class="text-white rounded-lg bg-orange-950 p-2 mb-4 block ml-4">Export To Pdf</button>
                    </form>
                    <div class="flex mb-6">
                        <input type="text" id="searchInput" class="flex-1 p-2 border rounded-l-lg" placeholder="Cari Buku">
                        <button id="searchButton" class="bg-[#593C2B] text-white p-2 rounded-r-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>
                    <div id="searchResults" class="space-y-4">
                        <!-- hasil search disini -->
                    </div>
                </div>
            </div>

            <!-- history peminjaman -->
            <div id="historyContent" class="content-section hidden">
                <h2 class="text-2xl font-semibold mb-4 items-center text-center">Riwayat Buku yang Anda Pinjam</h2>
                <div id="historyResults" class="space-y-4">
                    <!-- hasil history -->
                </div>
            </div>
        </main>
    </div>

    <!-- js -->
    <script>
        const csrfToken =document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>
    <script src="{{ asset('asset/js/userDashboard.js') }}"></script>
</body>
</html>