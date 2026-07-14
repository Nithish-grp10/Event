<?php

namespace App\Modules\Core\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Modules\Events\Models\Event;
use App\Modules\LegacyForms\Models\Form;
use App\Modules\Applications\Models\Application;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (empty($query)) {
            return response()->json([]);
        }

        $user = auth()->user();

        // Base Queries based on role
        $eventsQuery = Event::where('title', 'like', "%{$query}%");
        $formsQuery = Form::where('title', 'like', "%{$query}%");
        $appsQuery = Application::with('event')->where('applicant_name', 'like', "%{$query}%")
                                ->orWhere('applicant_email', 'like', "%{$query}%");

        if ($user->hasRole('admin')) {
            $eventsQuery->where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('assigned_admin_id', $user->id);
            });
            $appsQuery->whereHas('event', function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('assigned_admin_id', $user->id);
            });
        } elseif ($user->hasRole('staff')) {
            $eventsQuery->whereHas('staff', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
            $appsQuery->whereHas('event.staff', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
            $formsQuery->whereId(0); // Staff don't see forms
        }

        $results = [];

        $events = $eventsQuery->limit(5)->get();
        foreach ($events as $event) {
            $results[] = [
                'type' => 'Event',
                'title' => $event->title,
                'url' => route('events.show', $event),
                'icon' => 'calendar'
            ];
        }

        $forms = $formsQuery->limit(5)->get();
        foreach ($forms as $form) {
            $results[] = [
                'type' => 'Form',
                'title' => $form->title,
                'url' => route('forms.edit', $form),
                'icon' => 'clipboard-document-list'
            ];
        }

        $apps = $appsQuery->limit(5)->get();
        foreach ($apps as $app) {
            $results[] = [
                'type' => 'Application',
                'title' => $app->applicant_name . ' (' . $app->event->title . ')',
                'url' => route('applications.show', $app),
                'icon' => 'inbox-arrow-down'
            ];
        }

        return response()->json($results);
    }
}
