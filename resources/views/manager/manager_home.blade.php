@extends('layouts.app')
@section('content')
{{-- @auth('manager') --}}
@if ( Auth::guard('manager')->check() )
<p>Welcome <b>{{ Auth::guard('manager')->user()->name }}</b></p>
<h1>{{ Auth::guard('manager')->user()->id }}</h1>
<br>
<br><br>
<td><a href="{{ url('manager_profile') }}">Edit_profile</a></td>

<br><br>

<h1>manager</h1>
<br><br>
<td><a href="{{ url('Projects_requests') }}">Projects requests</a></td>

<br><br>
<td><a href="{{ url('Approved_projects') }}" >Approved projects</a></td>

<br><br>
<td><a href="{{ url('Team_requests') }}">Team requests</a></td>

<br><br>
<td><a href="{{ url('Tasks') }}">Tasks</a></td>

<br><br>
<td><a href="{{ url('Rank_interview') }}">Rank interview</a></td>

<br><br>
<td><a href="{{ url('Submitted_tasks') }}">Submitted tasks</a></td>

<br><br>
<td><a href="{{ url('Roadmaps') }}">Roadmaps</a></td>

<br><br>

{{-- <a class="btn btn-primary" href="{{ route('password') }}">Change Password</a> --}}
{{-- <a class="btn btn-danger" href="{{ route('logout') }}">Logout</a> --}}
<a class="btn btn-primary" href="{{ route('manager_login') }}">Login</a>
<a class="btn btn-danger" href="{{ route('manager_logout') }}">Logout</a>
    
@else
{{-- @endauth --}}
{{-- @guest --}}
<a class="btn btn-primary" href="{{ route('manager_login') }}">Login</a>

@endif
{{-- @endguest --}}
@endsection