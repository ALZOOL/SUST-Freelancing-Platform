@foreach($projects as $project)
    <form method="POST" action="{{ route('stars.store') }}">
        @csrf
        <input type="hidden" name="project_id" value="{{ $project->id }}">
        <input type="hidden" name="team_id" value="{{ $project->team_id }}">
        <button type="submit">Give star to {{ $project->team_id }}</button>
    </form>
@endforeach