<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    //
    use Notifiable, HasRoles;
    
    protected $table = 'admins'; // added this for admins reset password
    protected $fillable = ['name', 'email', 'password', 'barber_id'];
    protected $hidden = ['password', 'remember_token'];

    public function barber()
    {
        return $this->belongsTo(Barber::class);
    }

    public function isBarber(): bool
    {
        return !is_null($this->barber_id);
    }

    public function isOwner(): bool
    {
        return is_null($this->barber_id);
    }
    
    protected static function booted()
    {
        static::updated(function ($admin) {
            if ($admin->barber_id) {
                $admin->barber()->update([
                    'email' => $admin->email,
                ]);
            }
        });
    }
}
