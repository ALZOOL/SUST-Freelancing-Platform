@extends('layouts.app')
@section('content')
{{-- @auth('manager') --}}
@if ( Auth::guard('manager')->check() )
<p>Welcome <b>{{ Auth::guard('manager')->user()->name }}</b></p>
<h1>{{ Auth::guard('manager')->user()->id }}</h1>
<br>

<h1>tasks</h1>
<br><br>
<h1>desgin task creation</h1>
<br>
<div>
    <form action="{{url('/desgin_task')}}" method="post">
        @csrf
        <h1>Task title</h1>
        <input type="text" name="title" required="">
        <br><br>
        <h1>Level</h1>
        <input type="text" name="level" required="">
        <br><br>
        <h1>Description</h1>
        <input type="text" name="description" required="">
        <br><br>
        <h1>File path</h1>
        <input type="text" name="file_path" >
        <br><br>
        <h1>Rank</h1>
        <input type="text" name="rank" required="">
        <br><br>
        <h1>Points</h1>
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