@extends('layouts.app')

@section('content')
<div class="header">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.8rem;">📅 My Events Dashboard</h1>
            <p style="color: #64748b;">Manage all your events easily</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('events.calendar') }}" style="background: #8b5cf6; padding: 0.5rem 1rem; border-radius: 10px; text-decoration: none; color: white;">
                <i class="fas fa-calendar-alt"></i> Calendar
            </a>
            <a href="{{ route('events.summary') }}" style="background: #f1f5f9; padding: 0.5rem 1rem; border-radius: 10px; text-decoration: none; color: #475569;">
                <i class="fas fa-chart-simple"></i> Summary
            </a>
            <a href="{{ route('events.export') }}" style="background: #f1f5f9; padding: 0.5rem 1rem; border-radius: 10px; text-decoration: none; color: #475569;">
                <i class="fas fa-download"></i> Export
            </a>
            <a href="{{ route('events.create') }}" style="background: #3b82f6; padding: 0.5rem 1rem; border-radius: 10px; text-decoration: none; color: white;">
                <i class="fas fa-plus"></i> New Event
            </a>
        </div>
    </div>
</div>

<!-- Quick Add Form -->
<div style="background: white; border-radius: 16px; padding: 1rem; margin-bottom: 2rem; border: 1px solid #e2e8f0;">
    <h3 style="margin-bottom: 1rem;"><i class="fas fa-bolt"></i> Quick Add Event</h3>
    <form method="POST" action="{{ route('events.quick-add') }}" style="display: flex; gap: 1rem; flex-wrap: wrap;">
        @csrf
        <input type="text" name="title" placeholder="Event title..." required style="flex: 2; padding: 0.5rem; border-radius: 8px; border: 1px solid #e2e8f0;">
        <select name="type" style="flex: 1; padding: 0.5rem; border-radius: 8px; border: 1px solid #e2e8f0;">
            <option value="today">Today</option>
            <option value="daily">Daily</option>
            <option value="weekly">Weekly</option>
            <option value="2days">Every 2 Days</option>
        </select>
        <button type="submit" style="background: #10b981; color: white; border: none; padding: 0.5rem 1rem; border-radius: 8px; cursor: pointer;">
            <i class="fas fa-plus"></i> Add
        </button>
    </form>
</div>

<!-- Search and Filter Bar -->
<div style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;">
    <form method="GET" action="{{ route('events.search') }}" style="flex: 2; display: flex; gap: 0.5rem;">
        <input type="text" name="search" placeholder="🔍 Search events by title or description..." 
               style="flex: 1; padding: 0.6rem; border-radius: 10px; border: 1px solid #e2e8f0;">
        <button type="submit" style="background: #3b82f6; color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 10px;">Search</button>
    </form>
    
    <form method="GET" action="{{ route('events.daterange') }}" style="flex: 2; display: flex; gap: 0.5rem;">
        <input type="date" name="from" style="flex: 1; padding: 0.6rem; border-radius: 10px; border: 1px solid #e2e8f0;">
        <input type="date" name="to" style="flex: 1; padding: 0.6rem; border-radius: 10px; border: 1px solid #e2e8f0;">
        <button type="submit" style="background: #8b5cf6; color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 10px;">Filter</button>
    </form>
</div>

<!-- Stats Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    <div style="background: white; border-radius: 12px; padding: 1rem; text-align: center; border: 1px solid #e2e8f0;">
        <div style="font-size: 2rem;">{{ $events->count() }}</div>
        <div style="color: #64748b;">Total Events</div>
    </div>
    <div style="background: white; border-radius: 12px; padding: 1rem; text-align: center; border: 1px solid #e2e8f0;">
        <div style="font-size: 2rem;">{{ $events->where('type', 'daily')->count() }}</div>
        <div style="color: #64748b;">Daily</div>
    </div>
    <div style="background: white; border-radius: 12px; padding: 1rem; text-align: center; border: 1px solid #e2e8f0;">
        <div style="font-size: 2rem;">{{ $events->where('type', 'weekly')->count() }}</div>
        <div style="color: #64748b;">Weekly</div>
    </div>
    <div style="background: white; border-radius: 12px; padding: 1rem; text-align: center; border: 1px solid #e2e8f0;">
        <div style="font-size: 2rem;">{{ $events->where('type', 'today')->count() }}</div>
        <div style="color: #64748b;">Today's</div>
    </div>
</div>

<!-- Kanban Board -->
<div style="margin-bottom: 2rem;">
    <h3 style="margin-bottom: 1rem;"><i class="fas fa-columns"></i> Kanban Board</h3>
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
        @foreach(['Pending', 'In Progress', 'Completed'] as $status)
        <div style="background: #f8fafc; border-radius: 12px; padding: 1rem; min-height: 300px;">
            <h4 style="text-align: center; padding: 0.5rem; border-radius: 8px; 
                {{ $status == 'Pending' ? 'background: #fef3c7; color: #92400e;' : 
                   ($status == 'In Progress' ? 'background: #dbeafe; color: #1e40af;' : 
                   'background: #d1fae5; color: #065f46;') }}">
                {{ $status }}
                <span style="background: rgba(0,0,0,0.1); padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem;">
                    {{ $events->where('status', $status)->count() }}
                </span>
            </h4>
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                @foreach($events->where('status', $status) as $event)
                <div class="event-card" style="background: white; border-radius: 8px; padding: 0.75rem; border-left: 4px solid {{ $event->priority_color }}; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <div>
                            <div style="font-weight: 600; font-size: 0.9rem;">{{ $event->title }}</div>
                            <div style="font-size: 0.7rem; color: #94a3b8;">{{ $event->type }}</div>
                        </div>
                        <span style="font-size: 0.6rem; padding: 0.2rem 0.5rem; border-radius: 12px; background: {{ $event->priority_color }}20; color: {{ $event->priority_color }};">
                            {{ $event->priority }}
                        </span>
                    </div>
                    <div style="margin-top: 0.5rem; display: flex; gap: 0.3rem; flex-wrap: wrap;">
                        <form action="{{ route('events.update-status', $event->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <select name="status" onchange="this.form.submit()" style="font-size: 0.6rem; padding: 0.2rem 0.4rem; border-radius: 4px; border: 1px solid #e2e8f0; background: white;">
                                <option value="Pending" {{ $event->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="In Progress" {{ $event->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Completed" {{ $event->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </form>
                        <a href="{{ route('events.edit', $event->id) }}" style="font-size: 0.6rem; padding: 0.2rem 0.5rem; background: #e0e7ff; border-radius: 4px; text-decoration: none; color: #1e40af;">✏️</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Events Grid -->
<h3 style="margin-bottom: 1rem;"><i class="fas fa-list"></i> All Events</h3>
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;">
    @forelse($events as $event)
    <div class="card" style="background: white; border-radius: 16px; padding: 1.25rem; border: 1px solid #e2e8f0; border-left: 4px solid {{ $event->priority_color }};">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div class="card-title" style="font-size: 1.2rem; font-weight: 600;">{{ $event->title }}</div>
            <div style="display: flex; gap: 0.3rem;">
                <a href="{{ route('events.toggle-important', $event->id) }}" style="text-decoration: none; color: {{ strpos($event->description, 'IMPORTANT') !== false ? '#f59e0b' : '#cbd5e1' }}">
                    <i class="fas fa-star"></i>
                </a>
                <a href="{{ route('events.duplicate', $event->id) }}" style="text-decoration: none; color: #8b5cf6;">
                    <i class="fas fa-copy"></i>
                </a>
            </div>
        </div>
        <div class="card-desc" style="color: #64748b; font-size: 0.9rem; margin: 0.75rem 0;">{{ $event->description ?: '📝 No description' }}</div>
        
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin: 0.5rem 0;">
            <span style="background: #e0e7ff; padding: 0.2rem 0.75rem; border-radius: 20px; font-size: 0.75rem;">
                <i class="fas fa-tag"></i> {{ ucfirst($event->type) }}
            </span>
            <span style="background: {{ $event->priority_color }}20; padding: 0.2rem 0.75rem; border-radius: 20px; font-size: 0.75rem; color: {{ $event->priority_color }};">
                {{ $event->priority }}
            </span>
            <span style="background: {{ $event->status == 'Completed' ? '#d1fae5' : '#fef3c7' }}; padding: 0.2rem 0.75rem; border-radius: 20px; font-size: 0.75rem;">
                {{ $event->status }}
            </span>
            @if($event->recurring_text != 'None')
            <span style="background: #f3e8ff; padding: 0.2rem 0.75rem; border-radius: 20px; font-size: 0.75rem;">
                🔄 {{ $event->recurring_text }}
            </span>
            @endif
        </div>
        
        <div class="actions" style="display: flex; gap: 0.5rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e2e8f0;">
            <a href="{{ route('events.edit', $event->id) }}" class="btn-edit" style="background: #eff6ff; padding: 0.4rem 1rem; border-radius: 8px; text-decoration: none; color: #3b82f6;">
                <i class="fas fa-edit"></i> Edit
            </a>
            @if($event->status != 'Completed')
            <a href="{{ route('events.complete', $event->id) }}" style="background: #d1fae5; padding: 0.4rem 1rem; border-radius: 8px; text-decoration: none; color: #065f46;">
                <i class="fas fa-check"></i> Complete
            </a>
            @endif
            <a href="javascript:void(0)" onclick="confirmDelete('{{ route('events.delete', $event->id) }}')" class="btn-delete" style="background: #fef2f2; padding: 0.4rem 1rem; border-radius: 8px; text-decoration: none; color: #dc2626;">
                <i class="fas fa-trash"></i> Delete
            </a>
        </div>
    </div>
    @empty
    <div class="empty-state" style="text-align: center; padding: 3rem; grid-column: 1/-1;">
        <i class="fas fa-calendar-alt" style="font-size: 3rem; color: #cbd5e1;"></i>
        <h3 style="margin: 1rem 0;">No events found</h3>
        <p style="color: #64748b;">Create your first event to get started</p>
        <a href="{{ route('events.create') }}" class="btn-primary" style="display: inline-block; background: #3b82f6; color: white; padding: 0.5rem 1.5rem; border-radius: 10px; text-decoration: none; margin-top: 1rem;">
            <i class="fas fa-plus"></i> Create Event
        </a>
    </div>
    @endforelse
</div>

<script>
function confirmDelete(url) {
    if(confirm('Are you sure you want to delete this event?')) {
        window.location.href = url;
    }
}
</script>
@endsection