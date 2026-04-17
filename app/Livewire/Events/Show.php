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

    public function mount(Event $event)
    {
        $this->event = $event;

        // Check if user has the global 'events-show' permission (Spatie)
        $this->authorize('events-show');

        // Optional: additional ownership check for non-admins
        // if (!auth()->user()->can('events-edit-any') && $this->event->user_id !== auth()->id()) {
        //     abort(403, 'You do not own this event.');
        // }
    }

    public function delete()
    {
        // Uses policy (EventPolicy@delete) – ensure it checks ownership or permission
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
