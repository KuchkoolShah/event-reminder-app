<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Event;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    // Common
    public $isAdmin = false;

    public $totalUsers = 0;
    public $totalEvents = 0;
    public $newUsersThisMonth = 0;
    public $upcomingEvents = 0;
    public $recentEvents = [];
    public $chartData = ['labels' => [], 'values' => []];
    public $totalRoles = 0;
    public $totalPermissions = 0;
    public $eventsToday = 0;
    public $eventsThisWeek = 0;
    public $eventsThisMonth = 0;


    public $myEventsCount = 0;
    public $myUpcomingEventsCount = 0;
    public $myPastEventsCount = 0;
    public $recentMyEvents = [];

    public function mount()
    {
        $user = Auth::user();
        $this->isAdmin = $user->hasRole('admin');

        if ($this->isAdmin) {
            $this->loadAdminData();
        } else {
            $this->loadUserData();
        }
    }

    private function loadAdminData()
    {
        $this->totalUsers = User::count();
        $this->totalEvents = Event::count();
        $this->totalRoles = Role::count();
        $this->totalPermissions = Permission::count();

        $this->newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $this->eventsToday = Event::whereDate('event_time', Carbon::today())->count();
        $this->eventsThisWeek = Event::whereBetween('event_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $this->eventsThisMonth = Event::whereMonth('event_time', Carbon::now()->month)
            ->whereYear('event_time', Carbon::now()->year)
            ->count();

        $this->upcomingEvents = Event::where('event_time', '>=', Carbon::today())
            ->where('event_time', '<=', Carbon::today()->addDays(7))
            ->count();

        $this->recentEvents = Event::orderBy('event_time', 'desc')->take(5)->get();

        $months = [];
        $eventsCount = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M Y');
            $eventsCount[] = Event::whereYear('event_time', $month->year)
                ->whereMonth('event_time', $month->month)
                ->count();
        }
        $this->chartData = [
            'labels' => $months,
            'values' => $eventsCount,
        ];
    }

    private function loadUserData()
    {
        $userId = Auth::id();

        $this->myEventsCount = Event::where('user_id', $userId)->count();
        $this->myUpcomingEventsCount = Event::where('user_id', $userId)
            ->where('event_time', '>=', Carbon::today())
            ->count();
        $this->myPastEventsCount = Event::where('user_id', $userId)
            ->where('event_time', '<', Carbon::today())
            ->count();

        $this->recentMyEvents = Event::where('user_id', $userId)
            ->orderBy('event_time', 'desc')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
