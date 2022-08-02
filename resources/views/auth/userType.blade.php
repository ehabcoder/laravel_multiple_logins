@extends('dashboard')
@include('flash-message')
@section('content')
<main class="login-form">
    <div class="cotainer">
        <div class="row justify-content-center">
            
            <h3>Please choose the type of the email you want to register with</h3>
            @foreach($users as $user)
            <button class="btn btn-primary type"><a href="login/{{$user->type}}">{{$user->type}}</a></button>    
            @endforeach
        </div>
        </div>
</main>
@endsection