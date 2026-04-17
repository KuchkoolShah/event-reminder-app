<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\User;
use App\Notifications\EventReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class DispatchEventReminders extends Command
{
    protected $signature = 'reminders:dispatch';
    protected $description = 'Send reminders for events that have started to all users (throttled)';

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
            User::chunk(100, function ($users) use ($event) {
                foreach ($users as $user) {
                    $user->notify(new EventReminderNotification($event));
                    sleep(1); // throttling – 1 email per second
                }
            });

            $event->update(['reminder_sent' => true]);
            $this->info("Reminder for event ID {$event->id} sent to all users (throttled).");
        }

        return Command::SUCCESS;
    }
}
