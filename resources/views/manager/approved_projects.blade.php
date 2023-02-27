@extends('layouts.app')
@section('content')
{{-- @auth('manager') --}}
@if ( Auth::guard('manager')->check() )
<p>Welcome <b>{{ Auth::guard('manager')->user()->name }}</b></p>
<h1>{{ Auth::guard('manager')->user()->id }}</h1>
<br>
<br><br>
<td><a href="{{ url('manager_profile') }}">Edit_profile</a></td>

<br><br>

<h1>manager</h1>
<h1>approved projects</h1>

<table class="table">
  <thead>
    <tr>
	    <th scope="col">#ID</th>
        <th scope="col">#Client Email</th>
	    <th scope="col">Title</th>
        <th scope="col">category</th>
        <th scope="col">Description</th>
        <th scope="col">Deadline</th>
        <th scope="col">rank</th>
        <th scope="col">Frontend</th>
        <th scope="col">Backend</th>
        <th scope="col">UI/UX</th>
        <th scope="col">Security</th>
        
	    
	    
    </tr>
  </thead>
  <tbody>
  	@foreach($users as $user)
    <tr>
        <td>
      <form action="{{url('/publish')}}" method="post">
          @csrf
          <tr>
	        <td>
                <input type="text" name="id" style="width: 50px;" value="{{ $user->id }}">  
            </td>
            <td>
                <input type="text" name="client_email" style="width: 50px;" value="{{ $user->email }}" required>  
            </td>
	        <td>
                <input type="text" name="title" style="width: 170px;" value="{{ $user->title }}" required>  
            </td>
	        <td>
                <input type="text" name="category" style="width: 50px;" value="" required>
            </td>
            <td>
                <input type="text" name="description" style="width: 170px;" value="{{ $user->description }}" required>  
            </td>
            <td>
                <input type="number" name="deadline" style="width: 50px;" value="0">  
            </td>
            <td>
                <input type="text" name="rank" style="width: 50px;" value="" required>  
            </td>
            <td>
                <input name="frontend" type="number" value="1" style="width: 50px;" />
            </td>
            <td>
                <input name="backend" type="number" value="1" style="width: 50px;" />
            </td>
            <td>
                <input name="ui_ux" type="number" value="1" style="width: 50px;" />
            </td>
            <td>
                <input name="security" type="number" value="1" style="width: 50px;" />
            </td>
            <td>
                <input name="client_id" type="id" value="{{ $user->client_id }}" hidden="true" style="width: 50px;" />
            </td>
            <td>
	      	    <button class="btn btn-primary"  type="submit">Publish</button>
	      	</td>
    	  </tr>
        
        </form>
        </td>
        <td>
        <form action="{{route('publish.cancel',[$user->id,$user->email])}}" method="get">
            @csrf
            <h6>cancel</h6>
              <input type="number" name="cancel" style="width: 50px;" value="1" required>
              <button class="btn btn-danger"  type="submit">cancel</button>
        </form>
        </td>
    </tr>
 	@endforeach
  </tbody>
</table>

<br><br>
<h2>PROJECTS THAT I HAVE APPROVED</h2>

<br><br>

<table class="table">
    <thead>
      <tr>
          <th scope="col">#ID</th>
          <th scope="col">Project title</th>
          <th scope="col">client email</th>
          <th scope="col">category</th>
          <th scope="col">Description</th>
          <th scope="col">deadline</th>
          <th scope="col">status</th>
          <th scope="col">rank</th>
          <th scope="col">forntend</th>
          <th scope="col">backend</th>
          <th scope="col">security</th>
          <th scope="col">ui_ux</th>  
      </tr>
    </thead>
    <tbody>
      @foreach( $projects as $project )
  
          <tr>
            <td>{{ $project->id }}</td>
            <td>{{ $project->email }}</td>
            <td>{{ $project->title }}</td>
            <td>{{ $project->category }}</td>
            <td>{{ $project->description }}</td>
            <td>{{ $project->deadline }}</td>
            <td>{{ $project->status }}</td>
            <td>{{ $project->rank }}</td>
            <td>{{ $project->frontend }}</td>
            <td>{{ $project->backend }}</td>
            <td>{{ $project->security }}</td>
            <td>{{ $project->ui_ux }}</td>
            <td>
                <form action="{{route('status.update',$project->id)}}" method="post">
                    @csrf
                    @method('PUT')
                    <input name="status" type="text" value="{{ $project->status }}" style="width: 50px;" />
                    <button class="btn btn-primary" type="submit">update status</button>
                </form>
            </td>
          </tr>
      @endforeach
    </tbody>

    <table class="table">
        <thead>
          <tr>
              <th scope="col">students names </th>  
          </tr>
        </thead>
        <tbody>
          @foreach( $projects as $project )
      
              <tr>
                <td></td>
                
              </tr>
          @endforeach
        </tbody>

{{-- <a class="btn btn-primary" href="{{ route('password') }}">Change Password</a> --}}
{{-- <a class="btn btn-danger" href="{{ route('logout') }}">Logout</a> --}}
<a class="btn btn-primary" href="{{ route('manager_login') }}">Login</a>
<a class="btn btn-danger" href="{{ route('manager_logout') }}">Logout</a>
    
@else
{{-- @endauth --}}
{{-- @guest --}}
<a class="btn btn-primary" href="{{ route('manager_login') }}">Login</a>

@endif
{{-- @endguest --}}
@endsection