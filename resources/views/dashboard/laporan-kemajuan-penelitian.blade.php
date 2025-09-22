@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0">
            <strong>Upload Laporan Kemajuan</strong>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
            <strong>Data Laporan Kemajuan</strong>
        </a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="card mb-3">
            <div class="card-body">
                {!! Form::open(['files'=>true, 'url' => ['/simpan-laporan-kemajuan-penelitian']]) !!}
                @csrf
                <div class="form-group row">
                    <label for="judul_penelitian" class="col-md-4 col-form-label text-md-right">{{ __('Judul Penelitian*') }}</label>
                    <div class="col-md-6">
                        <select type="select" name="judul_penelitian" class="select2" style="width:100%;">
                            <option value="">- Pilih -</option>
                            @foreach($usulan as $row)
                            <option value="{{$row->id_usulan}}">{{$row->judul_penelitian}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="presentase_kemajuan" class="col-md-4 col-form-label text-md-right">{{ __('Presentase Kemajuan*') }}</label>
                    <div class="col-md-2">
                        <select type="select" name="presentase_kemajuan" class="custom-select">
                            <option value="">- Pilih -</option>
                            <option value="50">50%</option>
                            <option value="75">75%</option>
                            <option value="100">100%</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="file" class="col-md-4 col-form-label text-md-right">{{ __('Laporan Kemajuan*') }}</label>
                    <div class="col-md-6">
                        <input id="file" type="file" class="form-control{{ $errors->has('file') ? ' is-invalid' : '' }}" name="file" value="{{ old('file') }}" required autofocus>

                        <small class="text-muted">
                            Dokumen Ms. Word (Format : Doc, Docx)
                        </small>
                        @if ($errors->has('file'))
                        <div class="invalid-feedback">
                            {{ $errors->first('file') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="url" class="col-md-4 col-form-label text-md-right">{{ __('Link Pendukung') }}</label>
                    <div class="col-md-6">
                        <input id="url" type="text" class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}" name="url" value="{{ old('url') }}" placeholder="https://" autofocus>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="jenis_berkas" class="col-md-4 col-form-label text-md-right">{{ __('Output*') }}</label>
                    <div class="col-md-6">
                        <input id="jenis_berkas" type="text" class="form-control{{ $errors->has('jenis_berkas') ? ' is-invalid' : '' }}" name="jenis_berkas" value="{{ old('jenis_berkas') }}" placeholder="Misal: draft seminar, draft buku, draft jurnal" required autofocus>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2 offset-sm-4">
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
                <h4 class="mb-3">Data Laporan Kemajuan</h4>
                <div class="table-responsive">
                    <table id="example" class="mb-0 table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama Ketua</th>
                                <th class="text-center">Judul</th>
                                <th class="text-center">Jenis Berkas</th>
                                <th class="text-center">File</th>
                                <th class="text-center">Url</th>
                                <th class="text-center">Jenis Penelitian</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($laporan as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td>{{$value->nama_ketua}}</td>
                                <td>{{$value->judul_penelitian}}</td>
                                <td align="center">{{$value->jenis_berkas}}</td>
                                <td align="center"><a target="_blank" href="{{asset('/assets/file/laporan-kemajuan-penelitian')}}/{{$value->file}}"><i class="fa fa-download"></i></a></td>
                                <td align="center">{{$value->url}}</td>
                                <td align="center">
                                    @if($value->jenis_penelitian == 1)
                                    Mandiri
                                    @else
                                    Hibah PT
                                    @endif
                                </td>
                                <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                                <td align="center">
                                    <a title="Hapus" href="#" type="button" class="btn btn-danger btn-sm tooltipku" data-toggle="modal" data-target="#myModal1{{$value->id}}"><i class="fa fa-trash"></i></a>
                                </td>
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
@foreach($laporan as $row)
<div class="modal fade" id="myModal1{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                Hapus Data ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a title="Hapus" href="{!! url('/'.$row->id.'/delete-laporan-kemajuan-penelitian') !!}" class="btn btn-danger"><i class="fa fa-trash"></i> Ok</a>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection