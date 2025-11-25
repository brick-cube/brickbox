<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
    $tenantName = $tenant->name ?? config('app.name', 'Dashboard');
    $tenantColor = $tenant->color ?? '#4F46E5'; // default indigo
    $tenantLogo = $tenant?->logo ? asset('storage/' . ltrim($tenant->logo, '/')) : null;
    @endphp

    <title>{{ $tenantName }} — Welcome</title>

    <script src="https://cdn.tailwindcss.com"></script>

     <style>
        body {
            background: linear-gradient(135deg, {{ $tenantColor }}40, #000000ff, {{ $tenantColor}}40);
        }
    </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center text-white p-6">

    <!-- Branding Header -->
    <div class="text-center mb-10">
        @if ($tenantLogo)
        <img src="{{ $tenantLogo }}" alt="{{ $tenantName }}" class="h-20 mx-auto mb-4 rounded-md shadow-md">
        @endif

        <h1 class="text-4xl font-extrabold tracking-wide mb-2" style="color: {{ $tenantColor }}">
            {{ $tenantName }}
        </h1>
        <p class="text-gray-300 text-sm">Your smart business dashboard</p>
    </div>

    <!-- Action Buttons -->
    <!-- Action Buttons -->
    <div class="flex flex-col gap-4 w-full max-w-xs">
        @auth
            @php
                $redirectUrl =
                    auth()->user()->hasRole('super_admin')
                    ? '/admin'
                    : (auth()->user()->hasRole('company_admin')
                    ? '/company'
                    : '/');
            @endphp

        <a href="{{ $redirectUrl }}"
            class="w-full text-center py-3 rounded-lg font-semibold transition transform hover:scale-[1.02]"
            style="background: {{ $tenantColor }}">
            Go to Dashboard
        </a>

        @else
        <a href="{{ route('login') }}"
            class="w-full text-center py-3 rounded-lg font-semibold transition transform hover:scale-[1.02]"
            style="background: {{ $tenantColor }}">
            Login
        </a>
        @endauth
    </div>


    <!-- Footer Text -->
    <p class="mt-10 text-gray-400 text-xs">
        Powered by <span style="color: {{ $tenantColor }}">{{ $tenantName }}</span>
    </p>
</body>

</html>