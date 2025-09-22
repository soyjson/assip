<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ env('APP_NAME') }} - {{ env('APP_DESCRIPTION') }}</title>
    <meta name="description" content="Aplikasi {{ env('APP_DESCRIPTION') }}">
    <meta name="msapplication-tap-highlight" content="no">
    <link rel="shortcut icon" href="{{ asset('assets/image/logo/logo.png') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
    <link href="{{asset('plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{asset('plugins/datatables/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
    <style>
        .invalid-feedback {
            margin-bottom: 15px !important;
            line-height: 10px;
        }

        /* Make it a marquee */
        .marquee {
            margin: 0 auto;
            overflow: hidden;
            white-space: nowrap;
            box-sizing: border-box;
            animation: marquee 40s linear infinite;
        }

        .marquee:hover {
            animation-play-state: paused
        }

        /* Make it move */
        @keyframes marquee {
            0% {
                text-indent: 82.5em
            }

            100% {
                text-indent: -85em;
                /*transform: translateX(-66.6666%);*/
            }
        }
    </style>
</head>

<body>
    <div>
        <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top py-1 shadow-sm mb-4">
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
        <div class="container-fluid px-5 mb-3">
            <div class="row">
                <div class="col-md-8">
                    @if(Request::is('/') )
                    <div class="alert alert-warning py-3" role="alert">
                        <p class="marquee">Selamat Datang di {{ env('APP_NAME') }} - {{ env('APP_DESCRIPTION') }}</p>
                    </div>
                    @else
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                            <?php $link = "" ?>
                            @for($i = 1; $i <= count(Request::segments()); $i++) @if($i < count(Request::segments()) & $i> 0)
                                <?php $link .= "/" . Request::segment($i); ?>
                                <li class="breadcrumb-item"><a href="<?= url('/') . $link ?>">{{ ucwords(str_replace('-',' ',Request::segment($i)))}}</a></li>
                                @else
                                <li class="breadcrumb-item active" aria-current="page"><span class="d-inline-block text-truncate" style="max-width: 200px">{{ucwords(str_replace('-',' ',Request::segment($i)))}}</span></li>
                                @endif
                                @endfor
                        </ol>
                    </nav>
                    @endif
                    @yield('content')
                </div>
                <div class="col-md-4">
                    <div class="card card-body mt-3 shadow">
                        <h3 class="mb-4">Login</h3>
                        <form method="POST" action="{{ route('login') }}" novalidate>
                            @csrf
                            <div class="form-floating mb-4">
                                <input id="email" type="text" class="form-control rounded-4 {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="{{ __('E-mail/Username') }}" autofocus>
                                <label for="email">{{ __('E-mail/Username') }}</label>
                                @if ($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                                @endif
                            </div>

                            <div class="form-floating mb-4">
                                <input id="passwords" type="password" class="form-control rounded-4 {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Password') }}" name="password">
                                <label for="password">{{ __('Password') }}</label>
                                @if ($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                                @endif
                            </div>

                            <div class="form-group row mb-3">
                                <div class="col-md-6">
                                    <div class="checkbox">
                                        <!-- An element to toggle between password visibility -->
                                        <input class="form-check-input" type="checkbox" onclick="myPassword()" id="defaultCheck1">
                                        <label class="form-check-label" for="defaultCheck1">Lihat Password</label>
                                    </div>
                                    <!-- <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Ingatkan Saya') }}
                                        </label>
                                    </div> -->
                                </div>
                                <div class="col-md-6">
                                    <a class="float-end" href="{{ route('password.request') }}">
                                        {{ __('Lupa Password?') }}
                                    </a>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-dark bg-gradient text-light">
        <footer class="container-fluid px-5 py-5">
            <div class="row">
                <div class="col-md-6">
                    <h5>Menu</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="{{url('/')}}" class="nav-link p-0 text-light">Home</a></li>
                        <li class="nav-item mb-2"><a href="{{url('berita')}}" class="nav-link p-0 text-light">Berita</a></li>
                        <li class="nav-item mb-2"><a href="{{url('halaman-unduh')}}" class="nav-link p-0 text-light">Unduh</a></li>
                    </ul>
                </div>

                <div class="col-md-6">
                    <form>
                        <h5>Subscribe to our newsletter</h5>
                        <p>Monthly digest of whats new and exciting from us.</p>
                        <div class="d-flex w-100 gap-2">
                            <label for="newsletter1" class="visually-hidden">Email address</label>
                            <input id="newsletter1" type="text" class="form-control" placeholder="Email address">
                            <button class="btn btn-primary" type="button">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="d-flex justify-content-between pt-4 mt-4 border-top">
                <p>&copy; <?= date("Y"); ?> <strong>{{ env('CAMPUS_NAME') }}</strong> — {{ env('CAMPUS_ADDRESS') }}</p>
                <ul class="list-unstyled d-flex">
                    <li class="ms-3"><a class="text-info" href="#"><i class="fab fa-twitter-square fa-2x"></i></a></li>
                    <li class="ms-3"><a class="text-danger" href="#"><i class="fab fa-instagram-square fa-2x"></i></a></li>
                    <li class="ms-3"><a class="text-primary" href="#"><i class="fab fa-facebook-square fa-2x"></i></a></li>
                </ul>
            </div>
        </footer>
    </div>

    <script type="text/javascript" src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/datatables/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/datatables/js/dataTables.bootstrap5.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#table').DataTable();
        });

        function myPassword() {
            var x = document.getElementById("passwords");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
</body>

</html>