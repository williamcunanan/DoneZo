<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'name', 'description', 'category', 'start_date', 'end_date', 'completed'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
