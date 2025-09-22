@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-body">
                <button type="button" class="btn mr-2 mb-3 btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Tambah User</button>
                <br>
                <div class="table-responsive">
                    <table id="example" class="mb-0 table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center" width="10%">Foto</th>
                                <th class="text-center">NIK</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">No. Handphone</th>
                                <th class="text-center">Jafung</th>
                                <th class="text-center">Fakultas</th>
                                <th class="text-center">Program Studi</th>
                                <th class="text-center">Username</th>
                                <th class="text-center">Password</th>
                                <th class="text-center">Level</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $value)
                            <tr>
                                <th scope="row">{{$key+1}}</th>
                                <td>
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
                                <td>{{$value->fakultas}}</td>
                                <td>{{$value->program_studi}}</td>
                                <td>{{$value->username}}</td>
                                <td>{{$value->password_view}}</td>
                                <td align="center">
                                    @if($value->level == 1)
                                    Admin
                                    @elseif($value->level == 2)
                                    Pimpinan
                                    @elseif($value->level == 3)
                                    Operator
                                    @elseif($value->level == 4)
                                    Dosen
                                    @elseif($value->level == 5)
                                    Reviewer
                                    @endif
                                </td>
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
<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>
@endsection

@section('modal')
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Tambah User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open(['files'=>true, 'url' => ['/simpan-manajemen-user']]) !!}
                @csrf
                <h5>Data Login</h5>
                <hr>
                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-mail') }}</label>

                    <div class="col-md-5">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}">

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
                        <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username')}}" required autofocus>

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
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                        @if ($errors->has('password'))
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password*') }}</label>

                    <div class="col-md-5">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="level" class="col-md-4 col-form-label text-md-right">{{ __('Level *') }}</label>
                    <div class="col-md-5">
                        <select id="level" type="text" class="form-control{{ $errors->has('level') ? ' is-invalid' : '' }}" name="level" value="{{ old('level')}}" required autofocus>
                            <option>-- Pilih Hak Akses --</option>
                            <option value="1">Admin</option>
                            <option value="2">Pimpinan</option>
                            <option value="3">Operator</option>
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
                <h5>Data Diri</h5>
                <hr>
                <div class="form-group row">
                    <label for="foto" class="col-md-4 col-form-label text-md-right">{{ __('Foto*') }} (JPG/ PNG)</label>
                    <div class="col-md-4">
                        <input onchange="readFoto(event)" name="foto" id="foto" type="file" class="form-control{{ $errors->has('foto') ? ' is-invalid' : '' }}" foto="foto" value="{{ old('foto') }}" required autofocus>
                        <img id='output' style="width: 100px;">
                        @if ($errors->has('foto'))
                        <div class="invalid-feedback">
                            {{ $errors->first('foto') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nama Lengkap *') }}</label>
                    <div class="col-md-5">
                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name')}}" required autofocus>

                        @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nik" class="col-md-4 col-form-label text-md-right">{{ __('NIK*') }}</label>
                    <div class="col-md-5">
                        <input id="nik" type="number" min="0" class="form-control{{ $errors->has('nik') ? ' is-invalid' : '' }}" name="nik" value="{{ old('nik')}}" required autofocus>

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
                        <input id="jafung" type="text" class="form-control{{ $errors->has('jafung') ? ' is-invalid' : '' }}" name="jafung" value="{{ old('jafung')}}" required autofocus>

                        @if ($errors->has('jafung'))
                        <div class="invalid-feedback">
                            {{ $errors->first('jafung') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="fakultas" class="col-md-4 col-form-label text-md-right">{{ __('Fakultas*') }}</label>
                    <div class="col-md-5">
                        <input id="fakultas" type="text" class="form-control{{ $errors->has('fakultas') ? ' is-invalid' : '' }}" name="fakultas" value="{{ old('fakultas')}}" required autofocus>

                        @if ($errors->has('fakultas'))
                        <div class="invalid-feedback">
                            {{ $errors->first('fakultas') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="program_studi" class="col-md-4 col-form-label text-md-right">{{ __('Program Studi*') }}</label>
                    <div class="col-md-5">
                        <input id="program_studi" type="text" class="form-control{{ $errors->has('program_studi') ? ' is-invalid' : '' }}" name="program_studi" value="{{ old('program_studi')}}" required autofocus>

                        @if ($errors->has('program_studi'))
                        <div class="invalid-feedback">
                            {{ $errors->first('program_studi') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="no_handphone" class="col-md-4 col-form-label text-md-right">{{ __('Nomor Handphone*') }}</label>
                    <div class="col-md-5">
                        <input id="no_handphone" type="number" min="0" class="form-control{{ $errors->has('no_handphone') ? ' is-invalid' : '' }}" name="no_handphone" value="{{ old('no_handphone')}}" required autofocus>

                        @if ($errors->has('no_handphone'))
                        <div class="invalid-feedback">
                            {{ $errors->first('no_handphone') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
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
                <a title="Hapus" href="{!! url('/'.$row->id.'/delete-manajemen-user') !!}" class="btn btn-danger"><i class="fa fa-trash"></i> Ok</a>
            </div>
        </div>
    </div>
</div>
@endforeach
@foreach ($data as $row)
{!! Form::model($row, ['url' => ['/update-manajemen-user', $row->id]]) !!}
<div class="modal fade" id="largeModal{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Edit User</h5>
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
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $row->email }}">

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
                        <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ $row->username }}" required autofocus>

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
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{ $row->password_view }}" required>

                        @if ($errors->has('password'))
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="level" class="col-md-4 col-form-label text-md-right">{{ __('Level *') }}</label>
                    <div class="col-md-5">
                        <select id="level" type="text" class="form-control{{ $errors->has('level') ? ' is-invalid' : '' }}" name="level" required autofocus>
                            <option>-- Pilih Hak Akses --</option>
                            <option value="1" <?php if ($row->level == 1) {
                                                    echo "selected";
                                                } ?>>Admin</option>
                            <option value="2" <?php if ($row->level == 2) {
                                                    echo "selected";
                                                } ?>>Pimpinan</option>
                            <option value="3" <?php if ($row->level == 3) {
                                                    echo "selected";
                                                } ?>>Operator</option>
                            <option value="4" <?php if ($row->level == 4) {
                                                    echo "selected";
                                                } ?>>Dosen</option>
                            <option value="5" <?php if ($row->level == 5) {
                                                    echo "selected";
                                                } ?>>Reviewer</option>
                        </select>

                        @if ($errors->has('level'))
                        <div class="invalid-feedback">
                            {{ $errors->first('level') }}
                        </div>
                        @endif
                    </div>
                </div>
                <h5>Data Diri</h5>
                <hr>
                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nama Lengkap *') }}</label>
                    <div class="col-md-5">
                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $row->name }}" required autofocus>

                        @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nik" class="col-md-4 col-form-label text-md-right">{{ __('NIK*') }}</label>
                    <div class="col-md-5">
                        <input id="nik" type="number" min="0" class="form-control{{ $errors->has('nik') ? ' is-invalid' : '' }}" name="nik" value="{{$row->nik}}" required autofocus>

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
                        <input id="jafung" type="text" class="form-control{{ $errors->has('jafung') ? ' is-invalid' : '' }}" name="jafung" value="{{$row->jafung}}" required autofocus>

                        @if ($errors->has('jafung'))
                        <div class="invalid-feedback">
                            {{ $errors->first('jafung') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="fakultas" class="col-md-4 col-form-label text-md-right">{{ __('Fakultas*') }}</label>
                    <div class="col-md-5">
                        <input id="fakultas" type="text" class="form-control{{ $errors->has('fakultas') ? ' is-invalid' : '' }}" name="fakultas" value="{{$row->fakultas}}" required autofocus>

                        @if ($errors->has('fakultas'))
                        <div class="invalid-feedback">
                            {{ $errors->first('fakultas') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="program_studi" class="col-md-4 col-form-label text-md-right">{{ __('Program Studi*') }}</label>
                    <div class="col-md-5">
                        <input id="program_studi" type="text" class="form-control{{ $errors->has('program_studi') ? ' is-invalid' : '' }}" name="program_studi" value="{{$row->program_studi}}" required autofocus>

                        @if ($errors->has('program_studi'))
                        <div class="invalid-feedback">
                            {{ $errors->first('program_studi') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="no_handphone" class="col-md-4 col-form-label text-md-right">{{ __('Nomor Handphone*') }}</label>
                    <div class="col-md-5">
                        <input id="no_handphone" type="number" min="0" class="form-control{{ $errors->has('no_handphone') ? ' is-invalid' : '' }}" name="no_handphone" value="{{$row->no_handphone}}" required autofocus>

                        @if ($errors->has('no_handphone'))
                        <div class="invalid-feedback">
                            {{ $errors->first('no_handphone') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
<!--<script type="text/javascript">
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
</script>-->
{!!Form::close()!!}
@endforeach
@endsection