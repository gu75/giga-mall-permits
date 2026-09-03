<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Giga Mall Permits')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <nav class="bg-white shadow mb-6">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="font-bold text-lg">GIGA MALL Permits</a>
            <div class="flex gap-4 text-sm items-center">
                <a href="{{ route('work-permits.index') }}" class="hover:underline">Work Permits</a>
                <a href="{{ route('material-permits.index') }}" class="hover:underline">Material Permits</a>
                @auth
                    <span class="text-gray-500">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-red-600 hover:underline">Logout</button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 pb-12">
        @if (session('success'))
            <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-2 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded bg-red-100 text-red-800 px-4 py-2 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
