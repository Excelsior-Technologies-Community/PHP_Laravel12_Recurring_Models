<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI';
            background: #0f172a;
            color: white;
            display: flex;
        }

        /* SAME SIDEBAR */
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

        .active {
            background: #38bdf8 !important;
            color: black !important;
        }

        /* MAIN */
        .main {
            margin-left: 250px;
            padding: 40px;
            width: 100%;
        }

        /* CARD FORM */
        .card {
            max-width: 500px;
            margin: auto;
            background: #1e293b;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
        }

        .card h2 {
            text-align: center;
            color: #38bdf8;
            margin-bottom: 20px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: none;
            border-radius: 6px;
            background: #0f172a;
            color: white;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #22c55e;
            border: none;
            border-radius: 6px;
            font-size: 16px;
        }
    </style>

</head>

<body>

    <!-- SAME SIDEBAR -->
    <div class="sidebar">
        <h2><i class="fas fa-calendar"></i> Events</h2>

        <a href="/"><i class="fas fa-home"></i> Dashboard</a>
        <a href="/create-form"><i class="fas fa-plus"></i> Create Event</a>
        <a href="/daily"><i class="fas fa-sync"></i> Daily</a>
        <a href="/weekly"><i class="fas fa-calendar-week"></i> Weekly</a>
        <a href="/every-2-days"><i class="fas fa-repeat"></i> 2 Days</a>
        <a href="/today"><i class="fas fa-calendar-day"></i> Today</a>
        <a href="/between"><i class="fas fa-calendar-alt"></i> Next 7 Days</a>
    </div>

    <!-- FORM -->
    <div class="main">

        <div class="card">
            <h2>Edit Event</h2>

            <form method="POST" action="/update/{{ $event->id }}">
                @csrf

                <input type="text" name="title" value="{{ $event->title }}">

                <textarea name="description">{{ $event->description }}</textarea>

                <select name="type">
                    <option value="">Select Type</option>
                    <option value="today" {{ $event->type == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="daily" {{ $event->type == 'daily' ? 'selected' : '' }}>Daily</option>
                    <option value="weekly" {{ $event->type == 'weekly' ? 'selected' : '' }}>Weekly</option>
                    <option value="2days" {{ $event->type == '2days' ? 'selected' : '' }}>Every 2 Days</option>
                </select>

                <button>Update</button>
            </form>
        </div>

    </div>

</body>

</html>