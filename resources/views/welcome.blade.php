<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- link CDn Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[url('{{ asset('asset/welecome_page.jpg') }}')] flex items-center justify-center h-screen bg-no-repeat bg-cover">
    <div class="text-center flex flex-col items-center">
        <h1 class="text-center text-6xl font-semibold text-white py-4">Selamat Datang Di Aplikasi Peminjaman Buku</h1>
        <p class="text-center text-lg font-semibold text-neutral-50 mb-6 px-3 py-3">Kami menyediakan berbagai buku yang dapat Anda pinjam. Mulai dengan login untuk melanjutkan.</p>
        <div class="flex justify-center">
            <a href="/login">
                <button class="px-6 py-3 bg-orange-950 text-white rounded-full text-lg hover:bg-orange-700 transition duration-300">
                    Get Started
                </button>
            </a>
        </div>
    </div>
</body>
</html>