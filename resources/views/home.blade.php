@extends('layouts.app')
@extends('layouts.alert')
@section('content')
<!-- Jumbotron -->
<div id="intro" class="p-5 text-center" style="background-image: url('./assets/image/body/4117072.jpg') !important;background-position: center;background-repeat: no-repeat;-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;">
    <div class="mask" style="background-color: rgba(0, 0, 0, 0.4);">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="text-white">
                <h1 class="mb-0">Pendaftaran Akun</h1>
            </div>
        </div>
    </div>
</div>
<!-- Jumbotron -->
<div class="card mb-3">
    <div class="card-body">
        {!! Form::open(['files'=>true, 'url' => ['/register'], 'class' => 'needs-validation', 'novalidate' => 'novalidate']) !!}
        @csrf
        <div class="row">
            <div class="col-md-6">
                <h4 class="mb-3">Informasi Saya</h4>
                <div class="form-outline mb-4">
                    <input id="name" type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name" value="{{ old('name') }}" required>
                    <label for="name" class="form-label">{{ __('Nama dan Gelar*') }}</label>
                    @if ($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                    @endif
                </div>
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="form-outline mb-4">
                            <input id="nidn" type="number" min="0" class="form-control {{ $errors->has('nidn') ? 'is-invalid' : '' }}" name="nidn" value="{{ old('nidn') }}" required>
                            <label for="nidn" class="form-label">{{ __('NIDN*') }}</label>
                            @if ($errors->has('nidn'))
                            <div class="invalid-feedback">
                                {{ $errors->first('nidn') }}
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-outline mb-4">
                            <input id="nik" type="number" min="0" class="form-control {{ $errors->has('nik') ? 'is-invalid' : '' }}" name="nik" value="{{ old('nik') }}" required>
                            <label for="nik" class="form-label">{{ __('NIK*') }}</label>
                            @if ($errors->has('nik'))
                            <div class="invalid-feedback">
                                {{ $errors->first('nik') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-outline mb-4">
                    <input id="jafung" type="text" class="form-control {{ $errors->has('jafung') ? 'is-invalid' : '' }}" name="jafung" value="{{ old('jafung') }}">
                    <label for="jafung" class="form-label">{{ __('Jabatan Fungsional') }}</label>
                    @if ($errors->has('jafung'))
                    <div class="invalid-feedback">
                        {{ $errors->first('jafung') }}
                    </div>
                    @endif
                </div>
                <div class="form-outline mb-4">
                    <input id="institusi" type="text" min="0" class="form-control {{ $errors->has('institusi') ? ' is-invalid' : '' }}" name="institusi" value="" required>
                    <label for="institusi" class="form-label">{{ __('Institusi*') }}</label>
                    @if ($errors->has('institusi'))
                    <div class="invalid-feedback">
                        {{ $errors->first('institusi') }}
                    </div>
                    @endif
                </div>
                <div class="form-outline mb-4">
                    <input id="fakultas" type="text" class="form-control {{ $errors->has('fakultas') ? ' is-invalid' : '' }}" name="fakultas" value="{{ old('fakultas') }}" required>
                    <label for="fakultas" class="form-label">{{ __('Fakultas*') }}</label>
                    @if ($errors->has('fakultas'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fakultas') }}
                    </div>
                    @endif
                </div>
                <div class="form-outline mb-4">
                    <input id="program_studi" type="text" class="form-control {{ $errors->has('program_studi') ? 'is-invalid' : '' }}" name="program_studi" value="{{ old('program_studi') }}" required>
                    <label for="program_studi" class="form-label">{{ __('Program Studi*') }}</label>
                    @if ($errors->has('program_studi'))
                    <div class="invalid-feedback">
                        {{ $errors->first('program_studi') }}
                    </div>
                    @endif
                </div>
                <div class="form-group mb-1">
                    <label for="foto" class="form-label">{{ __('Foto*') }}
                        <span style="color:red; font-size: 10px;">JPG/ PNG Maks. 2 MB</span>
                    </label>
                    <input onchange="readFoto(event)" name="foto" id="foto" type="file" class="form-control {{ $errors->has('foto') ? 'is-invalid' : '' }}" foto="foto" value="{{ old('foto') }}" required>
                    @if ($errors->has('foto'))
                    <div class="invalid-feedback">
                        {{ $errors->first('foto') }}
                    </div>
                    @endif
                    <img id='output' class="mb-0" style="width: 100px;">
                </div>
            </div>

            <div class="col-md-6">
                <h4 class="mb-3">Informasi Akun</h4>
                <div class="form-outline mb-4">
                    <input id="email" type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                    <label for="email" class="form-label">{{ __('E-mail*') }}</label>
                    @if ($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                    @endif
                </div>
                <div class="form-outline mb-4">
                    <input id="no_handphone" type="number" min="0" class="form-control {{ $errors->has('no_handphone') ? ' is-invalid' : '' }}" name="no_handphone" value="{{ old('no_handphone') }}" required>
                    <label for="no_handphone" class="form-label">{{ __('Nomor HP*') }}</label>
                    @if ($errors->has('no_handphone'))
                    <div class="invalid-feedback">
                        {{ $errors->first('no_handphone') }}
                    </div>
                    @endif
                </div>
                <div class="form-group mb-4">
                    <label for="password-confirm" class="form-label">{{ __('Akses*') }}</label>
                    <select class="form-select" name="level">
                        <option value="">-- Pilih --</option>
                        <option value="4">Dosen</option>
                        <option value="5">Reviewer</option>
                    </select>
                    @if ($errors->has('level'))
                    <div class="invalid-feedback">
                        {{ $errors->first('level') }}
                    </div>
                    @endif
                </div>
                <div class="form-outline mb-4">
                    <input id="username" type="text" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" name="username" value="{{ old('username') }}" required>
                    <label for="username" class="form-label">{{ __('Username*') }}</label>
                    @if ($errors->has('username'))
                    <span class="invalid-feedback">
                        {{ $errors->first('username') }}
                    </span>
                    @endif
                </div>
                <div class="form-outline mb-4">
                    <input id="password" type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" name="password" required>
                    <label for="password" class="form-label">{{ __('Password*') }}</label>
                    @if ($errors->has('password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                    @endif
                </div>
                <div class="form-outline mb-4">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    <label for="password-confirm" class="form-label">{{ __('Password Ulangi*') }}</label>
                </div>
                <p class="mb-0" style="font-size:11px;"><i class="fas fa-info-circle"></i> Daftarkan dengan alamat E-mail yang valid. Buatlah Password yang Aman, mudah diingat namun sulit ditebak orang lain.</p>
            </div>
            <div class="mb-2">
                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    {{ __('Daftar') }}
                </button>
            </div>
            <p class="small">Dengan melakukan pendaftaran, saya setuju dengan <a href="#" title="Kebijakan Privasi" alt="Kebijakan Privasi">Kebijakan Privasi</a> dan <a href="#" title="Syarat & Ketentuan" alt="Syarat & Ketentuan">Syarat & Ketentuan</a></p>
            {!!Form::close()!!}
        </div>
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
</script>
@endsection