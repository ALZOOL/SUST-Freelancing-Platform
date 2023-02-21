@extends('layouts.app')

@section('content')
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