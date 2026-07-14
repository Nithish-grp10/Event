<?php

namespace App\Modules\Applications\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Modules\Events\Models\Event;
use App\Modules\Applications\Models\Application;
use Illuminate\Http\Request;

class PublicApplicationController extends Controller
{
    public function show(Event $event)
    {
        if ($event->status !== 'published') {
            abort(404);
        }

        $forms = $event->forms()->with(['fields' => function ($query) {
            $query->orderBy('sort_order');
        }])->get();

        if ($forms->isEmpty()) {
            abort(404);
        }

        $form = $forms->first(); // Take the first attached form

        return view('public.apply', compact('event', 'form'));
    }

    public function store(Request $request, Event $event)
    {
        if ($event->status !== 'published') {
            abort(404);
        }

        $forms = $event->forms()->with('fields')->get();
        if ($forms->isEmpty()) {
            abort(404);
        }
        
        $form = $forms->first();

        // Build validation rules dynamically
        $rules = [
            'applicant_name' => 'required|string|max:255',
            'applicant_email' => 'required|email|max:255',
        ];

        foreach ($form->fields as $field) {
            $fieldRule = [];
            if ($field->is_required) {
                $fieldRule[] = 'required';
            } else {
                $fieldRule[] = 'nullable';
            }
            if ($field->field_type === 'file') {
                $fieldRule[] = 'file';
                $fieldRule[] = 'max:10240'; // 10MB max
            }
            $rules['field_' . $field->id] = implode('|', $fieldRule);
        }

        $validated = $request->validate($rules);

        // Check for duplicates
        if (!$request->has('confirm_overwrite')) {
            $existing = Application::where('event_id', $event->id)
                ->where('applicant_email', $validated['applicant_email'])
                ->first();

            if ($existing) {
                return back()->withInput()->with('duplicate_warning', 'An application with this email already exists for this event. Submitting again will replace your previous answers with what you just entered. Continue?');
            }
        } else {
            $existing = Application::where('event_id', $event->id)
                ->where('applicant_email', $validated['applicant_email'])
                ->first();
        }

        // Collect answers
        $data = [];
        foreach ($form->fields as $field) {
            $key = 'field_' . $field->id;
            if ($field->field_type === 'file' && $request->hasFile($key)) {
                $path = $request->file($key)->store('applications', 'public');
                $data[$field->id] = $path;
            } else {
                $data[$field->id] = $validated[$key] ?? null;
            }
        }

        $barcodeToken = $existing ? $existing->barcode_token : \Illuminate\Support\Str::uuid()->toString();

        $application = Application::updateOrCreate(
            [
                'event_id' => $event->id,
                'applicant_email' => $validated['applicant_email']
            ],
            [
                'form_id' => $form->id,
                'applicant_name' => $validated['applicant_name'],
                'data' => $data,
                'status' => 'submitted',
                'barcode_token' => $barcodeToken,
            ]
        );

        // Optional: Send email with QR code here.
        if (!$existing) { // only send on new submission
            \Illuminate\Support\Facades\Mail::to($application->applicant_email)
                ->queue(new \App\Mail\ApplicationSubmitted($application));
            
            // Notify Event Owner and Assigned Admin
            $usersToNotify = collect();
            if ($event->creator) $usersToNotify->push($event->creator);
            
            $assignedAdmin = \App\Modules\Users\Models\User::find($event->assigned_admin_id);
            if ($assignedAdmin) $usersToNotify->push($assignedAdmin);

            \Illuminate\Support\Facades\Notification::send(
                $usersToNotify->unique(),
                new \App\Notifications\NewApplicationNotification($application)
            );
        }

        return redirect()->route('public.apply.confirmation', $application);
    }

    public function confirmation(Application $application)
    {
        $event = $application->event;
        return view('public.confirmation', compact('event'));
    }
}
