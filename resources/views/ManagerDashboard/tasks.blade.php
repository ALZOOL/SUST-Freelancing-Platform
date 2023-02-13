<!DOCTYPE html>
<html>
<head>
	<title>
		All Tasks
	</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>

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
	      	<a class="btn btn-primary" href="{{route('task.edit',$user->id)}}" role="button">Edit</a>
	      	<a class="btn btn-danger" href="{{route('task.delete',$user->id)}}" role="button">Delete</a>
	      </td>
    	</tr>
 	@endforeach
  </tbody>
</table>

@endsection
