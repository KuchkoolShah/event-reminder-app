<?php

namespace App\Livewire\Events;

use App\Models\Event;
use App\Services\EventService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('layouts.app')]
class Show extends Component
{
    use AuthorizesRequests;

    public Event $event;
    public string $status;
    public bool $isExpired;

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->authorize('events-show');

        // Compute status and expired flag
        $this->status = $event->event_time->isFuture() ? 'Upcoming' : 'Passed';
        $this->isExpired = $event->event_time->isPast();
    }

    public function delete()
    {
        $this->authorize('delete', $this->event);
        (new EventService())->deleteEvent($this->event);
        session()->flash('success', 'Event deleted.');
        return redirect()->route('admin.events.index');
    }

    public function render()
    {
        return view('livewire.events.show');
    }
}
