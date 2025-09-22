@extends('layouts.app-auth')
@section('content')
<div class="card rounded-4 shadow">
    <div class="card-header p-5 pb-4 border-bottom-0">
        <h2 class="fw-bold mb-0">{{ __('Reset Password') }}</h2>
    </div>
    <div class="card-body p-5 pt-0">
        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-floating mb-4">
                <input id="email" type="email" class="form-control rounded-4 {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="{{ __('Alamat E-mail anda') }}" required>
                <label for="email">{{ __('Alamat e-mail anda') }}</label>
                @if ($errors->has('email'))
                <div class="invalid-feedback">
                    {{ $errors->first('email') }}
                </div>
                @endif
            </div>
            <div class="form-group mb-4">
                <button type="submit" class="btn btn-primary">
                    {{ __('Kirim Email Atur Ulang Kata Sandi') }}
                </button>
            </div>
            <hr class="my-4">
            <div class="form-group row mb-3">
                <a href="{{ route('login') }}">
                    {{ __('Kembali ke Login') }}
                </a>
            </div>
        </form>
    </div>
</div>

@endsection