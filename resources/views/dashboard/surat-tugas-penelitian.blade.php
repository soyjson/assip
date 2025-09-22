@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0">
            <strong>Upload Surat Tugas</strong>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
            <strong>Download Surat Tugas</strong>
        </a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="card mb-3">
            <div class="card-body">
                {!! Form::open(['url' => ['/simpan-surat-tugas-penelitian']]) !!}
                <div class="row">
                    @foreach($data as $row)
                    <input type="hidden" name="id_lookbook" value="{{ $row->id_lookbook }}">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="judul_penelitian" class="col-md-5 col-form-label text-md-right">{{ __('Judul Penelitian*') }}</label>
                            <div class="col-md-7">
                                <textarea id="judul_penelitian" class="form-control{{ $errors->has('judul_penelitian') ? ' is-invalid' : '' }}" name="judul_penelitian" readonly="readonly">{{ $row->judul_penelitian }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="tempat" class="col-md-5 col-form-label text-md-right">{{ __('Tempat*') }}</label>
                            <div class="col-md-7">
                                <input id="tempat" type="text" class="form-control{{ $errors->has('tempat') ? ' is-invalid' : '' }}" name="tempat" value="{{ old('tempat') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="lama_penelitian" class="col-md-5 col-form-label text-md-right">{{ __('Lama Penelitian*') }}</label>
                            <div class="col-md-7">
                                <input id="lama_penelitian" type="text" class="form-control{{ $errors->has('lama_penelitian') ? ' is-invalid' : '' }}" name="lama_penelitian" value="{{ old('lama_penelitian') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="keperluan" class="col-md-5 col-form-label text-md-right">{{ __('Keperluan*') }}</label>
                            <div class="col-md-7">
                                <input id="keperluan" type="text" class="form-control{{ $errors->has('keperluan') ? ' is-invalid' : '' }}" name="keperluan" value="{{ old('keperluan') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="transport" class="col-md-5 col-form-label text-md-right">{{ __('Transport') }}</label>
                            <div class="col-md-7">
                                <input id="transport" type="text" class="form-control{{ $errors->has('transport') ? ' is-invalid' : '' }}" name="transport" value="{{ old('transport') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <h5 class="mb-3">
                            Data Afiliasi
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="nama_ketua" class="col-md-5 col-form-label text-md-right">{{ __('Nama Ketua*') }}</label>
                            <div class="col-md-7">
                                <input id="nama_ketua" type="text" class="form-control{{ $errors->has('nama_ketua') ? ' is-invalid' : '' }}" name="nama_ketua" value="{{ $row->nama_ketua }}" required>

                                @if ($errors->has('nama_ketua'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nama_ketua') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="afiliasi" class="col-md-5 col-form-label text-md-right">{{ __('Afiliasi') }}</label>
                            <div class="col-md-7">
                                <input id="afiliasi" type="text" class="form-control{{ $errors->has('afiliasi') ? ' is-invalid' : '' }}" name="afiliasi" value="{{ old('afiliasi') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="anggota_internal1" class="col-md-5 col-form-label text-md-right">{{ __('Anggota Internal 1') }}</label>
                            <div class="col-md-7">
                                <input id="anggota_internal1" type="text" class="form-control{{ $errors->has('anggota_internal1') ? ' is-invalid' : '' }}" name="anggota_internal1" value="{{ $row->anggota_internal1 }}">

                                @if ($errors->has('anggota_internal1'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('anggota_internal1') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="afiliasi_internal1" class="col-md-5 col-form-label text-md-right">{{ __('Afiliasi Internal 1') }}</label>
                            <div class="col-md-7">
                                <input id="afiliasi_internal1" type="text" class="form-control{{ $errors->has('afiliasi_internal1') ? ' is-invalid' : '' }}" name="afiliasi_internal1" value="{{ old('afiliasi_internal1') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="anggota_internal2" class="col-md-5 col-form-label text-md-right">{{ __('Anggota Internal 2') }}</label>
                            <div class="col-md-7">
                                <input id="anggota_internal2" type="text" class="form-control{{ $errors->has('anggota_internal2') ? ' is-invalid' : '' }}" name="anggota_internal2" value="{{ $row->anggota_internal2 }}">

                                @if ($errors->has('anggota_internal2'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('anggota_internal2') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="afiliasi_internal2" class="col-md-5 col-form-label text-md-right">{{ __('Afiliasi Internal 2') }}</label>
                            <div class="col-md-7">
                                <input id="afiliasi_internal2" type="text" class="form-control{{ $errors->has('afiliasi_internal2') ? ' is-invalid' : '' }}" name="afiliasi_internal2" value="{{ old('afiliasi_internal2') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="anggota_internal3" class="col-md-5 col-form-label text-md-right">{{ __('Anggota Internal 3') }}</label>
                            <div class="col-md-7">
                                <input id="anggota_internal3" type="text" class="form-control{{ $errors->has('anggota_internal3') ? ' is-invalid' : '' }}" name="anggota_internal3" value="{{ $row->anggota_internal3 }}">

                                @if ($errors->has('anggota_internal3'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('anggota_internal3') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="afiliasi_internal3" class="col-md-5 col-form-label text-md-right">{{ __('Afiliasi Internal 3') }}</label>
                            <div class="col-md-7">
                                <input id="afiliasi_internal3" type="text" class="form-control{{ $errors->has('afiliasi_internal3') ? ' is-invalid' : '' }}" name="afiliasi_internal3" value="{{ old('afiliasi_internal3') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="anggota_internal4" class="col-md-5 col-form-label text-md-right">{{ __('Anggota Internal 4') }}</label>
                            <div class="col-md-7">
                                <input id="anggota_internal4" type="text" class="form-control{{ $errors->has('anggota_internal4') ? ' is-invalid' : '' }}" name="anggota_internal4" value="{{ $row->anggota_internal4 }}">

                                @if ($errors->has('anggota_internal4'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('anggota_internal4') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="afiliasi_internal4" class="col-md-5 col-form-label text-md-right">{{ __('Afiliasi Internal 4') }}</label>
                            <div class="col-md-7">
                                <input id="afiliasi_internal4" type="text" class="form-control{{ $errors->has('afiliasi_internal4') ? ' is-invalid' : '' }}" name="afiliasi_internal4" value="{{ old('afiliasi_internal4') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="anggota_eksternal1" class="col-md-5 col-form-label text-md-right">{{ __('Anggota Eksternal 1') }}</label>
                            <div class="col-md-7">
                                <input id="anggota_eksternal1" type="text" class="form-control{{ $errors->has('anggota_eksternal1') ? ' is-invalid' : '' }}" name="anggota_eksternal1" value="{{ $row->anggota_eksternal1 }}">

                                @if ($errors->has('anggota_eksternal1'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('anggota_eksternal1') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="afiliasi_eksternal1" class="col-md-5 col-form-label text-md-right">{{ __('Afiliasi Eksternal 1') }}</label>
                            <div class="col-md-7">
                                <input id="afiliasi_eksternal1" type="text" class="form-control{{ $errors->has('afiliasi_eksternal1') ? ' is-invalid' : '' }}" name="afiliasi_eksternal1" value="{{ old('afiliasi_eksternal1') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="anggota_eksternal2" class="col-md-5 col-form-label text-md-right">{{ __('Anggota Eksternal 2') }}</label>
                            <div class="col-md-7">
                                <input id="anggota_eksternal2" type="text" class="form-control{{ $errors->has('anggota_eksternal2') ? ' is-invalid' : '' }}" name="anggota_eksternal2" value="{{ $row->anggota_eksternal2 }}">

                                @if ($errors->has('anggota_eksternal2'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('anggota_eksternal2') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="afiliasi_eksternal2" class="col-md-5 col-form-label text-md-right">{{ __('Afiliasi Eksternal 2') }}</label>
                            <div class="col-md-7">
                                <input id="afiliasi_eksternal2" type="text" class="form-control{{ $errors->has('afiliasi_eksternal2') ? ' is-invalid' : '' }}" name="afiliasi_eksternal2" value="{{ old('afiliasi_eksternal2') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="anggota_eksternal3" class="col-md-5 col-form-label text-md-right">{{ __('Anggota Eksternal 3') }}</label>
                            <div class="col-md-7">
                                <input id="anggota_eksternal3" type="text" class="form-control{{ $errors->has('anggota_eksternal3') ? ' is-invalid' : '' }}" name="anggota_eksternal3" value="{{ $row->anggota_eksternal3 }}">

                                @if ($errors->has('anggota_eksternal3'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('anggota_eksternal3') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="afiliasi_eksternal3" class="col-md-5 col-form-label text-md-right">{{ __('Afiliasi Eksternal 3') }}</label>
                            <div class="col-md-7">
                                <input id="afiliasi_eksternal3" type="text" class="form-control{{ $errors->has('afiliasi_eksternal3') ? ' is-invalid' : '' }}" name="afiliasi_eksternal3" value="{{ old('afiliasi_eksternal3') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="anggota_eksternal4" class="col-md-5 col-form-label text-md-right">{{ __('Anggota Eksternal 4') }}</label>
                            <div class="col-md-7">
                                <input id="anggota_eksternal4" type="text" class="form-control{{ $errors->has('anggota_eksternal4') ? ' is-invalid' : '' }}" name="anggota_eksternal4" value="{{ $row->anggota_eksternal4 }}">

                                @if ($errors->has('anggota_eksternal4'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('anggota_eksternal4') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="afiliasi_eksternal4" class="col-md-5 col-form-label text-md-right">{{ __('Afiliasi Eksternal 4') }}</label>
                            <div class="col-md-7">
                                <input id="afiliasi_eksternal4" type="text" class="form-control{{ $errors->has('afiliasi_eksternal4') ? ' is-invalid' : '' }}" name="afiliasi_eksternal4" value="{{ old('afiliasi_eksternal4') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="mahasiswa" class="col-md-5 col-form-label text-md-right">{{ __('Mahasiswa') }}</label>
                            <div class="col-md-7">
                                <input id="mahasiswa" type="text" class="form-control{{ $errors->has('mahasiswa') ? ' is-invalid' : '' }}" name="mahasiswa" value="{{ $row->mahasiswa }}">

                                @if ($errors->has('mahasiswa'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mahasiswa') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="afiliasi_mahasiswa" class="col-md-5 col-form-label text-md-right">{{ __('Afiliasi Mahasiswa') }}</label>
                            <div class="col-md-7">
                                <input id="afiliasi_mahasiswa" type="text" class="form-control{{ $errors->has('afiliasi_mahasiswa') ? ' is-invalid' : '' }}" name="afiliasi_mahasiswa" value="{{ old('afiliasi_mahasiswa') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="alumni" class="col-md-5 col-form-label text-md-right">{{ __('Alumni') }}</label>
                            <div class="col-md-7">
                                <input id="alumni" type="text" class="form-control{{ $errors->has('alumni') ? ' is-invalid' : '' }}" name="alumni" value="{{ $row->alumni }}">

                                @if ($errors->has('alumni'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('alumni') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="afiliasi_alumni" class="col-md-5 col-form-label text-md-right">{{ __('Afiliasi Alumni') }}</label>
                            <div class="col-md-7">
                                <input id="afiliasi_alumni" type="text" class="form-control{{ $errors->has('afiliasi_alumni') ? ' is-invalid' : '' }}" name="afiliasi_alumni" value="{{ old('afiliasi_alumni') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="admin" class="col-md-5 col-form-label text-md-right">{{ __('Admin') }}</label>
                            <div class="col-md-7">
                                <input id="admin" type="text" class="form-control{{ $errors->has('admin') ? ' is-invalid' : '' }}" name="admin" value="{{ $row->admin }}">

                                @if ($errors->has('admin'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('admin') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="afiliasi_admin" class="col-md-5 col-form-label text-md-right">{{ __('Afiliasi Admin') }}</label>
                            <div class="col-md-7">
                                <input id="afiliasi_admin" type="text" class="form-control{{ $errors->has('afiliasi_admin') ? ' is-invalid' : '' }}" name="afiliasi_admin" value="{{ old('afiliasi_admin') }}">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-3 offset-sm-9">
                            <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                        </div>
                    </div>
                </div>
                {!!Form::close()!!}
            </div>
        </div>
    </div>
    <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h4 class="mb-4">Download Surat Tugas</h4>
                <div class="table-responsive">
                    <table id="example" class="mb-0 table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nomor Surat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($surattugas as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td align="center">{{$value->no_surat}}</td>
                                <td align="center"><a title="Download Surat Tugas Penelitian" href="{!! url('/'.$value->id.'/download-surat-tugas-penelitian') !!}" target="_blank"><button class="btn btn-primary">
                                            <i class="fa fa-download"></i> Download</button></a>
                                    <a title="Hapus" href="#" type="button" class="btn btn-danger tooltipku" data-toggle="modal" data-target="#myModal1{{$value->id}}"><i class="fa fa-trash"></i></a>
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
@foreach($surattugas as $row)
<div class="modal fade" id="myModal1{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                Hapus Data ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a title="Hapus" href="{!! url('/'.$row->id.'/delete-surat-tugas-penelitian') !!}" class="btn btn-danger"><i class="fa fa-trash"></i> Ok</a>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection