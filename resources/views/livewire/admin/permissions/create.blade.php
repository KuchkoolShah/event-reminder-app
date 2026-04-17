<div class="py-6">
    <div class="max-w-2xl mx-auto">
        <!-- Simple White Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ $isEditing ? 'Edit Permission' : 'Create New Permission' }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $isEditing ? 'Update the permission details' : 'Add a new permission to the system' }}
                    </p>
                </div>
                <a href="{{ route('admin.permissions.index') }}" wire:navigate
                   class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                    ← Back to Permissions
                </a>
            </div>
        </div>

        <!-- Check permission based on mode -->
        @if(($isEditing && auth()->user()->can('permission-edit')) || (!$isEditing && auth()->user()->can('permission-create')))
            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100">
                <form wire:submit="save" class="p-6">
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Permission Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <input type="text" id="name" wire:model="name"
                                   class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="e.g., permission-list, task-create, user-delete">
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">Use lowercase letters and hyphens (e.g., permission-list, task-create)</p>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.permissions.index') }}" wire:navigate
                           class="px-5 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-sm font-medium">
                            {{ $isEditing ? 'Update Permission' : 'Create Permission' }}
                        </button>
                    </div>
                </form>
            </div>
        @else
            <!-- Unauthorized Message -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-8 text-center">
                <div class="flex justify-center mb-4">
                    <svg class="h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Access Denied</h3>
                <p class="text-gray-600">
                    You do not have the required permission to {{ $isEditing ? 'edit' : 'create' }} permissions.
                </p>
                <a href="{{ route('admin.permissions.index') }}" wire:navigate
                   class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Return to Permissions
                </a>
            </div>
        @endif
    </div>
</div>
