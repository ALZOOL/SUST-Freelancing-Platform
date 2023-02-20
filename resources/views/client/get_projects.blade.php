<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Description</th>
            <th>Status</th>
            <th>Team Members</th>
        </tr>
    </thead>
    <tbody>
        @foreach($projects as $project)
        <tr>
            <td>{{ $project->title }}</td>
            <td>{{ $project->category }}</td>
            <td>{{ $project->description }}</td>
            <td>{{ $project->status }}</td>
            <td>
                @foreach($project->team as $team)
                    @foreach($team->members as $member)
                        {{ $member->name }} ({{ $member->email }})
                        <br>
                    @endforeach
                @endforeach
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
