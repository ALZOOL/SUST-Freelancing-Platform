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

<h1>Profile </h1>
<br><br>
<h1>profile edition</h1>
<br>
<h1>manager</h1>
<br><br>
<td><a href="{{route('/m_username.edit',Auth::user()->id)}}">username</a></td>

<br><br>
<td><a href="{{route('/m_email.edit',Auth::user()->id)}}" >email</a></td>

<br><br>
<td><a href="{{route('/m_password.edit',Auth::user()->id)}}">password</a></td>

<br><br>


@endsection
