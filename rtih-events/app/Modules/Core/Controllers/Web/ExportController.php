<?php

namespace App\Modules\Core\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Modules\Events\Models\Event;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function exportApplications(Request $request)
    {
        abort_unless(auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'), 403);

        $query = \App\Modules\Applications\Models\Application::with('event')->latest();

        if (auth()->user()->hasRole('admin')) {
            $query->whereHas('event', function($q) {
                $q->where('created_by', auth()->id())
                  ->orWhere('assigned_admin_id', auth()->id());
            });
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $applications = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="applications_export_' . now()->format('Ymd_His') . '.csv"',
        ];

        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');
            
            // Header Row
            fputcsv($file, ['ID', 'Event Title', 'Applicant Name', 'Applicant Email', 'Status', 'Submitted At']);

            foreach ($applications as $app) {
                fputcsv($file, [
                    $app->id,
                    $app->event->title ?? 'Unknown',
                    $app->applicant_name,
                    $app->applicant_email,
                    ucfirst($app->status),
                    $app->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
