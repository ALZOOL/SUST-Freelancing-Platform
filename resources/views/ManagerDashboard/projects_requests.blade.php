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

<h1>project requests</h1>

<br><br>

</table>
<br><br>

<table class="table">
  <thead>
    <tr>
	    <th scope="col">#ID</th>
        <th scope="col">#Client ID</th>
	    <th scope="col">Request Title</th>
        <th scope="col">category</th>
	    <th scope="col">Description</th>
	    <th scope="col">accept or reject?</t>
    </tr>
  </thead>
  <tbody>
  	@foreach($users as $user)
    	<tr> 
	      <td>{{ $user->id }}</td>
          <td>{{ $user->client_id }}</td>
	      <td>{{ $user->title }}</td>
          <td>{{ $user->category }}</td>
	      <td>{{ $user->description }}</td>
	      <td>
            <form action="{{route('project_request.accept',$user->id)}}" method="get">
                
                <button class="btn btn-primary"  type="submit">Accept</button>    
            </form>
	      	
	      	<a class="btn btn-danger" href="" role="button">Reject</a>
	      </td>
    	</tr>
 	@endforeach
  </tbody>
</table>


@endsection
