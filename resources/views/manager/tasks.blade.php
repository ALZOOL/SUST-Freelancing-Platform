@extends('layouts.app')
@section('content')
{{-- @auth('manager') --}}
@if ( Auth::guard('manager')->check() )
<p>Welcome <b>{{ Auth::guard('manager')->user()->name }}</b></p>
<h1>{{ Auth::guard('manager')->user()->id }}</h1>
<br>
<h1>tasks</h1>
<br><br>
<td><a href="{{ url('create_web_task') }}">web devolopment</a></td>

<br><br>
<td><a href="{{ url('create_security_task') }}">security</a></td>

<br><br>
<td><a href="{{ url('create_design_task') }}">desgin</a></td>

<br><br>

</table>
<br><br>

<table class="table">
  <thead>
    <tr>
	    <th scope="col">#ID</th>
	    <th scope="col">Task Name</th>
		<th scope="col">Level</th>
        <th scope="col">category</th>
	    <th scope="col">Description</th>
		<th scope="col">File path</th>
		<th scope="col">Rank</th>
		<th scope="col">Points</th>
	    <th scope="col">sett</th>
    </tr>
  </thead>
  <tbody>
  	@foreach($users as $user)
    	<tr>
	      <td> {{ $user->id }}</td>
	      <td>{{ $user->title }}</td>
		  <td> {{ $user->level }}</td>
          <td>{{ $user->category }}</td>
	      <td>{{ $user->description }}</td>
		  <td> {{ $user->file_path }}</td>
		  <td> {{ $user->rank }}</td>
		  <td> {{ $user->points }}</td>
	      <td>
	      	<a class="btn btn-primary" href="{{route('task.edit',$user->id)}}" role="button">Edit</a>
	      	<a class="btn btn-danger" href="{{route('task.delete',$user->id)}}" role="button">Delete</a>
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
<a class="btn btn-primary" href="{{ route('manager_login') }}">Login</a>
<a class="btn btn-info" href="{{ route('manager_register') }}">Register</a>
@endif
{{-- @endguest --}}
@endsection