<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadDetail extends Model
{
    public const STATUSES = [
        'New' => ['label' => 'New', 'class' => 'bg-primary'],
        'Contacted' => ['label' => 'Contacted', 'class' => 'bg-info'],
        'No Response' => ['label' => 'No Response', 'class' => 'bg-warning'],
        'Interested' => ['label' => 'Interested', 'class' => 'bg-success'],
        'Converted' => ['label' => 'Converted', 'class' => 'bg-purple'],
        'Lost' => ['label' => 'Lost', 'class' => 'bg-danger'],
    ];

    public const ACTIVITY_TYPES = [
        'Lead Created', 'Call', 'Meeting', 'WhatsApp', 'Email', 'Note', 'Conversion',
    ];

    protected $fillable = [
        'party_id', 'lead_status', 'activity_type', 'follow_up_date', 'follow_up_time',
        'assigned_to', 'assigned_to_name', 'remarks', 'completed_at', 'cancelled_at',
        'supersedes_id', 'created_by',
    ];

    protected $casts = [
        'follow_up_date' => 'date:Y-m-d',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function supersededDetail()
    {
        return $this->belongsTo(self::class, 'supersedes_id');
    }

    public function scopeOpen($query)
    {
        return $query->whereNotNull('follow_up_date')
            ->whereNull('completed_at')
            ->whereNull('cancelled_at');
    }

    public static function statusClass(string $status): string
    {
        return self::STATUSES[$status]['class'] ?? 'bg-secondary';
    }
}
