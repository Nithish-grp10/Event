<?php

namespace App\Modules\Core\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\Events\Models\Event;
use App\Modules\Applications\Models\Application;
use App\Modules\LegacyForms\Models\Form;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Admin / Super Admin Dashboard
        if ($user->hasRole('super-admin') || $user->hasRole('admin')) {
            $eventsQuery = Event::query();
            $appsQuery = Application::query();

            if ($user->hasRole('admin')) {
                $eventsQuery->where(function($q) use ($user) {
                    $q->where('created_by', $user->id)
                      ->orWhere('assigned_admin_id', $user->id);
                });
                $appsQuery->whereHas('event', function($q) use ($user) {
                    $q->where('created_by', $user->id)
                      ->orWhere('assigned_admin_id', $user->id);
                });
            }

            $stats = [
                'total_events' => (clone $eventsQuery)->count(),
                'published_events' => (clone $eventsQuery)->where('status', 'published')->count(),
                'total_applications' => (clone $appsQuery)->count(),
                'approved_applications' => (clone $appsQuery)->where('status', 'approved')->count(),
                'rejected_applications' => (clone $appsQuery)->where('status', 'rejected')->count(),
                'total_forms' => Form::count(),
            ];

            $chartData = $this->getChartData($appsQuery);
            $recentEvents = (clone $eventsQuery)->latest()->take(5)->get();

            return view('dashboard.admin', compact('stats', 'chartData', 'recentEvents'));
        }

        // Organizer Dashboard
        if ($user->hasRole('organizer')) {
            $eventsQuery = Event::where('created_by', $user->id);
            $appsQuery = Application::whereHas('event', function($q) use ($user) {
                $q->where('created_by', $user->id);
            });

            $stats = [
                'total_events' => (clone $eventsQuery)->count(),
                'total_applications' => (clone $appsQuery)->count(),
                'pending_applications' => (clone $appsQuery)->where('status', 'pending')->count(),
            ];

            $chartData = $this->getChartData($appsQuery);
            $upcomingEvents = (clone $eventsQuery)->where('start_date', '>=', now())->orderBy('start_date')->take(5)->get();

            return view('dashboard.organizer', compact('stats', 'chartData', 'upcomingEvents'));
        }

        // Staff Dashboard
        if ($user->hasRole('staff')) {
            $eventsQuery = Event::whereHas('staff', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
            $appsQuery = Application::whereHas('event.staff', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });

            $stats = [
                'assigned_events' => (clone $eventsQuery)->count(),
                'applications_to_review' => (clone $appsQuery)->where('status', 'pending')->count(),
            ];
            
            $assignedEvents = (clone $eventsQuery)->orderBy('start_date')->take(5)->get();

            return view('dashboard.staff', compact('stats', 'assignedEvents'));
        }

        // Volunteer Dashboard
        if ($user->hasRole('volunteer')) {
            $stats = [
                'hours_logged' => 0, // Placeholder for future feature
                'shifts_completed' => 0,
            ];
            return view('dashboard.volunteer', compact('stats'));
        }

        // Attendee / Public User Dashboard (Default fallback)
        $myApplications = Application::where('applicant_email', $user->email)->with('event')->latest()->get();
        $stats = [
            'applications_submitted' => $myApplications->count(),
            'approved_events' => $myApplications->where('status', 'approved')->count(),
        ];

        return view('dashboard.attendee', compact('stats', 'myApplications'));
    }

    private function getChartData($appsQuery)
    {
        $months = collect();
        $appCounts = collect();
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months->push($date->format('M'));
            $count = (clone $appsQuery)->whereYear('created_at', $date->year)
                                       ->whereMonth('created_at', $date->month)
                                       ->count();
            $appCounts->push($count);
        }

        return [
            'labels' => $months->toArray(),
            'data' => $appCounts->toArray(),
        ];
    }
}
