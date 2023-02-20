@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-md-6">
        @if($errors->any())
        @foreach($errors->all() as $err)
        <p class="alert alert-danger">{{ $err }}</p>
        @endforeach
        @endif
        <form action="{{ route('student.register') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>First Name <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="first_name" value="{{ old('first_name') }}" />
            </div>
            <div class="mb-3">
                <label>Last Name <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="last_name" value="{{ old('last_name') }}" />
            </div>
            <div class="mb-3">
                <label>Email <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="email" value="{{ old('email') }}" />
            </div>
            <div class="mb-3">
                <label>Username <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="username" value="{{ old('username') }}" />
            </div>
            <div class="mb-3">
                <label>role <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="role" value="{{ old('role') }}" />
            </div>
            <div class="mb-3">
                <label>Password <span class="text-danger">*</span></label>
                <input class="form-control" type="password" name="password" />
            </div>
            <div class="mb-3">
                <label>Password Confirmation<span class="text-danger">*</span></label>
                <input class="form-control" type="password" name="password_confirm" />
            </div>
            <div class="mb-3">
                <button class="btn btn-primary">Register</button>
                <a class="btn btn-danger" href="{{ route('student.register') }}">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection