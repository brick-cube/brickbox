<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $tenant->name ?? 'Forgot Password' }} - Reset Password</title>

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
            <p class="text-gray-300 text-sm mt-1">Forgot Password</p>
        </div>

        {{-- Success --}}
        @if (session('status'))
            <div class="mb-4 bg-green-600/20 text-green-300 px-4 py-3 rounded border border-green-500/40">
                {{ session('status') }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-5">
                <label class="block text-gray-200 mb-1 font-medium">Email</label>
                <input type="email" name="email" required autofocus
                       class="w-full px-4 py-3 rounded-lg bg-white/20 text-white border border-white/30
                              placeholder-gray-300 focus:border-[{{ $color }}]"
                       placeholder="you@example.com">
            </div>

            @error('email')
                <p class="text-red-400 text-sm mb-3">{{ $message }}</p>
            @enderror

            <button type="submit"
                class="w-full py-3 text-white font-semibold rounded-lg transition transform hover:scale-[1.02]"
                style="background: {{ $color }}">
                Send Reset Link
            </button>
        </form>

        {{-- Back to Login --}}
        <p class="text-center text-gray-300 text-sm mt-6">
            <a href="/login" class="font-medium hover:underline" style="color: {{ $color }}">
                Back to Login
            </a>
        </p>
    </div>

</body>
</html>
