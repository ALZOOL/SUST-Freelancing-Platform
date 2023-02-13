@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>

<h1>tasks</h1>
<br><br>
<h1>web task creation</h1>
<br>
<div>
    <form action="{{url('/web_task')}}" method="post">
        @csrf
        <h1>Task title</h1>
        <input type="text" name="title" required="">
        <br><br>
        <h1>Description</h1>
        <input type="text" name="description" required="">
        <br><br>
        <input type="submit">
        <br><br>
        <div class="mg-toolbar">
            <div class="mg-option checkbox-custom checkbox-inline">
                <input class="product-list" id="file_1" type="radio" name="PDF" value="File1">
                <label for="file_1">easy</label>
            </div>
            <div class="mg-option checkbox-custom checkbox-inline">
                <input class="product-list" id="file_2" type="radio" name="PDF" value="File2">
                <label for="file_2">mideum</label>
            </div>
            <div class="mg-option checkbox-custom checkbox-inline">
                <input class="product-list" id="file_3" type="radio" name="PDF" value="File3">
                <label for="file_3">hard</label>
            </div>
        </div>  
    </form>
</div>



@endsection
