<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination, AuthorizesRequests;

    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;

    protected $queryString = ['search', 'statusFilter', 'perPage'];

    public function mount()
    {
        // Authorize viewing the event list
        $this->authorize('events-list');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function deleteEvent($eventId)
    {
        // Authorize delete action using Spatie permission
        $this->authorize('events-delete');

        $event = Event::findOrFail($eventId);

        // Additional check: if user is not admin/manager, ensure they own the event
        // But if they have 'events-delete', they can delete any event (admin)
        // If you want to restrict to own events only for non-admins, keep this:
        if (!auth()->user()->can('events-delete-any')) { // optional separate permission
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
        // If user has 'events-list' (which they already do to be here),
        // but we may want to show all events or only their own.
        // For simplicity: users see their own events, admins see all.
        // Using Spatie role/permission to determine.
        if (auth()->user()->hasRole('admin') || auth()->user()->can('events-view-all')) {
            return Event::query();
        }
        return Event::where('user_id', auth()->id());
    }

    protected function getFilteredEvents()
    {
        $query = $this->getBaseQuery();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter === 'upcoming') {
            $query->where('event_time', '>', now());
        } elseif ($this->statusFilter === 'passed') {
            $query->where('event_time', '<=', now());
        }

        return $query->orderBy('event_time', 'asc');
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
