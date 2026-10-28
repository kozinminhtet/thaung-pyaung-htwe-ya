<x-guest-layout>
    <div class="auth-title">
        <h2>သင့်နာမည်စာရင်းသွင်းရန်...</h2>
        <p>သင့်အချက်အလက်များကို ဖြည့်သွင်းပါ။</p>
    </div>

    <form class="auth-form" method="POST" action="{{ route('register') }}">
        @csrf

        <div class="auth-field">
            <x-input-label for="name" value="အမည်" />
            <x-text-input id="name" class="auth-input" type="text" name="name" :value="old('name')" required autofocus
                autocomplete="name" placeholder="သင့်အမည်" />
            <x-input-error :messages="$errors->get('name')" class="auth-error" />
        </div>

        <div class="auth-field">
            <x-input-label for="email" value="အီးမေးလ်" />
            <x-text-input id="email" class="auth-input" type="email" name="email" :value="old('email')" required
                autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="auth-error" />
        </div>

        <div class="auth-field">
            <x-input-label for="password" value="စကားဝှက်" />
            <x-text-input id="password" class="auth-input" type="password" name="password" required
                autocomplete="new-password" placeholder="စကားဝှက်ထည့်ပါ" />
            <x-input-error :messages="$errors->get('password')" class="auth-error" />
        </div>

        <div class="auth-field">
            <x-input-label for="password_confirmation" value="စကားဝှက် အတည်ပြုရန်" />
            <x-text-input id="password_confirmation" class="auth-input" type="password" name="password_confirmation"
                required autocomplete="new-password" placeholder="စကားဝှက်ကို ထပ်ရိုက်ပါ" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="auth-error" />
        </div>

        <x-primary-button class="auth-submit">အကောင့်ဖွင့်မည်</x-primary-button>
    </form>

    <p class="auth-switch">အကောင့်ရှိပြီးသားလား။ <a href="{{ route('login') }}">အကောင့်ဝင်ရန်</a></p>
</x-guest-layout>