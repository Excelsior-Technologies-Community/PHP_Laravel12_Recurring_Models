<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Events Dashboard</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI';
            background: #0f172a;
            color: white;
            display: flex;
        }

        /* SIDEBAR */
        .sidebar {
            width: 230px;
            height: 100vh;
            background: #1f2937;
            padding: 20px;
            position: fixed;
        }

        .sidebar h2 {
            color: #38bdf8;
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: #cbd5e1;
            text-decoration: none;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 8px;
            font-size: 16px;
            transition: 0.3s;
        }

        .sidebar a i {
            margin-right: 8px;
        }

        .sidebar a:hover {
            background: #334155;
            color: #38bdf8;
        }

        /* ACTIVE LINK */
        .active {
            background: #38bdf8 !important;
            color: black !important;
        }

        /* MAIN CONTENT */
        .main {
            margin-left: 250px;
            padding: 40px;
            width: 100%;
        }

        h2 {
            color: #38bdf8;
            text-align: center;
            margin-bottom: 30px;
        }

        /* CARD */
        .card {
            background: #1e293b;
            padding: 20px;
            margin: 15px auto;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
        }

        .card p {
            font-size: 16px;
            margin: 6px 0;
        }

        .actions {
            margin-top: 10px;
        }

        .actions a {
            margin-right: 12px;
            text-decoration: none;
            font-size: 15px;
        }

        .edit {
            color: #22c55e;
        }

        .delete {
            color: #ef4444;
        }
    </style>

    <script>
        function confirmDelete(url) {
            if (confirm("Are you sure you want to delete this event?")) {
                window.location.href = url;
            }
        }
    </script>

</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2><i class="fas fa-calendar"></i> Events</h2>

        <a href="/" class="active"><i class="fas fa-home"></i> Dashboard</a>
        <a href="/create-form"><i class="fas fa-plus"></i> Create Event</a>
        <a href="/daily"><i class="fas fa-sync"></i> Daily</a>
        <a href="/weekly"><i class="fas fa-calendar-week"></i> Weekly</a>
        <a href="/every-2-days"><i class="fas fa-repeat"></i> 2 Days</a>
        <a href="/today"><i class="fas fa-calendar-day"></i> Today</a>
        <a href="/between"><i class="fas fa-calendar-alt"></i> Next 7 Days</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main">
        <h2>{{ $message }}</h2>

        @forelse($data as $event)
            <div class="card">
                <p><strong>Title:</strong> {{ $event->title }}</p>
                <p><strong>Description:</strong> {{ $event->description }}</p>
                <p><strong>Type:</strong> {{ $event->type }}</p>
                <p><strong>Date:</strong> {{ $event->created_at }}</p>

                <div class="actions">
                    <a href="/edit/{{ $event->id }}" class="edit">
                        <i class="fas fa-edit"></i> Edit
                    </a>

                    <a href="javascript:void(0)" onclick="confirmDelete('/delete/{{ $event->id }}')" class="delete">
                        <i class="fas fa-trash"></i> Delete
                    </a>
                </div>
            </div>
        @empty
            <p style="text-align:center;">No events found 🚫</p>
        @endforelse
    </div>

</body>

</html>