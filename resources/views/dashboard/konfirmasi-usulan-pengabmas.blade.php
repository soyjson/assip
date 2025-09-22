@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0">
            <strong>Usulan Baru</strong>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
            <strong>Usulan di Terima</strong>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
            <strong>Usulan di Tolak</strong>
        </a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="card mb-3">
            <div class="card-body">
                <h3 class="mb-3">Data Usulan Baru</h3>
                <form action="{{url('downloadusulanpengabmasExcel/csv')}}" method="GET" target="_blank">
                    <button class="btn btn-primary mb-3">Download</button>
                </form>
                <div class="table-responsive">
                    <table id="example" class="mb-0 table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Pengusul</th>
                                <th class="text-center">Judul</th>
                                <th class="text-center">File</th>
                                <th class="text-center">Jenis Pengabmas</th>
                                <th class="text-center">Biaya</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usulanbaru as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td width="30%">
                                    <table class="mb-0 table table-bordered table-hover table-striped">
                                        <tr>
                                            <th width="40%">NIK</th>
                                            <td width="60%">{{$value->nik}}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Pengusul</th>
                                            <td>{{$value->name}}</td>
                                        </tr>
                                        <tr>
                                            <th>Fakultas</th>
                                            <td>{{$value->fakultas}}</td>
                                        </tr>
                                        <tr>
                                            <th>Program Studi</th>
                                            <td>{{$value->program_studi}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td>{{$value->judul_pengabmas}}</td>
                                <td align="center"><a target="_blank" href="{{asset('/assets/file/usulan-pengabmas')}}/{{$value->file}}"><i class="fa fa-download"></i></a></td>
                                <td align="center">
                                    @if($value->jenis_pengabmas == 1)
                                    Mandiri
                                    @else
                                    Hibah PT
                                    @endif
                                </td>
                                <td align="center">
                                    @if($value->jenis_pengabmas == 1)
                                    Rp. {{number_format($value->biaya_hibah_luar)}}
                                    @else
                                    Rp. {{number_format($value->biaya_hibah_pt)}}
                                    @endif
                                </td>
                                <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                                <td align="center">{{$value->status}}</td>
                                <td align="center">
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#largeModal{{$value->id}}"> <i class="fa fa-pen"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
        <div class="card mb-3">
            <div class="card-body">
                <h3 class="mb-3">Data Usulan di Terima</h3>
                <div class="table-responsive">
                    <table id="example1" class="mb-0 table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Pengusul</th>
                                <th class="text-center">Judul</th>
                                <th class="text-center">File</th>
                                <th class="text-center">Jenis Pengabmas</th>
                                <th class="text-center">Biaya</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usulanditerima as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td width="30%">
                                    <table class="mb-0 table table-bordered table-hover table-striped">
                                        <tr>
                                            <th width="40%">NIK</th>
                                            <td width="60%">{{$value->nik}}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Pengusul</th>
                                            <td>{{$value->name}}</td>
                                        </tr>
                                        <tr>
                                            <th>Fakultas</th>
                                            <td>{{$value->fakultas}}</td>
                                        </tr>
                                        <tr>
                                            <th>Program Studi</th>
                                            <td>{{$value->program_studi}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td>{{$value->judul_pengabmas}}</td>
                                <td align="center"><a target="_blank" href="{{asset('/assets/file/usulan-pengabmas')}}/{{$value->file}}"><i class="fa fa-download"></i></a></td>
                                <td align="center">
                                    @if($value->jenis_pengabmas == 1)
                                    Mandiri
                                    @else
                                    Hibah PT
                                    @endif
                                </td>
                                <td align="center">
                                    @if($value->jenis_pengabmas == 1)
                                    Rp. {{number_format($value->biaya_hibah_luar)}}
                                    @else
                                    Rp. {{number_format($value->biaya_hibah_pt)}}
                                    @endif
                                </td>
                                <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                                <td align="center">{{$value->status}}</td>
                                <td align="center">
                                    <a title="Lihat Penilaian" href="{!! url('/'.$value->id_usulan.'/lihat-penilaian-usulan-pengabmas') !!}" target="_blank"><button class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></button></a>
                                    <p></p>
                                    <a title="Batalkan" href="#" type="button" class="btn btn-danger btn-sm tooltipku" data-toggle="modal" data-target="#myModal1{{$value->id}}"><i class="fa fa-window-close"></i></a>
                                    <p></p>
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#largeModal{{$value->id}}"> <i class="fa fa-edit"></i></button>
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
                <h3 class="mb-3">Data Usulan di Tolak</h3>
                <div class="table-responsive">
                    <table id="example2" class="mb-0 table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Pengusul</th>
                                <th class="text-center">Judul</th>
                                <th class="text-center">File</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usulanditolak as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td width="30%">
                                    <table class="mb-0 table table-bordered table-hover table-striped">
                                        <tr>
                                            <th width="40%">NIK</th>
                                            <td width="60%">{{$value->nik}}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Pengusul</th>
                                            <td>{{$value->name}}</td>
                                        </tr>
                                        <tr>
                                            <th>Fakultas</th>
                                            <td>{{$value->fakultas}}</td>
                                        </tr>
                                        <tr>
                                            <th>Program Studi</th>
                                            <td>{{$value->program_studi}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td>{{$value->judul_pengabmas}}</td>
                                <td align="center"><a target="_blank" href="{{asset('/assets/file/usulan-pengabmas')}}/{{$value->file}}"><i class="fa fa-download"></i></a></td>
                                <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                                <td align="center">{{$value->status}}</td>
                                <td align="center">
                                    <a title="Batalkan" href="#" type="button" class="btn btn-danger btn-sm tooltipku" data-toggle="modal" data-target="#myModal2{{$value->id}}"><i class="fa fa-window-close"></i></a>
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
@foreach ($usulanbaru as $row)
{!! Form::model($row, ['url' => ['/simpan-konfirmasi-usulan-pengabmas', $row->id]]) !!}
<div class="modal fade" id="largeModal{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Konfirmasi Usulan Pengabmas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="status" class="col-md-4 col-form-label text-md-right">{{ __('Konfirmasi ') }}</label>

                    <div class="col-md-5">
                        <input type="hidden" name="nama" value="{{$row->name}}">
                        <input type="hidden" name="email" value="{{$row->email}}">
                        <select id="status" type="text" class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}" name="status" value="{{ Auth::user()->status }}">
                            <option>- Pilih -</option>
                            <option value="di terima">Terima</option>
                            <option value="di tolak">Tolak</option>
                        </select>

                        @if ($errors->has('status'))
                        <div class="invalid-feedback">
                            {{ $errors->first('status') }}
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

@foreach($usulanditerima as $row)
<div class="modal fade" id="myModal1{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                Batalkan status ini ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a title="Hapus" href="{!! url('/'.$row->id.'/batal-usulan-pengabmas-diterima') !!}" class="btn btn-danger"><i class="fa fa-trash"></i> Ok</a>
            </div>
        </div>
    </div>
</div>
@endforeach

@foreach($usulanditolak as $row)
<div class="modal fade" id="myModal2{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                Batalkan status ini ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a title="Hapus" href="{!! url('/'.$row->id.'/batal-usulan-pengabmas-ditolak') !!}" class="btn btn-danger"><i class="fa fa-trash"></i> Ok</a>
            </div>
        </div>
    </div>
</div>
@endforeach
@foreach ($usulanditerima as $row)
{!! Form::model($row, ['url' => ['/update-dana-hibah-pt-pengabmas', $row->id]]) !!}
<div class="modal fade" id="largeModal{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Edit Dana Hibah PT</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="biaya_hibah_pt" class="col-md-4 col-form-label text-md-right">{{ __('Dana Hibah PT') }}</label>

                    <div class="col-md-5">
                        <input id="biaya_hibah_pt" type="text" class="form-control currency {{ $errors->has('biaya_hibah_pt') ? ' is-invalid' : '' }}" name="biaya_hibah_pt" value="{{ $row->biaya_hibah_pt }}" autofocus>

                        @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
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