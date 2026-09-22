<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $upcomingEvents = Event::with(['category'])
            ->withCount('activeRegistrations')
            ->whereIn('status', ['upcoming', 'ongoing'])
            ->latest('event_date')
            ->limit(6)
            ->get();

        $categories = Category::withCount('events')->orderBy('name')->get();

        $stats = [
            'total_events' => Event::count(),
            'total_registrations' => Registration::count(),
            'total_categories' => Category::count(),
            'upcoming_events' => Event::whereIn('status', ['upcoming', 'ongoing'])->count(),
        ];

        return view('home', compact('upcomingEvents', 'categories', 'stats'));
    }
}
