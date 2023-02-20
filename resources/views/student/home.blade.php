@extends('layouts.app')
@section('content')
{{-- @auth('client') --}}
@if ( Auth::guard('student')->check() )
<p>Welcome <b>{{ Auth::guard('student')->user()->first_name }}</b></p>
{{-- <a class="btn btn-primary" href="{{ route('password') }}">Change Password</a> --}}
{{-- <a class="btn btn-danger" href="{{ route('logout') }}">Logout</a> --}}
<a class="btn btn-primary" href="{{ route('student_login') }}">Login</a>
<a class="btn btn-danger" href="{{ route('student_logout') }}">Logout</a>
<form action="{{ route('upgrade_rank') }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-primary">Upgrade Rank</button>
</form>
    
@else
{{-- @endauth --}}
{{-- @guest --}}
<a class="btn btn-primary" href="{{ route('login') }}">Login</a>
<a class="btn btn-info" href="{{ route('register') }}">Register</a>
@endif
{{-- @endguest --}}
@endsection