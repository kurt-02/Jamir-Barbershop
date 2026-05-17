<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'barber_id', 'rating', 'is_anonymous', 'quick_feedback', 'comment', 'approved'];

    protected $casts = ['quick_feedback' => 'array', 'is_anonymous' => 'boolean', 'approved' => 'boolean'];

    public function barber(){
        return $this->belongsTo(Barber::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
