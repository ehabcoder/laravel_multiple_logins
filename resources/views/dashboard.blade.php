<!DOCTYPE html>
<html>
<head>
    <title>Multiple Logins</title>
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        .type a {
            color: #fff;
            text-decoration: none;
        }
        .type {
            margin: 1rem;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-light navbar-expand-lg mb-5" style="background-color: #e3f2fd;">
        <div class="container">
            <a class="navbar-brand mr-auto" href="#">Multiple Logins</a>

            <div id="navbarNav">
                <ul class="navbar-nav">
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register-orchestra') }}">Register Orchastra</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register-musician') }}">Register Musician</a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('signout') }}">Logout</a>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')
</body>
</html>
