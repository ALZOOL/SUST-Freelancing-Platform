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

<h1>edit ayman manager info as admin </h1>
<br><br>
<h1>info edition</h1>
<br>
<div>
    <form action="{{route('system_managers.update',$data->id)}}" method="post">
        @csrf
        @method('PUT')
        <h1>Username</h1>
        <input type="text" name="name" value="{{$data->name}}" required="">
        <br><br>
        <h1>Email</h1>
        <input type="text" name="email" value="{{$data->email}}" required="">
        <br><br>
        <h1>Role</h1>
        <input type="text" name="role" value="{{$data->role}}" required="">
        <br><br>
        <h1>Password</h1>
        <input type="text" name="password" value="{{$data->password}}" required="">
        <br><br>
        <button type="submit">edit</button>
        <br><br>
        
    </form>
</div>
<br><br>


</form>
@endsection
