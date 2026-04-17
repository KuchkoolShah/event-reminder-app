<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\User;
use App\Notifications\EventReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class DispatchEventReminders extends Command
{
    protected $signature = 'reminders:dispatch';
    protected $description = 'Send reminders for events that have started';

    public function handle(): int
    {
        $now = Carbon::now();

        $events = Event::query()
            ->where('event_time', '<=', $now)
            ->where('reminder_sent', false)
            ->get();

        if ($events->isEmpty()) {
            $this->info('No pending reminders.');
            return Command::SUCCESS;
        }

        foreach ($events as $event) {
            // Option 1: Send only to the event creator
            if ($event->user) {
                $event->user->notify(new EventReminderNotification($event));
                $this->info("Reminder queued for event ID {$event->id} to user ID {$event->user_id}");
            }

            // Option 2: If you need to send to multiple specific users (e.g., attendees)
            // $event->attendees->each->notify(new EventReminderNotification($event));

            $event->update(['reminder_sent' => true]);
        }

        return Command::SUCCESS;
    }
}
