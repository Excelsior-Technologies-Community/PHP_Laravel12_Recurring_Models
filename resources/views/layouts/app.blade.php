
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recurring Events</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #0f172a;
        }

        /* Layout */
        .app {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: white;
            border-right: 1px solid #e2e8f0;
            padding: 2rem 1.5rem;
            position: fixed;
            height: 100vh;
        }

        .sidebar h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #3b82f6;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            margin: 4px 0;
            border-radius: 10px;
            color: #475569;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .sidebar a i {
            width: 20px;
            font-size: 1rem;
        }

        .sidebar a:hover {
            background: #f1f5f9;
            color: #3b82f6;
        }

        .sidebar a.active {
            background: #3b82f6;
            color: white;
        }

        /* Main Content */
        .main {
            flex: 1;
            margin-left: 260px;
            padding: 2rem;
        }

        /* Header */
        .header {
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            color: #0f172a;
        }

        .header p {
            color: #64748b;
            margin-top: 0.25rem;
        }

        /* Cards Grid */
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 1.25rem;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transform: translateY(-2px);
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #0f172a;
        }

        .card-desc {
            color: #64748b;
            font-size: 0.9rem;
            line-height: 1.5;
            margin: 0.75rem 0;
        }

        .badge {
            display: inline-block;
            padding: 0.2rem 0.75rem;
            background: #f1f5f9;
            color: #475569;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .btn-edit, .btn-delete {
            padding: 0.4rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        .btn-edit {
            color: #16a34a;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
        }

        .btn-edit:hover {
            background: #16a34a;
            color: white;
        }

        .btn-delete {
            color: #dc2626;
            background: #fef2f2;
            border: 1px solid #fecaca;
        }

        .btn-delete:hover {
            background: #dc2626;
            color: white;
        }

        /* Form */
        .form-container {
            max-width: 500px;
            margin: 0 auto;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid #e2e8f0;
        }

        .form-card h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
            color: #0f172a;
        }

        .input-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.4rem;
            font-weight: 500;
            font-size: 0.9rem;
            color: #334155;
        }

        input, textarea, select {
            width: 100%;
            padding: 0.7rem 0.9rem;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: inherit;
            background: white;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            width: 100%;
            cursor: pointer;
            margin-top: 0.5rem;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        /* Alert */
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
        }

        .empty-state i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #94a3b8;
            margin-bottom: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                padding: 1.5rem 0.5rem;
            }
            .sidebar h2 span, .sidebar a span {
                display: none;
            }
            .sidebar h2 i {
                margin: 0 auto;
            }
            .sidebar a i {
                margin: 0 auto;
            }
            .main {
                margin-left: 70px;
            }
            .events-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="app">
    <div class="sidebar">
        <h2><i class="fas fa-calendar"></i> <span>Events</span></h2>
        <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
            <i class="fas fa-home"></i> <span>Dashboard</span>
        </a>
        <a href="{{ url('/create-form') }}" class="{{ request()->is('create-form') ? 'active' : '' }}">
            <i class="fas fa-plus"></i> <span>Create</span>
        </a>
        <a href="{{ url('/daily') }}" class="{{ request()->is('daily') ? 'active' : '' }}">
            <i class="fas fa-sync"></i> <span>Daily</span>
        </a>
        <a href="{{ url('/weekly') }}" class="{{ request()->is('weekly') ? 'active' : '' }}">
            <i class="fas fa-calendar-week"></i> <span>Weekly</span>
        </a>
        <a href="{{ url('/every-2-days') }}" class="{{ request()->is('every-2-days') ? 'active' : '' }}">
            <i class="fas fa-clock"></i> <span>2 Days</span>
        </a>
        <a href="{{ url('/today') }}" class="{{ request()->is('today') ? 'active' : '' }}">
            <i class="fas fa-calendar-day"></i> <span>Today</span>
        </a>
        <a href="{{ url('/between') }}" class="{{ request()->is('between') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> <span>Next 7D</span>
        </a>
    </div>

    <main class="main">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif
        
        @yield('content')
    </main>
</div>
</body>
</html>