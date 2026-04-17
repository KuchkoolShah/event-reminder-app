<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\EventService;

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

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_time' => 'required|date|after:now',
            'is_public' => 'boolean',
        ];
    }

    protected function messages()
    {
        return [
            'title.required' => 'The event title is required.',
            'title.max' => 'The title must not exceed 255 characters.',
            'event_time.required' => 'The event date and time is required.',
            'event_time.date' => 'Please provide a valid date and time.',
            'event_time.after' => 'The event time must be in the future.',
        ];
    }

    public function boot(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function mount($event = null)
    {
        if ($event) {
            $this->authorize('events-edit');

            $eventModel = $event instanceof Event
                ? $event
                : Event::findOrFail($event);

            if (!auth()->user()->can('events-edit-any') && $eventModel->user_id !== auth()->id()) {
                abort(403, 'You do not own this event.');
            }

            $this->eventId = $eventModel->id;
            $this->title = $eventModel->title;
            $this->description = $eventModel->description;
            $this->event_time = $eventModel->event_time->format('Y-m-d\TH:i');
            $this->is_public = (bool) $eventModel->is_public;
        } else {
            $this->authorize('events-create');
        }
    }

    public function save()
    {
        // Validate using Livewire's built-in validation
        $validatedData = $this->validate();

        $data = [
            'title'       => $this->title,
            'description' => $this->description,
            'event_time'  => $this->event_time,
            'is_public'   => $this->is_public,
        ];

        if ($this->eventId) {
            $this->authorize('events-edit');

            $event = Event::findOrFail($this->eventId);

            if (!auth()->user()->can('events-edit-any') && $event->user_id !== auth()->id()) {
                abort(403, 'You do not own this event.');
            }

            $this->eventService->updateEvent($event, $data);
            session()->flash('success', 'Event updated successfully.');
        } else {
            $this->authorize('events-create');
            $this->eventService->createEvent($data, Auth::id());
            session()->flash('success', 'Event created successfully.');
        }

        return redirect()->route('admin.events.index');
    }

    // Optional: Real-time validation
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.events.create');
    }
}
