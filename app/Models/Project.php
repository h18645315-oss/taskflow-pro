<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'status', 'deadline'];

    // Ek project mein kai tasks ho sakte hain (One-to-Many Relationship)
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Ye project kis user ne banaya hai
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}



