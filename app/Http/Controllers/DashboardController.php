<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $stats = [
            'total_events' => Event::count(),
            'upcoming_events' => Event::where('status', 'upcoming')->count(),
            'total_registrations' => Registration::count(),
            'total_users' => User::count(),
        ];

        $recentEvents = Event::with('category', 'creator')
            ->withCount('activeRegistrations')
            ->latest()
            ->take(5)
            ->get();

        $myRegistrations = collect();
        if ($user->isPeserta()) {
            $myRegistrations = Registration::with('event.category')
                ->where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
        }

        $myManagedEvents = collect();
        if ($user->isPanitia()) {
            $myManagedEvents = Event::with('category')
                ->withCount('activeRegistrations')
                ->where('created_by', $user->id)
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dashboard', compact('stats', 'recentEvents', 'myRegistrations', 'myManagedEvents'));
    }
}
