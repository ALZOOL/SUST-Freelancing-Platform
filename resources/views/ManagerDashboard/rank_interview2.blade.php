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

<h1>upgrade or cancel</h1>
<br><br>

<br><br>

<table class="table">
  <thead>
    <tr>
	    <th scope="col">#ID</th>
	    <th scope="col">Name</th>
        <th scope="col">role</th>
	    <th scope="col">from</th>
	    <th scope="col">to</t>
    </tr>
  </thead>
  <tbody>
  	@foreach($users as $user)
        @method('PUT')
    	<tr>
	      <td> {{ $user->id }}</td>
	      <td>{{ $user->student_name }}</td>
          <td>{{ $user->role }}</td>
	      <td>{{ $user->current_rank }}</td>
          <td>{{ $user->next_rank }}</td>
	      <td>
            <form action="{{route('rank.upgrade',[$user->id,$user->next_rank])}}" method="post">
                @csrf
                @method('PUT')
                <button type="submit">edit</button>
	      	    <a class="btn btn-primary" href="" role="button">Upgrade</a>
	      	    <a class="btn btn-danger" href="" role="button">cancel</a>
            </form>
            </td>
    	</tr>
 	@endforeach
  </tbody>
</table>
<br><br>

@endsection
