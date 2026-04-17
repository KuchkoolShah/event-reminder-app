<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination, AuthorizesRequests;

    // Existing filters
    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;

    // New filters
    public $sortOrder = 'asc';          // 'asc' or 'desc'
    public $dateRange = 'all';          // 'all', 'today', 'week', 'month', 'custom'
    public $startDate = null;           // for custom range (Y-m-d)
    public $endDate = null;             // for custom range (Y-m-d)

    protected $queryString = [
        'search', 'statusFilter', 'perPage',
        'sortOrder', 'dateRange', 'startDate', 'endDate',
    ];

    public function mount()
    {
        $this->authorize('events-list');
    }

    // Reset page when any filter changes
    public function updatingSearch()    { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingPerPage()   { $this->resetPage(); }
    public function updatingSortOrder() { $this->resetPage(); }
    public function updatingDateRange() { $this->resetPage(); }
    public function updatingStartDate() { $this->resetPage(); }
    public function updatingEndDate()   { $this->resetPage(); }

    public function deleteEvent($eventId)
    {
        $this->authorize('events-delete');

        $event = Event::findOrFail($eventId);
        if (!auth()->user()->can('events-delete-any')) {
            if (auth()->id() !== $event->user_id) {
                abort(403, 'You do not own this event.');
            }
        }

        $event->delete();
        session()->flash('success', 'Event deleted successfully.');
        $this->resetPage();
    }

    protected function getBaseQuery()
    {
        if (auth()->user()->hasRole('admin') || auth()->user()->can('events-view-all')) {
            return Event::query();
        }
        return Event::where('user_id', auth()->id());
    }

    protected function getFilteredEvents()
    {
        $query = $this->getBaseQuery();

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Date range filter (takes precedence over statusFilter)
        $now = now();
        switch ($this->dateRange) {
            case 'today':
                $query->whereDate('event_time', $now->toDateString());
                break;
            case 'week':
                $query->whereBetween('event_time', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('event_time', $now->month)
                      ->whereYear('event_time', $now->year);
                break;
            case 'custom':
                if ($this->startDate) {
                    $query->whereDate('event_time', '>=', $this->startDate);
                }
                if ($this->endDate) {
                    $query->whereDate('event_time', '<=', $this->endDate);
                }
                break;
            default: // 'all' – use statusFilter
                if ($this->statusFilter === 'upcoming') {
                    $query->where('event_time', '>', $now);
                } elseif ($this->statusFilter === 'passed') {
                    $query->where('event_time', '<=', $now);
                }
                break;
        }

        // Apply sorting
        $query->orderBy('event_time', $this->sortOrder);

        return $query;
    }

    public function render()
    {
        $events = $this->getFilteredEvents()->paginate($this->perPage);

        $baseQuery = $this->getBaseQuery();
        $stats = [
            'total'    => (clone $baseQuery)->count(),
            'upcoming' => (clone $baseQuery)->where('event_time', '>', now())->count(),
            'passed'   => (clone $baseQuery)->where('event_time', '<=', now())->count(),
        ];

        return view('livewire.events.index', [
            'events' => $events,
            'stats'  => $stats,
        ]);
    }
}
