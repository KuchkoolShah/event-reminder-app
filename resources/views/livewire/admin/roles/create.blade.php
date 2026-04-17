<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Create New Role</h2>
                <p class="text-sm text-gray-500 mt-1">Define a role and assign permissions</p>
            </div>
            <a href="{{ route('admin.roles.index') }}" wire:navigate
               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                ← Back to Roles
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100">
            <form wire:submit="save" class="p-6">
                <!-- Role Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Role Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <input type="text" id="name" wire:model="name"
                               class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="e.g., editor, moderator, viewer">
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Permissions Section with Select All -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <label class="block text-sm font-semibold text-gray-700">Assign Permissions</label>
                        @if($permissions->isNotEmpty())
                            <button type="button"
                                    wire:click="toggleSelectAll"
                                    class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition">
                                {{ $allSelected ? 'Deselect All' : 'Select All' }}
                            </button>
                        @endif
                    </div>

                    @if($permissions->isEmpty())
                        <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200 text-yellow-800 text-sm">
                            No permissions available. Please create permissions first.
                        </div>
                    @else
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            @foreach($permissions as $perm)
                                <label class="inline-flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox"
                                           wire:model="selectedPermissions"
                                           value="{{ $perm->id }}"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="text-sm text-gray-700">{{ $perm->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    @endif

                    @error('selectedPermissions')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.roles.index') }}" wire:navigate
                       class="px-5 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-sm font-medium">
                        Create Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
