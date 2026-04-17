<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8"
     x-data="{
         flashMessage: '{{ session('message') }}',
         showPassword: false,
         showPasswordConfirmation: false
     }"
     x-init="setTimeout(() => flashMessage = '', 4000)">

    {{-- Auto-dismiss flash message --}}
    <template x-if="flashMessage">
        <div class="mb-6 flex items-center p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm">
            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-green-700" x-text="flashMessage"></span>
            <button @click="flashMessage = ''" class="ml-auto text-green-500 hover:text-green-700">✕</button>
        </div>
    </template>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-sm">
            <div class="flex">
                <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="font-medium text-red-700">Please fix the following errors:</p>
                    <ul class="list-disc list-inside text-sm text-red-600 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Header Card --}}
    <div class="flex justify-center mb-6">
        <div class="w-full md:w-2/3 lg:w-1/2">
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Create New User</h2>
                        <p class="text-sm text-gray-500 mt-1">Fill in the details below</p>
                    </div>
                    <a href="{{ route('admin.users.index') }}" wire:navigate
                       class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                        ← Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="flex justify-center">
        <div class="w-full md:w-2/3 lg:w-1/2">
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                <form wire:submit.prevent="save" class="p-6 space-y-5">
                    {{-- Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" wire:model.live.debounce.300ms="name" placeholder="John Doe"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                        <input type="email" wire:model.live.debounce.300ms="email" placeholder="john@example.com"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-500 @enderror">
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Password with toggle & generator --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" wire:model="password" id="password" placeholder="••••••••"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('password') border-red-500 @enderror pr-24">
                            <div class="absolute inset-y-0 right-0 flex items-center gap-1 pr-3">
                                <button type="button" @click="showPassword = !showPassword" class="text-gray-400 hover:text-indigo-600 text-sm">
                                    <span x-text="showPassword ? '🙈' : '👁️'"></span>
                                </button>
                                <button type="button" wire:click="generatePassword" class="text-xs bg-indigo-100 hover:bg-indigo-200 text-indigo-700 px-2 py-1 rounded transition">Generate</button>
                            </div>
                        </div>
                        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                        {{-- Strength meter --}}
                        <div class="mt-2 h-1 w-full bg-gray-200 rounded-full overflow-hidden">
                            <div x-data="{ strength: 0 }"
                                 x-init="$watch('$wire.password', val => strength = val.length > 0 ? Math.min(100, (val.length * 10) + (/\d/.test(val) ? 20 : 0) + (/[a-z]/.test(val) ? 20 : 0) + (/[A-Z]/.test(val) ? 20 : 0) + (/\W/.test(val) ? 20 : 0)) : 0)">
                                <div class="h-full transition-all duration-300" :style="`width: ${strength}%; background-color: ${strength < 30 ? '#ef4444' : strength < 70 ? '#f59e0b' : '#10b981'}`"></div>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-400">Minimum 8 characters, at least one letter and one number.</p>
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                        <div class="relative">
                            <input :type="showPasswordConfirmation ? 'text' : 'password'" wire:model="password_confirmation" placeholder="••••••••"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-10">
                            <button type="button" @click="showPasswordConfirmation = !showPasswordConfirmation" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-indigo-600">
                                <span x-text="showPasswordConfirmation ? '🙈' : '👁️'"></span>
                            </button>
                        </div>
                    </div>

                    {{-- Roles Section – only visible if user has 'role-assign' permission --}}
                    @can('role-assign')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Assign Roles
                                @if(count($roles) > 0)
                                    <span class="ml-2 bg-indigo-100 text-indigo-800 text-xs font-medium px-2 py-0.5 rounded-full">{{ count($roles) }} selected</span>
                                @endif
                            </label>
                            <div class="grid grid-cols-1 gap-2 bg-gray-50 p-3 rounded-md">
                                @foreach($allRoles as $role)
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" value="{{ $role->id }}" wire:model="roles"
                                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-sm text-gray-700">{{ ucfirst($role->name) }}</span>
                                        @if($role->description)
                                            <span class="text-xs text-gray-400" title="{{ $role->description }}">ⓘ</span>
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                            @error('roles') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            @if($allRoles->isEmpty())
                                <p class="mt-2 text-sm text-amber-600">
                                    No roles defined yet. <a href="{{ route('admin.roles.create') }}" class="text-indigo-600 hover:underline">Create a role</a> first.
                                </p>
                            @endif
                        </div>
                    @else
                        {{-- Optional: show a message that role assignment is not available --}}
                        <div class="text-sm text-gray-500 bg-gray-50 p-3 rounded-md">
                            ⚠️ You don't have permission to assign roles. New users will be created without any roles.
                        </div>
                    @endcan

                    {{-- Action Buttons --}}
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.users.index') }}"
                           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition text-sm font-medium">
                            Cancel
                        </a>
                        <button type="submit" wire:loading.attr="disabled"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition shadow-sm disabled:opacity-50 flex items-center">
                            <span wire:loading.remove wire:target="save">Create User</span>
                            <span wire:loading wire:target="save" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Creating...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
