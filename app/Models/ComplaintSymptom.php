<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintSymptom extends Model
{
    protected $guarded = ['id'];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_complaints')
                    ->withPivot('assessment_answer');
    }
}