<nav class="sidebar-nav">
    <a href="{{ route('admin.dashboard') }}" class="{{ Route::currentRouteName() === 'admin.dashboard' ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('admin.members.index') }}" class="{{ str_starts_with(Route::currentRouteName(), 'admin.members') ? 'active' : '' }}">
        <i class="bi bi-people"></i>
        <span>Members</span>
    </a>

    <a href="{{ route('admin.rooms.index') }}" class="{{ str_starts_with(Route::currentRouteName(), 'admin.rooms') ? 'active' : '' }}">
        <i class="bi bi-door-open"></i>
        <span>Faculty Rooms</span>
    </a>

    <a href="{{ route('admin.room-bookings.index') }}" class="{{ str_starts_with(Route::currentRouteName(), 'admin.room-bookings') ? 'active' : '' }}">
        <i class="bi bi-calendar-check"></i>
        <span>Room Bookings</span>
    </a>

</nav>
