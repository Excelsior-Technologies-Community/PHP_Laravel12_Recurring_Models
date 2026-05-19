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
        $validator = Validator::make($request->all(), [

            'title' => 'required|string|max:255',

            'description' => 'nullable|string',

            'type' => 'required|in:today,daily,weekly,2days',

            'priority' => 'required|in:High,Medium,Low'

        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        Event::create([

            'title' => $request->title,

            'description' => $request->description,

            'type' => $request->type,

            'priority' => $request->priority,

            'status' => 'Pending'

        ]);

        return redirect('/')
            ->with(
                'success',
                'Event created successfully!'
            );
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

        $validator = Validator::make(
            $request->all(),
            [

                'title' => 'required|string|max:255',

                'description' => 'nullable|string',

                'type' => 'required|in:today,daily,weekly,2days',

                'priority' => 'required|in:High,Medium,Low'

            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $event->update([

            'title' => $request->title,

            'description' => $request->description,

            'type' => $request->type,

            'priority' => $request->priority

        ]);

        return redirect('/')
            ->with(
                'success',
                'Event updated successfully!'
            );
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
        $query = Event::query();

        if ($request->filled('from')) {

            $from = Carbon::parse($request->from)
                ->startOfDay();

            $query->where(
                'created_at',
                '>=',
                $from
            );
        }

        if ($request->filled('to')) {

            $to = Carbon::parse($request->to)
                ->endOfDay();

            $query->where(
                'created_at',
                '<=',
                $to
            );
        }

        $events = $query
            ->latest()
            ->get();

        $message = "Filtered Events";

        return view(
            'events.result',
            [
                'data' => $events,
                'message' => $message
            ]
        );
    }

    // ===============================
    // EXPORT TO CSV
    // ===============================
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

        $callback = function () use ($events) {

            $file = fopen('php://output', 'w');

            // UTF-8 for Excel
            fprintf(
                $file,
                chr(0xEF) . chr(0xBB) . chr(0xBF)
            );

            // CSV Header
            fputcsv($file, [

                'ID',
                'Title',
                'Description',
                'Type',
                'Priority',
                'Status',
                'Created Date'

            ]);

            // Data
            foreach ($events as $event) {

                fputcsv($file, [

                    $event->id,

                    $event->title,

                    $event->description,

                    ucfirst($event->type),

                    $event->priority ?? 'Low',

                    $event->status ?? 'Pending',

                    $event->created_at
                        ->format('Y-m-d H:i:s')

                ]);
            }

            fclose($file);
        };

        return response()->stream(
            $callback,
            200,
            $headers
        );
    }

    // ===============================
    // DUPLICATE EVENT
    // ===============================
    public function duplicate($id)
    {
        try {

            $originalEvent =
                Event::findOrFail($id);

            Event::create([

                'title' =>
                $originalEvent->title
                    . ' (Copy)',

                'description' =>
                $originalEvent->description,

                'type' =>
                $originalEvent->type,

                'priority' =>
                $originalEvent->priority,

                'status' => 'Pending'

            ]);

            return redirect('/')
                ->with(
                    'success',
                    'Event duplicated successfully!'
                );
        } catch (\Exception $e) {

            return redirect('/')
                ->with(
                    'error',
                    'Could not duplicate'
                );
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
        $validator = Validator::make(
            $request->all(),
            [

                'title' => 'required',

                'type' =>
                'required|in:today,daily,weekly,2days'

            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        Event::create([

            'title' => $request->title,

            'description' =>
            $request->description,

            'type' => $request->type,

            'priority' => 'Medium',

            'status' => 'Pending'

        ]);

        return redirect('/')
            ->with(
                'success',
                'Quick event added'
            );
    }

    // ===============================
    // COMPLETE EVENT (for recurring)
    // ===============================
    public function complete($id)
    {
        try {

            $event =
                Event::findOrFail($id);

            $event->status =
                'Completed';

            $event->save();

            return redirect('/')
                ->with(
                    'success',
                    'Event marked completed!'
                );
        } catch (\Exception $e) {

            return redirect('/')
                ->with(
                    'error',
                    'Could not complete event'
                );
        }
    }
}
