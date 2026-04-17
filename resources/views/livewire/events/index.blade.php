<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8" wire:poll.30s="$refresh">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">My Events</h2>

        @can('events-create')
            <a href="{{ route('admin.events.create') }}"
               class="inline-flex items-center justify-center px-5 py-2.5 bg-gray-700 hover:bg-gray-800 rounded-xl text-sm font-semibold text-white shadow-md transition">
                + Create Event
            </a>
        @endcan
    </div>

    <!-- Flash Message -->
    @if (session()->has('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-sm text-gray-500">Total Events</p>
            <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Upcoming</p>
            <p class="text-2xl font-bold">{{ $stats['upcoming'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-gray-500">
            <p class="text-sm text-gray-500">Passed</p>
            <p class="text-2xl font-bold">{{ $stats['passed'] }}</p>
        </div>
    </div>

    <!-- Search & Per Page -->
    <div class="flex flex-col md:flex-row gap-4 mb-6 justify-between items-end">
        <div class="relative w-full md:w-96">
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   placeholder="Search title or description..."
                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500">
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Show</label>
            <select wire:model.live="perPage"
                    class="rounded-lg border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
            </select>
        </div>
    </div>

    <!-- Events Table -->
    @if($events->count())
        <div class="overflow-x-auto bg-white rounded-lg shadow-sm border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($events as $event)
                        <tr wire:key="event-{{ $event->id }}" class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.events.show', $event) }}"
                                   class="font-medium text-gray-700 hover:underline">
                                    {{ $event->title }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $event->event_time->format('M d, Y H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $event->status == 'Upcoming'
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-gray-100 text-gray-600' }}">
                                    {{ $event->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                     @can('events-show')
                                    <a href="{{ route('admin.events.show', $event) }}"
                                       class="text-blue-600 hover:underline">View</a>
                                      @endcan
                                    @can('events-create')
                                        <a href="{{ route('admin.events.create', $event) }}"
                                           class="text-gray-600 hover:underline">Edit</a>
                                    @endcan

                                    @can('events-delete')
                                        <button wire:click="deleteEvent({{ $event->id }})"
                                                wire:confirm="Are you sure?"
                                                class="text-red-600 hover:underline">Delete</button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex flex-col md:flex-row justify-between items-center gap-3">
            <p class="text-sm text-gray-500">
                Showing {{ $events->firstItem() }} to {{ $events->lastItem() }}
                of {{ $events->total() }} results
            </p>
            {{ $events->appends([
                'search' => $search,
                'statusFilter' => $statusFilter,
                'perPage' => $perPage
            ])->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-lg shadow border">
            <h3 class="text-lg font-semibold text-gray-800">No Events Found</h3>
            <p class="text-sm text-gray-500 mt-1">Create your first event.</p>
            @can('events-create')
                <a href="{{ route('admin.events.create') }}"
                   class="mt-4 inline-block px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800">
                    + Create Event
                </a>
            @endcan
        </div>
    @endif
</div>
