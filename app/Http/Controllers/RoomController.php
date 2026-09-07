<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::withCount(['bookings as active_bookings' => function ($query) {
            $query->whereIn('status', ['pending', 'approved'])->where('end_at', '>=', now());
        }])->latest()->get();
        $rooms->each->syncBookingStatus();

        return view('rooms.index', compact('rooms'));
    }

    public function memberIndex()
    {
        Room::all()->each->syncBookingStatus();
        $rooms = Room::where('status', 'available')->orderBy('name')->get();
        return view('rooms.member-index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create', ['room' => new Room()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1|max:10000',
            'description' => 'nullable|string|max:2000',
            'status' => 'required|in:available,unavailable',
        ]);

        Room::create($data);
        return redirect()->route('admin.rooms.index')->with('success', 'Room added successfully.');
    }

    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1|max:10000',
            'description' => 'nullable|string|max:2000',
            'status' => 'required|in:available,unavailable',
        ]);

        $room->update($data);
        return redirect()->route('admin.rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        if ($room->bookings()->whereIn('status', ['pending', 'approved'])->where('end_at', '>=', now())->exists()) {
            return back()->with('error', 'A room with active bookings cannot be deleted.');
        }

        $room->delete();
        return redirect()->route('admin.rooms.index')->with('success', 'Room deleted successfully.');
    }
}
