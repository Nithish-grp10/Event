<?php

namespace App\Modules\Events\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Modules\Events\Models\Event;
use App\Modules\Applications\Models\Application;
use App\Modules\Events\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function scan(Event $event)
    {
        // Simple authorization check for scanning
        $user = auth()->user();
        $canScan = $user->hasRole('super-admin') || 
                   ($user->hasRole('admin') && $event->created_by === $user->id) ||
                   ($user->hasRole('staff') && $event->staff()->where('user_id', $user->id)->exists());

        if (!$canScan) {
            abort(403, 'Unauthorized to scan attendance for this event.');
        }

        return view('attendance.scan', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $user = auth()->user();
        $canScan = $user->hasRole('super-admin') || 
                   ($user->hasRole('admin') && $event->created_by === $user->id) ||
                   ($user->hasRole('staff') && $event->staff()->where('user_id', $user->id)->exists());

        if (!$canScan) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate(['barcode_token' => 'required|string']);

        $application = Application::where('event_id', $event->id)
            ->where('barcode_token', $request->barcode_token)
            ->first();

        if (!$application) {
            return response()->json(['success' => false, 'message' => 'Invalid or unknown ticket.'], 404);
        }

        // Idempotent attendance marking
        $attendance = Attendance::firstOrCreate(
            ['event_id' => $event->id, 'application_id' => $application->id],
            ['scanned_by' => $user->id]
        );

        if ($attendance->wasRecentlyCreated) {
            \Illuminate\Support\Facades\Mail::to($application->applicant_email)
                ->queue(new \App\Mail\FeedbackRequest($application));
        }

        return response()->json([
            'success' => true,
            'message' => 'Attendance marked successfully.',
            'applicant' => $application->applicant_name,
            'was_already_present' => !$attendance->wasRecentlyCreated
        ]);
    }
}
