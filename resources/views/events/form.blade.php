
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .form-container {
            max-width: 500px;
            width: 100%;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .card h2 {
            margin-bottom: 25px;
            color: #333;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }

        input, textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #666;
            text-decoration: none;
        }

        .back-link:hover {
            color: #007bff;
        }
    </style>
</head>
<body>
<div class="form-container">
    <div class="card">
        <h2>➕ Create New Event</h2>
        
        <form method="POST" action="/store">
            @csrf
            <div class="form-group">
                <label>Title *</label>
                <input type="text" name="title" placeholder="Enter event title" required>
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4" placeholder="Event description (optional)"></textarea>
            </div>
            
            <div class="form-group">
                <label>Recurrence Type</label>
                <select name="type" required>
                    <option value="today">📅 Today (One time)</option>
                    <option value="daily">🔄 Daily (Every day)</option>
                    <option value="weekly">📆 Weekly (Every week)</option>
                    <option value="2days">⏩ Every 2 Days</option>
                </select>
            </div>
            
            <button type="submit">Create Event</button>
        </form>
        
        <a href="/" class="back-link">← Back to Dashboard</a>
    </div>
</div>
</body>
</html>