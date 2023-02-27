@extends('layouts.app')
@section('content')
{{-- @auth('manager') --}}
@if ( Auth::guard('manager')->check() )
<p>Welcome <b>{{ Auth::guard('manager')->user()->name }}</b></p>
<h1>{{ Auth::guard('manager')->user()->id }}</h1>
<br>
<h1>rank interviews</h1>
<br><br>


<br><br>

<table class="table">
  <thead>
    <tr>
	    <th scope="col">#ID</th>
      <th scope="col">Username</th>
	    <th scope="col">from</th>
	    <th scope="col">to</>
    </tr>
  </thead>
  <tbody>
  	@foreach($users as $user)
    	<tr>
	      <td> {{ $user->id }}</td>
        <td>{{ $user->username }}</td>
	      <td>{{ $user->current_rank }}</td>
        <td>{{ $user->next_rank }}</td>
	      <td>
          <form action="{{route('interview_request.accept',$user->id)}}" method="get">
            @csrf
	      	  
	      	  <h6>accept</h6>
              <input type="number" name="accept" style="width: 50px;" value="1" required>
              <button class="btn btn-primary"  type="submit">Accept</button>
            </form>
          </form>
          <form action="{{route('interview_request.reject',$user->id)}}" method="get">
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
<br><br>

<h1>go upgrade</h1>

<br><br>

<table class="table">
  <thead>
    <tr>
	    <th scope="col">#ID</th>
      <th scope="col">Username</th>
	    <th scope="col">from</th>
	    <th scope="col">to</>
    </tr>
  </thead>
  <tbody>
    @foreach( $results as $result )

    	<tr>
            <td> {{ $result->id }}</td>
            <td>{{ $result->username }}</td>
            <td>{{ $result->current_rank }}</td>
            <td>{{ $result->next_rank }}</td>
            <td>
                <form action="{{route('rank.upgrade',[$result->id,$result->next_rank])}}" method="post">
                    @csrf
                    @method('PUT')
                    <h6>upgrade</h6>
                    <input type="number" name="upgrade" style="width: 50px;" value="1" required>
                    <button class="btn btn-primary" type="submit">Upgrade</button>
                  
                </form>
                <form action="{{route('rank_upgrading.cancel',$user->id)}}" method="get">
                  @csrf
                  
                  <h6>cancel</h6>
                    <input type="number" name="cancel" style="width: 50px;" value="1" required>
                    <button class="btn btn-danger"  type="submit">Cancel</button>
                </form>                
            </td>

    	</tr>
    @endforeach
  </tbody>
</table>

<br><br>
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