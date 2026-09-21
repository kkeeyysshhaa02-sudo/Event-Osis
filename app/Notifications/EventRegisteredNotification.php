<?php

namespace App\Notifications;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventRegisteredNotification extends Notification
{
    use Queueable;

    public function __construct(public Registration $registration) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'registration_id' => $this->registration->id,
            'event_id' => $this->registration->event_id,
            'event_name' => $this->registration->event->name,
            'user_name' => $this->registration->user->name,
            'status' => $this->registration->status,
            'title' => 'Pendaftaran Event Berhasil',
            'message' => "Anda telah berhasil mendaftar pada event '{$this->registration->event->name}'.",
            'url' => route('registrations.my'),
        ];
    }
}
