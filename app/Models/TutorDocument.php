<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorDocument extends Model
{
    protected $fillable = [
        'user_id',
        'parent_id',
        'student_name',
        'student_school',
        'level',
        'age',
        'city',
        'quarter',
        'type',
        'comment',
        'subjects',
    ];
}
