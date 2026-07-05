<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ConsultationSlot;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'domain_id',
        'supervisor_id',
        'semester_id',
        'title',
        'abstract',
        'tools_technologies',
        'proposal_status',
        'supervisor_remarks',
        'is_published',
        'presentation_date',
        'presentation_venue',
        'presentation_link',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'presentation_date' => 'date',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function domain()
    {
        return $this->belongsTo(ProjectDomain::class, 'domain_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    public function documents()
    {
        return $this->hasMany(DocumentSubmission::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function consultationSlots()
    {
        return $this->hasMany(ConsultationSlot::class);
    }

    public function presentationStatus(): string
    {
        if (!$this->presentation_date) {
            return 'not_scheduled';
        }

        if (!$this->is_published) {
            return 'draft';
        }

        return $this->presentation_date->isPast() ? 'completed' : 'scheduled';
    }
}
