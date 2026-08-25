<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-gradient">Secure Login</h2>
        <p class="mt-2 text-sm text-gray-600">Access the Work Permit Command Center</p>
    </div>

    @if ($errors->any())
        <div class="alert-box">
            <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    @if (session('status'))
        <div class="success-box">
            <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" autocomplete="off">
        @csrf

        <div class="input-group">
            <i data-lucide="mail" class="input-icon w-5 h-5"></i>
            <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" placeholder=" " required autofocus autocomplete="username">
            <label for="email" class="input-label">Email</label>
        </div>

        <div class="input-group">
            <i data-lucide="lock" class="input-icon w-5 h-5"></i>
            <input id="password" class="form-input" type="password" name="password" placeholder=" " required autocomplete="current-password">
            <label for="password" class="input-label">Password</label>
            <span class="password-toggle" data-target="password">
                <i data-lucide="eye" class="w-5 h-5"></i>
            </span>
        </div>

        <div class="flex items-center justify-between mb-6 text-sm">
            <label for="remember_me" class="inline-flex items-center gap-2 text-gray-600 font-medium cursor-pointer select-none">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span>Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="font-semibold" style="color: var(--secondary-color);">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="btn-primary">
            <i data-lucide="log-in" class="w-5 h-5"></i>
            <span>Login to Dashboard</span>
        </button>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Don’t have an account?
                <a href="{{ route('register') }}" class="font-bold hover:underline" style="color: var(--secondary-color);">Register here</a>
            </p>
        </div>
    </form>
</x-guest-layout>

