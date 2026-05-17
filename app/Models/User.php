<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'contact_number',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function hasVerifiedEmail()
    {
        // If no email is provided, treat as verified
        if (empty($this->email)) {
            return true;
        }
    
        return !is_null($this->email_verified_at);
    }

    public function hasVerifiedPhone()
    {
        return ! is_null($this->phone_verified_at);
    }

    public function markPhoneAsVerified()
    {
        return $this->forceFill([
            'phone_verified_at' => now(),
        ])->save();
    }

    public function isVerified()
    {
        return $this->hasVerifiedEmail() || $this->hasVerifiedPhone();
    }


    public function appointments(){
        return $this->hasMany(Appointment::class);
    }

    /**
     * Find a user by their contact number.
     */
    public function findForPassport($contact_number)
    {
        return $this->where('contact_number', $contact_number)->first();
    }

    
    public function barbersWithAppointments()
    {
        return Barber::whereIn('id', $this->appointments()->pluck('barber_id'))->get();
    }
}
