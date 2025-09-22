@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0">
            <strong>Tambah</strong>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
            <strong>Daftar Berita</strong>
        </a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="card mb-3">
            {!! Form::open(['files'=>true, 'url' => ['/simpan-manajemen-berita']]) !!}
            @csrf
            <div class="card-body">
                <div class="form-group row">
                    <label for="judul" class="col-md-2 col-form-label">{{ __('Judul *') }}</label>
                    <div class="col-md-10">
                        <input id="judul" type="text" class="form-control{{ $errors->has('judul') ? ' is-invalid' : '' }}" name="judul" value="{{ old('judul')}}" autofocus>

                        @if ($errors->has('judul'))
                        <div class="invalid-feedback">
                            {{ $errors->first('judul') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="gambar" class="col-md-2 col-form-label">{{ __('Gambar*') }}</label>
                    <div class="col-md-5">
                        <input onchange="readGambar(event)" name="gambar" id="gambar" type="file" class="form-control{{ $errors->has('gambar') ? ' is-invalid' : '' }}" gambar="gambar" value="{{ old('gambar') }}">
                        <small class="text-muted">
                            *Format gambar jpg/jpeg/png
                        </small>
                        @if ($errors->has('gambar'))
                        <div class="invalid-feedback">
                            {{ $errors->first('gambar') }}
                        </div>
                        @endif
                        <img id='output' style="width: 400px;">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="isi" class="col-md-2 col-form-label">{{ __('Isi*') }}</label>
                    <div class="col-md-10">
                        <textarea class="form-control{{ $errors->has('isi') ? ' is-invalid' : '' }}" name="isi" id="summernote" rows="5"></textarea>
                        @if ($errors->has('isi'))
                        <div class="invalid-feedback">
                            {{ $errors->first('isi') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
        <div class="card mb-3">
            <div class="card-body">
                <h3 class="mb-3">Daftar Berita</h3>
                <div class="table-responsive">
                    <table id="example" class="mb-0 table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Gambar</th>
                                <th class="text-center">Judul</th>
                                <th class="text-center">Isi</th>
                                <th class="text-center">Penulis</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Viewer</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td align="center" width="20%">
                                    <img src="{{asset('/assets/image/postingan/')}}/{{$value->gambar}}" width="100%">
                                <td>{{$value->judul}}</td>
                                <td>{!! $value->isi !!}</td>
                                <td>{{$value->name}}</td>
                                <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                                <td align="center">{{$value->view}}</td>
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
<script type="text/javascript">
    var readGambar = function(event) {
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
                <a title="Hapus" href="{!! url('/'.$row->id.'/delete-manajemen-berita') !!}" class="btn btn-danger"><i class="fa fa-trash"></i> Ok</a>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection