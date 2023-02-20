@extends('layouts.app')
@section('content')
{{-- @auth('manager') --}}
@if ( Auth::guard('manager')->check() )
<p>Welcome <b>{{ Auth::guard('manager')->user()->name }}</b></p>
<h1>{{ Auth::guard('manager')->user()->id }}</h1>
<br>
<h1>tasks</h1>
<br><br>
<h1>web task creation</h1>
<br>
<div>
    <form action="{{route('web.add')}}" method="post">
        @csrf
        <h5>Task title</h5>
        <input type="text" name="title" required="">
        <br><br>
        <h5>Level</h5>
        <input type="text" name="level" required="">
        <br><br>
        <h5>Description</h5>
        <input type="text" name="description" required="">
        <br><br>
        <h5>File path</h5>
        <input type="text" name="file_path" >
        <br><br>
        <h5>Flag or solution</h5>
        <input type="text" name="solution" >
        <br><br>
        <h5>Rank</h5>
        <input type="text" name="rank" required="">
        <br><br>
        <h5>Points</h5>
        <input type="number" name="points" value="5" required="">
        <br><br>
        <input type="submit">
        <br><br>

    </form>
</div>



{{-- <a class="btn btn-primary" href="{{ route('password') }}">Change Password</a> --}}
{{-- <a class="btn btn-danger" href="{{ route('logout') }}">Logout</a> --}}
<a class="btn btn-primary" href="{{ route('manager_login') }}">Login</a>
<a class="btn btn-danger" href="{{ route('manager_logout') }}">Logout</a>
    
@else
{{-- @endauth --}}
{{-- @guest --}}
<a class="btn btn-primary" href="{{ route('login') }}">Login</a>
<a class="btn btn-info" href="{{ route('register') }}">Register</a>
@endif
{{-- @endguest --}}
@endsection