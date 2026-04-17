<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Event;
use App\Notifications\EventReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEventReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $event;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, Event $event)
    {
        $this->user = $user;
        $this->event = $event;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->user->notify(new EventReminderNotification($this->event));

            Log::info('Event reminder sent', [
                'user_id' => $this->user->id,
                'user_email' => $this->user->email,
                'event_id' => $this->event->id,
                'event_name' => $this->event->title,
                'event_time' => $this->event->event_time->format('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send event reminder', [
                'user_id' => $this->user->id,
                'event_id' => $this->event->id,
                'error' => $e->getMessage()
            ]);

            throw $e; // Re-throw to trigger retry
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Event reminder job failed permanently', [
            'user_id' => $this->user->id,
            'event_id' => $this->event->id,
            'error' => $exception->getMessage()
        ]);
    }
}
