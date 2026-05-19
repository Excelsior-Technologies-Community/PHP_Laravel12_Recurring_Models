<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Manager</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        /* HEADER */

        .header {
            background: white;
            padding: 20px;
            border-radius: 14px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 18px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            transition: .3s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-gray {
            background: #6c757d;
        }


        /* SEARCH SECTION */

        .search-bar {
            background: white;
            padding: 25px;
            border-radius: 16px;
            margin-bottom: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, .08);
        }

        .search-title {
            margin-bottom: 18px;
            color: #333;
            font-size: 18px;
        }

        .search-wrapper {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .modern-form {
            flex: 1;
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .input-group {
            flex: 1;
            min-width: 180px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 12px;
            background: #fafafa;
        }

        .input-group span {
            font-size: 18px;
        }

        .input-group input {
            border: none;
            outline: none;
            background: transparent;
            width: 100%;
            font-size: 14px;
        }

        .input-group:focus-within {
            border-color: #007bff;
            box-shadow: 0 0 0 4px rgba(0, 123, 255, .1);
        }

        /* ALERT */

        .alert-success {
            background: #d4edda;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        /* GRID */

        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .event-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .08);
            border-left: 5px solid #007bff;
            transition: .3s;
        }

        .event-card:hover {
            transform: translateY(-5px);
        }

        .event-title {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: 600;
        }

        .event-desc {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .badge-today {
            background: #ffeaa7;
        }

        .badge-daily {
            background: #dfe6e9;
        }

        .badge-weekly {
            background: #b2bec3;
        }

        .badge-2days {
            background: #fab1a0;
        }

        .event-date {
            margin-top: 10px;
            font-size: 13px;
            color: #777;
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .actions a {
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
        }

        .edit-btn {
            background: #e3f2fd;
            color: #1565c0;
        }

        .delete-btn {
            background: #ffebee;
            color: #d32f2f;
        }

        .complete-btn {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .duplicate-btn {
            background: #f3e5f5;
            padding: 5px 8px;
            border-radius: 8px;
            text-decoration: none;
        }

        .important-btn {
            background: #fff3e0;
            padding: 5px 8px;
            border-radius: 8px;
            text-decoration: none;
        }

        /* PRIORITY */

        .high {
            background: #ef4444;
            padding: 6px 10px;
            border-radius: 6px;
            color: white;
        }

        .medium {
            background: #f59e0b;
            padding: 6px 10px;
            border-radius: 6px;
            color: white;
        }

        .low {
            background: #22c55e;
            padding: 6px 10px;
            border-radius: 6px;
            color: white;
        }

        /* STATUS */

        .completed {
            color: #22c55e;
            font-weight: bold;
        }

        .pending {
            color: #ef4444;
            font-weight: bold;
        }

        /* EMPTY */

        .empty-state {
            text-align: center;
            background: white;
            padding: 60px;
            border-radius: 16px;
        }

        @media(max-width:768px) {

            body {
                padding: 10px;
            }

            .modern-form {
                flex-direction: column;
                align-items: stretch;
            }

            .events-grid {
                grid-template-columns: 1fr;
            }

        }
    </style>

</head>

<body>

    <div class="container">

        <div class="header">

            <h2>📅 Event Dashboard</h2>

            <div style="display:flex;gap:10px;flex-wrap:wrap">

                <a href="/summary" class="btn btn-gray">
                    📊 Summary
                </a>

                <a href="/export-csv" class="btn btn-gray">
                    📁 Export
                </a>

                <a href="/create-form" class="btn">
                    ➕ New Event
                </a>

            </div>

        </div>


        @if(session('success'))
        <div class="alert-success">
            ✓ {{session('success')}}
        </div>
        @endif


        <!-- SEARCH -->

        <div class="search-bar">

            <h3 class="search-title">

                🔎 Search & Filter Events

            </h3>

            <div class="search-wrapper">

                <form method="GET"
                    action="/search"
                    class="modern-form">

                    <div class="input-group">

                        <span>🔍</span>

                        <input
                            type="text"
                            name="search"
                            placeholder="Search title or description...">

                    </div>

                    <button type="submit"
                        class="btn">

                        Search

                    </button>

                </form>


                <form method="GET"
                    action="/date-range"
                    class="modern-form">

                    <div class="input-group">

                        <span>📅</span>

                        <input
                            type="date"
                            name="from">

                    </div>

                    <div class="input-group">

                        <span>➡</span>

                        <input
                            type="date"
                            name="to">

                    </div>

                    <button
                        type="submit"
                        class="btn btn-gray">

                        Filter

                    </button>

                </form>

            </div>

        </div>


        @if(isset($data) && $data->count()>0)

        <div class="events-grid">

            @foreach($data as $event)

            <div class="event-card">

                <div class="event-title">

                    <span>
                        {{ $event->title }}
                    </span>

                    <div>

                        <a href="/duplicate/{{$event->id}}" class="duplicate-btn">
                            📋
                        </a>

                        <a href="/toggle-important/{{$event->id}}" class="important-btn">
                            ⭐
                        </a>

                    </div>

                </div>

                <div class="event-desc">
                    {{ $event->description ?: 'No description'}}
                </div>

                @php

                $badgeClass='badge-today';

                if($event->type=='daily')
                $badgeClass='badge-daily';

                if($event->type=='weekly')
                $badgeClass='badge-weekly';

                if($event->type=='2days')
                $badgeClass='badge-2days';

                @endphp

                <span class="badge {{$badgeClass}}">
                    {{ ucfirst($event->type) }}
                </span>

                <br><br>

                <p>

                    <strong>Priority:</strong>

                    @if($event->priority=='High')

                    <span class="high">
                        🔥 High
                    </span>

                    @elseif($event->priority=='Medium')

                    <span class="medium">
                        ⚡ Medium
                    </span>

                    @else

                    <span class="low">
                        ✅ Low
                    </span>

                    @endif

                </p>

                <br>

                <p>

                    <strong>Status:</strong>

                    @if($event->status=='Completed')

                    <span class="completed">
                        Completed
                    </span>

                    @else

                    <span class="pending">
                        Pending
                    </span>

                    @endif

                </p>

                <div class="event-date">
                    📅 {{ $event->created_at->format('M d,Y') }}
                </div>

                <div class="actions">

                    <a href="/edit/{{$event->id}}" class="edit-btn">
                        ✏ Edit
                    </a>

                    @if($event->status=='Pending')

                    <a href="/complete/{{$event->id}}"
                        class="complete-btn"
                        onclick="return confirm('Complete event?')">

                        ✔ Complete

                    </a>

                    @endif

                    <a href="/delete/{{$event->id}}"
                        class="delete-btn"
                        onclick="return confirm('Delete this event?')">

                        🗑 Delete

                    </a>

                </div>

            </div>

            @endforeach

        </div>

        @else

        <div class="empty-state">

            <h2>📭 No events found</h2>

            <p style="margin:15px 0;">
                Create your first event
            </p>

            <a href="/create-form" class="btn">

                ➕ Create Event

            </a>

        </div>

        @endif

    </div>

</body>

</html>