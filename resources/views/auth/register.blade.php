<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-gradient">Create Account</h2>
        <p class="mt-2 text-sm text-gray-600">Register for Work Permit Access</p>
    </div>

    @if ($errors->any())
        <div class="alert-box">
            <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" autocomplete="off">
        @csrf

        <div class="input-group">
            <i data-lucide="user" class="input-icon w-5 h-5"></i>
            <input id="name" class="form-input" type="text" name="name" value="{{ old('name') }}" placeholder=" " required autofocus autocomplete="name">
            <label for="name" class="input-label">Full Name</label>
        </div>

        <div class="input-group">
            <i data-lucide="mail" class="input-icon w-5 h-5"></i>
            <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" placeholder=" " required autocomplete="username">
            <label for="email" class="input-label">Email Address</label>
        </div>

        <div class="input-group">
            <i data-lucide="lock" class="input-icon w-5 h-5"></i>
            <input id="password" class="form-input" type="password" name="password" placeholder=" " required autocomplete="new-password">
            <label for="password" class="input-label">Password</label>
            <span class="password-toggle" data-target="password">
                <i data-lucide="eye" class="w-5 h-5"></i>
            </span>
        </div>

        <div class="input-group">
            <i data-lucide="check-circle" class="input-icon w-5 h-5"></i>
            <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" placeholder=" " required autocomplete="new-password">
            <label for="password_confirmation" class="input-label">Confirm Password</label>
            <span class="password-toggle" data-target="password_confirmation">
                <i data-lucide="eye" class="w-5 h-5"></i>
            </span>
        </div>
                <div class="input-group">
            <i data-lucide="store" class="input-icon w-5 h-5"></i>
            <input id="shop_name" class="form-input" type="text" name="shop_name" value="{{ old('shop_name') }}" placeholder=" " required autocomplete="off">
            <label for="shop_name" class="input-label">Shop Name</label>
        </div>

        <div class="input-group">
            <i data-lucide="map-pin" class="input-icon w-5 h-5"></i>
            <input id="floor_location" class="form-input" type="text" name="floor_location" value="{{ old('floor_location') }}" placeholder=" " required autocomplete="off">
            <label for="floor_location" class="input-label">Floor / Location</label>
        </div>

        <div class="input-group">
            <i data-lucide="phone" class="input-icon w-5 h-5"></i>
            <input id="cell_no" class="form-input" type="text" name="cell_no" value="{{ old('cell_no') }}" placeholder=" " required autocomplete="off">
            <label for="cell_no" class="input-label">Cell No</label>
        </div>


        <button type="submit" class="btn-primary mt-2">
            <i data-lucide="user-plus" class="w-5 h-5"></i>
            <span>Create Account</span>
        </button>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Already registered?
                <a href="{{ route('login') }}" class="font-bold hover:underline" style="color: var(--secondary-color);">Login here</a>
            </p>
        </div>
    </form>
</x-guest-layout>
