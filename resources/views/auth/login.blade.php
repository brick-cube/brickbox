<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: linear-gradient(135deg, #020617, #1e293b);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-8 shadow-xl">

        <h2 class="text-3xl font-bold text-center text-white mb-2">
            Welcome Back
        </h2>

        <p class="text-center text-gray-300 mb-6">
            Login to manage your dashboard
        </p>

        @if ($errors->any())
            <div class="mb-4 bg-red-500/20 border border-red-400 text-red-200 px-4 py-3 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf

            <div class="mb-5">
                <label class="block text-gray-200 mb-1 font-medium">Email</label>
                <input type="email" name="email"
                       class="w-full px-4 py-2 rounded-lg bg-white/20 text-white border border-white/30
                              placeholder-gray-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-400/40"
                       placeholder="you@example.com"
                       required autofocus>
            </div>

            <div class="mb-6">
                <label class="block text-gray-200 mb-1 font-medium">Password</label>
                <input type="password" name="password"
                       class="w-full px-4 py-2 rounded-lg bg-white/20 text-white border border-white/30
                              placeholder-gray-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-400/40"
                       placeholder="••••••••"
                       required>
            </div>

            <button type="submit"
                    class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                Login
            </button>
        </form>

        <p class="text-center text-gray-300 text-sm mt-6">
            Need help? Contact admin.
        </p>
    </div>

</body>
</html>
