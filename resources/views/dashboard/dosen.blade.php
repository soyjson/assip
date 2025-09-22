@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-body">
                @if(Auth::user()->level == 4)
                <div class="row">
                    <div class="col-12">
                        <h4>{{Auth::user()->name}}</h4>
                        <h5>NIK : {{Auth::user()->nik}}</h5>
                        <h5>NIDN : {{Auth::user()->nidn}}</h5>
                        <h5>Jabatan Fungsional : {{Auth::user()->jafung}}</h5>
                        <h5>Institusi : {{Auth::user()->institusi}}</h5>
                        <h5>Fakultas : {{Auth::user()->fakultas}}</h5>
                        <h5>Program Studi : {{Auth::user()->program_studi}}</h5>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-md-6 col-xl-6">
                        <div class="card bg-gradient-info">
                            <div class="card-body">
                                <h4 class="mb-3">Usulan Penelitian</h4>
                                @foreach($usulan_penelitian as $row)
                                <h5>Usulan {{$row->status}} : {{$row->jumlah}}</h5>
                                @endforeach
                                @foreach($penelitian_selesai as $row)
                                <h5>Penelitian Selesai : {{$row->jumlah}}</h5>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-6">
                        <div class="card bg-gradient-warning">
                            <div class="card-body">
                                <h4 class="mb-3">Usulan Pengabdian Masyarakat</h4>
                                @foreach($usulan_pengabmas as $row)
                                <h5>Usulan {{$row->status}} : {{$row->jumlah}}</h5>
                                @endforeach
                                @foreach($pengabmas_selesai as $row)
                                <h5>Pengabmas Selesai : {{$row->jumlah}}</h5>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <button type="button" class="btn mr-2 mb-3 btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Tambah Dosen</button>
                <br />
                <div class="table-responsive">
                    <table id="example" class="mb-0 table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center" width="10%">Foto</th>
                                <th class="text-center">NIK</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Penelitian</th>
                                <th class="text-center">Pengabmas</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td align="center">
                                    @if($value->foto)
                                    <img src="{{asset('/assets/image/foto')}}/{{$value->foto}}" width="50" class="img-size-50" alt="Foto">
                                    @else
                                    <img src="{{asset('/assets/image/foto/default.png')}}" width="50" class="img-size-50" alt="Foto">
                                    @endif
                                </td>
                                <td align="center">{{$value->nik}}</td>
                                <td>{{$value->name}}</td>
                                <td>Total Pengajuan = {{$value->jml_penelitian}}</td>
                                <td>Total Pengajuan = {{$value->jml_pengabmas}}</td>
                                <td align="center"><button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#largeModal{{$value->id}}"> <i class="fa fa-edit"></i></button>
                                    <a title="Hapus" href="#" type="button" class="btn btn-danger btn-sm tooltipku" data-toggle="modal" data-target="#myModal1{{$value->id}}"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
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
                <h5 class="modal-title" id="exampleModalLongTitle">Tambah Data Dosen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open(['files'=>true, 'url' => ['/simpan-dosen']]) !!}
                @csrf
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nama Lengkap*') }}</label>
                            <div class="col-md-8">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username*') }}</label>
                            <div class="col-md-8">
                                <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus>

                                @if ($errors->has('username'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('username') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address*') }}</label>

                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password*') }}</label>

                            <div class="col-md-8">
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

                            <div class="col-md-8">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="foto" class="col-md-4 col-form-label text-md-right">{{ __('Foto*') }} (JPG/ PNG)</label>
                            <div class="col-md-8">
                                <input onchange="readFoto(event)" name="foto" id="foto" type="file" class="form-control{{ $errors->has('foto') ? ' is-invalid' : '' }}" foto="foto" value="{{ old('foto') }}">
                                <img id='output' style="width: 100px;">
                                @if ($errors->has('foto'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('foto') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="nik" class="col-md-4 col-form-label text-md-right">{{ __('NIK*') }}</label>
                            <div class="col-md-8">
                                <input id="nik" type="number" min="0" class="form-control{{ $errors->has('nik') ? ' is-invalid' : '' }}" name="nik" value="{{ old('nik') }}" required autofocus>

                                @if ($errors->has('nik'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nik') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="jafung" class="col-md-4 col-form-label text-md-right">{{ __('Jabatan Fungsional*') }}</label>
                            <div class="col-md-8">
                                <input id="jafung" type="text" class="form-control{{ $errors->has('jafung') ? ' is-invalid' : '' }}" name="jafung" value="{{ old('jafung') }}" required autofocus>

                                @if ($errors->has('jafung'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('jafung') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="fakultas" class="col-md-4 col-form-label text-md-right">{{ __('Fakultas*') }}</label>
                            <div class="col-md-8">
                                <input id="fakultas" type="text" class="form-control{{ $errors->has('fakultas') ? ' is-invalid' : '' }}" name="fakultas" value="{{ old('fakultas') }}" required autofocus>

                                @if ($errors->has('fakultas'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('fakultas') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="program_studi" class="col-md-4 col-form-label text-md-right">{{ __('Program Studi*') }}</label>
                            <div class="col-md-8">
                                <input id="program_studi" type="text" class="form-control{{ $errors->has('program_studi') ? ' is-invalid' : '' }}" name="program_studi" value="{{ old('program_studi') }}" required autofocus>

                                @if ($errors->has('program_studi'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('program_studi') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="no_handphone" class="col-md-4 col-form-label text-md-right">{{ __('Nomor Handphone*') }}</label>
                            <div class="col-md-8">
                                <input id="no_handphone" type="number" min="0" class="form-control{{ $errors->has('no_handphone') ? ' is-invalid' : '' }}" name="no_handphone" value="{{ old('no_handphone') }}" required autofocus>

                                @if ($errors->has('no_handphone'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('no_handphone') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
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
                <a title="Hapus" href="{!! url('/'.$row->id.'/delete-dosen') !!}" class="btn btn-danger"><i class="fa fa-trash"></i> Ok</a>
            </div>
        </div>
    </div>
</div>
@endforeach
@foreach ($data as $row)
{!! Form::model($row, ['url' => ['/update-dosen', $row->id]]) !!}
<div class="modal fade" id="largeModal{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Edit Dosen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Data Login</h5>
                <hr>
                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-mail') }}</label>

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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
{!!Form::close()!!}
@endforeach
@endsection