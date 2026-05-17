<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = 'branches';
    /** @use HasFactory<\Database\Factories\BranchFactory> */
    use HasFactory;

    public function barbers(){
        return $this->hasMany(Barber::class);
    }

    public function appointments(){
        return $this->hasMany(Appointment::class);
    }
}
