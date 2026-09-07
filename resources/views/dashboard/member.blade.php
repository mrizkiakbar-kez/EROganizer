@extends('layouts.app')

@section('content')

<div class="mb-4">
    <h2>Halo, {{ $member->nama }}!</h2>
    <p class="text-muted">Reserve and manage faculty rooms from one place.</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-2">Active Room Bookings</p>
                        <h3>{{ $activeRoomBookings }}</h3>
                    </div>
                    <i class="bi bi-calendar-check" style="font-size: 32px; color: var(--primary-blue); opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-2">Find a Room</p>
                        <a href="{{ route('rooms.index') }}" class="btn btn-primary btn-sm">Browse Available Rooms</a>
                    </div>
                    <i class="bi bi-door-open" style="font-size: 32px; color: var(--accent-success); opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="card">
    <div class="card-body">
        <h5 class="mb-3">My Recent Room Bookings</h5>
        @if($recentRoomBookings->count())
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0" style="--bs-table-bg: transparent; --bs-table-border-color: var(--border-color);">
                    <thead>
                        <tr>
                            <th>Room</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentRoomBookings as $booking)
                            <tr>
                                <td class="text-white">
                                    {{ $booking->room->name }}
                                </td>
                                <td>{{ $booking->start_at->format('d M Y H:i') }}</td>
                                <td>
                                    {{ $booking->end_at->format('d M Y H:i') }}
                                </td>
                                <td>
                                    @php
                                        $dispStatus = ucfirst($booking->status);
                                        $badgeClass = $booking->status === 'approved' ? 'bg-success' : ($booking->status === 'cancelled' ? 'bg-danger' : 'bg-warning text-dark');
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ $dispStatus }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted mb-0">You have no room bookings yet.</p>
        @endif
    </div>
</div>

@endsection
