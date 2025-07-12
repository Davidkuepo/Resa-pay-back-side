<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
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
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function parent() {
        return $this->belongsTo(User::class, 'parent_id');
    }
}
