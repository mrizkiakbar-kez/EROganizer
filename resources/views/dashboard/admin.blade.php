@extends('layouts.app')

@section('content')

<div class="mb-4">
    <h2>Faculty Room Organizer</h2>
    <p class="text-muted">Manage rooms and coordinate faculty reservations.</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-2">Faculty Rooms</p>
                        <h3>{{ $totalRooms }}</h3>
                    </div>
                    <i class="bi bi-door-open" style="font-size: 32px; color: var(--primary-blue); opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-2">Active Bookings</p>
                        <h3>{{ $activeRoomBookings }}</h3>
                    </div>
                    <i class="bi bi-people" style="font-size: 32px; color: var(--primary-blue); opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-2">Registered Members</p>
                        <h3>{{ $totalMembers }}</h3>
                    </div>
                    <i class="bi bi-people" style="font-size: 32px; color: var(--primary-blue); opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="card">
    <div class="card-body">
        <h5 class="mb-3">Recent Room Bookings</h5>
        @if($recentRoomBookings->count())
            <div class="list-group list-group-flush">
                @foreach($recentRoomBookings as $booking)
                    <div class="list-group-item d-flex justify-content-between align-items-start" style="background: transparent; border-color: var(--border-color);">
                        <div>
                            <h6 class="mb-0 text-white">{{ $booking->room->name }} <span class="text-muted fw-normal">booked by</span> {{ $booking->user->name }}</h6>
                            <small class="text-muted">{{ $booking->start_at->format('d M Y - H:i') }} · {{ $booking->purpose }}</small>
                        </div>
                        @php
                            $dispStatus = ucfirst($booking->status);
                            $badgeClass = $booking->status === 'approved' ? 'bg-success' : ($booking->status === 'cancelled' ? 'bg-danger' : 'bg-warning text-dark');
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $dispStatus }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted mb-0">Belum ada aktivitas.</p>
        @endif
    </div>
</div>

@endsection
