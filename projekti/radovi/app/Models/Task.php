<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\StudyType;
use Illuminate\Support\Facades\App;

class Task extends Model
{
    protected $fillable = [
        'user_id', 'name_hr', 'name_en', 'description_hr', 
        'description_en', 'study_type', 'assigned_student_id'
    ];

    protected $casts = [
        'study_type' => StudyType::class,
    ];

    /**
     * Virtual Attribute: name
     * Automatically returns English or Croatian based on current App locale.
     */
    public function getNameAttribute()
    {
        return App::getLocale() === 'hr' ? $this->name_hr : $this->name_en;
    }

    /**
     * Virtual Attribute: description
     */
    public function getDescriptionAttribute()
    {
        return App::getLocale() === 'hr' ? $this->description_hr : $this->description_en;
    }

    /**
     * Relationship: A task belongs to one Professor (User)
     */
    public function professor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: A task has many Applications (This was missing)
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}