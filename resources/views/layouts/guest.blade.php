<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'GIGA MALL WORK PERMIT') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo2.png') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

        <style>
            :root {
                --primary-color: #0A2342;
                --secondary-color: #C8A951;
                --soft-bg: #f5f7fa;
                --card-bg: #ffffff;
                --muted: #64748b;
                --stroke: #e2e8f0;
            }

            * { box-sizing: border-box; }

            body {
                font-family: 'Inter', sans-serif;
                background: var(--soft-bg);
                color: var(--primary-color);
            }

            .text-gradient {
                background-image: linear-gradient(to right, var(--primary-color), var(--secondary-color));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .glass-header {
                background: rgba(255,255,255,0.95);
                backdrop-filter: blur(20px);
                border-bottom: 1px solid rgba(200, 169, 81, 0.25);
                box-shadow: 0 4px 15px rgba(10, 35, 66, 0.08);
            }

            .auth-card {
                background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
                border: 1px solid var(--stroke);
                border-radius: 22px;
                box-shadow: 0 16px 50px rgba(10, 35, 66, 0.08);
                padding: 2.25rem;
                width: min(100%, 28rem);
            }

            .input-group {
                position: relative;
                margin-bottom: 1.4rem;
            }

            .input-icon {
                position: absolute;
                left: 1.15rem;
                top: 50%;
                transform: translateY(-50%);
                color: #94a3b8;
                z-index: 2;
            }

            .input-group:focus-within .input-icon {
                color: var(--secondary-color);
            }

            .input-label {
                position: absolute;
                left: 3.1rem;
                top: 1.05rem;
                font-size: 0.97rem;
                font-weight: 500;
                color: #718096;
                background: white;
                padding: 0 0.25rem;
                pointer-events: none;
                transition: all 0.2s ease;
                z-index: 2;
            }

            .input-group:focus-within .input-label,
            .input-group .form-input:not(:placeholder-shown) ~ .input-label,
            .input-group .form-select:valid ~ .input-label {
                top: -0.6rem;
                left: 2.7rem;
                font-size: 0.72rem;
                font-weight: 700;
                color: var(--secondary-color);
            }

            .form-input,
            .form-select {
                width: 100%;
                border: 2px solid var(--stroke);
                border-radius: 0.9rem;
                background: white;
                color: var(--primary-color);
                font-size: 1rem;
                padding: 1rem 1.2rem 1rem 3.1rem;
                transition: all 0.2s ease;
                outline: none;
                box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
            }

            .form-input:focus,
            .form-select:focus {
                border-color: var(--secondary-color);
                box-shadow: 0 0 0 4px rgba(200, 169, 81, 0.14);
                transform: translateY(-1px);
            }

            .form-select {
                appearance: none;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23A0AEC0'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 1rem center;
                background-size: 1.15rem;
            }

            .password-toggle {
                position: absolute;
                right: 1rem;
                top: 50%;
                transform: translateY(-50%);
                cursor: pointer;
                color: #94a3b8;
                z-index: 3;
                padding: 0.25rem;
            }

            .password-toggle:hover {
                color: var(--secondary-color);
            }

            .btn-primary {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.6rem;
                width: 100%;
                border-radius: 0.9rem;
                background: var(--primary-color);
                color: white;
                font-weight: 700;
                padding: 0.9rem 1.2rem;
                box-shadow: 0 8px 20px rgba(10, 35, 66, 0.16);
                transition: all 0.2s ease;
            }

            .btn-primary:hover {
                background: #0d2d55;
                transform: translateY(-1px);
            }

            .btn-secondary {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.6rem;
                border-radius: 0.9rem;
                background: var(--secondary-color);
                color: var(--primary-color);
                font-weight: 700;
                padding: 0.85rem 1.2rem;
                transition: all 0.2s ease;
            }

            .btn-secondary:hover {
                transform: translateY(-1px);
                filter: brightness(0.98);
            }

            .alert-box {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                border-left: 4px solid #ef4444;
                background: #fef2f2;
                color: #991b1b;
                border-radius: 0.75rem;
                padding: 0.85rem 1rem;
                margin-bottom: 1rem;
                font-size: 0.9rem;
                font-weight: 600;
            }

            .success-box {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                border-left: 4px solid #10b981;
                background: #ecfdf5;
                color: #065f46;
                border-radius: 0.75rem;
                padding: 0.85rem 1rem;
                margin-bottom: 1rem;
                font-size: 0.9rem;
                font-weight: 600;
            }
        </style>
    </head>
    <body class="min-h-screen antialiased flex flex-col">
        <header class="p-4 sticky top-0 z-50 glass-header">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <a href="{{ url('/') }}" class="group flex items-center gap-3 focus:outline-none">
                    <div class="relative">
                        <div class="absolute inset-0 bg-blue-500/10 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <img src="{{ asset('images/logo2.png') }}" class="relative z-10 w-16 md:w-20 object-contain drop-shadow-sm transition-transform duration-500 ease-out transform group-hover:scale-110 group-hover:-rotate-2" alt="Work Permit Logo">
                    </div>
                    <span class="font-extrabold text-3xl text-gradient tracking-tight transition-all duration-300 group-hover:tracking-wide">
                        Work Permit
                    </span>
                </a>

                <nav class="hidden md:flex items-center">
                    @if (request()->routeIs('login'))
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-secondary px-6 py-2 rounded-lg text-sm font-bold shadow-md flex items-center gap-2" style="background-color: var(--secondary-color); color: var(--primary-color);">
                                <i data-lucide="user-plus" class="w-4 h-4"></i> Register
                            </a>
                        @endif
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="btn-primary px-6 py-2 rounded-lg text-sm font-bold flex items-center gap-2" style="background-color: var(--primary-color); color: white;">
                                <i data-lucide="log-in" class="w-4 h-4"></i> Login
                            </a>
                        @endif
                    @endif
                </nav>
            </div>
        </header>

        <main class="grow flex items-center justify-center py-12 px-4">
            <div class="auth-card">
                {{ $slot }}
            </div>
        </main>

        <footer class="py-8 bg-gray-900 mt-auto">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <div class="flex justify-center items-center gap-3 mb-4">
                    <img src="{{ asset('images/logo7.png') }}" alt="Work Permit Logo" class="w-16 h-auto object-contain opacity-90">
                    <span class="text-gray-200 font-extrabold text-2xl tracking-wide">Work Permit</span>
                </div>
                <p class="text-sm text-gray-500 font-medium">&copy; {{ date('Y') }} Giga Mall IT Department.</p>
            </div>
        </footer>

        <script>
            lucide.createIcons();

            document.querySelectorAll('.password-toggle').forEach(function (button) {
                button.addEventListener('click', function () {
                    const targetId = this.dataset.target;
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (!input || !icon) return;

                    const isPassword = input.getAttribute('type') === 'password';
                    input.setAttribute('type', isPassword ? 'text' : 'password');
                    icon.setAttribute('data-lucide', isPassword ? 'eye-off' : 'eye');
                    lucide.createIcons();
                });
            });
        </script>
    </body>
</html>

