<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\EventService;   // ✅ Import existing service

#[Layout('layouts.app')]
class Create extends Component
{
    use AuthorizesRequests;

    protected EventService $eventService;

    public $eventId = null;
    public $title = '';
    public $description = '';
    public $event_time = '';
    public $is_public = false;


    public function boot(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    protected function rules()
    {
        $rules = [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'event_time'  => 'required|date',
            'is_public'   => 'boolean',
        ];

        if (!$this->eventId) {
            $rules['event_time'] .= '|after:now';
        }

        return $rules;
    }

    public function mount($event = null)
    {
        if ($event) {
            $this->authorize('events-edit');

            if ($event instanceof Event) {
                $eventModel = $event;
            } else {
                $eventModel = Event::find($event);
            }

            if (!$eventModel) {
                abort(404, 'Event not found.');
            }

            if (!auth()->user()->can('events-edit-any') && $eventModel->user_id !== auth()->id()) {
                abort(403, 'You do not own this event.');
            }

            $this->eventId     = $eventModel->id;
            $this->title       = $eventModel->title;
            $this->description = $eventModel->description;
            $this->event_time  = $eventModel->event_time->format('Y-m-d\TH:i');
            $this->is_public   = (bool) $eventModel->is_public;
        } else {
            $this->authorize('events-create');
        }
    }

    public function save()
    {
        if ($this->eventId) {
            $this->authorize('events-edit');
        } else {
            $this->authorize('events-create');
        }

        $this->validate();

        $data = [
            'title'       => $this->title,
            'description' => $this->description,
            'event_time'  => $this->event_time,
            'is_public'   => $this->is_public,
        ];

        if ($this->eventId) {

            $event = Event::findOrFail($this->eventId);


            if (!auth()->user()->can('events-edit-any') && $event->user_id !== auth()->id()) {
                abort(403, 'You do not own this event.');
            }

            $this->eventService->updateEvent($event, $data);
            session()->flash('success', 'Event updated successfully.');
        } else {
            $this->eventService->createEvent($data, Auth::id());
            session()->flash('success', 'Event created successfully.');
        }

        return redirect()->route('admin.events.index');
    }

    public function render()
    {
        return view('livewire.events.create');
    }
}
