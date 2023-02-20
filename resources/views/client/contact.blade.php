<form method="POST" action="{{ route('contact_us.store') }}">
    @csrf

    <div class="form-group">
        <label for="description">Message:</label>
        <textarea name="description" id="description" class="form-control" rows="5">{{ old('description') }}</textarea>
        @error('description')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
