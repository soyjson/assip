@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-body">
                @if(Auth::user()->level != 4 and Auth::user()->level != 5)
                <button type="button" class="btn mr-2 mb-3 btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Tambah Unduhan</button>
                <br>
                @endif
                <div class="table-responsive">
                    <table id="example" class="mb-0 table table-sm table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Keterangan</th>
                                <th class="text-center">File</th>
                                <th class="text-center">Tanggal Upload</th>
                                <th class="text-center">Jenis File</th>
                                @if(Auth::user()->level != 4 and Auth::user()->level != 5)
                                <th class="text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td>{{$value->keterangan}}</td>
                                <td align="center"><a target="_blank" href="{{asset('/assets/file/unduhan')}}/{{$value->file}}"><i class="fa fa-download"></i></a></td>
                                <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                                <td>
                                    @if($value->id_status == 1)
                                    Format Usulan Penelitian
                                    @elseif($value->id_status == 2)
                                    Format Usulan PengabMas
                                    @elseif($value->id_status == 0)
                                    Lainnya
                                    @endif
                                </td>
                                @if(Auth::user()->level != 4 and Auth::user()->level != 5)
                                <td align="center">
                                    <a title="Hapus" href="#" type="button" class="btn btn-danger btn-sm tooltipku" data-toggle="modal" data-target="#myModal1{{$value->id}}"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @endif
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
                {!! Form::open(['files'=>true, 'url' => ['/simpan-unduhan']]) !!}
                @csrf
                <div class="form-group row">
                    <label for="file" class="col-md-3 col-form-label text-md-right">{{ __('File*') }}</label>
                    <div class="col-md-8">
                        <input id="file" type="file" class="form-control{{ $errors->has('file') ? ' is-invalid' : '' }}" name="file" value="{{ old('file') }}" required autofocus>
                        <small class="text-muted">
                            Upload dokumen Ms. Word dengan format (.Doc or .Docx) dan PDF dengan format (.PDF)
                        </small>
                        @if ($errors->has('file'))
                        <div class="invalid-feedback">
                            {{ $errors->first('file') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="keterangan" class="col-md-3 col-form-label text-md-right">{{ __('Keterangan*') }}</label>
                    <div class="col-md-8">
                        <textarea id="keterangan" type="text" class="form-control{{ $errors->has('keterangan') ? ' is-invalid' : '' }}" name="keterangan" value="{{ old('keterangan') }}" required autofocus></textarea>
                        @if ($errors->has('keterangan'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('keterangan') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="id_status" class="col-md-3 col-form-label text-md-right">{{ __('Jenis File*') }}</label>
                    <div class="col-md-4">
                        <select type="select" id="exampleCustomSelect" name="id_status" class="custom-select">
                            <option value="">- Pilih -</option>
                            <option value="1">Format Penelitian</option>
                            <option value="2">Format Pengabmas</option>
                            <option value="0">Lainnya</option>
                        </select>
                        @if ($errors->has('id_status'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('id_status') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            {!! Form::close() !!}
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
                <a title="Hapus" href="{!! url('/'.$row->id.'/delete-unduhan') !!}" class="btn btn-danger"><i class="fa fa-trash"></i> Ok</a>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection