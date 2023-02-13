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

<h1>roadmaps</h1>
<br><br>
<div>
    <form action="{{url('/addRoadmap')}}" method="post">
        @csrf
        <h1>Road-map title</h1>
        <input type="text" name="title" required="">
        <br><br>
        <h1>Category</h1>
        <input type="text" name="category" required="">
        <br><br>
        <h1>Description</h1>
        <input type="text" name="description" required="">
        <br><br>
        <input type="submit">
        <br><br>
        
    </form>
</div>

<br><br>

<table class="table">
  <thead>
    <tr>
	    <th scope="col">#ID</th>
	    <th scope="col">Road-map Name</th>
        <th scope="col">category</th>
	    <th scope="col">Description</th>
	    <th scope="col">sett</t>
    </tr>
  </thead>
  <tbody>
  	@foreach($users as $user)
    	<tr>
	      <td> {{ $user->id }}</td>
	      <td>{{ $user->title }}</td>
          <td>{{ $user->category }}</td>
	      <td>{{ $user->description }}</td>
	      <td>
	      	<a class="btn btn-primary" href="{{route('roadmap.edit',$user->id)}}" role="button">Edit</a>
	      	<a class="btn btn-danger" href="{{route('roadmap.delete',$user->id)}}" role="button">Delete</a>
	      </td>
    	</tr>
 	@endforeach
  </tbody>
</table>

@endsection
