<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">User Details</h2>
            <p class="text-sm text-gray-500 mt-1">View user information and assigned roles</p>
        </div>
        <div class="flex space-x-3">
            @can('user-edit')
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition text-sm font-medium">
                    Edit User
                </a>
            @endcan
            <a href="{{ route('admin.users.index') }}" wire:navigate
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition text-sm font-medium">
                ← Back to Users
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Full Name</label>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $user->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Email Address</label>
                    <p class="mt-1 text-lg text-gray-900">{{ $user->email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Account Created</label>
                    <p class="mt-1 text-gray-900">{{ $user->created_at->format('F j, Y, g:i a') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Last Updated</label>
                    <p class="mt-1 text-gray-900">{{ $user->updated_at->diffForHumans() }}</p>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <label class="block text-sm font-medium text-gray-500 mb-3">Assigned Roles</label>
                <div class="flex flex-wrap gap-2">
                    @forelse($user->roles as $role)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                            {{ ucfirst($role->name) }}
                        </span>
                    @empty
                        <p class="text-gray-500 italic">No roles assigned.</p>
                    @endforelse
                </div>
            </div>

            @if($user->getAllPermissions()->count())
                <div class="border-t border-gray-200 pt-6">
                    <label class="block text-sm font-medium text-gray-500 mb-3">Effective Permissions</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($user->getAllPermissions() as $permission)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                {{ $permission->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
