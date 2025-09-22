@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-body">
                @if(empty($cekdata))
                @foreach($data as $row => $value)
                {!! Form::open(['files'=>true, 'url' => ['/simpan-tanggapan-usulan-penelitian']]) !!}
                <div class="form-group row">
                    <label for="judul" class="col-md-2 col-form-label">{{ __('Judul Penelitian*') }}</label>
                    <div class="col-md-10">
                        <textarea id="judul" type="text" class="form-control{{ $errors->has('judul') ? ' is-invalid' : '' }}" name="judul" readonly>{!! $value->judul_penelitian !!}</textarea><br>
                        <table class="mb-0 table table-bordered table-hover" style="width: 100%;">
                            <thead>
                                <tr class="bg-info">
                                    <th colspan="4" class="text-center" width="50%">PENILAIAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="80%">Ketajaman Perumusan Masalah & Tujuan Penelitian</td>
                                    <td align="center"><input id="nilai_1" min="1" max="7" type="number" class="form-control{{ $errors->has('nilai_1') ? ' is-invalid' : '' }}" name="nilai_1" value="{{ old('nilai_1') }}" required autofocus></td>
                                </tr>
                                <tr>
                                    <td width="80%">Pengembangan IPTEKS, Pembangunan, dan atau Pengembangan Kelembagaan serta luaran penelitian sesuai IKUP</td>
                                    <td align="center"><input id="nilai_2" min="1" max="7" type="number" class="form-control{{ $errors->has('nilai_2') ? ' is-invalid' : '' }}" name="nilai_2" value="{{ old('nilai_2') }}" required autofocus></td>
                                </tr>
                                <tr>
                                    <td width="80%">Relevansi, Kemutakhiran jurnal ilmiah dan Penyusunan Daftar Pustaka </td>
                                    <td align="center"><input id="nilai_3" min="1" max="7" type="number" class="form-control{{ $errors->has('nilai_3') ? ' is-invalid' : '' }}" name="nilai_3" value="{{ old('nilai_3') }}" required autofocus></td>
                                </tr>
                                <tr>
                                    <td width="80%">Ketetapan Metode yang digunakan</td>
                                    <td align="center"><input id="nilai_4" min="1" max="7" type="number" class="form-control{{ $errors->has('nilai_4') ? ' is-invalid' : '' }}" name="nilai_4" value="{{ old('nilai_4') }}" required autofocus></td>
                                </tr>
                                <tr>
                                    <td width="80%">Kesesuaian jadwal, Kesesuaian keahlian personalia & kewajaran biaya</td>
                                    <td align="center"><input id="nilai_5" min="1" max="7" type="number" class="form-control{{ $errors->has('nilai_5') ? ' is-invalid' : '' }}" name="nilai_5" value="{{ old('nilai_5') }}" required autofocus></td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="text-black font-weight-bold">
                            Setiap kriteria diberi skor : 1, 2, 3, 4, 5, 6, 7
                        </p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-form-label">Catatan Penilai</label>
                    <textarea name="tanggapan" id="summernote" value="{{ old('tanggapan') }}" required></textarea>
                </div>
                <div class="form-group row">
                    <label for="file" class="col-md-2 col-form-label">{{ __('File Pendukung') }}</label>
                    <div class="col-md-5">
                        <input name="file" id="file" type="file" class="form-control{{ $errors->has('file') ? ' is-invalid' : '' }}" file="file" value="{{ old('file') }}" autofocus>
                        <small class="text-muted">
                            *File Ms. Word Format Docx/ Doc
                        </small>
                        @if ($errors->has('file'))
                        <div class="invalid-feedback">
                            {{ $errors->first('file') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                {!!Form::close()!!}
                @endforeach
                @else
                <div class="alert alert-success">
                    <h4 class="alert-heading">Sudah Dinilai!</h4>
                    <p>Terima Kasih anda sudah memberikan tanggapan untuk penelitian ini.</p>
                    <a title="Hapus" href="{!! url('/'.$id_usulan.'/delete-tanggapan-usulan-penelitian') !!}">
                        <button class="btn btn-danger" type="submit">
                            <i class="fa fa-trash"></i> Batalkan
                        </button>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection