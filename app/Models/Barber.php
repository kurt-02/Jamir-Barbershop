<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;

class Barber extends Model
{
    protected $fillable = ['name','branch_id','photo', 'email', 'days_off', 'specialties']; // Added photo field];
    /** @use HasFactory<\Database\Factories\BarberFactory> */

    protected $casts = [
        'days_off' => 'array', 
        'specialties' => 'array',
    ];
    use HasFactory;

    public function branch(){
        return $this->belongsTo(Branch::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }
    
    public function appointments(){
        return $this->hasMany(Appointment::class);
    }

    protected static function booted()
    {
        static::updated(function ($barber) {
            Admin::where('barber_id', $barber->id)->update([
                'email' => $barber->email,
            ]);
        });
    }

    public function getPhotoUrlAttribute()
    {
        if (!$this->photo) {
            return asset('images/photos/default-barber.png');
        }

        if (str_starts_with($this->photo, 'images/')) {
            return asset($this->photo);
        }

        // ✅ simplest and most reliable
        return asset('storage/' . $this->photo);
    }
}
