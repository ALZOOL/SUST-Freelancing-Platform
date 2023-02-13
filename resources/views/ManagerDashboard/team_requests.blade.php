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

<h1>Team Requests</h1>
<br>
</table>
<br><br>

<table class="table">
  <thead>
    <tr>
	    <th scope="col">#ID</th>
        <th scope="col">Project name</th>
        <th scope="col">client name</th>
	    <th scope="col">student Name</th>
        <th scope="col">student Role</th>
    </tr>
  </thead>
  <tbody>
    @foreach( $users as $user )

    	<tr>
          <td>{{ $user->id }}</td>
	      <td>{{ $user->project_name }}</td>
          <td>{{ $user->client_email }}</td>
          <td>{{ $user->student_name }}</td>
          <td>{{ $user->student_role }}</td>
	      <td>
            <form action="{{route('accept.team',$user->id)}}" method="post">
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

<br><br>

<table class="table">
  <thead>
    <tr>
	    <th scope="col">#ID</th>
        <th scope="col">Project name</th>
        <th scope="col">client name</th>
	    <th scope="col">student Name</th>
        <th scope="col">student Role</th>
    </tr>
  </thead>
  <tbody>
    @foreach( $teams as $team )

    	<tr>
          <td>{{ $team->id }}</td>
	      <td>{{ $team->project_name }}</td>
          <td>{{ $team->client_email }}</td>
          <td>{{ $team->student_name }}</td>
          <td>{{ $team->student_role }}</td>

    	</tr>
    @endforeach
  </tbody>
</table>


@endsection
