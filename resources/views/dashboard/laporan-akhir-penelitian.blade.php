@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0">
            <strong>Upload Laporan Akhir</strong>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
            <strong>Data Laporan Akhir</strong>
        </a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="card mb-3">
            <div class="card-body">
                {!! Form::open(['files'=>true, 'url' => ['/simpan-laporan-akhir-penelitian']]) !!}
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
                    <label for="lama_penelitian" class="col-md-4 col-form-label text-md-right">{{ __('Lama Penelitian*') }}</label>
                    <div class="col-md-2">
                        <div class="input-group"><input type="number" class="form-control{{ $errors->has('lama_penelitian') ? ' is-invalid' : '' }}" name="lama_penelitian" value="{{ old('lama_penelitian') }}" required autofocus>
                            <div class="input-group-append"><span class="input-group-text">Bulan</span></div>
                        </div>

                        @if ($errors->has('lama_penelitian'))
                        <div class="invalid-feedback">
                            {{ $errors->first('lama_penelitian') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="file" class="col-md-4 col-form-label text-md-right">{{ __('Laporan Akhir*') }}</label>
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
                    <label for="jenis_luaran" class="col-md-4 col-form-label text-md-right">{{ __('Jenis Luaran*') }}</label>
                    <div class="col-md-3">
                        <select type="select" name="jenis_luaran" class="custom-select">
                            <option value="">- Pilih -</option>
                            <option>Seminar Internasional</option>
                            <option>Seminar Nasional</option>
                            <option>Jurnal Internasional</option>
                            <option>Jurnal Nasional</option>
                            <option>Buku</option>
                            <option>Leaflet</option>
                            <option>Lainnya</option>
                        </select>

                        <div id="exampleAccordion" data-children=".item">
                            <div class="item">
                                <button type="button" aria-expanded="false" aria-controls="exampleAccordion2" data-toggle="collapse" href="#collapseExample2" class="btn btn-warning btn-sm"><i class="fa fa-plus"></i> Lainnya</button>
                                <div data-parent="#exampleAccordion" id="collapseExample2" class="collapse">
                                    <input id="jenis_luaran1" type="text" class="form-control{{ $errors->has('jenis_luaran1') ? ' is-invalid' : '' }}" name="jenis_luaran1" value="{{ old('jenis_luaran1') }}" autofocus>
                                    <input id="jenis_luaran2" type="text" class="form-control{{ $errors->has('jenis_luaran2') ? ' is-invalid' : '' }}" name="jenis_luaran2" value="{{ old('jenis_luaran2') }}" autofocus>
                                    <input id="jenis_luaran3" type="text" class="form-control{{ $errors->has('jenis_luaran3') ? ' is-invalid' : '' }}" name="jenis_luaran3" value="{{ old('jenis_luaran3') }}" autofocus>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="luaran" class="col-md-4 col-form-label text-md-right">{{ __('Luaran*') }}</label>
                    <div class="col-md-6">
                        <input id="luaran" type="file" class="form-control{{ $errors->has('luaran') ? ' is-invalid' : '' }}" name="luaran" value="{{ old('luaran') }}" required autofocus>

                        <small class="text-muted">
                            Dokumen Ms. Word (Format : Doc, Docx)
                        </small>
                        @if ($errors->has('luaran'))
                        <div class="invalid-feedback">
                            {{ $errors->first('luaran') }}
                        </div>
                        @endif
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
                <h4 class="mb-3">Data Laporan Akhir</h4>
                <div class="table-responsive">
                    <table id="example" class="mb-0 table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama Ketua</th>
                                <th class="text-center">Judul</th>
                                <th class="text-center">Lama Penelitian</th>
                                <th class="text-center">Lama Penelitian Riil</th>
                                <th class="text-center">Luaran</th>
                                <th class="text-center">File</th>
                                <th class="text-center">Url</th>
                                <th class="text-center">Jenis Penelitian</th>
                                <th class="text-center">Luaran</th>
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
                                <td align="center">{{$value->lama_penelitian}} Bulan</td>
                                <td align="center">{{$value->lama_penelitian_riil}} Bulan</td>
                                <td align="center">{{$value->jenis_luaran}}</td>
                                <td align="center"><a target="_blank" href="{{asset('/assets/file/laporan-akhir-penelitian')}}/{{$value->file}}"><i class="fa fa-download"></i></a></td>
                                <td align="center">{{$value->url}}</td>
                                <td align="center">
                                    @if($value->jenis_penelitian == 1)
                                    Mandiri
                                    @else
                                    Hibah PT
                                    @endif
                                </td>
                                <td align="center"><a target="_blank" href="{{asset('/assets/file/laporan-akhir-penelitian')}}/{{$value->luaran}}"><i class="fa fa-download"></i></a></td>
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
                <a title="Hapus" href="{!! url('/'.$row->id.'/delete-laporan-akhir-penelitian') !!}" class="btn btn-danger"><i class="fa fa-trash"></i> Ok</a>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection