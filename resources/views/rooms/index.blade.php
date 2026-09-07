@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h2>Faculty Rooms</h2><p class="text-muted mb-0">Manage room inventory and availability.</p></div>
    <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Room</a>
</div>
<div class="card"><div class="table-responsive"><table class="table table-dark table-hover mb-0" style="--bs-table-bg: transparent; --bs-table-border-color: var(--border-color);">
<thead><tr><th class="ps-4">Room</th><th>Location</th><th>Capacity</th><th>Status</th><th>Bookings</th><th class="text-end pe-4">Actions</th></tr></thead>
<tbody>
@forelse($rooms as $room)
<tr><td class="ps-4"><strong>{{ $room->name }}</strong><div class="text-muted small">{{ $room->description }}</div></td><td>{{ $room->location ?: '-' }}</td><td>{{ $room->capacity }} people</td><td><span class="badge {{ $room->status === 'available' ? 'bg-success' : ($room->status === 'booked' ? 'bg-warning text-dark' : 'bg-danger') }}">{{ ucfirst($room->status) }}</span></td><td>{{ $room->active_bookings }}</td><td class="text-end pe-4"><a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i> Edit</a><form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this room?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td></tr>
@empty
<tr><td colspan="6" class="text-center text-muted py-5">No rooms have been added yet.</td></tr>
@endforelse
</tbody></table></div></div>
@endsection
