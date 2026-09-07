<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

use App\Models\User;
use App\Models\RoomBooking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Aksi tidak diperbolehkan.');
        }

        $members = Member::latest()->get();

        foreach ($members as $member) {
            $user = User::where('email', $member->email)->first();
            $member->user_id = $user?->id;
            $member->user_role = $user?->role ?? $member->role;
            if ($user) {
                $member->active_room_bookings_count = RoomBooking::where('user_id', $user->id)
                    ->whereIn('status', ['pending', 'approved'])
                    ->where('end_at', '>=', now())
                    ->count();
            } else {
                $member->active_room_bookings_count = 0;
            }
        }

        return view('members.index', compact('members'));
    }

    public function create()
    {
        abort(403, 'Aksi tidak diperbolehkan.');
    }

    public function store(Request $request)
    {
        abort(403, 'Aksi tidak diperbolehkan.');
    }

    public function show(Member $member)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Aksi tidak diperbolehkan.');
        }

        $user = User::where('email', $member->email)->first();
        $roomBookings = collect();
        if ($user) {
            $roomBookings = RoomBooking::where('user_id', $user->id)
                ->with('room')
                ->latest('start_at')
                ->get();
        }

        return view('members.show', compact('member', 'user', 'roomBookings'));
    }

    public function edit(Member $member)
    {
        abort(403, 'Aksi tidak diperbolehkan.');
    }

    public function update(Request $request, Member $member)
    {
        abort(403, 'Aksi tidak diperbolehkan.');
    }

    public function destroy(Member $member)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Aksi tidak diperbolehkan.');
        }

        $user = User::where('email', $member->email)->first();

        // Prevent deleting the currently logged-in Admin.
        if ($user && $user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus Admin yang sedang login.');
        }

        // Prevent deleting another Admin account.
        if (($user && $user->role === 'admin') || $member->role === 'admin') {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun Admin.');
        }

        DB::beginTransaction();
        try {
            if ($user) {
                $user->delete();
            }
            $member->delete();
            DB::commit();

            return redirect()->route('admin.members.index')->with('success', 'Anggota berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus anggota: ' . $e->getMessage());
        }
    }
}