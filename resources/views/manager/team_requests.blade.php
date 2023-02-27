@extends('layouts.app')
@section('content')
{{-- @auth('manager') --}}
@if ( Auth::guard('manager')->check() )
<p>Welcome <b>{{ Auth::guard('manager')->user()->name }}</b></p>
<h1>{{ Auth::guard('manager')->user()->id }}</h1>
<br>
<br><br>
<td><a href="{{ url('manager_profile') }}">Edit_profile</a></td>

<h1>Team Requests</h1>
<br>
<h1>single student Requests</h1>
<br>
</table>
<br><br>

<table class="table">
  <thead>
    <tr>
	    <th scope="col">#ID</th>
        <th scope="col">Project name</th>
        <th scope="col">client email</th>
	    <th scope="col">student Name</th>
        <th scope="col">student Role</th>
    </tr>
  </thead>
  <tbody>
    @foreach( $users as $user )

    	<tr>
        <td>{{ $user->id }}</td>
	      <td>{{ $user->project_title }}</td>
          <td>{{ $user->client_email }}</td>
          <td>{{ $user->student_name }}</td>
          <td>{{ $user->student_role }}</td>
	      <td>
            <form action="{{route('accept_single.team',$user->id)}}" method="post">
                @csrf
                @method('PUT')
                <button class="btn btn-primary" type="submit">accept</button>
            </form>
            <form action="" method="post">
                @csrf
                @method('PUT')
                <button class="btn btn-danger" name="full" type="submit" value="">reject</button>
            </form>
	      </td>
    	</tr>
    @endforeach
  </tbody>


</table>
<h1>full team Requests</h1>
<br>
<br><br>

<table class="table">
  <thead>
    <tr>
	    <th scope="col">#ID</th>
        <th scope="col">Project name</th>
        <th scope="col">client email</th>
	    <th scope="col">Team Name</th>  
    </tr>
  </thead>
  <tbody>
    @foreach( $teams as $team )

    	<tr>
        <td>{{ $team->id }}</td>
	      <td>{{ $team->project_title }}</td>
          <td>{{ $team->client_email }}</td>
          <td>{{ $team->team_name }}</td>
	      <td>
            <form action="{{route('accept_full.team',$team->id)}}" method="post">
                @csrf
                @method('PUT')
                <button class="btn btn-primary" type="submit">accept</button>
            </form>
            <form action="" method="post">
                @csrf
                @method('PUT')
                <button class="btn btn-danger" name="full" type="submit" value="">reject</button>
            </form>
	      </td>
    	</tr>
    @endforeach
  </tbody>

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