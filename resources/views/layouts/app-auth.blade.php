<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ env('APP_NAME') }} - {{ env('APP_DESCRIPTION') }}</title>
    <meta name="description" content="{{ env('APP_DESCRIPTION') }}">
    <meta name="msapplication-tap-highlight" content="no">
    <link rel="shortcut icon" href="{{ asset('assets/image/logo/logo.png') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
    <link href="{{asset('plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" />
    <style>
        .invalid-feedback {
            margin-bottom: 15px !important;
            line-height: 10px;
        }
    </style>
</head>

<body>
    <div class="bg-dark bg-image" style="background-image: url('https://picsum.photos/910/600?random') !important;background-position: center;background-repeat: no-repeat;-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;min-height: 100vh;">
        <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top py-1 shadow-sm">
            <div class="container-fluid px-5">
                <a href="{{url('/')}}" class="navbar-brand">
                    <!--<img src="{{asset('assets/image/logo/logo.png')}}" class="float-start me-2" height="40">-->
                    <h6><span class="h4 fw-bold">{{ env('APP_NAME') }}</span><br /><span style="font-size: small;font-weight:400;">{{ env('APP_HEADER') }}</span></h6>
                </a>

                <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-bars fa-lg"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link me-3 {{Request::is('/*') ? 'active' : ''}}" aria-current="page" href="{{url('/')}}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link me-3 {{Request::is('berita*') ? 'active' : ''}}" href="{{url('berita')}}">Berita</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link me-3 {{Request::is('halaman-unduh*') ? 'active' : ''}}" href="{{url('halaman-unduh')}}">Unduh</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-primary {{Request::is('login*') ? 'active' : ''}}" href="{{url('login')}}"><i class="fas fa-sign-in-alt"></i> Login</a>
                        </li>
                    </ul>
                    <form class="form-inline my-1 my-lg-0">

                    </form>
                </div>
            </div>
        </nav>

        <div class="container-fluid pt-5">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
</body>

</html>