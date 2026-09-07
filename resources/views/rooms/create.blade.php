@extends('layouts.app')
@section('content')
<h2 class="mb-4">Add Faculty Room</h2>
<div class="card"><div class="card-body">@include('rooms.form', ['action' => route('admin.rooms.store'), 'method' => 'POST'])</div></div>
@endsection
