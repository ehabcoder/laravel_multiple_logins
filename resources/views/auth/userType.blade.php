@extends('dashboard')
@section('content')
<main class="login-form">
    <div class="cotainer">
        <div class="row justify-content-center">
            <h3>Please choose the type of the email you want to register with</h3>
            @foreach($users as $user)
            <button class="btn btn-primary type"><a href="login/{{$user->type}}">{{$user->type}}</a></button>
            @endforeach
            <!-- we are certainly know that if we have member it will be only one wiht one email
                 so we do not need foreach for the members object
             -->
            <button class="btn btn-primary type"><a href="login/{{$member[0]->type}}">{{$member[0]->type}}</a></button>
            <!-- After we logged the user in we can use the login/{{type}}
                 To do whatever we want.
            -->
        </div>
        </div>
</main>
@endsection
