<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Perpustakaan</title>

    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- bootstraps icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-[url('{{ asset('asset/login_page.jpg') }}')] bg-no-repeat bg-cover">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full space-y-8 p-8 bg-orange-950 rounded-lg shadow-lg bg-opacity-65">
            <h2 class="text-2xl font-bold text-center text-white">Create a New Account</h2>
            
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

            <form action="{{ route('register.store') }}" method="POST" class="mt-8 space-y-6">
                @csrf

                <!-- Name -->
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="name" class="sr-only text-white">Full Name</label>
                        <input id="name" name="name" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Full Name" value="{{ old('name') }}">
                    </div>
                </div>

                <!-- Address -->
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="address" class="sr-only text-white">Address</label>
                        <input id="address" name="address" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Address" value="{{ old('address') }}">
                    </div>
                </div>

                <!-- Phone Number -->
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="phone" class="sr-only text-white">Phone Number</label>
                        <input id="phone" name="phone" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Phone Number" value="{{ old('phone') }}">
                    </div>
                </div>

                <!-- Email -->
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email" class="sr-only text-white">Email Address</label>
                        <input id="email" name="email" type="email" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Email Address" value="{{ old('email') }}">
                    </div>
                </div>

                <!-- Password -->
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="password" class="sr-only text-white">Password</label>
                        <input id="password" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Password">
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="password_confirmation" class="sr-only text-white">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Confirm Password">
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Register
                    </button>
                </div>
            </form>

            <div class="mt-4 text-center">
                <span class="text-sm text-white font-semibold">
                    Already have an account? <a href="{{ route('login') }}" class="font-medium text-white hover:text-orange-600">Log in</a>
                </span>
            </div>
        </div>
    </div>
</body>
</html>
