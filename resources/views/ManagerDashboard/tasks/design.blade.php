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
<h1>desgin task creation</h1>
<br>
<div>
    <form action="{{url('/desgin_task')}}" method="post">
        @csrf
        <h1>Task title</h1>
        <input type="text" name="title" required="">
        <br><br>
        <h1>Description</h1>
        <input type="text" name="description" required="">
        <br><br>
        <input type="submit">
        <br><br>
        
    </form>
</div>

@endsection
