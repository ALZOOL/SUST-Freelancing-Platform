<form method="POST" action="{{route('teams.join')}}">
    @csrf
    <div class="form-group">
        <label for="invitation_link">Invitation Link</label>
        <input type="text" class="form-control" name="invitation_link" id="invitation_link" placeholder="Enter invitation link">
        @error('invitation_link')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">Join Team</button>
</form>
