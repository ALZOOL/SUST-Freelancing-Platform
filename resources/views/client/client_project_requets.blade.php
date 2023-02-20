<form method="POST" action="{{ route('client_project.request') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="project_title">Project Title</label>
        <input type="text" class="form-control" id="project_title" name="project_title" placeholder="Enter project title">
    </div>
    <div class="form-group">
        <label for="project_description">Project Description</label>
        <textarea class="form-control" id="project_description" name="project_description" rows="3" placeholder="Enter project description"></textarea>
    </div>
    <div class="form-group">
        <label for="project_file">Project File</label>
        <input type="file" class="form-control-file" id="project_file" name="project_file">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
