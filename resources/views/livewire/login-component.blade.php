<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Login - Library Management System</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="bg-gray-100 flex items-center justify-center h-screen">
        <div class="max-w-sm w-full p-8 bg-white rounded-lg shadow-lg">
            <div class="text-center mb-8">
                <img
                    src="{{ asset('assets/login.png') }}"
                    alt="Library Logo"
                    class="w-24 h-24 mx-auto mb-4 object-contain"
                />
                <h2 class="text-2xl font-bold text-gray-800">Login Dulu Cuy</h2>
            </div>
            <form>
                <div class="mb-6">
                    <input
                        type="text"
                        wiremodel="email"
                        id="email"
                        placeholder="Email Address"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                        @error('email') class="border-red-500" @enderror
                    />
                </div>
                <div class="mb-6">
                    <input
                        type="password"
                        wiremodel="password"
                        id="password"
                        placeholder="Password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                        @error('password') class="border-red-500" @enderror
                    />
                </div>
                <button
                    type="submit"
                    class="w-full bg-blue-500 text-white py-2 px-4 rounded-full hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
                    Login
                </button>
            </form>
            <div class="text-center mt-6">
                <a href="#" class="text-blue-500 hover:underline"
                    >Forgot password?</a
                >
            </div>
        </div>
    </body>
</html>
