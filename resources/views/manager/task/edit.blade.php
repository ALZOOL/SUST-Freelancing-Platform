@extends('layouts.app')
@section('content')
{{-- @auth('manager') --}}
@if ( Auth::guard('manager')->check() )
<p>Welcome <b>{{ Auth::guard('manager')->user()->name }}</b></p>
<h1>{{ Auth::guard('manager')->user()->id }}</h1>
<br>

<h1>tasks</h1>
<br><br>
<h1>task edition</h1>
<br>
<div>
    <form action="{{route('task.update',$myTask->id)}}" method="post">
        @csrf
        @method('PUT')
        <h1>Task title</h1>
        <input type="text" name="title" value="{{$myTask->title}}" required="">
        <br><br>
        <h1>Level</h1>
        <input type="text" name="level" value="{{$myTask->level}}" required="">
        <br><br>
        <h1>Task category</h1>
        <input type="text" name="category" value="{{$myTask->category}}" required="">
        <br><br>
        <h1>Description</h1>
        <input type="text" name="description" value="{{$myTask->description}}" required="">
        <br><br>
        <h1>File path</h1>
        <input type="text" name="file_path" value="{{$myTask->file_path}}" >
        <br><br>
        <h1>Rank</h1>
        <input type="text" name="rank" value="{{$myTask->rank}}" required="">
        <br><br>
        <h1>Points</h1>
        <input type="number" name="points" value="{{$myTask->points}}"  required="">
        <br><br>
        <button type="submit">edit</button>
        <br><br>
        
    </form>
</div>
<br><br>


</form>

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