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

<h1>approved projects</h1>

<table class="table">
  <thead>
    <tr>
	    <th scope="col">#ID</th>
        <th scope="col">#Client ID</th>
	    <th scope="col">Request Title</th>
        <th scope="col">category</th>
	    <th scope="col">Description</th>
	    
    </tr>
  </thead>
  <tbody>
  	@foreach($users as $user)
      <form action="{{url('/publish')}}" method="post">
          @csrf
          <tr>
	        <td>
                <input type="text" name="id" style="width: 50px;" value="{{ $user->id }}">  
            </td>
            <td>
                <input type="text" name="client_id" style="width: 50px;" value="{{ $user->client_id }}">  
            </td>
	        <td>
                <input type="text" name="title" style="width: 170px;" value="{{ $user->title }}">  
            </td>
            <td>
                <input type="text" name="category" style="width: 50px;" value="{{ $user->category }}">
            </td>
	        <td>
                <input type="text" name="description" style="width: 170px;" value="{{ $user->description }}">  
            </td>
            <td>
                <h8>frontend developer</h8>
                <input name="frontend" type="number" value="1" style="width: 50px;" />
            </td>
            <td>
                <h8>backend developer</h8>
                <input name="backend" type="number" value="1" style="width: 50px;"/>
            </td>
            <td>
                <h8>desginer</h8>
                <input name="designer" type="number" value="1" style="width: 50px;"/>
            </td>
            <td>
	      	    <button class="btn btn-primary"  type="submit">Publish</button>
	      	    <a class="btn btn-danger" href="" role="button">Cancel</a>
	        </td>
    	  </tr>
        
        </form>
    	
 	@endforeach
  </tbody>
</table>

<br><br>
<h2>PROJECTS THAT I HAVE APPROVED</h2>

<br><br>

<!-- <table class="table">
  <thead>
    <tr>
	    <th scope="col">#ID</th>
	    <th scope="col">client id</th>
        <th scope="col">project title</t>
        <th scope="col">category</th>
	    <th scope="col">Description</th>
        <th scope="col">frontend</t>
        <th scope="col">backend</t>
        <th scope="col">designer</t>
	    
    </tr>
  </thead>
  <tbody>
  	@foreach($users as $details)
    	<tr>
	      <td> {{ $details->id }}</td>
	      <td>{{ $details->client_id }}</td>
          <td>{{ $details->title }}</td>
          <td>{{ $details->category }}</td>
	      <td>{{ $details->description }}</td>
          <td></td>
          <td></td>
          <td></td>
	      <td>
	      	
	      </td>
    	</tr>
 	@endforeach
  </tbody>
</table>
 -->

@endsection
