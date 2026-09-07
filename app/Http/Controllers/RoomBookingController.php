<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomBookingController extends Controller
{
    public function index()
    {
        $userId = $this->memberUserId();
        abort_unless($userId, 403);

        $bookings = RoomBooking::with('room')->where('user_id', $userId)->latest('start_at')->paginate(15);
        return view('room-bookings.index', compact('bookings'));
    }

    public function adminIndex()
    {
        Room::all()->each->syncBookingStatus();
        $bookings = RoomBooking::with(['room', 'user'])->latest('start_at')->paginate(20);
        return view('room-bookings.admin-index', compact('bookings'));
    }

    public function store(Request $request, Room $room)
    {
        $userId = $this->memberUserId();
        abort_unless($userId, 403);

        $data = $request->validate([
            'start_at' => 'required|date|after_or_equal:now',
            'end_at' => 'required|date|after:start_at',
            'purpose' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if (!$room->isAvailable()) {
            return back()->withInput()->with('error', 'This room is currently unavailable.');
        }

        $start = Carbon::parse($data['start_at']);
        $end = Carbon::parse($data['end_at']);
        $overlaps = RoomBooking::where('room_id', $room->id)
            ->whereIn('status', ['pending', 'approved'])
            ->where('start_at', '<', $end)
            ->where('end_at', '>', $start)
            ->exists();

        if ($overlaps) {
            return back()->withInput()->with('error', 'This room is already booked for that time.');
        }

        RoomBooking::create([
            'room_id' => $room->id,
            'user_id' => $userId,
            'start_at' => $start,
            'end_at' => $end,
            'purpose' => $data['purpose'],
            'notes' => $data['notes'] ?? null,
            'status' => 'approved',
        ]);

        $room->markBooked();

        return redirect()->route('room-bookings.index')->with('success', 'Room booked successfully.');
    }

    public function cancel(RoomBooking $roomBooking)
    {
        abort_unless($roomBooking->user_id === $this->memberUserId(), 403);
        if ($roomBooking->status === 'approved' && $roomBooking->start_at->isFuture()) {
            $roomBooking->update(['status' => 'cancelled']);
            $roomBooking->room->markAvailable();
        }

        return back()->with('success', 'Room booking cancelled.');
    }

    public function updateStatus(Request $request, RoomBooking $roomBooking)
    {
        $data = $request->validate(['status' => 'required|in:pending,approved,cancelled,completed']);
        $roomBooking->update($data);

        if (in_array($data['status'], ['cancelled', 'completed'], true)) {
            $roomBooking->room->markAvailable();
        } elseif (in_array($data['status'], ['pending', 'approved'], true)) {
            $roomBooking->room->markBooked();
        }

        return back()->with('success', 'Booking status updated.');
    }

    private function memberUserId(): ?int
    {
        if (Auth::id()) {
            return Auth::id();
        }

        $member = session('member_id') ? Member::find(session('member_id')) : null;
        return $member ? User::where('email', $member->email)->value('id') : null;
    }
}
