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
<h1>task edition</h1>
<br>
<div>
    <form action="{{route('task.update',$myTask->id)}}" method="post">
        @csrf
        @method('PUT')
        <h1>Task title</h1>
        <input type="text" name="title" value="{{$myTask->title}}" required="">
        <br><br>
        <h1>Description</h1>
        <input type="text" name="description" value="{{$myTask->description}}" required="">
        <br><br>
        <button type="submit">edit</button>
        <br><br>
        
    </form>
</div>
<br><br>


</form>
@endsection
