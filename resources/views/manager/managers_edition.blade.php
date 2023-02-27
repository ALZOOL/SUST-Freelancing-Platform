@extends('layouts.app')
@section('content')
{{-- @auth('manager') --}}
@if ( Auth::guard('manager')->check() )
<p>Welcome <b>{{ Auth::guard('manager')->user()->name }}</b></p>
<h1>{{ Auth::guard('manager')->user()->id }}</h1>
<br>
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

<h1>edit manager info as admin </h1>
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
{{-- <a class="btn btn-primary" href="{{ route('password') }}">Change Password</a> --}}
{{-- <a class="btn btn-danger" href="{{ route('logout') }}">Logout</a> --}}
<a class="btn btn-primary" href="{{ route('admin_login') }}">Login</a>
<a class="btn btn-danger" href="{{ route('admin_logout') }}">Logout</a>
    
@else
{{-- @endauth --}}
{{-- @guest --}}
<a class="btn btn-primary" href="{{ route('admin_login') }}">Login</a>

@endif
{{-- @endguest --}}
@endsection
