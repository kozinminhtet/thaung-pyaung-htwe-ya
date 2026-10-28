<x-guest-layout>
    <div class="auth-title">
        <h2>သင့်နာမည်ကို ရှာရန်...</h2>
        <p>သင့်အကောင့်သို့ ဝင်ရောက်ပါ။</p>
    </div>

    <x-auth-session-status class="auth-status" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf
        <div class="auth-field">
            <x-input-label for="email" value="အီးမေးလ်" />
            <x-text-input id="email" class="auth-input" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="auth-error" />
        </div>
        <div class="auth-field">
            <x-input-label for="password" value="စကားဝှက်" />
            <x-text-input id="password" class="auth-input" type="password" name="password" required
                autocomplete="current-password" placeholder="သင့်စကားဝှက်" />
            <x-input-error :messages="$errors->get('password')" class="auth-error" />
        </div>
        <div class="auth-options">
            <label for="remember_me" class="auth-remember"><input id="remember_me" type="checkbox" name="remember">
                <span>မှတ်ထားပါ</span></label>
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">စကားဝှက်မေ့နေပါသလား။</a>
            @endif
        </div>
        <x-primary-button class="auth-submit">အကောင့်ဝင်မည်</x-primary-button>
    </form>

    <p class="auth-switch">နာမည်မရှိသေးဘူးလား။ <br><a href="{{ route('register') }}">နာမည်စာရင်းသွင်းပါ။</a></p>
</x-guest-layout>