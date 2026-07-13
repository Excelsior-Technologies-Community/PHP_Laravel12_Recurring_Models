<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'priority',
        'status',
        'start_date',
        'end_date',
        'is_all_day',
        'recurring_interval',
        'recurring_unit',
        'week_of_month',
        'day_of_week'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_all_day' => 'boolean'
    ];

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'Pending' => '#f59e0b',
            'In Progress' => '#3b82f6',
            'Completed' => '#22c55e',
            default => '#6b7280'
        };
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'High' => '#ef4444',
            'Medium' => '#f59e0b',
            'Low' => '#22c55e',
            default => '#6b7280'
        };
    }

    public function getRecurringTextAttribute()
    {
        if (!$this->recurring_interval) return 'None';
        
        $unit = match($this->recurring_unit) {
            'day' => 'day(s)',
            'week' => 'week(s)',
            'month' => 'month(s)',
            'year' => 'year(s)',
            default => ''
        };
        
        $text = "Every {$this->recurring_interval} {$unit}";
        
        if ($this->week_of_month && $this->day_of_week) {
            $text .= " on {$this->week_of_month} {$this->day_of_week}";
        }
        
        return $text;
    }

    public function getFullCalendarEventAttribute()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'start' => $this->start_date ? $this->start_date->toISOString() : null,
            'end' => $this->end_date ? $this->end_date->toISOString() : null,
            'allDay' => $this->is_all_day,
            'color' => $this->priority_color,
            'textColor' => '#ffffff',
            'extendedProps' => [
                'description' => $this->description,
                'type' => $this->type,
                'priority' => $this->priority,
                'status' => $this->status,
                'recurring' => $this->recurring_text
            ]
        ];
    }

    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'In Progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'High');
    }

    public function scopeDateRange($query, $start, $end)
    {
        return $query->where(function($q) use ($start, $end) {
            $q->whereBetween('start_date', [$start, $end])
              ->orWhereBetween('end_date', [$start, $end])
              ->orWhere(function($sub) use ($start, $end) {
                  $sub->where('start_date', '<=', $start)
                      ->where('end_date', '>=', $end);
              });
        });
    }
}