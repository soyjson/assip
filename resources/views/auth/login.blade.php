@extends('layouts.app-auth')
@extends('layouts.alert')
@section('content')
<div class="card card-content rounded-4 shadow">
    <div class="card-header p-5 pb-4 border-bottom-0">
        <h2 class="fw-bold mb-0">Login</h2>
    </div>

    <div class="card-body p-5 pt-0">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-floating mb-4">
                <input id="email" type="text" class="form-control rounded-4 {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="{{ __('E-mail/Username') }}" required autofocus>
                <label for="email">{{ __('E-mail/Username') }}</label>
                @if ($errors->has('email'))
                <div class="invalid-feedback">
                    {{ $errors->first('email') }}
                </div>
                @endif
            </div>

            <div class="form-floating mb-4">
                <input id="password" type="password" class="form-control rounded-4 {{ $errors->has('password') ? 'is-invalid' : '' }}" name="password" placeholder="{{ __('Password') }}" required>
                <label for="password">{{ __('Password') }}</label>
                @if ($errors->has('password'))
                <div class="invalid-feedback">
                    {{ $errors->first('password') }}
                </div>
                @endif
            </div>
            <div class="form-group mb-3">
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
            <button class="w-100 mb-2 btn btn-lg rounded-4 btn-primary" type="submit">{{ __('Login') }}</button>
            <p class="small text-muted" style="font-size: 11px;">Dengan mengklik Login, Anda menyetujui persyaratan penggunaan.</p>
            <hr class="my-4">
            <div class="form-group row mb-3">
                <a href="{{ route('password.request') }}">
                    {{ __('Lupa Password?') }}
                </a>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    var readFoto = function(event) {
        var input = event.target;

        var reader = new FileReader();
        reader.onload = function() {
            var dataURL = reader.result;
            var output = document.getElementById('output');
            output.src = dataURL;
        };
        reader.readAsDataURL(input.files[0]);
    };

    function myPassword() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>
@endsection