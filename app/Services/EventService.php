<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use App\Notifications\EventReminderNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventService
{
    /**
     * Generate a unique slug: slugified-title + YmdHis
     * Example: "My Event" + "2025-04-17 14:30:00" -> "my-event-20250417143000"
     */
    protected function generateUniqueSlug(string $title, string $eventTime, ?int $excludeId = null): string
    {
        $titleSlug = Str::slug($title);
        $timeFormatted = Carbon::parse($eventTime)->format('YmdHis');
        $baseSlug = $titleSlug . '-' . $timeFormatted;

        $originalSlug = $baseSlug;
        $counter = 1;

        while ($this->slugExists($baseSlug, $excludeId)) {
            $baseSlug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $baseSlug;
    }

    protected function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $query = Event::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        return $query->exists();
    }

    public function createEvent(array $data, int $userId): Event
    {
        $slug = $this->generateUniqueSlug($data['title'], $data['event_time']);

        $event = DB::transaction(function () use ($data, $userId, $slug) {
            return Event::create([
                'user_id'       => $userId,
                'title'         => $data['title'],
                'slug'          => $slug,
                'description'   => $data['description'] ?? null,
                'event_time'    => $data['event_time'],
                'is_public'     => $data['is_public'] ?? false,
                'reminder_sent' => false,
            ]);
        });

        User::chunk(100, function ($users) use ($event) {
            foreach ($users as $user) {
                $user->notify(new EventReminderNotification($event));
                sleep(1);
            }
        });

        return $event;
    }

    public function updateEvent(Event $event, array $data): Event
    {
        $newSlug = $event->slug;
        if (($data['title'] ?? $event->title) !== $event->title ||
            ($data['event_time'] ?? $event->event_time) != $event->event_time) {
            $newSlug = $this->generateUniqueSlug(
                $data['title'] ?? $event->title,
                $data['event_time'] ?? $event->event_time,
                $event->id
            );
        }

        return DB::transaction(function () use ($event, $data, $newSlug) {
            $oldTime = $event->event_time;

            $event->update([
                'title'       => $data['title'],
                'slug'        => $newSlug,
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
