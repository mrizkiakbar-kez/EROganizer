@extends('layouts.app')
@section('content')
<h2 class="mb-4">Edit Faculty Room</h2>
<div class="card"><div class="card-body">@include('rooms.form', ['action' => route('admin.rooms.update', $room), 'method' => 'PUT'])</div></div>
@endsection
