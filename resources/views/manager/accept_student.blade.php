@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
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
@endsection