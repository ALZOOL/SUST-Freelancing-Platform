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
<div>
    <form action="{{route('m_username.update',Auth::user()->id)}}" method="post">
        @csrf
        @method('PUT')
        <h1>Username</h1>
        <input type="text" name="username" value="{{$data->name}}" required="">
        <br><br>
        <button type="submit">edit</button>
        <br><br>
        
    </form>
</div>
<br><br>


</form>
@endsection
