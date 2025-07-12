<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'level',
        'subject',
        'location',
        'mode',
        'price',
        'tutor_id',
    ];

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function documents()
    {
        return $this->hasMany(CourseDocument::class);
    }
}

