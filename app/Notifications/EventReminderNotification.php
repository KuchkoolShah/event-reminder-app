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

    /**
     * Create a new notification instance.
     *
     * @param Event $event
     * @param string|null $customEmail
     */
    public function __construct(Event $event, $customEmail = null)
    {
        $this->event = $event;
        $this->customEmail = $customEmail;
        Log::debug('Notification instance created', ['event_id' => $event->id]);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route('admin.events.show', $this->event->slug);

        $mail = (new MailMessage)
            ->subject('Reminder: ' . $this->event->title)
            ->greeting('Hello ' . ($notifiable->name ?? 'User') . '!')
            ->line('Your event is happening now!')
            ->line('**Event:** ' . $this->event->title)
            ->line('**Date & Time:** ' . $this->event->event_time->format('Y-m-d H:i'))
            ->line('**Location:** ' . ($this->event->location ?? 'Online/Virtual'))
            ->action('View Event Details', $url)
            ->line('Thank you for using our application.');

        // Override recipient email if custom email is provided
        if ($this->customEmail) {
            $mail->to($this->customEmail);
        }

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'event_time' => $this->event->event_time->format('Y-m-d H:i:s'),
            'message' => 'Event "' . $this->event->title . '" is happening now!'
        ];
    }

    /**
     * Handle a notification failure.
     *
     * @param mixed $notifiable
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('EventReminderNotification failed', [
            'event_id' => $this->event->id,
            'exception' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
