<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegistrationRequest;
use App\Models\Event;
use App\Models\Registration;
use App\Notifications\EventRegisteredNotification;
use App\Notifications\RegistrationStatusChangedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function store(StoreRegistrationRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $event = Event::findOrFail($request->event_id);

        // 1. Check if event is completed or cancelled
        if (in_array($event->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Event ini sudah selesai atau dibatalkan.');
        }

        // 2. Check duplicate registration
        $existing = Registration::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah mendaftar pada event ini sebelumnya.');
        }

        // 3. Check capacity limit
        if ($event->isFull()) {
            return back()->with('error', 'Maaf, kuota pendaftaran event ini sudah penuh.');
        }

        // 4. Create registration
        $registration = Registration::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'registration_date' => now(),
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        // Send notification to registering user
        $user->notify(new EventRegisteredNotification($registration));

        // Send notification to event creator / admin
        if ($event->creator && $event->creator->id !== $user->id) {
            $event->creator->notify(new EventRegisteredNotification($registration));
        }

        return redirect()->route('registrations.my')
            ->with('success', 'Pendaftaran Anda berhasil dikirim! Menunggu konfirmasi panitia.');
    }

    public function myRegistrations(): View
    {
        $user = Auth::user();
        $registrations = Registration::with(['event.category', 'event.creator'])
            ->where('user_id', $user->id)
            ->latest('registration_date')
            ->paginate(10);

        return view('registrations.my_registrations', compact('registrations'));
    }

    public function cancel(Registration $registration): RedirectResponse
    {
        $user = Auth::user();
        if ($registration->user_id !== $user->id && ! $user->isAdmin()) {
            abort(403, 'Anda tidak memiliki hak akses untuk membatalkan pendaftaran ini.');
        }

        $registration->update(['status' => 'cancelled']);

        return back()->with('success', 'Pendaftaran event berhasil dibatalkan.');
    }

    public function index(Request $request): View
    {
        $user = Auth::user();
        $filters = $request->only(['search', 'event_id', 'status']);

        $query = Registration::with(['user', 'event.category'])->latest('registration_date');

        // If user is Panitia (and not Admin), restrict to events created by this panitia
        if ($user->isPanitia()) {
            $query->whereHas('event', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            });
        }

        $registrations = $query->filter($filters)
            ->paginate(10)
            ->withQueryString();

        $eventsQuery = Event::orderBy('name');
        if ($user->isPanitia()) {
            $eventsQuery->where('created_by', $user->id);
        }
        $events = $eventsQuery->get();

        return view('registrations.index', compact('registrations', 'events', 'filters'));
    }

    public function updateStatus(Request $request, Registration $registration): RedirectResponse
    {
        $user = Auth::user();

        if ($user->isPanitia() && $registration->event->created_by !== $user->id && ! $user->isAdmin()) {
            abort(403, 'Anda tidak diizinkan mengubah status pendaftaran event ini.');
        }

        $request->validate([
            'status' => 'required|in:pending,approved,rejected,attended,cancelled',
        ]);

        $registration->update([
            'status' => $request->status,
        ]);

        // Send status change notification to registered student
        $registration->user->notify(new RegistrationStatusChangedNotification($registration));

        return back()->with('success', 'Status pendaftaran peserta berhasil diperbarui.');
    }

    public function quickCheckIn(Registration $registration): RedirectResponse
    {
        $user = Auth::user();

        if ($user->isPanitia() && $registration->event->created_by !== $user->id && ! $user->isAdmin()) {
            abort(403, 'Anda tidak diizinkan mengubah status presensi event ini.');
        }

        $registration->update(['status' => 'attended']);
        $registration->user->notify(new RegistrationStatusChangedNotification($registration));

        return back()->with('success', "Presensi kehadiran peserta {$registration->user->name} berhasil dikonfirmasi (Hadir).");
    }

    public function showTicket(Registration $registration): View
    {
        $user = Auth::user();

        if ($registration->user_id !== $user->id && ! $user->isAdmin() && ! $user->isPanitia()) {
            abort(403, 'Anda tidak diizinkan melihat E-Tiket pengguna lain.');
        }

        $registration->load(['user', 'event.category', 'event.creator']);

        return view('registrations.ticket', compact('registration'));
    }
}
