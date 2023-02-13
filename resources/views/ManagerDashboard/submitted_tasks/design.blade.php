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

<h1>submitted design tasks</h1>
<br>
</table>
<br><br>

<table class="table">
  <thead>
    <tr>
	    <th scope="col">#ID</th>
        <th scope="col">Student Name</th>
        <th scope="col">Role</th>
	    <th scope="col">Task Name</th>
        <th scope="col">Level</th>
        <th scope="col">Points</th>
        <th scope="col">category</th>
	    <th scope="col">Answer</th>
    </tr>
  </thead>
  <tbody>
  	@foreach($users as $user)
    	<tr>
	      <td> {{ $user->id }}</td>
	      <td>{{ $user->student_name }}</td>
          <td>{{ $user->role }}</td>
	      <td>{{ $user->task_name }}</td>
          <td>{{ $user->level }}</td>
          <td>{{ $user->points }}</td>
          <td>{{ $user->category }}</td>
          <td>{{ $user->answer }}</td>
	      <td>
            <h8>points</h8>
            <form action="{{route('custom',[$user->student_name,$user->id])}}" method="post">
                @csrf
                @method('PUT')
                <input name="points_n" type="number" value="1" style="width: 50px;" />
                <button class="btn btn-primary" type="submit">custom</button>
            </form>
            <form action="{{route('full',[$user->student_name,$user->id])}}" method="post">
                @csrf
                @method('PUT')
                <button class="btn btn-danger" name="full" type="submit" value="{{ $user->points }}">full</button>
            </form>
	      </td>
    	</tr>
 	@endforeach
  </tbody>
</table>

@endsection
