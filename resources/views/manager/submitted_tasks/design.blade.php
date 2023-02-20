@extends('layouts.app')
@section('content')
{{-- @auth('manager') --}}
@if ( Auth::guard('manager')->check() )
<p>Welcome <b>{{ Auth::guard('manager')->user()->name }}</b></p>
<h1>{{ Auth::guard('manager')->user()->id }}</h1>
<br>
<h1>submitted design tasks</h1>
<br>
</table>
<br><br>

<table class="table">
  <thead>
    <tr>
	    <th scope="col">#ID</th>
        <th scope="col">Student Name</th>
        <th scope="col">Role</th>
	    <th scope="col">Task Name</th>
        <th scope="col">Level</th>
        <th scope="col">Points</th>
        <th scope="col">category</th>
	    <th scope="col">Answer</th>
    </tr>
  </thead>
  <tbody>
  	@foreach($users as $user)
    	<tr>
	      <td> {{ $user->id }}</td>
	      <td>{{ $user->student_name }}</td>
          <td>{{ $user->role }}</td>
	      <td>{{ $user->task_name }}</td>
          <td>{{ $user->level }}</td>
          <td>{{ $user->points }}</td>
          <td>{{ $user->category }}</td>
          <td>{{ $user->answer }}</td>
	      <td>
            <h6>points</h6>
            <form action="{{route('custom',[$user->student_name,$user->id])}}" method="post">
                @csrf
                @method('PUT')
                <input name="points_n" type="number" value="1" style="width: 50px;" />
                <button class="btn btn-primary" type="submit">custom</button>
            </form>
            <form action="{{route('full',[$user->student_name,$user->id])}}" method="post">
                @csrf
                @method('PUT')
                <button class="btn btn-danger" name="full" type="submit" value="{{ $user->points }}">full</button>
            </form>
	      </td>
    	</tr>
 	@endforeach
  </tbody>
</table>

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