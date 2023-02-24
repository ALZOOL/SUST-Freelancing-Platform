@extends('layouts.app')
@section('content')
{{-- @auth('client') --}}
@if ( Auth::guard('manager')->check() )
<p>Welcome <b>{{ Auth::guard('manager')->user()->name }}</b></p>
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
      <th scope="col">Teacher</th>
	    <th scope="col">Road-map Name</th>
      <th scope="col">category</th>
	    <th scope="col">Description</th>
	    <th scope="col">sett</th>
    </tr>
  </thead>
  <tbody>
  	@foreach($users as $user)
    	<tr>
	      <td> {{ $user->id }}</td>
        <td> {{ $user->name }}</td>
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

    
@else
{{-- @endauth --}}
{{-- @guest --}}
<a class="btn btn-primary" href="{{ route('login') }}">Login</a>
<a class="btn btn-info" href="{{ route('register') }}">Register</a>
@endif
{{-- @endguest --}}
@endsection