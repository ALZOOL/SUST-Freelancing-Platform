<table>
    <thead>
        <tr>
            <th>Project ID</th>
            <th>Project Title</th>
            <th>Category</th>
            <th>description</th>
            <th>deadline</th>
            <th>Rank</th>
            <th>frontend</th>
            <th>backend</th>
            <th>ui_ux</th>
            <th>security</th>
        </tr>
    </thead>
    <tbody>
        @foreach($projects as $project)
        <tr>
            <td>{{ $project->id }}</td>
            <td>{{ $project->title }}</td>
            <td>{{ $project->rank }}</td>
            <td>{{ $project->category }}</td>
            <td>{{ $project->description }}</td>
            <td>{{ $project->deadline }}</td>
            <td>{{ $project->rank }}</td>
            <td>{{ $project->frontend }}</td>
            <td>{{ $project->backend }}</td>
            <td>{{ $project->ui_ux }}</td>
            <td>{{ $project->security }}</td>
            <td>
                <form action="{{ route('student_project_request') }}" method="POST">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                    <button type="submit" class="btn btn-primary">Join as Individual</button>
                </form>
                <form action="{{ route('team_project_request') }}" method="POST">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                    <button type="submit" class="btn btn-primary">Join as Team</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
