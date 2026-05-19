
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Summary</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
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
            padding: 8px 16px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
        }

        .recent-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
        }

        .recent-section h3 {
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f9fa;
            font-weight: 600;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            background: #e0e7ff;
        }

        @media (max-width: 768px) {
            table { font-size: 14px; }
            th, td { padding: 8px; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <h1>📊 Events Summary</h1>
            <small style="color: #666;">Quick statistics overview</small>
        </div>
        <a href="/" class="btn">← Back</a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $summary['total'] }}</div>
            <div>Total Events</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $summary['today'] }}</div>
            <div>Today</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $summary['daily'] }}</div>
            <div>Daily</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $summary['weekly'] }}</div>
            <div>Weekly</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $summary['two_days'] }}</div>
            <div>Every 2 Days</div>
        </div>
    </div>

    <div class="recent-section">
        <h3>📋 Recent Events</h3>
        <table>
            <thead>
                <tr><th>Title</th><th>Type</th><th>Created</th><th>Action</th></tr>
            </thead>
            <tbody>
                @foreach($summary['recent'] as $event)
                <tr>
                    <td>{{ $event->title }}</td>
                    <td><span class="badge">{{ ucfirst($event->type) }}</span></td>
                    <td>{{ $event->created_at->format('M d, Y') }}</td>
                    <td><a href="/edit/{{ $event->id }}" style="color: #007bff;">Edit</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>