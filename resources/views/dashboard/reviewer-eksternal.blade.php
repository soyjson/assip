@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-body">
                <button type="button" class="btn mr-2 mb-3 btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Tambah</button>
                <br>
                <div class="table-responsive">
                    <table id="example" class="mb-0 table table-sm table-bordered table-hover table-striped w-100 d-block d-md-table">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center" width="10%">Foto</th>
                                <th class="text-center">NIK</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">HP</th>
                                <th class="text-center">Jafung</th>
                                <th class="text-center">Institusi</th>
                                <th class="text-center">Fakultas</th>
                                <th class="text-center">Prodi</th>
                                <th class="text-center">Username</th>
                                <th class="text-center">Password</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $value)
                            <tr>
                                <th scope="row" class="text-center">{{$key+1}}</th>
                                <td align="center">
                                    @if($value->foto)
                                    <img src="{{asset('/assets/image/foto')}}/{{$value->foto}}" width="50" class="img-size-50" alt="Foto">
                                    @else
                                    <img src="{{asset('/assets/image/foto/default.png')}}" width="50" class="img-size-50" alt="Foto">
                                    @endif
                                </td>
                                <td align="center">{{$value->nik}}</td>
                                <td>{{$value->name}}</td>
                                <td>{{$value->email}}</td>
                                <td>{{$value->no_handphone}}</td>
                                <td>{{$value->jafung}}</td>
                                <td>{{$value->institusi}}</td>
                                <td>{{$value->fakultas}}</td>
                                <td>{{$value->program_studi}}</td>
                                <td>{{$value->username}}</td>
                                <td>{{$value->password_view}}</td>
                                <td align="center"><button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#largeModal{{$value->id}}"> <i class="fa fa-edit"></i></button>
                                    <a title="Hapus" href="#" type="button" class="btn btn-danger btn-sm tooltipku" data-toggle="modal" data-target="#myModal1{{$value->id}}"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Tambah <?= $title; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open(['files'=>true, 'url' => ['/simpan-reviewer-eksternal']]) !!}
                @csrf
                <h5>Data Login</h5>
                <hr>
                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                    <div class="col-md-5">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}">

                        @if ($errors->has('email'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username *') }}</label>
                    <div class="col-md-5">
                        <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username')}}" required autofocus>

                        @if ($errors->has('username'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password*') }}</label>

                    <div class="col-md-5">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                        @if ($errors->has('password'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password*') }}</label>

                    <div class="col-md-5">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>
                <h5>Data Diri</h5>
                <hr>
                <div class="form-group row">
                    <label for="foto" class="col-md-4 col-form-label text-md-right">{{ __('Foto*') }} (JPG/ PNG)</label>
                    <div class="col-md-4">
                        <input onchange="readFoto(event)" name="foto" id="foto" type="file" class="form-control{{ $errors->has('foto') ? ' is-invalid' : '' }}" foto="foto" value="{{ old('foto') }}" required autofocus>
                        <img id='output' style="width: 100px;">
                        @if ($errors->has('foto'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('foto') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nama Lengkap *') }}</label>
                    <div class="col-md-5">
                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name')}}" required autofocus>

                        @if ($errors->has('name'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nik" class="col-md-4 col-form-label text-md-right">{{ __('NIK*') }}</label>
                    <div class="col-md-5">
                        <input id="nik" type="number" min="0" class="form-control{{ $errors->has('nik') ? ' is-invalid' : '' }}" name="nik" value="{{ old('nik')}}" required autofocus>

                        @if ($errors->has('nik'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('nik') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="jafung" class="col-md-4 col-form-label text-md-right">{{ __('Jabatan Fungsional*') }}</label>
                    <div class="col-md-5">
                        <input id="jafung" type="text" class="form-control{{ $errors->has('jafung') ? ' is-invalid' : '' }}" name="jafung" value="{{ old('jafung')}}" required autofocus>

                        @if ($errors->has('jafung'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('jafung') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="institusi" class="col-md-4 col-form-label text-md-right">{{ __('Institusi*') }}</label>
                    <div class="col-md-5">
                        <input id="institusi" type="text" class="form-control{{ $errors->has('institusi') ? ' is-invalid' : '' }}" name="institusi" value="{{ old('institusi')}}" required autofocus>

                        @if ($errors->has('institusi'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('institusi') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="fakultas" class="col-md-4 col-form-label text-md-right">{{ __('Fakultas*') }}</label>
                    <div class="col-md-5">
                        <input id="fakultas" type="text" class="form-control{{ $errors->has('fakultas') ? ' is-invalid' : '' }}" name="fakultas" value="{{ old('fakultas')}}" required autofocus>

                        @if ($errors->has('fakultas'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('fakultas') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="program_studi" class="col-md-4 col-form-label text-md-right">{{ __('Program Studi*') }}</label>
                    <div class="col-md-5">
                        <input id="program_studi" type="text" class="form-control{{ $errors->has('program_studi') ? ' is-invalid' : '' }}" name="program_studi" value="{{ old('program_studi')}}" required autofocus>

                        @if ($errors->has('program_studi'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('program_studi') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="no_handphone" class="col-md-4 col-form-label text-md-right">{{ __('Nomor Handphone*') }}</label>
                    <div class="col-md-5">
                        <input id="no_handphone" type="number" min="0" class="form-control{{ $errors->has('no_handphone') ? ' is-invalid' : '' }}" name="no_handphone" value="{{ old('no_handphone')}}" required autofocus>

                        @if ($errors->has('no_handphone'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('no_handphone') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            {!!Form::close()!!}
        </div>
    </div>
</div>
@foreach($data as $row)
<div class="modal fade" id="myModal1{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                Hapus Data ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a title="Hapus" href="{!! url('/'.$row->id.'/delete-reviewer-eksternal') !!}" class="btn btn-danger"><i class="fa fa-trash"></i> Ok</a>
            </div>
        </div>
    </div>
</div>
@endforeach
@foreach ($data as $row)
{!! Form::model($row, ['url' => ['/update-reviewer-eksternal', $row->id]]) !!}
<div class="modal fade" id="largeModal{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel"><i class="fa fa-reviewer-eksternal"></i> Edit <?= $title; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Data Login</h5>
                <hr>
                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                    <div class="col-md-5">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{$row->email }}">

                        @if ($errors->has('email'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username *') }}</label>
                    <div class="col-md-5">
                        <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{$row->username }}" required autofocus>

                        @if ($errors->has('username'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password*') }}</label>

                    <div class="col-md-5">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">

                        @if ($errors->has('password'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password*') }}</label>

                    <div class="col-md-5">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                    </div>
                </div>
                <h5>Data Diri</h5>
                <hr>
                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nama Lengkap *') }}</label>
                    <div class="col-md-5">
                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{$row->name }}" required autofocus>

                        @if ($errors->has('name'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nik" class="col-md-4 col-form-label text-md-right">{{ __('NIK*') }}</label>
                    <div class="col-md-5">
                        <input id="nik" type="number" min="0" class="form-control{{ $errors->has('nik') ? ' is-invalid' : '' }}" name="nik" value="{{$row->nik}}" required autofocus>

                        @if ($errors->has('nik'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('nik') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="jafung" class="col-md-4 col-form-label text-md-right">{{ __('Jabatan Fungsional*') }}</label>
                    <div class="col-md-5">
                        <input id="jafung" type="text" class="form-control{{ $errors->has('jafung') ? ' is-invalid' : '' }}" name="jafung" value="{{$row->jafung}}" required autofocus>

                        @if ($errors->has('jafung'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('jafung') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="institusi" class="col-md-4 col-form-label text-md-right">{{ __('Institusi*') }}</label>
                    <div class="col-md-5">
                        <input id="institusi" type="text" class="form-control{{ $errors->has('institusi') ? ' is-invalid' : '' }}" name="institusi" value="{{$row->institusi}}" required autofocus>

                        @if ($errors->has('institusi'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('institusi') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="fakultas" class="col-md-4 col-form-label text-md-right">{{ __('Fakultas*') }}</label>
                    <div class="col-md-5">
                        <input id="fakultas" type="text" class="form-control{{ $errors->has('fakultas') ? ' is-invalid' : '' }}" name="fakultas" value="{{$row->fakultas}}" required autofocus>

                        @if ($errors->has('fakultas'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('fakultas') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="program_studi" class="col-md-4 col-form-label text-md-right">{{ __('Program Studi*') }}</label>
                    <div class="col-md-5">
                        <input id="program_studi" type="text" class="form-control{{ $errors->has('program_studi') ? ' is-invalid' : '' }}" name="program_studi" value="{{$row->program_studi}}" required autofocus>

                        @if ($errors->has('program_studi'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('program_studi') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="no_handphone" class="col-md-4 col-form-label text-md-right">{{ __('Nomor Handphone*') }}</label>
                    <div class="col-md-5">
                        <input id="no_handphone" type="number" min="0" class="form-control{{ $errors->has('no_handphone') ? ' is-invalid' : '' }}" name="no_handphone" value="{{$row->no_handphone}}" required autofocus>

                        @if ($errors->has('no_handphone'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('no_handphone') }}</strong>
                        </span>
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
{!!Form::close()!!}
@endforeach
@endsection