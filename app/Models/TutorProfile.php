<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'country',
        'city',
        'district',
        'education_level',
        'main_degree',
        'institution',
        'experience',
        'subjects',
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function documents() {
        return $this->hasMany(TutorDocument::class);
    }
}
