<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseDocument extends Model
{
    protected $fillable = [
        'course_id',
        'file_path',
        'type', 
        'title',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}