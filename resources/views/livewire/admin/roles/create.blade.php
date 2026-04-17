<div class="py-6">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Create New Role</h2>
                    <p class="text-sm text-gray-500 mt-1">Add a role and assign permissions</p>
                </div>
                <a href="{{ route('admin.roles.index') }}" wire:navigate
                   class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                    ← Back to Roles
                </a>
            </div>
        </div>

        @can('role-create')
            <div class="bg-white rounded-xl shadow-md border border-gray-100">
                <form wire:submit="save" class="p-6">
                    <!-- Role Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Role Name</label>
                        <input type="text" id="name" wire:model="name"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="e.g., admin, editor, viewer">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">Use lowercase letters (e.g., admin, editor, viewer)</p>
                    </div>

                    <!-- Permissions Section -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Assign Permissions</label>

                        @if($permissions && $permissions->isNotEmpty())
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <!-- Select All Toggle -->
                                <div class="mb-3 pb-3 border-b border-gray-200">
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox"
                                               wire:model.live="allSelected"
                                               wire:change="toggleSelectAll"
                                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-sm font-medium text-gray-700">Select All Permissions</span>
                                    </label>
                                </div>

                                <!-- Individual Permissions -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-64 overflow-y-auto">
                                    @foreach($permissions as $permission)
                                        <label class="flex items-center space-x-2 cursor-pointer">
                                            <input type="checkbox"
                                                   value="{{ $permission->id }}"
                                                   wire:model.live="selectedPermissions"
                                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            @error('selectedPermissions')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        @else
                            <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                                <p class="text-sm text-yellow-700">
                                    No permissions found.
                                    <a href="{{ route('admin.permissions.manage') }}" wire:navigate class="text-indigo-600 hover:underline">
                                        Create a permission
                                    </a>
                                    first before assigning to roles.
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
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
        @else
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-8 text-center">
                <div class="flex justify-center mb-4">
                    <svg class="h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Access Denied</h3>
                <p class="text-gray-600">You do not have permission to create roles.</p>
                <a href="{{ route('admin.roles.index') }}" wire:navigate
                   class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Return to Roles
                </a>
            </div>
        @endcan
    </div>
</div>
