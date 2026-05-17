<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HaircutStyle extends Model
{
    protected $fillable = ['name', 'image'];

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