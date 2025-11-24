<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenant->name ?? 'Login' }} – Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>

    @php
        $name = $tenant->name ?? 'BrickBox';
        $color = $tenant->color ?? '#4997D3';
        $logo = $tenant?->logo ? asset('storage/' . ltrim($tenant->logo, '/')) : null;
    @endphp

    <style>
        body {
            background: linear-gradient(135deg, {{ $color }}33, #0f172a);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 text-white">

    <div class="w-full max-w-md bg-black/80 backdrop-blur-xl border border-white/20 rounded-2xl p-8 shadow-2xl">

        {{-- Branding --}}
        <div class="text-center mb-7">
            @if ($logo)
                <img src="{{ $logo }}" alt="{{ $name }}" class="h-16 mx-auto mb-3 rounded-md shadow-md">
            @endif
            <h2 class="text-3xl font-extrabold tracking-wide" style="color: {{ $color }}">
                {{ $name }}
            </h2>
            <p class="text-gray-300 text-sm mt-1">Sign in to continue</p>
        </div>

        {{-- Error --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-500/25 border border-red-500 text-red-100 px-4 py-3 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Login Form --}}
        <form method="POST" action="{{ url('/login') }}">
            @csrf

            <div class="mb-5">
                <label class="block text-gray-200 mb-1 font-medium">Email</label>
                <input type="email" name="email"
                       class="w-full px-4 py-3 rounded-lg bg-white/20 text-white border border-white/30
                              placeholder-gray-300 focus:border-[{{ $color }}]"
                       placeholder="you@example.com"
                       required autofocus>
            </div>

            <div class="mb-3 relative">
                <label class="block text-gray-200 mb-1 font-medium">Password</label>
                <input type="password" id="password" name="password"
                       class="w-full px-4 py-3 pr-12 rounded-lg bg-white/20 text-white border border-white/30
                              placeholder-gray-300 focus:border-[{{ $color }}]"
                       placeholder="••••••••"
                       required>
                
                {{-- Show/Hide Password Button --}}
                <button type="button" onclick="togglePassword()"
                    class="absolute right-3 top-10 text-gray-300 hover:text-white transition"
                    id="toggleIcon">
                    👁️
                </button>
            </div>

            {{-- Forgot Password --}}
            <div class="text-right mb-6">
                <a href="{{ url('/password/reset') }}" class="text-sm font-medium hover:underline"
                   style="color: {{ $color }}">
                    Forgot Password?
                </a>
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full py-3 text-white font-semibold rounded-lg transition transform hover:scale-[1.02]"
                style="background: {{ $color }}">
                Login
            </button>
        </form>

        <p class="text-center text-gray-300 text-xs mt-6">
            Powered by <span style="color: {{ $color }};">{{ $name }}</span>
        </p>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById("password");
            const icon = document.getElementById("toggleIcon");
            if (input.type === "password") {
                input.type = "text";
                icon.textContent = "🙈";
            } else {
                input.type = "password";
                icon.textContent = "👁️";
            }
        }
    </script>

</body>
</html>
