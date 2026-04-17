<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm p-6 md:p-8">

            <!-- Status Badges -->
            <div class="mb-4 flex flex-wrap gap-2">
                <!-- Upcoming / Passed Status -->
                <span class="px-2 py-1 text-xs font-medium rounded-md
                    {{ $status === 'Upcoming' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $status }}
                </span>

                <!-- Public / Private -->
                <span class="px-2 py-1 text-xs font-medium rounded-md
                    {{ $event->is_public ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                    {{ $event->is_public ? 'Public' : 'Private' }}
                </span>

                <!-- Reminder Status -->
                <span class="px-2 py-1 text-xs font-medium rounded-md
                    {{ $event->reminder_sent ? 'bg-purple-100 text-purple-700' : 'bg-yellow-100 text-yellow-700' }}">
                    Reminder: {{ $event->reminder_sent ? 'Sent' : 'Pending' }}
                </span>
            </div>

            <!-- Expired Event Warning -->
            @if($isExpired)
                <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-md text-yellow-800 text-sm">
                    ⚠️ This event has already passed.
                </div>
            @endif

            <h1 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h1>

            <div class="mt-4 space-y-2 text-gray-700">
                <p><strong class="font-medium text-gray-900">Description:</strong>
                     {!! $event->description ?: 'No description' !!}
                </p>
                <p><strong class="font-medium text-gray-900">Date & Time:</strong>
                    {{ $event->event_time->format('l, F j, Y \a\t g:i A') }}
                </p>
                <p><strong class="font-medium text-gray-900">Created by:</strong>
                    {{ $event->user->name ?? 'Unknown' }}
                </p>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                @can('events-list')
                    <a href="{{ route('admin.events.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-md transition-colors">
                        ← Back
                    </a>
                @endcan

                @can('events-edit')
                    <a href="{{ route('admin.events.create', $event) }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white text-sm font-medium rounded-md transition-colors">
                        Edit
                    </a>
                @endcan

                @can('delete', $event)
                    <button wire:click="delete" wire:confirm="Are you sure you want to delete this event?"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                        Delete
                    </button>
                @endcan
            </div>
        </div>
    </div>
</div>
