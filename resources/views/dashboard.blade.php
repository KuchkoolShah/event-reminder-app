<div>
    @if($isAdmin)
        <!-- ========== ADMIN DASHBOARD ========== -->
        <!-- First row: main stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm">Total Users</h2>
                        <p class="text-2xl font-semibold text-gray-800">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm">Total Events</h2>
                        <p class="text-2xl font-semibold text-gray-800">{{ $totalEvents }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm">Total Roles</h2>
                        <p class="text-2xl font-semibold text-gray-800">{{ $totalRoles }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-pink-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-pink-100 text-pink-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm">Total Permissions</h2>
                        <p class="text-2xl font-semibold text-gray-800">{{ $totalPermissions }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second row: additional stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-indigo-100 text-indigo-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm">New Users (This Month)</h2>
                        <p class="text-2xl font-semibold text-gray-800">{{ $newUsersThisMonth }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm">Events Today</h2>
                        <p class="text-2xl font-semibold text-gray-800">{{ $eventsToday }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-teal-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-teal-100 text-teal-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm">Upcoming (7 days)</h2>
                        <p class="text-2xl font-semibold text-gray-800">{{ $upcomingEvents }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart and recent events -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Events per Month</h3>
                <canvas id="eventsChart" width="400" height="200" x-data="{
                    init() {
                        const ctx = document.getElementById('eventsChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: {{ Js::from($chartData['labels']) }},
                                datasets: [{
                                    label: 'Events Created',
                                    data: {{ Js::from($chartData['values']) }},
                                    borderColor: 'rgb(59, 130, 246)',
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                    tension: 0.3,
                                    fill: true
                                }]
                            },
                            options: { responsive: true, maintainAspectRatio: true }
                        });
                    }
                }"></canvas>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Events</h3>
                <ul class="divide-y divide-gray-200">
                    @forelse($recentEvents as $event)
                        <li class="py-3 flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-800">{{ $event->title }}</p>
                                <p class="text-sm text-gray-500">{{ $event->event_time->format('M d, Y H:i') }}</p>
                            </div>
                            <a href="{{ route('admin.events.show', $event) }}" class="text-blue-600 hover:underline">View</a>
                        </li>
                    @empty
                        <li class="py-3 text-gray-500">No events found.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    @else
        <!-- ========== USER DASHBOARD ========== -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm">My Total Events</h2>
                        <p class="text-2xl font-semibold text-gray-800">{{ $myEventsCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm">Upcoming Events</h2>
                        <p class="text-2xl font-semibold text-gray-800">{{ $myUpcomingEventsCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-gray-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-gray-100 text-gray-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm">Past Events</h2>
                        <p class="text-2xl font-semibold text-gray-800">{{ $myPastEventsCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">My Recent Events</h3>
            <ul class="divide-y divide-gray-200">
                @forelse($recentMyEvents as $event)
                    <li class="py-3 flex justify-between items-center">
                        <div>
                            <p class="font-medium text-gray-800">{{ $event->title }}</p>
                            <p class="text-sm text-gray-500">{{ $event->event_time->format('M d, Y H:i') }}</p>
                        </div>
                        <a href="{{ route('admin.events.show', $event) }}" class="text-blue-600 hover:underline">View</a>
                    </li>
                @empty
                    <li class="py-3 text-gray-500">No events found. Create your first event!</li>
                @endforelse
            </ul>
        </div>
    @endif
</div>
