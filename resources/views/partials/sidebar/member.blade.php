<nav class="sidebar-nav">
    <a href="{{ route('member.dashboard') }}" class="{{ Route::currentRouteName() === 'member.dashboard' ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('rooms.index') }}" class="{{ Route::currentRouteName() === 'rooms.index' ? 'active' : '' }}">
        <i class="bi bi-door-open"></i>
        <span>Faculty Rooms</span>
    </a>

    <a href="{{ route('room-bookings.index') }}" class="{{ str_starts_with(Route::currentRouteName(), 'room-bookings') ? 'active' : '' }}">
        <i class="bi bi-calendar-check"></i>
        <span>My Room Bookings</span>
    </a>

    <a href="{{ route('member.profile') }}" class="{{ Route::currentRouteName() === 'member.profile' ? 'active' : '' }}">
        <i class="bi bi-person-gear"></i>
        <span>Profile</span>
    </a>
</nav>
