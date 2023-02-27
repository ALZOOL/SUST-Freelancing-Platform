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

<h1>Admin</h1>
<br><br>

<div>
    <form action="{{url('/addmanager')}}" method="post">
        @csrf
        <h1>name</h1>
        <input type="text" name="name" required="">
        <br><br>
        <h1>email</h1>
        <input type="email" name="email" required="">
        <br><br>
        <h1>password</h1>
        <input type="password" name="password" required="">
        <br><br>
        <h1>role</h1>
        <input type="text" name="role" required="">
        <br><br>
        <input type="submit">
        <br><br>
        
    </form>
</div>
<br>
<h6>Clinets Number</h6>
<h4> {{ $clients_number }}</h4>
<br>
<h6>Managers Number</h6>
<h4> {{ $managers_number }}</h4>
<br>
<h6>Students Number</h6>
<h4> {{ $students_number }}</h4>
<br>

<table class="table">
  <thead>
    <tr>
	    <th scope="col">#ID</th>
	    <th scope="col">Name</th>
        <th scope="col">Role</th>
    </tr>
  </thead>
  <tbody>
  	@foreach($users as $user)
    	<tr>
	      <td> {{ $user->id }}</td>
	      <td>{{ $user->name }}</td>
          <td>{{ $user->role }}</td>
	      <td>
	      	<a class="btn btn-primary" href="{{route('system_managers.edit',$user->id)}}" role="button">Edit</a>
	      	<a class="btn btn-danger" href="{{route('system_managers.delete',$user->id)}}" role="button">Delete</a>
	      </td>
    	</tr>
 	@endforeach
  </tbody>
</table>

{{-- <a class="btn btn-primary" href="{{ route('password') }}">Change Password</a> --}}
{{-- <a class="btn btn-danger" href="{{ route('logout') }}">Logout</a> --}}
<a class="btn btn-primary" href="{{ route('admin_login') }}">Login</a>
<a class="btn btn-danger" href="{{ route('admin_logout') }}">Logout</a>
    
@else
{{-- @endauth --}}
{{-- @guest --}}
<a class="btn btn-primary" href="{{ route('admin_login') }}">Login</a>

@endif
{{-- @endguest --}}
@endsection