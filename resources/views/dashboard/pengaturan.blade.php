@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')
@if(Auth::user()->level != 4 and Auth::user()->level != 5)
<div class="row">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-body">
                @foreach($data as $row => $value)
                {!! Form::model($value, ['files'=>true, 'url' => ['/update-pengaturan', $value->id]]) !!}
                <div class="form-group">
                    <label for="logo" class="col-form-label">{{ __('Logo*') }}</label>
                    <img src="{{asset('/assets/image/logo/logo.png')}}" class="d-block mb-3" width="100" alt="Logo">
                    <input onchange="readGambar(event)" name="logo" id="logo" type="file" class="form-control {{ $errors->has('logo') ? 'is-invalid' : '' }}" accept="image/*" value="{{ old('logo') }}">
                    <small class="text-muted">
                        *Format Logo .png
                    </small>
                    @if ($errors->has('logo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('logo') }}
                    </div>
                    @endif
                    <img id='output' style="width: 100px;display:block;">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="nama_instansi" placeholder="Nama Instansi" value="{{$value->nama_instansi}}">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="lembaga" placeholder="Singkatan Lembaga" value="{{$value->lembaga}}">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="nama_lembaga" placeholder="Nama Lembaga" value="{{$value->nama_lembaga}}">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="ketua_lembaga" placeholder="ketua Lembaga" value="{{$value->ketua_lembaga}}">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="ketua_nik" placeholder="NIK Ketua Lembaga" value="{{$value->ketua_nik}}">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="alamat" placeholder="Alamat" value="{{$value->alamat}}">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="kota" placeholder="Kota" value="{{$value->kota}}">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="telepon" placeholder="Telepon" value="{{$value->telepon}}">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="email" placeholder="Email" value="{{$value->email}}">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="website" placeholder="Website" value="{{$value->website}}">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                {!!Form::close()!!}
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif
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