<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['project_id', 'title', 'priority', 'status'];

    // Har task ek project ka hissa hai
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

