<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

       $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">
    <div class="text-center">
        <!-- Logo with subtle border and shadow -->
        <div class="mx-auto h-16 w-16 bg-white border border-gray-200 rounded-2xl flex items-center justify-center shadow-md">
            <svg class="h-8 w-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </div>
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900 tracking-tight">Welcome back</h2>
        <p class="mt-2 text-sm text-gray-500">Sign in to your account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 mt-4" :status="session('status')" />

    <!-- Login Form -->
    <form wire:submit="login" class="mt-8 space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email address')" class="text-gray-700 font-semibold text-sm" />
            <div class="mt-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                </div>
                <x-text-input wire:model="form.email" id="email"
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-400 focus:border-gray-400 transition shadow-sm"
                    type="email" name="email" required autofocus autocomplete="username"
                    placeholder="you@example.com" />
            </div>
            <x-input-error :messages="$errors->get('form.email')" class="mt-1 text-sm text-red-600" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold text-sm" />
            <div class="mt-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <x-text-input wire:model="form.password" id="password"
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-400 focus:border-gray-400 transition shadow-sm"
                    type="password" name="password" required autocomplete="current-password"
                    placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-1 text-sm text-red-600" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember" class="flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="rounded border-gray-300 text-gray-700 shadow-sm focus:ring-gray-400 focus:ring-offset-0">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" wire:navigate
                    class="text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit"
                class="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-700 transition-all shadow-md">
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
                {{ __('Log in') }}
            </button>
        </div>
    </form>

    <!-- Sign up link -->
    <p class="mt-6 text-center text-sm text-gray-500">
        Don't have an account?
        <a href="{{ route('register') }}" wire:navigate class="font-semibold text-gray-700 hover:text-gray-900 transition">
            Sign up
        </a>
    </p>
</div>
