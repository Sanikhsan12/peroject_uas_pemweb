<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Perpustakaan</title>

    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- bootstraps icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-[url('{{ asset('asset/login_page.jpg') }}')] bg-no-repeat bg-cover">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full space-y-8 p-8 bg-orange-950 rounded-lg shadow-lg bg-opacity-65">
            <h2 class="text-2xl font-bold text-center text-white">Login to Your Account</h2>
            
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-500 text-white p-3 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="mt-8 space-y-6">
                @csrf

                <!-- Email -->
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email" class="sr-only text-white">Email address</label>
                        <input id="email" name="email" type="email" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Email Address" value="{{ old('email') }}">
                    </div>
                </div>

                <!-- Password -->
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="password" class="sr-only text-white">Password</label>
                        <input id="password" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-sm text-white">
                            Remember me
                        </label>
                    </div>
                    <div class="text-sm">
                        <a href="{{ route('forgot-pass') }}" class="font-medium text-white hover:text-white">
                            Forgot your password?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Log in
                    </button>
                </div>
            </form>

            <div class="flex items-center justify-center mt-4">
                <span class="text-white font-semibold">or</span>
            </div>

            <!-- Google Login -->
            <div class="mt-4">
                <a href="{{ url('login/google') }}" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Login / Register with Google
                    <i class="bi bi-google px-2"></i>
                </a>
            </div>

            <div class="mt-4 text-center">
                <span class="text-sm text-white font-semibold">
                    Don't have an account? <a href="{{ route('register') }}" class="font-medium text-white hover:text-orange-600">Sign up</a>
                </span>
            </div>
        </div>
    </div>
</body>
</html>
