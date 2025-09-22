@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')
<div class="row">
    <div class="col-md-3">
        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    @if(Auth::user()->foto)
                    <img class="profile-user-img img-fluid img-circle" src="{{asset('/assets/image/foto')}}/{{Auth::user()->foto}}" alt="User profile picture {{Auth::user()->name}}">
                    @else
                    <img class="profile-user-img img-fluid img-circle" src="{{asset('/assets/image/foto/default.png')}}" alt="Foto Default">
                    @endif
                </div>

                <h3 class="profile-username text-center">{{Auth::user()->name}}</h3>

                <p class="text-muted text-center">{{Auth::user()->jafung}}</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Username</b> <a class="float-right">{{Auth::user()->username}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Email</b> <a class="float-right">{{Auth::user()->email}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Password</b> <a class="float-right">{{Auth::user()->password_view}}</a>
                    </li>
                </ul>

                <button type="submit" class="btn btn-primary btn-block" data-toggle="modal" data-target="#mediumModal{{Auth::user()->id}}"><i class="fa fa-check-square-o"></i> Edit Account</button>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <div class="col-md-9">
        <!-- About Me Box -->
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#tentang" data-toggle="tab">Tentang Saya</a></li>
                    <li class="nav-item"><a class="nav-link" href="#histori" data-toggle="tab">Log Akses</a></li>
                </ul>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="active tab-pane" id="tentang">
                        NIDN
                        <p class="text-muted">{{Auth::user()->nidn}}</p>

                        NIK
                        <p class="text-muted">{{Auth::user()->nik}}</p>

                        Nama lengkap
                        <p class="text-muted">{{Auth::user()->name}}</p>

                        Jabatan Fungsional
                        <p class="text-muted">{{Auth::user()->jafung}}</p>

                        Program Studi
                        <p class="text-muted">{{Auth::user()->program_studi}}</p>

                        Fakultas
                        <p class="text-muted">{{Auth::user()->fakultas}}</p>

                        Institusi
                        <p>{{Auth::user()->institusi}}</p>

                        Nomor Handphone
                        <p class="text-muted">{{Auth::user()->no_handphone}}</p>
                    </div>

                    <div class="tab-pane" id="histori">
                        <div class="table-responsive">
                            <table id="example" class="mb-0 table table-sm table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($histori as $key => $value)
                                    <tr>
                                        <td align="center">{{$key+1}}</td>
                                        <td align="center">{{$value->name}}</td>
                                        <td align="center">{{date_format(date_create($value->created_at),"d F Y")}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@endsection

@section('modal')
@foreach ($data as $row)
{!! Form::model($row, ['files'=>true, 'url' => ['/update-account', Auth::user()->id]]) !!}
<div class="modal fade" id="mediumModal{{Auth::user()->id}}" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Edit Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Data Login</h5>
                <hr>
                <div class="form-group row">
                    <label for="foto" class="col-md-4 col-form-label text-md-right">{{ __('Foto*') }} (JPG/ PNG)<br>
                        <h6 style="color:red; font-size: 10px;">Maks. 2 MB</h6>
                    </label>
                    <div class="col-md-4">
                        <input onchange="readFoto(event)" name="foto" id="foto" type="file" class="form-control{{ $errors->has('foto') ? ' is-invalid' : '' }}" foto="foto">
                        <img id='output' style="width: 100px;">
                        @if ($errors->has('foto'))
                        <div class="invalid-feedback">
                            {{ $errors->first('foto') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                    <div class="col-md-5">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ Auth::user()->email }}">

                        @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username *') }}</label>
                    <div class="col-md-5">
                        <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ Auth::user()->username }}" required autofocus>

                        @if ($errors->has('username'))
                        <div class="invalid-feedback">
                            {{ $errors->first('username') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password*') }}</label>

                    <div class="col-md-5">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" value="{{Auth::user()->password_view}}" name="password" required>

                        @if ($errors->has('password'))
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="no_handphone" class="col-md-4 col-form-label text-md-right">{{ __('Nomor Handphone*') }}</label>
                    <div class="col-md-5">
                        <input id="no_handphone" type="number" min="0" class="form-control{{ $errors->has('no_handphone') ? ' is-invalid' : '' }}" name="no_handphone" value="{{Auth::user()->no_handphone}}" required autofocus>

                        @if ($errors->has('no_handphone'))
                        <div class="invalid-feedback">
                            {{ $errors->first('no_handphone') }}
                        </div>
                        @endif
                    </div>
                </div>

                <h5>Data Diri</h5>
                <hr>
                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nama Lengkap *') }}</label>
                    <div class="col-md-5">
                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ Auth::user()->name }}" required autofocus>

                        @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nidn" class="col-md-4 col-form-label text-md-right">{{ __('NIDN*') }}</label>
                    <div class="col-md-5">
                        <input id="nidn" type="number" min="0" class="form-control{{ $errors->has('nidn') ? ' is-invalid' : '' }}" name="nidn" value="{{Auth::user()->nidn}}" required autofocus>

                        @if ($errors->has('nidn'))
                        <div class="invalid-feedback">
                            {{ $errors->first('nidn') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nik" class="col-md-4 col-form-label text-md-right">{{ __('NIK*') }}</label>
                    <div class="col-md-5">
                        <input id="nik" type="number" min="0" class="form-control{{ $errors->has('nik') ? ' is-invalid' : '' }}" name="nik" value="{{Auth::user()->nik}}" required autofocus>

                        @if ($errors->has('nik'))
                        <div class="invalid-feedback">
                            {{ $errors->first('nik') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="jafung" class="col-md-4 col-form-label text-md-right">{{ __('Jabatan Fungsional*') }}</label>
                    <div class="col-md-5">
                        <input id="jafung" type="text" class="form-control{{ $errors->has('jafung') ? ' is-invalid' : '' }}" name="jafung" value="{{Auth::user()->jafung}}" required autofocus>

                        @if ($errors->has('jafung'))
                        <div class="invalid-feedback">
                            {{ $errors->first('jafung') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="program_studi" class="col-md-4 col-form-label text-md-right">{{ __('Program Studi*') }}</label>
                    <div class="col-md-5">
                        <input id="program_studi" type="text" class="form-control{{ $errors->has('program_studi') ? ' is-invalid' : '' }}" name="program_studi" value="{{Auth::user()->program_studi}}" required autofocus>

                        @if ($errors->has('program_studi'))
                        <div class="invalid-feedback">
                            {{ $errors->first('program_studi') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="fakultas" class="col-md-4 col-form-label text-md-right">{{ __('Fakultas*') }}</label>
                    <div class="col-md-5">
                        <input id="fakultas" type="text" class="form-control{{ $errors->has('fakultas') ? ' is-invalid' : '' }}" name="fakultas" value="{{Auth::user()->fakultas}}" required autofocus>

                        @if ($errors->has('fakultas'))
                        <div class="invalid-feedback">
                            {{ $errors->first('fakultas') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="institusi" class="col-md-4 col-form-label text-md-right">{{ __('Institusi*') }}</label>
                    <div class="col-md-5">
                        <input id="institusi" type="text" class="form-control{{ $errors->has('institusi') ? ' is-invalid' : '' }}" name="institusi" value="{{Auth::user()->institusi}}" required autofocus>

                        @if ($errors->has('institusi'))
                        <div class="invalid-feedback">
                            {{ $errors->first('institusi') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="level" class="col-md-4 col-form-label text-md-right">{{ __('Akses*') }}</label>
                    <div class="col-md-3">
                        <select class="form-control" name="level" type="text">
                            <option value="{{Auth::user()->level}}">- Pilih -</option>
                            <option value="4">Dosen</option>
                            <option value="5">Reviewer</option>
                        </select>

                        @if ($errors->has('level'))
                        <div class="invalid-feedback">
                            {{ $errors->first('level') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
{!!Form::close()!!}
@endforeach
@endsection