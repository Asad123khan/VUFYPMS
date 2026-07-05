<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'leader_id',
        'semester_id',
        'supervisor_id',
        'status',
    ];

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function project()
    {
        return $this->hasOne(Project::class);
    }
}
