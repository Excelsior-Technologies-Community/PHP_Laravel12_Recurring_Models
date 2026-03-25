# PHP_Laravel12_Recurring_Models


## Project Description

This project is a web-based event management system developed using Laravel 12 that allows users to create, manage, and view events with recurring functionality.

Users can create events such as daily tasks, weekly meetings, or custom recurring schedules like every 2 days. The system automatically manages and filters events based on their recurrence type.

It also provides filtering options such as:

- Today’s events
- Daily events
- Weekly events
- Events occurring in the next 7 days

This makes it useful for task tracking, scheduling, and planning systems.



## Features 

- Users can create, edit, and delete events easily with a title and description. 

- It supports different types of recurring events such as daily, weekly, every 2 days, and one-time (today) events. 

- The system also provides filtering options to view specific events like daily events, weekly events, today’s events, and events occurring in the next 7 days. 

- It includes a simple and user-friendly dashboard with a sidebar for easy navigation. 

- All event data is stored in a MySQL database, and the project follows the MVC architecture of Laravel for better code organization and maintainability.



## Technologies Used

- Laravel 12 (PHP Framework) – Backend
- MySQL – Database
- HTML, CSS, Blade – Frontend
- Composer – Dependency Manager
- Recurring Models Package – Recurring logic





---


## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Recurring_Models "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Recurring_Models

```

#### Explanation:

Installs a fresh Laravel 12 project using Composer.

Creates a new project folder and sets up all default Laravel files.




## STEP 2: Database Setup

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_Recurring_Models
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_Recurring_Models

```

### Then Run:

```
php artisan migrate

```


#### Explanation:

Connects your Laravel project to MySQL database using .env file.

php artisan migrate creates default tables (users, cache, jobs, etc.).




## STEP 3: Install Package 

### Run:

```
composer require mohammedmanssour/laravel-recurring-models

```

#### Explanation:

Installs Recurring Models package to handle repeating events automatically.

Adds new functionality like daily, weekly recurrence.





## STEP 4: Publish + Migrate

```
php artisan vendor:publish --tag="recurring-models-migrations"
php artisan migrate

```

#### Explanation:

Publishes package migration files into your project.

php artisan migrate creates required tables for recurring system.





## STEP 5: Create Models + Migrations 

### Run:

```
php artisan make:model Event -m

```


### Migration File: database/migrations/xxxx_create_events_table.php

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');   
            $table->text('description')->nullable(); 
            $table->string('type')->nullable();
            $table->timestamps();       //(created_at important)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

```


### Then Run:

```
php artisan migrate

```



### Model: app/Models/Event.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterface;
use MohammedManssour\LaravelRecurringModels\Contracts\Repeatable as RepeatableContract;
use MohammedManssour\LaravelRecurringModels\Concerns\Repeatable;
use MohammedManssour\LaravelRecurringModels\Enums\RepetitionType;

class Event extends Model implements RepeatableContract
{
    use Repeatable;

    protected $fillable = ['title', 'description', 'type'];

    public function repetitionBaseDate(?RepetitionType $type = null): CarbonInterface
    {
        return $this->created_at;
    }
}

```

#### Explanation:

Creates Event model and database table for storing events.

Model uses Repeatable trait to enable recurring functionality.




## STEP 6: Create Controller

### Run:

```
php artisan make:controller EventController

```

### Open: app/Http/Controllers/EventController.php

```
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

```

#### Explanation:

Handles all business logic (create, update, delete events).

Applies recurrence logic (daily, weekly, every 2 days, Today).




## STEP 7: Add Routes

### routes/web.php
 
```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/', [EventController::class, 'index']);

Route::get('/create-form', [EventController::class, 'createForm']);
Route::post('/store', [EventController::class, 'store']);

Route::get('/edit/{id}', [EventController::class, 'edit']);
Route::post('/update/{id}', [EventController::class, 'update']);

Route::get('/delete/{id}', [EventController::class, 'delete']);

Route::get('/daily', [EventController::class, 'daily']);
Route::get('/weekly', [EventController::class, 'weekly']);
Route::get('/every-2-days', [EventController::class, 'everyTwoDays']);
Route::get('/today', [EventController::class, 'today']);
Route::get('/between', [EventController::class, 'between']);

```

#### Explanation:

Defines URLs and connects them to controller methods.

Allows navigation like /daily, /weekly, /between.




## STEP 8: Create Blade View

### resources/views/events/result.blade.php

```
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

```


### resources/views/events/form.blade.php

```
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Event</title>

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
            background: #38bdf8;
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
        <a href="/create-form" class="active"><i class="fas fa-plus"></i> Create Event</a>
        <a href="/daily"><i class="fas fa-sync"></i> Daily</a>
        <a href="/weekly"><i class="fas fa-calendar-week"></i> Weekly</a>
        <a href="/every-2-days"><i class="fas fa-repeat"></i> 2 Days</a>
        <a href="/today"><i class="fas fa-calendar-day"></i> Today</a>
        <a href="/between"><i class="fas fa-calendar-alt"></i> Next 7 Days</a>
    </div>

    <!-- FORM -->
    <div class="main">

        <div class="card">
            <h2>Create Event</h2>

            <form method="POST" action="/store">
                @csrf

                <input type="text" name="title" placeholder="Title">

                <textarea name="description" placeholder="Description"></textarea>

                <select name="type">
                    <option value="">Select Type</option>
                    <option value="today">Today</option>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="2days">Every 2 Days</option>
                </select>

                <button>Create</button>
            </form>
        </div>

    </div>

</body>

</html>

```

### resources/views/events/edit.blade.php

```
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

```

#### Explanation:

Creates UI (frontend) using Blade templates.

Forms for create/edit and dashboard to display events.




## STEP 9: Run the App  

### Start dev server:

```
php artisan serve

```

### Open in browser:

```
http://127.0.0.1:8000

```

#### Explanation:

Starts Laravel development server.

Opens project in browser to test all features.




## Expected Output:


### Dashboard:


<img src="screenshots/Screenshot 2026-03-25 110611.png" width="900">


### Create Event Form:


<img src="screenshots/Screenshot 2026-03-25 110809.png" width="900">

<img src="screenshots/Screenshot 2026-03-25 110857.png" width="900">

<img src="screenshots/Screenshot 2026-03-25 110940.png" width="900">

<img src="screenshots/Screenshot 2026-03-25 111025.png" width="900">



### Daily Events List:


<img src="screenshots/Screenshot 2026-03-25 111048.png" width="900">


### Weekly Events List:


<img src="screenshots/Screenshot 2026-03-25 111055.png" width="900">


### 2 Days Events List:


<img src="screenshots/Screenshot 2026-03-25 111103.png" width="900">


### Today Events List: 


<img src="screenshots/Screenshot 2026-03-25 111110.png" width="900">


### Next 7 Days Events List:


<img src="screenshots/Screenshot 2026-03-25 111129.png" width="900">



### Edit Event:


<img src="screenshots/Screenshot 2026-03-25 111727.png" width="900">


### Updated Dashboard:


<img src="screenshots/Screenshot 2026-03-25 111738.png" width="900">


### Delete Event:


<img src="screenshots/Screenshot 2026-03-25 111752.png" width="900">



---

## Project Folder Structure:

```
PHP_Laravel12_Recurring_Models/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── EventController.php         (Main Logic)
│   │
│   └── Models/
│       └── Event.php                       (Recurring Model)
│
├── bootstrap/
│
├── config/
│
├── database/
│   ├── migrations/
│   │   ├── xxxx_create_events_table.php    (Event Table)
│   │   └── xxxx_recurring_models_tables    (Package Tables)
│   │
│   └── seeders/
│
├── public/
│
├── resources/
│   └── views/
│       └── events/
│           ├── form.blade.php              (Create Event)
│           ├── edit.blade.php              (Edit Event)
│           └── result.blade.php            (Dashboard / List)
│
├── routes/
│   └── web.php                             (All Routes)
│
├── storage/
│
├── tests/
│
├── vendor/                                 (Auto generated)
│
├── .env                                    (Database Config)
├── artisan                                 (CLI Tool)
├── composer.json                           (Packages List)
└── package.json

```
