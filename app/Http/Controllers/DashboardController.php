<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use App\Models\RoomBooking;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalMembers = Member::count();
        $totalRooms = Room::count();
        $activeRoomBookings = RoomBooking::where('status', 'approved')
            ->where('end_at', '>=', now())->count();
        $recentRoomBookings = RoomBooking::with(['user', 'room'])->latest('start_at')->limit(10)->get();

        return view('dashboard.admin', compact('totalMembers', 'totalRooms', 'activeRoomBookings', 'recentRoomBookings'));
    }

    public function member()
    {
        $userId = Auth::id();
        $member = session('member');

        if (!$userId && session()->has('member_id')) {
            $member = Member::find(session('member_id'));
            $user = User::where('email', $member->email)->first();
            $userId = $user?->id;
        }

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $activeRoomBookings = RoomBooking::where('user_id', $userId)
            ->where('status', 'approved')->where('end_at', '>=', now())->count();
        $recentRoomBookings = RoomBooking::where('user_id', $userId)
            ->with('room')->latest('start_at')->limit(10)->get();

        return view('dashboard.member', compact('member', 'activeRoomBookings', 'recentRoomBookings'));
    }
}
