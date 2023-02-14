@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>

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


@endsection
