@extends('layouts.app')

@section('content')
<div style="background: white; border-radius: 16px; padding: 1.5rem; border: 1px solid #e2e8f0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2><i class="fas fa-calendar-alt"></i> Event Calendar</h2>
        <a href="{{ route('events.index') }}" style="background: #e2e8f0; padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; color: #475569;">
            ← Back to List
        </a>
    </div>
    <div id="calendar"></div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: '{{ route("events.calendar.data") }}',
        editable: true,
        droppable: true,
        eventDrop: function(info) {
            var event = info.event;
            var start = event.start.toISOString();
            var end = event.end ? event.end.toISOString() : null;
            
            fetch('{{ route("events.update-date") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id: event.id,
                    start_date: start,
                    end_date: end
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert(data.message);
                    info.revert();
                }
            })
            .catch(() => {
                alert('⚠️ Error updating event date');
                info.revert();
            });
        },
        eventClick: function(info) {
            var event = info.event;
            if (confirm('Edit event: ' + event.title + '?')) {
                window.location.href = '/edit/' + event.id;
            }
        },
        eventDidMount: function(info) {
            // Add tooltip with description
            var tooltip = document.createElement('div');
            tooltip.className = 'fc-tooltip';
            tooltip.style.cssText = 'position:absolute;z-index:1000;background:white;padding:0.5rem;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.15);max-width:300px;display:none;';
            tooltip.innerHTML = `
                <strong>${info.event.title}</strong><br>
                <small style="color:#64748b;">${info.event.extendedProps.description || 'No description'}</small><br>
                <small style="color:#94a3b8;">Priority: ${info.event.extendedProps.priority} | Status: ${info.event.extendedProps.status}</small>
                ${info.event.extendedProps.recurring ? `<br><small style="color:#8b5cf6;">🔄 ${info.event.extendedProps.recurring}</small>` : ''}
            `;
            document.body.appendChild(tooltip);

            info.el.addEventListener('mouseenter', function(e) {
                tooltip.style.display = 'block';
                tooltip.style.left = (e.pageX + 10) + 'px';
                tooltip.style.top = (e.pageY + 10) + 'px';
            });

            info.el.addEventListener('mouseleave', function() {
                tooltip.style.display = 'none';
            });

            info.el.addEventListener('mousemove', function(e) {
                tooltip.style.left = (e.pageX + 10) + 'px';
                tooltip.style.top = (e.pageY + 10) + 'px';
            });
        }
    });
    calendar.render();
});
</script>

<style>
    .fc-tooltip {
        font-size: 0.8rem;
        line-height: 1.4;
    }
    .fc-tooltip strong {
        color: #0f172a;
    }
    .fc-event {
        cursor: pointer;
    }
    .fc-event:hover {
        opacity: 0.8;
    }
</style>
@endsection