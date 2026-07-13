@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div style="background: white; border-radius: 16px; padding: 2rem; border: 1px solid #e2e8f0;">
        <h2 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem; text-align: center;">➕ Create New Event</h2>

        @if($errors->any())
            <div style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem;">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('events.store') }}">
            @csrf

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.4rem; font-weight: 500; color: #334155;">Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Enter event title" required
                       style="width: 100%; padding: 0.7rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.4rem; font-weight: 500; color: #334155;">Description</label>
                <textarea name="description" rows="4" placeholder="Event description (optional)"
                          style="width: 100%; padding: 0.7rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem;">{{ old('description') }}</textarea>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.4rem; font-weight: 500; color: #334155;">Recurrence Type</label>
                <select name="type" required style="width: 100%; padding: 0.7rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem;">
                    <option value="today" {{ old('type')=='today' ? 'selected':'' }}>📅 Today (One time)</option>
                    <option value="daily" {{ old('type')=='daily' ? 'selected':'' }}>🔄 Daily (Every day)</option>
                    <option value="weekly" {{ old('type')=='weekly' ? 'selected':'' }}>📆 Weekly (Every week)</option>
                    <option value="2days" {{ old('type')=='2days' ? 'selected':'' }}>⏩ Every 2 Days</option>
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.4rem; font-weight: 500; color: #334155;">Priority Level</label>
                <select name="priority" required style="width: 100%; padding: 0.7rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem;">
                    <option value="High" {{ old('priority')=='High' ? 'selected':'' }}>🔥 High Priority</option>
                    <option value="Medium" {{ old('priority','Medium')=='Medium' ? 'selected':'' }}>⚡ Medium Priority</option>
                    <option value="Low" {{ old('priority')=='Low' ? 'selected':'' }}>✅ Low Priority</option>
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.4rem; font-weight: 500; color: #334155;">Status</label>
                <select name="status" style="width: 100%; padding: 0.7rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem;">
                    <option value="Pending" {{ old('status','Pending')=='Pending' ? 'selected':'' }}>⏳ Pending</option>
                    <option value="In Progress" {{ old('status')=='In Progress' ? 'selected':'' }}>🔄 In Progress</option>
                    <option value="Completed" {{ old('status')=='Completed' ? 'selected':'' }}>✅ Completed</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.4rem; font-weight: 500; color: #334155;">Start Date</label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date') }}"
                           style="width: 100%; padding: 0.7rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.4rem; font-weight: 500; color: #334155;">End Date</label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date') }}"
                           style="width: 100%; padding: 0.7rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem;">
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="is_all_day" value="1" {{ old('is_all_day') ? 'checked' : '' }}>
                    All Day Event
                </label>
            </div>

            <div style="border-top: 1px solid #e2e8f0; padding-top: 1rem; margin-top: 1rem;">
                <h4 style="margin-bottom: 1rem; color: #334155;">🔄 Advanced Recurring Options</h4>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.4rem; font-weight: 500; color: #334155;">Interval</label>
                        <input type="number" name="recurring_interval" value="{{ old('recurring_interval', 1) }}" min="1"
                               style="width: 100%; padding: 0.7rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.4rem; font-weight: 500; color: #334155;">Unit</label>
                        <select name="recurring_unit" style="width: 100%; padding: 0.7rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem;">
                            <option value="day" {{ old('recurring_unit')=='day' ? 'selected':'' }}>Day</option>
                            <option value="week" {{ old('recurring_unit')=='week' ? 'selected':'' }}>Week</option>
                            <option value="month" {{ old('recurring_unit')=='month' ? 'selected':'' }}>Month</option>
                            <option value="year" {{ old('recurring_unit')=='year' ? 'selected':'' }}>Year</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.4rem; font-weight: 500; color: #334155;">Week of Month</label>
                        <select name="week_of_month" style="width: 100%; padding: 0.7rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem;">
                            <option value="">-- None --</option>
                            <option value="first" {{ old('week_of_month')=='first' ? 'selected':'' }}>First</option>
                            <option value="second" {{ old('week_of_month')=='second' ? 'selected':'' }}>Second</option>
                            <option value="third" {{ old('week_of_month')=='third' ? 'selected':'' }}>Third</option>
                            <option value="fourth" {{ old('week_of_month')=='fourth' ? 'selected':'' }}>Fourth</option>
                            <option value="last" {{ old('week_of_month')=='last' ? 'selected':'' }}>Last</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.4rem; font-weight: 500; color: #334155;">Day of Week</label>
                        <select name="day_of_week" style="width: 100%; padding: 0.7rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem;">
                            <option value="">-- None --</option>
                            <option value="monday" {{ old('day_of_week')=='monday' ? 'selected':'' }}>Monday</option>
                            <option value="tuesday" {{ old('day_of_week')=='tuesday' ? 'selected':'' }}>Tuesday</option>
                            <option value="wednesday" {{ old('day_of_week')=='wednesday' ? 'selected':'' }}>Wednesday</option>
                            <option value="thursday" {{ old('day_of_week')=='thursday' ? 'selected':'' }}>Thursday</option>
                            <option value="friday" {{ old('day_of_week')=='friday' ? 'selected':'' }}>Friday</option>
                            <option value="saturday" {{ old('day_of_week')=='saturday' ? 'selected':'' }}>Saturday</option>
                            <option value="sunday" {{ old('day_of_week')=='sunday' ? 'selected':'' }}>Sunday</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" style="width: 100%; background: #3b82f6; color: white; border: none; padding: 0.75rem; border-radius: 10px; font-weight: 600; font-size: 0.95rem; cursor: pointer; margin-top: 1rem;">
                Create Event
            </button>
        </form>

        <a href="/" style="display: block; text-align: center; margin-top: 1.5rem; color: #64748b; text-decoration: none;">
            ← Back to Dashboard
        </a>
    </div>
</div>
@endsection