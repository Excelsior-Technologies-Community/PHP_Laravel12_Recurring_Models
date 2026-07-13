<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->get();
        return view('events.index', compact('events'));
    }

    public function calendar()
    {
        return view('events.calendar');
    }

    public function getCalendarEvents(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');
        
        $events = Event::query()
            ->when($start && $end, function($q) use ($start, $end) {
                return $q->dateRange($start, $end);
            })
            ->get()
            ->map(function($event) {
                return $event->full_calendar_event;
            });

        return response()->json($events);
    }

    public function createForm()
    {
        return view('events.form');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:today,daily,weekly,2days',
            'priority' => 'required|in:High,Medium,Low',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'recurring_interval' => 'nullable|integer|min:1',
            'recurring_unit' => 'nullable|in:day,week,month,year',
            'week_of_month' => 'nullable|in:first,second,third,fourth,last',
            'day_of_week' => 'nullable|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check for conflicts - ONLY if BOTH start_date AND end_date are provided and NOT empty
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        if (!empty($startDate) && !empty($endDate)) {
            $conflict = $this->checkConflict($startDate, $endDate);
            if ($conflict) {
                return redirect()->back()
                    ->with('error', '⚠️ Time Conflict! Another event exists during this time.')
                    ->withInput();
            }
        }

        Event::create($request->all());

        return redirect('/')->with('success', '✅ Event created successfully!');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:today,daily,weekly,2days',
            'priority' => 'required|in:High,Medium,Low',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'recurring_interval' => 'nullable|integer|min:1',
            'recurring_unit' => 'nullable|in:day,week,month,year',
            'week_of_month' => 'nullable|in:first,second,third,fourth,last',
            'day_of_week' => 'nullable|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check for conflicts - ONLY if BOTH start_date AND end_date are provided and NOT empty
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        if (!empty($startDate) && !empty($endDate)) {
            $currentStart = $event->start_date ? $event->start_date->format('Y-m-d H:i:s') : null;
            $currentEnd = $event->end_date ? $event->end_date->format('Y-m-d H:i:s') : null;
            
            // Only check conflict if dates have changed
            if ($startDate != $currentStart || $endDate != $currentEnd) {
                $conflict = $this->checkConflict($startDate, $endDate, $id);
                if ($conflict) {
                    return redirect()->back()
                        ->with('error', '⚠️ Time Conflict! Another event exists during this time.')
                        ->withInput();
                }
            }
        }

        $event->update($request->all());

        return redirect('/')->with('success', '✅ Event updated successfully!');
    }

    public function delete($id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->delete();
            return redirect('/')->with('success', '🗑️ Event deleted successfully!');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Event not found!');
        }
    }

    public function complete($id)
    {
        $event = Event::findOrFail($id);
        $event->status = 'Completed';
        $event->save();
        return redirect('/')->with('success', '✅ Event marked as completed!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed'
        ]);

        $event = Event::findOrFail($id);
        $event->status = $request->status;
        $event->save();

        return response()->json(['success' => true, 'status' => $event->status]);
    }

    public function daily()
    {
        $events = Event::where('type', 'daily')->get();
        return view('events.result', ['message' => 'Daily Events', 'data' => $events]);
    }

    public function weekly()
    {
        $events = Event::where('type', 'weekly')->get();
        return view('events.result', ['message' => 'Weekly Events', 'data' => $events]);
    }

    public function everyTwoDays()
    {
        $events = Event::where('type', '2days')->get();
        return view('events.result', ['message' => 'Every 2 Days Events', 'data' => $events]);
    }

    public function today()
    {
        $events = Event::where('type', 'today')
            ->whereDate('created_at', Carbon::today())
            ->get();
        return view('events.result', ['message' => 'Today Events', 'data' => $events]);
    }

    public function between()
    {
        $events = Event::whereIn('type', ['daily', 'weekly', '2days'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('events.result', ['message' => 'Upcoming Recurring Events', 'data' => $events]);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        if (empty($search)) {
            return redirect('/');
        }

        $events = Event::where('title', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('events.result', [
            'data' => $events,
            'message' => "Search results for: '{$search}'"
        ]);
    }

    public function dateRange(Request $request)
    {
        $query = Event::query();

        if ($request->filled('from')) {
            $query->where('created_at', '>=', Carbon::parse($request->from)->startOfDay());
        }

        if ($request->filled('to')) {
            $query->where('created_at', '<=', Carbon::parse($request->to)->endOfDay());
        }

        $events = $query->latest()->get();
        return view('events.result', ['data' => $events, 'message' => 'Filtered Events']);
    }

    public function exportCsv()
    {
        $events = Event::all();
        $filename = "events_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($events) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['ID', 'Title', 'Description', 'Type', 'Priority', 'Status', 'Created Date']);

            foreach ($events as $event) {
                fputcsv($file, [
                    $event->id,
                    $event->title,
                    $event->description,
                    ucfirst($event->type),
                    $event->priority ?? 'Low',
                    $event->status ?? 'Pending',
                    $event->created_at->format('Y-m-d H:i:s')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function duplicate($id)
    {
        try {
            $original = Event::findOrFail($id);
            Event::create([
                'title' => $original->title . ' (Copy)',
                'description' => $original->description,
                'type' => $original->type,
                'priority' => $original->priority,
                'status' => 'Pending',
                'start_date' => $original->start_date,
                'end_date' => $original->end_date
            ]);
            return redirect('/')->with('success', '📋 Event duplicated successfully!');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Could not duplicate');
        }
    }

    public function toggleImportant($id)
    {
        try {
            $event = Event::findOrFail($id);
            $isImportant = strpos($event->description, '⭐ IMPORTANT') !== false;

            if ($isImportant) {
                $event->description = str_replace(['⭐ IMPORTANT', ' ⭐ IMPORTANT'], '', $event->description);
                $message = 'Removed from important!';
            } else {
                $event->description = trim($event->description) . ' ⭐ IMPORTANT';
                $message = 'Marked as important!';
            }

            $event->save();
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Could not update event!');
        }
    }

    public function summary()
    {
        $events = Event::all();
        $summary = [
            'total' => $events->count(),
            'today' => $events->where('type', 'today')->count(),
            'daily' => $events->where('type', 'daily')->count(),
            'weekly' => $events->where('type', 'weekly')->count(),
            'two_days' => $events->where('type', '2days')->count(),
            'pending' => $events->where('status', 'Pending')->count(),
            'in_progress' => $events->where('status', 'In Progress')->count(),
            'completed' => $events->where('status', 'Completed')->count(),
            'recent' => $events->take(5),
        ];

        return view('events.summary', compact('summary'));
    }

    public function quickAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'type' => 'required|in:today,daily,weekly,2days'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'priority' => 'Medium',
            'status' => 'Pending'
        ]);

        return redirect('/')->with('success', '⚡ Quick event added');
    }

    protected function checkConflict($start, $end, $excludeId = null)
    {
        // If dates are null or empty, no conflict
        if (empty($start) || empty($end)) {
            return false;
        }

        // If end date is before start date, no conflict
        if ($end <= $start) {
            return false;
        }

        $query = Event::where(function($q) use ($start, $end) {
            $q->where(function($sub) use ($start, $end) {
                // Event starts during this period
                $sub->where('start_date', '>=', $start)
                    ->where('start_date', '<', $end);
            })->orWhere(function($sub) use ($start, $end) {
                // Event ends during this period
                $sub->where('end_date', '>', $start)
                    ->where('end_date', '<=', $end);
            })->orWhere(function($sub) use ($start, $end) {
                // Event completely covers this period
                $sub->where('start_date', '<=', $start)
                    ->where('end_date', '>=', $end);
            });
        });

        // Exclude current event when updating
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        // Only check events that have both start and end dates
        $query->whereNotNull('start_date')
              ->whereNotNull('end_date')
              ->where('start_date', '!=', '')
              ->where('end_date', '!=', '');

        return $query->exists();
    }

    public function updateEventDate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:events,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date'
        ]);

        $event = Event::findOrFail($request->id);
        
        // Check for conflicts
        $conflict = $this->checkConflict($request->start_date, $request->end_date, $request->id);
        if ($conflict) {
            return response()->json([
                'success' => false,
                'message' => '⚠️ Time Conflict! Another event exists during this time.'
            ], 409);
        }

        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->save();

        return response()->json([
            'success' => true,
            'message' => '✅ Event date updated successfully!'
        ]);
    }
}