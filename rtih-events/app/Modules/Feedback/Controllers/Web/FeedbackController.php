<?php

namespace App\Modules\Feedback\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Modules\Events\Models\Event;
use App\Modules\Feedback\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'), 403);
        
        $query = Feedback::with(['event', 'application'])->latest();

        if (auth()->user()->hasRole('admin')) {
            $query->whereHas('event', function ($q) {
                $q->where('created_by', auth()->id())
                  ->orWhere('assigned_admin_id', auth()->id());
            });
        }

        $feedbacks = $query->paginate(25);
        return view('feedback.index', compact('feedbacks'));
    }

    public function create(Event $event)
    {
        return view('public.feedback', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'overall_rating' => 'required|integer|min:1|max:5',
            'speaker_rating' => 'nullable|integer|min:1|max:5',
            'venue_rating' => 'nullable|integer|min:1|max:5',
            'content_rating' => 'nullable|integer|min:1|max:5',
            'comments' => 'nullable|string',
            'suggestions' => 'nullable|string',
        ]);

        $feedback = Feedback::create([
            'event_id' => $event->id,
            'application_id' => $request->application_id ?? null,
            'overall_rating' => $validated['overall_rating'],
            'speaker_rating' => $validated['speaker_rating'],
            'venue_rating' => $validated['venue_rating'],
            'content_rating' => $validated['content_rating'],
            'comments' => $validated['comments'],
            'suggestions' => $validated['suggestions'],
        ]);

        $usersToNotify = collect();
        if ($event->creator) $usersToNotify->push($event->creator);
        
        $assignedAdmin = \App\Modules\Users\Models\User::find($event->assigned_admin_id);
        if ($assignedAdmin) $usersToNotify->push($assignedAdmin);

        \Illuminate\Support\Facades\Notification::send(
            $usersToNotify->unique(),
            new \App\Notifications\NewFeedbackNotification($feedback)
        );

        return redirect()->route('public.feedback.thanks');
    }
}
