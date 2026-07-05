<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'supervisor_id',
        'project_id',
        'starts_at',
        'ends_at',
        'venue_or_link',
        'agenda',
        'remarks',
        'status',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function isPast(): bool
    {
        return $this->ends_at?->isPast() ?? false;
    }

    public function isUpcoming(): bool
    {
        return $this->starts_at?->isFuture() ?? false;
    }
}