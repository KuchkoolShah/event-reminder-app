<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Event;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public $totalUsers;
    public $totalEvents;
    public $newUsersThisMonth;
    public $upcomingEvents;
    public $recentEvents;
    public $chartData;

    public function mount()
    {
        if (!in_array(Auth::user()->role, [0, 1])) {
            abort(403, 'Unauthorized');
        }

        $this->totalUsers = User::count();
        $this->totalEvents = Event::count();

        $this->newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Upcoming events (next 7 days) based on event_time
        $this->upcomingEvents = Event::where('event_time', '>=', Carbon::today())
            ->where('event_time', '<=', Carbon::today()->addDays(7))
            ->count();

        // Recent events (last 5) ordered by event_time
        $this->recentEvents = Event::orderBy('event_time', 'desc')->take(5)->get();

        // Chart data: events per month (last 6 months) using event_time
        $months = collect();
        $eventsCount = collect();

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months->push($month->format('M Y'));
            $count = Event::whereYear('event_time', $month->year)
                ->whereMonth('event_time', $month->month)
                ->count();
            $eventsCount->push($count);
        }

        $this->chartData = [
            'labels' => $months,
            'values' => $eventsCount,
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
