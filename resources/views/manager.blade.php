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
<h1>{{ Auth::user()->id }}</h1>
<br><br>
<td><a href="{{ url('manager_profile') }}">Edit_profile</a></td>

<br><br>

<h1>manager</h1>
<br><br>
<td><a href="{{ url('Projects_requests') }}">Projects requests</a></td>

<br><br>
<td><a href="{{ url('Approved_projects') }}" >Approved projects</a></td>

<br><br>
<td><a href="{{ url('Team_requests') }}">Team requests</a></td>

<br><br>
<td><a href="{{ url('Tasks') }}">Tasks</a></td>

<br><br>
<td><a href="{{ url('Rank_interview') }}">Rank interview</a></td>

<br><br>
<td><a href="{{ url('Submitted_tasks') }}">Submitted tasks</a></td>

<br><br>
<td><a href="{{ url('Roadmaps') }}">Roadmaps</a></td>

<br><br>



@endsection
