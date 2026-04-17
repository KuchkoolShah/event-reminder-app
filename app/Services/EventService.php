<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use App\Notifications\EventReminderNotification;
use Illuminate\Support\Facades\DB;

class EventService
{
    public function createEvent(array $data, int $userId): Event
    {
        // 1. Create the event inside a short transaction
        $event = DB::transaction(function () use ($data, $userId) {
            return Event::create([
                'user_id'       => $userId,
                'title'         => $data['title'],
                'description'   => $data['description'] ?? null,
                'event_time'    => $data['event_time'],
                'is_public'     => $data['is_public'] ?? false,
                'reminder_sent' => false,
            ]);
        });

        // 2. Send notification to ALL users (chunked + throttled) after commit
        User::chunk(100, function ($users) use ($event) {
            foreach ($users as $user) {
                $user->notify(new EventReminderNotification($event));
                sleep(1); // 1 email per second – respects Mailtrap rate limit
            }
        });

        return $event;
    }

    public function updateEvent(Event $event, array $data): Event
    {
        return DB::transaction(function () use ($event, $data) {
            $oldTime = $event->event_time;

            $event->update([
                'title'       => $data['title'],
                'description' => $data['description'] ?? null,
                'event_time'  => $data['event_time'],
                'is_public'   => $data['is_public'] ?? false,
            ]);

            if ($oldTime != $event->event_time) {
                $event->update(['reminder_sent' => false]);
            }

            return $event;
        });
    }

    public function deleteEvent(Event $event): void
    {
        $event->delete();
    }
}
