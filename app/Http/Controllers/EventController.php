<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'category', 'status']);

        $events = Event::with(['category', 'creator'])
            ->withCount('activeRegistrations')
            ->filter($filters)
            ->latest('event_date')
            ->paginate(6)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('events.index', compact('events', 'categories', 'filters'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('events.create', compact('categories'));
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = Auth::id();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event = Event::create($data);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event berhasil ditambahkan!');
    }

    public function show(Event $event): View
    {
        $event->load(['category', 'creator', 'registrations.user']);
        $event->loadCount('activeRegistrations');

        $userRegistration = Auth::check()
            ? $event->registrations->firstWhere('user_id', Auth::id())
            : null;

        return view('events.show', compact('event', 'userRegistration'));
    }

    public function edit(Event $event): View
    {
        $user = Auth::user();
        if ($user->isPanitia() && $event->created_by !== $user->id && ! $user->isAdmin()) {
            abort(403, 'Anda hanya dapat mengedit event yang Anda buat.');
        }

        $categories = Category::orderBy('name')->get();

        return view('events.edit', compact('event', 'categories'));
    }

    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $user = Auth::user();
        if ($user->isPanitia() && $event->created_by !== $user->id && ! $user->isAdmin()) {
            abort(403, 'Anda hanya dapat memperbarui event yang Anda buat.');
        }

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($data);

        return redirect()->route('events.show', $event)
            ->with('success', 'Data event berhasil diperbarui!');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $user = Auth::user();
        if ($user->isPanitia() && $event->created_by !== $user->id && ! $user->isAdmin()) {
            abort(403, 'Anda hanya dapat menghapus event yang Anda buat.');
        }

        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event berhasil dihapus.');
    }
}
