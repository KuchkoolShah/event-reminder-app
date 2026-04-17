<?php

use Spatie\Permission\Models\Role; // add at top
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        // Assign default role "user" (must exist in database)
        $user->assignRole('user');

        Auth::login($user);

        $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: true);
    }
};
?>

<div class="min-h-screen flex items-center justify-center bg-white px-4">

    <div class="w-full max-w-md bg-white border border-gray-200 rounded-2xl shadow-sm p-8">

        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-white border border-gray-200 rounded-2xl flex items-center justify-center shadow-sm">
                <svg class="h-8 w-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>

            <h2 class="mt-6 text-2xl font-bold text-gray-900">Create Account</h2>
            <p class="mt-2 text-sm text-gray-500">Sign up to continue</p>
        </div>

        <!-- Form -->
        <form wire:submit="register" class="mt-6 space-y-4">

            <input type="text" wire:model.live="name"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-0"
                placeholder="Full Name">

            @error('name')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror

            <input type="email" wire:model.live="email"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-0"
                placeholder="Email">

            @error('email')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror

            <input type="password" wire:model.live="password"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-0"
                placeholder="Password">

            @error('password')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror

            <input type="password" wire:model.live="password_confirmation"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-0"
                placeholder="Confirm Password">

            <!-- Actions -->
            <div class="flex items-center justify-between pt-2">

                <a href="{{ route('login') }}"
                   class="text-sm text-gray-600 hover:text-gray-900 underline">
                    Already registered?
                </a>

                <button type="submit"
                    class="px-6 py-3 bg-gray-900 text-white rounded-xl hover:bg-black transition">
                    Register
                </button>

            </div>

        </form>

    </div>
</div>
