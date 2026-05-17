<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['user_id', 'branch_id', 'barber_id', 'appointment_date', 'appointment_time', 'client_name', 'client_email', 'client_phone', 'status', 'total_duration_minutes', 'reminder_sent'];
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;

    protected $casts =[
        'confirmation_expires_at' => 'datetime', // para ma-read as carbon date 
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function branch(){
        return $this->belongsTo(Branch::class);
    }

    public function barber(){
        return $this->belongsTo(Barber::class);
    }

    public function services(){
        return $this->belongsToMany(Service::class, 'appointment_service')->withPivot('dropdown_option');
    }

}
