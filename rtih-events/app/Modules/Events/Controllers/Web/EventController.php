<?php

namespace App\Modules\Events\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Modules\Events\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EventController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Event::class);
        
        $events = Event::viewableByUser($request->user())
            ->latest()
            ->paginate(25);

        return view('events.index', compact('events'));
    }

    public function create()
    {
        $this->authorize('create', Event::class);
        $admins = \App\Modules\Users\Models\User::role(['admin', 'staff'])->get();
        return view('events.create', compact('admins'));
    }

    public function store(\App\Http\Requests\StoreEventRequest $request, \App\Services\EventService $eventService)
    {
        $event = $eventService->storeEvent($request->validated(), $request->user()->id);

        return redirect()->route('events.edit', $event)->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        $this->authorize('view', $event);
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        $forms = \App\Modules\LegacyForms\Models\Form::all();
        $admins = \App\Modules\Users\Models\User::role(['admin', 'staff'])->get();
        return view('events.edit', compact('event', 'forms', 'admins'));
    }

    public function update(\App\Http\Requests\UpdateEventRequest $request, Event $event, \App\Services\EventService $eventService)
    {
        $eventService->updateEvent($event, $request->validated());

        return redirect()->route('events.edit', $event)->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }

    public function publish(Event $event)
    {
        $this->authorize('publish', $event);
        if ($event->forms()->count() === 0) {
            return back()->withErrors(['publish' => 'Attach at least one form before publishing.']);
        }
        $event->update(['status' => 'published']);
        return back()->with('success', 'Event published successfully.');
    }

    public function unpublish(Event $event)
    {
        $this->authorize('publish', $event);
        $event->update(['status' => 'draft']);
        return back()->with('success', 'Event unpublished successfully.');
    }

    public function duplicate(Event $event)
    {
        $this->authorize('create', Event::class);
        $newSlug = $event->slug . '-copy-' . time();
        $newEvent = $event->replicate()->fill([
            'title' => $event->title . ' (Copy)',
            'slug' => $newSlug,
            'status' => 'draft'
        ]);
        $newEvent->save();
        $newEvent->forms()->sync($event->forms->pluck('id'));
        return redirect()->route('events.edit', $newEvent)->with('success', 'Event duplicated successfully.');
    }

    public function updateStatus(Request $request, Event $event, $status)
    {
        $this->authorize('update', $event);
        if (!in_array($status, ['draft', 'published', 'completed', 'cancelled', 'archived'])) {
            abort(400, 'Invalid status.');
        }
        $event->update(['status' => $status]);
        return back()->with('success', "Event status updated to {$status}.");
    }
}
