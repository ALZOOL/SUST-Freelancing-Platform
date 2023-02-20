@extends('layouts.app')
@section('content')
{{-- @auth('client') --}}
@if ( Auth::guard('client')->check() )
<p>Welcome <b>{{ Auth::guard('client')->user()->first_name }}</b></p>
{{-- <a class="btn btn-primary" href="{{ route('password') }}">Change Password</a> --}}
{{-- <a class="btn btn-danger" href="{{ route('logout') }}">Logout</a> --}}
<a class="btn btn-primary" href="{{ route('client_login') }}">Login</a>
<a class="btn btn-danger" href="{{ route('client_logout') }}">Logout</a>
    
@else
{{-- @endauth --}}
{{-- @guest --}}
<a class="btn btn-primary" href="{{ route('login') }}">Login</a>
<a class="btn btn-info" href="{{ route('register') }}">Register</a>
@endif
{{-- @endguest --}}
@endsection