<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = ['name', 'location', 'capacity', 'description', 'status'];

    public function bookings(): HasMany
    {
        return $this->hasMany(RoomBooking::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function markBooked(): void
    {
        $this->update(['status' => 'booked']);
    }

    public function markAvailable(): void
    {
        $this->update(['status' => 'available']);
    }

    public function syncBookingStatus(): void
    {
        $hasActiveBooking = $this->bookings()
            ->whereIn('status', ['pending', 'approved'])
            ->where('end_at', '>', now())
            ->exists();

        if ($hasActiveBooking && $this->status === 'available') {
            $this->markBooked();
        } elseif (!$hasActiveBooking && $this->status === 'booked') {
            $this->markAvailable();
        }
    }
}
