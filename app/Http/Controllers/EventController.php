<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type // ✅ IMPORTANT
        ]);

        // Apply recurrence
        if ($request->type == 'daily') {
            $event->repeat()->daily();
        } elseif ($request->type == 'weekly') {
            $event->repeat()->weekly()->on(['monday']);
        } elseif ($request->type == '2days') {
            $event->repeat()->everyNDays(2);
        }

        return redirect('/');
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

        $event->update([
            'title' => $request->title,
            'description' => $request->description
        ]);

        return redirect('/');
    }

    // ===============================
    // DELETE EVENT
    // ===============================
    public function delete($id)
    {
        Event::findOrFail($id)->delete();
        return redirect('/');
    }

    // ===============================
    // FILTER: DAILY EVENTS ✅
    // ===============================
    // DAILY
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
        $events = Event::whereOccurresBetween(
            \Carbon\Carbon::today(),
            \Carbon\Carbon::today()->addDays(7)
        )->get();

        return view('events.result', [
            'message' => 'Next 7 Days Events',
            'data' => $events
        ]);
    }
}