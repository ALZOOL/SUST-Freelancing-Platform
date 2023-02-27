@extends('layouts.app')
@section('content')
{{-- @auth('teacher') --}}
@if ( Auth::guard('teacher')->check() )
<p>Welcome <b>{{ Auth::guard('teacher')->user()->name }}</b></p>
<h1>{{ Auth::guard('teacher')->user()->id }}</h1>
<br>
<br><br>
<td><a href="{{ url('manager_profile') }}">Edit_profile</a></td>

<br><br>

<h1>teacher</h1>
<br><br>
<td><a href="{{ url('Roadmaps') }}">Roadmaps</a></td>

<br><br>

{{-- <a class="btn btn-primary" href="{{ route('password') }}">Change Password</a> --}}
{{-- <a class="btn btn-danger" href="{{ route('logout') }}">Logout</a> --}}
<a class="btn btn-primary" href="{{ route('teacher_login') }}">Login</a>
<a class="btn btn-danger" href="{{ route('teacher_logout') }}">Logout</a>
    
@else
{{-- @endauth --}}
{{-- @guest --}}
<a class="btn btn-primary" href="{{ route('teacher_login') }}">Login</a>

@endif
{{-- @endguest --}}
@endsection