<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory;

    protected $casts = [
        'dropdown_options' => 'array',
    ];

    protected $fillable = [
        'name',
        'price',
        'dropdown_options',
    ];

    public function appointments(){
        return $this->belongsToMany(Appointment::class, 'appointment_service');
    }
}
