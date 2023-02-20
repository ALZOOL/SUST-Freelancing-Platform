<form method="POST" action="{{route('teams.create')}}">
    @csrf
    <div class="form-group">
        <label for="team_name">Team Name</label>
        <input type="text" class="form-control" name="team_name" id="team_name" placeholder="Enter team name" value="{{old('team_name')}}">
        @error('team_name')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">Create Team</button>
</form>