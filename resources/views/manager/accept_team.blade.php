@extends('layouts.app')
@section('content')
{{-- @auth('manager') --}}
@if ( Auth::guard('manager')->check() )
<p>Welcome <b>{{ Auth::guard('manager')->user()->name }}</b></p>
<h1>{{ Auth::guard('manager')->user()->id }}</h1>
<br>
@if ($studentRequests)
    @foreach($studentRequests as $request)
        <tr>
            <td>{{ $request->project_id }}</td>
            <td>{{ $request->project_title }}</td>
            <td>{{ $request->team_id }}</td>
            <td>
                <form method="POST" action="{{ route('add_team_to_project') }}">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $request->project_id }}">
                    <input type="hidden" name="project_title" value="{{ $request->project_title }}">
                    <input type="hidden" name="team_id" value="{{ $request->team_id }}">
                    <button type="submit" class="btn btn-success">Accept</button>
                </form>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="4">No team join requests found.</td>
    </tr>
@endif


{{-- <a class="btn btn-primary" href="{{ route('password') }}">Change Password</a> --}}
{{-- <a class="btn btn-danger" href="{{ route('logout') }}">Logout</a> --}}
<a class="btn btn-primary" href="{{ route('admin_login') }}">Login</a>
<a class="btn btn-danger" href="{{ route('admin_logout') }}">Logout</a>
    
@else
{{-- @endauth --}}
{{-- @guest --}}
<a class="btn btn-primary" href="{{ route('manager_login') }}">Login</a>

@endif
{{-- @endguest --}}
@endsection