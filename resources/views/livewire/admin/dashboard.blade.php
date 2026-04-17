<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold">Total Users</h2>
                <p class="text-4xl">{{ $totalUsers }}</p>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold">Total Events</h2>
                <p class="text-4xl">{{ $totalEvents }}</p>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <h3 class="text-xl font-semibold">Recent Events</h3>
        <ul class="mt-2 space-y-2">
            @foreach($recentEvents as $event)
                <li class="bg-gray-100 p-3 rounded">
                    {{ $event->title }} – {{ $event->date }}
                </li>
            @endforeach
        </ul>
    </div>
</div>
