@extends('layouts.app')
@section('content')
{{-- @auth('manager') --}}
@if ( Auth::guard('manager')->check() )
<p>Welcome <b>{{ Auth::guard('manager')->user()->name }}</b></p>
<h1>{{ Auth::guard('manager')->user()->id }}</h1>
<br>
                <h1>Student Requests</h1>
                @if(count($studentRequests) > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Project Title</th>
                                <th>Team ID</th>
                                <th>Student ID</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($studentRequests as $studentRequest)
                                <tr>
                                    <td>{{ $studentRequest->project_title }}</td>
                                    {{-- <td>{{ $studentRequest->team_id }}</td> --}}
                                    <td>{{ $studentRequest->student_id }}</td>
                                    <td>
                                        <form action="{{ route('add_student_to_project') }}" method="POST">
                                            @csrf
                                            {{-- <input type="hidden" name="team_id" value="{{ $studentRequest->team_id }}"> --}}
                                            <input type="hidden" name="project_id" value="{{ $studentRequest->project_id }}">
                                            <input type="hidden" name="student_id" value="{{ $studentRequest->student_id }}">
                                            <button type="submit" class="btn btn-primary">Accept</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No student requests found.</p>
                @endif
            </div>
        </div>
    </div>

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