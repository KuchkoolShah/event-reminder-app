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

  <!-- Filter Bar – Compact & Full Width -->
<div class="w-full mb-4">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2 items-end">
        <!-- Search -->
        <div class="col-span-1 lg:col-span-2">
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   placeholder="Search title or description..."
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 text-sm">
        </div>

        <!-- Sort Order -->
        <div>
            <select wire:model.live="sortOrder"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 text-sm">
                <option value="asc">Sort: Soonest first</option>
                <option value="desc">Sort: Latest first</option>
            </select>
        </div>

        <!-- Date Range -->
        <div>
            <select wire:model.live="dateRange"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 text-sm">
                <option value="all">All dates</option>
                <option value="today">Today</option>
                <option value="week">This week</option>
                <option value="month">This month</option>
                <option value="custom">Custom range</option>
            </select>
        </div>

        <!-- Custom Range Date Pickers -->
        @if($dateRange === 'custom')
            <div class="col-span-1 lg:col-span-2">
                <div class="flex gap-1">
                    <input type="date"
                           wire:model.live="startDate"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 text-sm">
                    <span class="self-center text-gray-500">–</span>
                    <input type="date"
                           wire:model.live="endDate"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 text-sm">
                </div>
            </div>
        @endif

        <!-- Per Page -->
        <div>
            <select wire:model.live="perPage"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 text-sm">
                <option value="5">5 per page</option>
                <option value="10">10 per page</option>
                <option value="15">15 per page</option>
                <option value="20">20 per page</option>
            </select>
        </div>
    </div>
</div>
    <!-- Status Filter (only shown when dateRange is 'all') -->
    @if($dateRange === 'all')
        <div class="flex gap-3 mb-6">
            <button wire:click="$set('statusFilter', '')"
                    class="px-3 py-1 rounded-full text-sm {{ $statusFilter === '' ? 'bg-gray-700 text-white' : 'bg-gray-200 text-gray-700' }}">
                All
            </button>
            <button wire:click="$set('statusFilter', 'upcoming')"
                    class="px-3 py-1 rounded-full text-sm {{ $statusFilter === 'upcoming' ? 'bg-gray-700 text-white' : 'bg-gray-200 text-gray-700' }}">
                Upcoming
            </button>
            <button wire:click="$set('statusFilter', 'passed')"
                    class="px-3 py-1 rounded-full text-sm {{ $statusFilter === 'passed' ? 'bg-gray-700 text-white' : 'bg-gray-200 text-gray-700' }}">
                Passed
            </button>
        </div>
    @endif

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
                                {{ $event->event_time > now() ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $event->event_time > now() ? 'Upcoming' : 'Passed' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                @can('events-show')
                                    <a href="{{ route('admin.events.show', $event->slug) }}"
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
                'perPage' => $perPage,
                'sortOrder' => $sortOrder,
                'dateRange' => $dateRange,
                'startDate' => $startDate,
                'endDate' => $endDate,
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
