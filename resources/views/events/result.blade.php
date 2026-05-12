
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
            margin: 0 auto;
        }

        /* Header */
        .header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header h1 {
            font-size: 24px;
            color: #333;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-green {
            background: #28a745;
        }

        .btn-gray {
            background: #6c757d;
        }

        /* Quick Add */
        .quick-add {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .quick-add h3 {
            margin-bottom: 15px;
            font-size: 18px;
            color: #333;
        }

        .form-inline {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .form-inline input, .form-inline select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-inline input {
            flex: 2;
            min-width: 200px;
        }

        /* Search */
        .search-bar {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .search-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .search-form input, .search-form select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .search-form input {
            flex: 2;
            min-width: 200px;
        }

        /* Stats */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }

        /* Alert */
        .alert {
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        /* Events Grid */
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .event-card {
            background: white;
            border-radius: 8px;
            padding: 18px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-left: 4px solid #007bff;
        }

        .event-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: start;
        }

        .event-desc {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 12px;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            margin-right: 8px;
        }

        .badge-today { background: #ffeaa7; color: #d63031; }
        .badge-daily { background: #dfe6e9; color: #0984e3; }
        .badge-weekly { background: #b2bec3; color: #2d3436; }
        .badge-2days { background: #fab1a0; color: #d63031; }

        .event-date {
            font-size: 12px;
            color: #999;
            margin-bottom: 12px;
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #eee;
        }

        .actions a {
            padding: 5px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
        }

        .edit-btn { background: #e3f2fd; color: #1976d2; }
        .complete-btn { background: #e8f5e9; color: #388e3c; }
        .delete-btn { background: #ffebee; color: #d32f2f; }
        .duplicate-btn { background: #f3e5f5; color: #7b1fa2; }
        .important-btn { background: #fff3e0; color: #f57c00; }

        .empty-state {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 8px;
        }

        .empty-state i {
            font-size: 48px;
            color: #ccc;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            body { padding: 10px; }
            .events-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Header -->
    <div class="header">
       
        <div style="display: flex; gap: 10px;">
            <a href="/summary" class="btn btn-gray">Summary</a>
            <a href="/export-csv" class="btn btn-gray"> Export</a>
            <a href="/create-form" class="btn"> New Event</a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            ✓ {{ session('success') }}
        </div>
    @endif

 
    <!-- Search -->
    <div class="search-bar">
        <div class="search-form">
            <form method="GET" action="/search" style="display: flex; gap: 10px; flex: 2;">
                <input type="text" name="search" placeholder="🔍 Search events...">
                <button type="submit" class="btn">Search</button>
            </form>
            <form method="GET" action="/date-range" style="display: flex; gap: 10px; flex: 2;">
                <input type="date" name="from" placeholder="From">
                <input type="date" name="to" placeholder="To">
                <button type="submit" class="btn">Filter</button>
            </form>
        </div>
    </div>

   

    <!-- Events List -->
    @if(isset($data) && $data->count() > 0)
    <div class="events-grid">
        @foreach($data as $event)
        <div class="event-card">
            <div class="event-title">
                <span>{{ $event->title }}</span>
                <div>
                    <a href="/duplicate/{{ $event->id }}" class="duplicate-btn" style="padding: 2px 8px; text-decoration: none; border-radius: 5px; font-size: 12px;">📋</a>
                    <a href="/toggle-important/{{ $event->id }}" class="important-btn" style="padding: 2px 8px; text-decoration: none; border-radius: 5px; font-size: 12px;">⭐</a>
                </div>
            </div>
            <div class="event-desc">{{ $event->description ?: 'No description' }}</div>
            <div>
                @php
                    $badgeClass = 'badge-today';
                    if($event->type == 'daily') $badgeClass = 'badge-daily';
                    if($event->type == 'weekly') $badgeClass = 'badge-weekly';
                    if($event->type == '2days') $badgeClass = 'badge-2days';
                @endphp
                <span class="badge {{ $badgeClass }}">{{ ucfirst($event->type) }}</span>
            </div>
            <div class="event-date">📅 {{ $event->created_at->format('M d, Y') }}</div>
            <div class="actions">
                <a href="/edit/{{ $event->id }}" class="edit-btn">✏️ Edit</a>
                @if($event->type != 'today')
                <a href="/complete/{{ $event->id }}" class="complete-btn" onclick="return confirm('Complete this event?')">✓ Complete</a>
                @endif
                <a href="/delete/{{ $event->id }}" class="delete-btn" onclick="return confirm('Delete this event?')">🗑️ Delete</a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <div>📭</div>
        <h3>No events found</h3>
        <p>Create your first event to get started</p>
        <a href="/create-form" class="btn" style="margin-top: 15px; display: inline-block;">+ Create Event</a>
    </div>
    @endif
</div>
</body>
</html>