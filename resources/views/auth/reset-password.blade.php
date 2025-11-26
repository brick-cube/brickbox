<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $tenant->name ?? 'Reset Password' }} – Change Password</title>

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

    <div class="w-full max-w-md bg-black/80 backdrop-blur-xl border border-white/20 rounded-lg p-8 shadow-2xl">

        {{-- Branding --}}
        <div class="text-center mb-7">
            @if ($logo)
                <img src="{{ $logo }}" alt="{{ $name }}" class="h-16 mx-auto mb-3 rounded-md shadow-md">
            @endif
            <h2 class="text-3xl font-extrabold tracking-wide" style="color: {{ $color }}">
                {{ $name }}
            </h2>
            <p class="text-gray-300 text-sm mt-1">Reset Your Password</p>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Email --}}
            <div class="mb-5">
                <label class="block text-gray-200 mb-1 font-medium">Email</label>
                <input type="email" name="email" required autofocus
                        class="w-full px-4 py-3 rounded-lg bg-white/20 text-white border border-white/30
                            placeholder-gray-300 focus:border-[{{ $color }}]"
                        placeholder="you@example.com">
            </div>

            {{-- New Password --}}
            <div class="mb-5 relative">
                <label class="block text-gray-200 mb-1 font-medium">New Password</label>
                <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 pr-12 rounded-lg bg-white/20 text-white border border-white/30
                            placeholder-gray-300 focus:border-[{{ $color }}]"
                        placeholder="••••••••">
                <button type="button" onclick="togglePassword('password', 'eye1')"
                    class="absolute right-3 top-10 text-gray-300 hover:text-white transition"
                    id="eye1">😐</button>
            </div>

            {{-- Confirm Password --}}
            <div class="mb-6 relative">
                <label class="block text-gray-200 mb-1 font-medium">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-4 py-3 pr-12 rounded-lg bg-white/20 text-white border border-white/30
                            placeholder-gray-300 focus:border-[{{ $color }}]"
                        placeholder="••••••••">
                <button type="button" onclick="togglePassword('password_confirmation', 'eye2')"
                    class="absolute right-3 top-10 text-gray-300 hover:text-white transition"
                    id="eye2">😐</button>
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full py-3 text-white font-semibold rounded-lg transition transform hover:scale-[1.02]"
                style="background: {{ $color }}">
                Reset Password
            </button>
        </form>

        <p class="text-center text-gray-300 text-sm mt-6">
            <a href="/login" class="font-medium hover:underline" style="color: {{ $color }}">
                Back to Login
            </a>
        </p>
    </div>

    {{-- Show/Hide password script --}}
    <script>
        function togglePassword(field, iconId) {
            const input = document.getElementById(field);
            const icon = document.getElementById(iconId);

            if (input.type === "password") {
                input.type = "text";
                icon.textContent = "😑"; // visible
            } else {
                input.type = "password";
                icon.textContent = "😐"; // hidden
            }
        }
    </script>

</body>
</html>
