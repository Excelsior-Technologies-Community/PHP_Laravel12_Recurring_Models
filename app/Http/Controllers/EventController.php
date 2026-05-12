<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    // ===============================
    // SHOW ALL EVENTS
    // ===============================
    public function index()
    {
        $events = Event::latest()->get();

        return view('events.result', [
            'message' => 'All Events',
            'data' => $events
        ]);
    }

    // ===============================
    // SHOW CREATE FORM
    // ===============================
    public function createForm()
    {
        return view('events.form');
    }

    // ===============================
    // STORE EVENT
    // ===============================
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:today,daily,weekly,2days'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type
        ]);

        return redirect('/')->with('success', 'Event created successfully!');
    }

    // ===============================
    // EDIT FORM
    // ===============================
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('events.edit', compact('event'));
    }

    // ===============================
    // UPDATE EVENT
    // ===============================
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:today,daily,weekly,2days'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type
        ]);

        return redirect('/')->with('success', 'Event updated successfully!');
    }

    // ===============================
    // DELETE EVENT
    // ===============================
    public function delete($id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->delete();
            return redirect('/')->with('success', 'Event deleted successfully!');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Event not found!');
        }
    }

    // ===============================
    // FILTER: DAILY EVENTS
    // ===============================
    public function daily()
    {
        $events = Event::where('type', 'daily')->get();

        return view('events.result', [
            'message' => 'Daily Events',
            'data' => $events
        ]);
    }

    // WEEKLY
    public function weekly()
    {
        $events = Event::where('type', 'weekly')->get();

        return view('events.result', [
            'message' => 'Weekly Events',
            'data' => $events
        ]);
    }

    // EVERY 2 DAYS
    public function everyTwoDays()
    {
        $events = Event::where('type', '2days')->get();

        return view('events.result', [
            'message' => 'Every 2 Days Events',
            'data' => $events
        ]);
    }

    // TODAY
    public function today()
    {
        $events = Event::where('type', 'today')
            ->whereDate('created_at', Carbon::today())
            ->get();

        return view('events.result', [
            'message' => 'Today Events',
            'data' => $events
        ]);
    }

    // NEXT 7 DAYS
    public function between()
    {
        $events = Event::whereIn('type', ['daily', 'weekly', '2days'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('events.result', [
            'message' => 'Upcoming Recurring Events',
            'data' => $events
        ]);
    }

    // ===============================
    // SEARCH EVENTS
    // ===============================
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
        
        $message = "Search results for: '{$search}'";
        return view('events.result', ['data' => $events, 'message' => $message]);
    }

    // ===============================
    // DATE RANGE FILTER
    // ===============================
    public function dateRange(Request $request)
    {
        $from = $request->input('from') ? Carbon::parse($request->from) : Carbon::today()->subDays(30);
        $to = $request->input('to') ? Carbon::parse($request->to) : Carbon::today();
        
        $events = Event::whereBetween('created_at', [$from, $to])
                       ->orderBy('created_at', 'desc')
                       ->get();
        
        $message = "Events from {$from->format('M d, Y')} to {$to->format('M d, Y')}";
        return view('events.result', ['data' => $events, 'message' => $message]);
    }

    // ===============================
    // EXPORT TO CSV
    // ===============================
    public function exportCsv()
    {
        $events = Event::all();
        
        $filename = "events_" . date('Y-m-d_H-i-s') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($events) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Add headers
            fputcsv($file, ['ID', 'Title', 'Description', 'Type', 'Created Date']);
            
            // Add data rows
            foreach ($events as $event) {
                fputcsv($file, [
                    $event->id,
                    $event->title,
                    $event->description,
                    $event->type,
                    $event->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    // ===============================
    // DUPLICATE EVENT
    // ===============================
    public function duplicate($id)
    {
        try {
            $originalEvent = Event::findOrFail($id);
            
            Event::create([
                'title' => $originalEvent->title . " (Copy)",
                'description' => $originalEvent->description,
                'type' => $originalEvent->type,
            ]);
            
            return redirect('/')->with('success', 'Event duplicated successfully!');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Could not duplicate event!');
        }
    }

    // ===============================
    // TOGGLE IMPORTANT (using description)
    // ===============================
    public function toggleImportant($id)
    {
        try {
            $event = Event::findOrFail($id);
            
            // Check if already important
            $isImportant = strpos($event->description, '⭐ IMPORTANT') !== false;
            
            if ($isImportant) {
                $event->description = str_replace(' ⭐ IMPORTANT', '', $event->description);
                $event->description = str_replace('⭐ IMPORTANT', '', $event->description);
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

    // ===============================
    // EVENTS SUMMARY
    // ===============================
    public function summary()
    {
        $events = Event::all();
        
        $summary = [
            'total' => $events->count(),
            'today' => $events->where('type', 'today')->count(),
            'daily' => $events->where('type', 'daily')->count(),
            'weekly' => $events->where('type', 'weekly')->count(),
            'two_days' => $events->where('type', '2days')->count(),
            'recent' => $events->take(5),
        ];
        
        return view('events.summary', compact('summary'));
    }

    // ===============================
    // QUICK ADD EVENT
    // ===============================
    public function quickAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'required|in:today,daily,weekly,2days'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $event = Event::create([
            'title' => $request->title,
            'description' => $request->input('description', ''),
            'type' => $request->type,
        ]);
        
        if ($request->ajax()) {
            return response()->json(['success' => true, 'event' => $event]);
        }
        
        return redirect('/')->with('success', 'Event added quickly!');
    }

    // ===============================
    // COMPLETE EVENT (for recurring)
    // ===============================
    public function complete($id)
    {
        try {
            $event = Event::findOrFail($id);
            
            // If it's a recurring event, create next occurrence
            if ($event->type != 'today') {
                Event::create([
                    'title' => $event->title,
                    'description' => $event->description,
                    'type' => $event->type,
                ]);
                $message = 'Event completed! Next occurrence created.';
            } else {
                $message = 'Event completed!';
            }
            
            $event->delete();
            
            return redirect('/')->with('success', $message);
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Could not complete event!');
        }
    }
}