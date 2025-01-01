<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User dashboard</title>
    <script>
        const TIMEZONE_DB_API_KEY = "{{ config('app.timezone_db_api_key') }}";
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        darkMode: 'class'
    }
    </script>
</head>
<body class="dark:bg-gray-900 min-h-screen dark:text-white flex flex-col">
    <!-- navbar -->
    <nav class="flex bg-orange-950 w-full h-16 justify-between items-center border-b-4 border-black fixed top-0 left-0 right-0 z-50">
        <div class="flex items-center px-4">
            <button id="sidebarToggle" class="p-2 hover:bg-orange-700 rounded-lg border-2 border-white text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="ml-4 text-white font-medium">Halo {{ auth()->user()->name }}</span>
        </div>
        <h1 class="text-xl font-bold mr-4 text-white">Aplikasi Perpustakaan</h1>
    </nav>

    <!-- main container -->
    <div class="flex flex-1 pt-16">
        <!-- sidebar -->
        <aside id="sidebar" class="bg-orange-950 min-h-screen w-64 fixed left-0 top-16 bottom-0 transform -translate-x-full transition-transform duration-300 ease-in-out z-40">
            <div class="p-4">
                <ul class="space-y-4">
                    <li>
                        <span id="darkModeToogle" class="block p-3 text-white text-center hover:bg-orange-700 rounded-lg cursor-pointer transition duration-200">Mode</span>
                    </li>
                    <li>
                        <a href="{{ route('user.dashboard') }}" id="menuHome" class="block p-3 text-white text-center hover:bg-orange-700 rounded-lg transition duration-200">Home</a>
                    </li>
                    <li>
                        <a href="#" id="menuHistory" class="block p-3 text-white text-center hover:bg-orange-700 rounded-lg transition duration-200">History Pinjaman</a>
                    </li>
                    <li>
                        <a href="#" id="menuSearch" class="block p-3 text-white text-center hover:bg-orange-700 rounded-lg transition duration-200">Cari Buku</a>
                    </li>
                    <li>
                        <form action="{{ route('logout')}}" method="post" class="block">
                            @csrf
                            <button type="submit" class="w-full p-3 text-white text-center hover:bg-rose-700 rounded-lg transition duration-200">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- main content -->
        <main class="flex-1 p-6 ml-0 transition-margin duration-300 min-h-[calc(100vh-4rem-3rem)]">
            <!-- home content -->
            <div id="homeContent" class="content-section">
                <div class="flex flex-col items-center justify-center min-h-[calc(100vh-13rem)]">
                    <h1 class="text-4xl font-bold text-center mb-10">
                        SELAMAT DATANG DI APLIKASI PERPUSTAKAAN
                    </h1>
                    <div id="world-time" class="text-center">
                        <h2 id="time" class="text-6xl font-bold">Loading..</h2>
                    </div>
                </div>
            </div>

            <!-- search content -->
            <div id="searchContent" class="content-section hidden">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-2xl font-semibold mb-4">Cari Buku</h2>
                    <form action="{{ route('export.pdf') }}" method="post" class="flex items-center mb-6">
                        @csrf
                        <p class="text-black dark:text-white">Export Semua Data Buku Ke PDF</p>
                        <button class="text-white rounded-lg bg-orange-950 hover:bg-orange-900 px-4 py-2 ml-4 transition duration-200">Export To PDF</button>
                    </form>
                    <div class="flex mb-6">
                        <input type="text" id="searchInput" class="flex-1 p-2 border rounded-l-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white" placeholder="Cari Buku">
                        <button id="searchButton" class="bg-orange-950 hover:bg-orange-900 text-white p-2 rounded-r-lg transition duration-200">
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

            <!-- history content -->
            <div id="historyContent" class="content-section hidden">
                <h2 class="text-2xl font-semibold mb-6 text-center">Riwayat Buku yang Anda Pinjam</h2>
                <div id="historyResults" class="space-y-4 max-w-4xl mx-auto">
                    <!-- hasil history -->
                </div>
            </div>
        </main>
    </div>

    <!-- footer -->
    <footer class="bg-orange-950 text-white py-4 mt-auto">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 Aplikasi Perpustakaan | Porject Pemrograman Web</p>
        </div>
    </footer>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>
    <script src="{{ asset('asset/js/userDashboard.js') }}"></script>
</body>
</html>