<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\User;
use App\Jobs\SendEventReminderJob;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class DispatchEventReminders extends Command
{
    protected $signature = 'reminders:dispatch';
    protected $description = 'Send reminders for events that have started to all users';

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
            $delay = 0;
            $userCount = 0;

            // Queue notifications with 1 second delay between each
            User::chunk(100, function ($users) use ($event, &$delay, &$userCount) {
                foreach ($users as $user) {
                    SendEventReminderJob::dispatch($user, $event)
                        ->delay(now()->addSeconds($delay));
                    $delay++;
                    $userCount++;
                }
            });

            $event->update(['reminder_sent' => true]);
            $this->info("Queued {$userCount} reminders for event ID {$event->id}");
        }

        $this->info('All reminders have been queued successfully!');
        return Command::SUCCESS;
    }
}
