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

<h1>submitted tasks</h1>
<br>
<a href="{{ url('submitted_web') }}">web</a>
<br>
<a href="{{ url('submitted_security') }}">security</a>
<br>
<a href="{{ url('submitted_design') }}">design</a>
<br>

@endsection
