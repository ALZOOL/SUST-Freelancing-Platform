@extends('layouts.app')
@section('content')
{{-- @auth('manager') --}}
@if ( Auth::guard('manager')->check() )
<p>Welcome <b>{{ Auth::guard('manager')->user()->name }}</b></p>
<h1>{{ Auth::guard('manager')->user()->id }}</h1>
<br>
<br><br>
<h1>project requests</h1>

<br><br>

</table>
<br><br>

<table class="table">
  <thead>
    <tr>
	    <th scope="col">#ID</th>
        <th scope="col">#Client Email</th>
	    <th scope="col">Request Title</th>
        <th scope="col">category</th>
	    <th scope="col">File path</th>
	    <th scope="col">accept or reject?</th>
    </tr>
  </thead>
  <tbody>
  	@foreach($users as $user)
    	<tr> 
	      <td>{{ $user->id }}</td>
          <td>{{ $user->email }}</td>
	      <td>{{ $user->project_title }}</td>
          <td>{{ $user->project_description }}</td>
	      <td>{{ $user->project_file_path }}</td>
	      <td>
            <form action="{{route('project_request.accept',$user->id)}}" method="get"> 	
                
                <button class="btn btn-primary"  type="submit">Accept</button>    
            </form>
			<form action="{{route('project_request.reject',$user->id)}}" method="get">
				@csrf
				<h6>reject</h6>
				  <input type="number" name="reject" style="width: 50px;" value="1" required>
				  <button class="btn btn-danger"  type="submit">reject</button>
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
<a class="btn btn-primary" href="{{ route('manager_login') }}">Login</a>

@endif
{{-- @endguest --}}
@endsection
