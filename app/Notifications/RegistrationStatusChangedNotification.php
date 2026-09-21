<?php

namespace App\Notifications;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RegistrationStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(public Registration $registration) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $statusText = match ($this->registration->status) {
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'attended' => 'Hadir',
            'cancelled' => 'Dibatalkan',
            default => 'Pending',
        };

        return [
            'registration_id' => $this->registration->id,
            'event_id' => $this->registration->event_id,
            'event_name' => $this->registration->event->name,
            'status' => $this->registration->status,
            'title' => 'Update Status Pendaftaran',
            'message' => "Status pendaftaran Anda pada event '{$this->registration->event->name}' diubah menjadi: {$statusText}.",
            'url' => route('registrations.my'),
        ];
    }
}
