<div class="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-door-open"></i>
        <span>EROganizer</span>
    </div>

    @if(Auth::check() && Auth::user()->role === 'admin')
        @include('partials.sidebar.admin')
    @elseif(session()->has('member_id'))
        @include('partials.sidebar.member')
    @endif
</div>