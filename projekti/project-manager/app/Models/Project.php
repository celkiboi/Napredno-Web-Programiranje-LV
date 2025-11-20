<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'completed_work',
        'start_date',
        'end_date',
        'user_id',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function teamMembers()
    {
        return $this->belongsToMany(User::class);
    }
}

