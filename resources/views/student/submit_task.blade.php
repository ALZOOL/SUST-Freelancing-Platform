@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Submit Solution</h1>

        <form action="{{ route('submitTask') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="task_id">Task</label>
                <select class="form-control" name="task_id" id="task_id">
                    @foreach($tasks as $task)
                        <option value="{{ $task->id }}">{{ $task->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" class="form-control" name="category" id="category" value="{{ $task->category }}" disabled>
            </div>

            <div class="form-group">
                <label for="level">Level</label>
                <input type="text" class="form-control" name="level" id="level" value="{{ $task->level }}" disabled>
            </div>

            <div class="form-group">
                <label for="solution">Solution</label>
                <textarea class="form-control" name="solution" id="solution"></textarea>
            </div>

            <div class="form-group">
                <label for="file_path">Upload File</label>
                <input type="file" class="form-control-file" name="file" id="file">
            </div>

            <div class="form-group">
                <label for="report">Report</label>
                <textarea class="form-control" name="report" id="report"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection


