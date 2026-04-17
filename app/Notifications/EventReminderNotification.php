<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class EventReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $event;
    protected $customEmail;

    public function __construct(Event $event, $customEmail = null)
    {
        $this->event = $event;
        $this->customEmail = $customEmail;
        Log::debug('Notification instance created', ['event_id' => $event->id]);
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('admin.events.show', $this->event);

        $mail = (new MailMessage)
            ->subject('Reminder: ' . $this->event->title)
            ->line('Your event is happening now!')
            ->line('Event: ' . $this->event->title)
            ->line('Date & Time: ' . $this->event->event_time->format('Y-m-d H:i'))
            ->action('View Event', $url)
            ->line('Thank you for using our application.');

        if ($this->customEmail) {
            $mail->to($this->customEmail);
        }

        return $mail;
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('EventReminderNotification failed', [
            'event_id' => $this->event->id,
            'exception' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
