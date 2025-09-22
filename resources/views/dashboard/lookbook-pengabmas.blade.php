@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0">
            <strong>Input Logbook</strong>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
            <strong>Logbook</strong>
        </a>
    </li>
    @if(Auth::user()->level != 4 )
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
            <strong>Rekap Logbook Pengabdian Masyarakat</strong>
        </a>
    </li>
    @endif
</ul>
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="card mb-3">
            <div class="card-body">
                {!! Form::open(['url' => ['/simpan-lookbook-pengabmas']]) !!}
                <div class="form-group row">
                    <label for="judul_pengabmas" class="col-md-3 col-form-label text-md-right">{{ __('Judul PengabMas*') }}</label>
                    <div class="col-md-6">
                        <select type="select" name="id_usulan" class="custom-select">
                            <option value="">- Pilih -</option>
                            @foreach($usulan as $row)
                            <option value="{{$row->id_usulan}}">{{$row->judul_pengabmas}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="judul_kegiatan" class="col-md-3 col-form-label text-md-right">{{ __('Judul Kegiatan*') }}</label>
                    <div class="col-md-6">
                        <input id="judul_kegiatan" type="text" class="form-control{{ $errors->has('judul_kegiatan') ? ' is-invalid' : '' }}" name="judul_kegiatan" value="{{ old('judul_kegiatan') }}" required autofocus>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="jenis_kegiatan" class="col-md-3 col-form-label text-md-right">{{ __('Jenis Kegiatan*') }}</label>
                    <div class="col-md-6">
                        <input id="jenis_kegiatan" type="text" class="form-control{{ $errors->has('jenis_kegiatan') ? ' is-invalid' : '' }}" name="jenis_kegiatan" value="{{ old('jenis_kegiatan') }}" required autofocus>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tanggal" class="col-md-3 col-form-label text-md-right">{{ __('Tanggal Kegiatan*') }}</label>
                    <div class="col-md-6">
                        <input id="datepicker" class="form-control{{ $errors->has('tanggal') ? ' is-invalid' : '' }}" name="tanggal" value="{{ old('tanggal') }}" required autofocus>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="catatan" class="col-md-3 col-form-label text-md-right">{{ __('Catatan*') }}</label>
                    <div class="col-md-6">
                        <textarea id="summernote" id="catatan" type="text" class="form-control{{ $errors->has('catatan') ? ' is-invalid' : '' }}" name="catatan" value="{{ old('catatan') }}" value="{{ old('catatan') }}" required autofocus></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2 offset-sm-3">
                        <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                    </div>
                </div>
                {!!Form::close()!!}
            </div>
        </div>
    </div>
    <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="mb-4">Histori Logbook</h4>
                <div class="table-responsive">
                    <table id="example" class="mb-0 table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Judul PengabMas</th>
                                <th class="text-center">Judul Kegiatan</th>
                                <th class="text-center">Jenis Kegiatan</th>
                                <th class="text-center">Surat Tugas</th>
                                <th class="text-center">Catatan</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">User</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td>{{$value->judul_pengabmas}}</td>
                                <td>{{$value->judul_kegiatan}}</td>
                                <td>{{$value->jenis_kegiatan}}</td>
                                <td align="center">
                                    <a title="Buat Surat Tugas" href="{!! url('/'.$value->id.'/buat-surat-tugas-pengabmas') !!}"><button class="btn btn-primary">
                                            <i class="fa fa-pen"></i> Buat</button></a>
                                </td>
                                <td>{!!$value->catatan!!}</td>
                                <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                                <td align="center">{{$value->user}}</td>
                                <td align="center">
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
    <div class="tab-pane tabs-animation fade" id="tab-content-2" role="tabpanel">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Rekap Logbook Pengabdian Masyarakat</h5>
                <div class="table-responsive">
                    <table id="example1" class="mb-0 table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Judul PengabMas</th>
                                <th class="text-center">Judul Kegiatan</th>
                                <th class="text-center">Jenis Kegiatan</th>
                                <th class="text-center">Surat Tugas</th>
                                <th class="text-center">Catatan</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">User</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data_lengkap as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td>{{$value->judul_pengabmas}}</td>
                                <td>{{$value->judul_kegiatan}}</td>
                                <td>{{$value->jenis_kegiatan}}</td>
                                <td align="center">
                                    <a title="Lihat Surat Tugas" href="{!! url('/'.$value->id.'/surat-tugas-pengabmas') !!}"><button class="btn btn-primary">
                                            <i class="fa fa-eye"></i> Lihat</button></a>
                                </td>
                                <td>{!!$value->catatan!!}</td>
                                <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                                <td align="center">{{$value->user}}</td>
                                <td align="center">
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
    @endsection

    @section('modal')
    @foreach($data as $row)
    <div class="modal fade" id="myModal1{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    Hapus Data ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <a title="Hapus" href="{!! url('/'.$row->id.'/delete-lookbook-pengabmas') !!}" class="btn btn-danger"><i class="fa fa-trash"></i> Ok</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endsection