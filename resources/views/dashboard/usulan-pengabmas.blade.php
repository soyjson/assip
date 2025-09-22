@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0">
            <strong>Data Usulan</strong>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
            <strong>Upload Usulan</strong>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
            <strong>Download Format Usulan</strong>
        </a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="mb-2">Data Usulan</h5>
                    <p class="mb-3 text-muted">
                        Jika ada kesalahan judul dan berkas silahkan bisa di edit sebelum proses pengajuan berakhir
                    </p>
                    <div class="table-responsive">
                        <table id="example" class="mb-0 table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Judul</th>
                                    <th class="text-center">File</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usulan as $key => $value)
                                <tr>
                                    <td align="center">{{$key+1}}</td>
                                    <td width="40%">{{$value->judul_pengabmas}}</td>
                                    <td align="center"><a target="_blank" href="{{asset('/assets/file/usulan-pengabmas')}}/{{$value->file}}"><i class="fa fa-download"></i></a></td>
                                    <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                                    <td align="center">{{$value->status}}</td>
                                    <td align="center">
                                        <a title="Lihat Penilaian" href="{!! url('/'.$value->id_usulan.'/lihat-penilaian-usulan-pengabmas') !!}" target="_blank">
                                            <button class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></button>
                                        </a>
                                        @if($value->status == 'pengajuan')
                                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#largeModal{{$value->id}}" title="Edit"> <i class="fa fa-edit"></i></button>
                                        <a title="Hapus" href="#" type="button" class="btn btn-danger btn-sm tooltipku" data-toggle="modal" data-target="#myModal1{{$value->id}}"><i class="fa fa-trash"></i></a>
                                        @else
                                        <span class="btn btn-success btn-sm">Final</span>
                                        @endif
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
                <h4 class="mb-2">Upload Usulan</h4>
                <p class="mb-3 text-muted">
                    Lengkapi data usulan, tanda (*) artinya wajib di isi
                </p>
                {!! Form::open(['files'=>true, 'url' => ['/simpan-usulan-pengabmas']]) !!}
                @csrf
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="nama_ketua" class="col-md-4 col-form-label text-md-right">{{ __('Nama Ketua*') }}</label>
                            <div class="col-md-8">
                                <select type="select" name="nama_ketua" class="form-control select2 {{ $errors->has('nama_ketua') ? 'is-invalid' : '' }}" style="width: 100%;">
                                    <option value="">- Pilih Ketua -</option>
                                    @foreach($dosen as $row)
                                    <option value="{{$row->name}}">{{$row->name}}</option>
                                    @endforeach
                                </select>
                                <!-- <input id="nama_ketua" type="text" class="form-control {{ $errors->has('nama_ketua') ? 'is-invalid' : '' }}" name="nama_ketua" value="{{ old('nama_ketua') }}"> -->

                                @if ($errors->has('nama_ketua'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nama_ketua') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="anggota_internal1" class="col-md-4 col-form-label text-md-right">{{ __('Anggota Internal') }}</label>
                            <div class="col-md-8">
                                <select type="select" name="anggota_internal1" class="select2" style="width: 100%;">
                                    <option value="">- Pilih Anggota -</option>
                                    @foreach($dosen as $row)
                                    <option value="{{$row->name}}">{{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="anggota_internal" class="col-md-4 col-form-label text-md-right"></label>
                            <div class="col-md-8">
                                <div id="exampleAccordion" data-children=".item">
                                    <div class="item">
                                        <button type="button" aria-expanded="false" aria-controls="exampleAccordion2" data-toggle="collapse" href="#collapseExample3" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></button>
                                        <div data-parent="#exampleAccordion" id="collapseExample3" class="collapse">
                                            <select type="select" name="anggota_internal2" class="select2" style="width: 100%;">
                                                <option value="">- Pilih Anggota -</option>
                                                @foreach($dosen as $row)
                                                <option value="{{$row->name}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                            <select type="select" name="anggota_internal3" class="select2" style="width: 100%;">
                                                <option value="">- Pilih Anggota -</option>
                                                @foreach($dosen as $row)
                                                <option value="{{$row->name}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                            <select type="select" name="anggota_internal4" class="select2" style="width: 100%;">
                                                <option value="">- Pilih Anggota -</option>
                                                @foreach($dosen as $row)
                                                <option value="{{$row->name}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="anggota_eksternal1" class="col-md-4 col-form-label text-md-right">{{ __('Anggota Eksternal') }}</label>
                            <div class="col-md-8">
                                <input id="anggota_eksternal1" type="text" class="form-control{{ $errors->has('anggota_eksternal1') ? ' is-invalid' : '' }}" name="anggota_eksternal1" value="{{ old('anggota_eksternal1') }}" autofocus>
                            </div>
                            <label for="anggota_eksternal" class="col-md-4 col-form-label text-md-right"></label>
                            <div class="col-md-8">
                                <div id="exampleAccordion" data-children=".item">
                                    <div class="item">
                                        <button type="button" aria-expanded="false" aria-controls="exampleAccordion2" data-toggle="collapse" href="#collapseExample2" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></button>
                                        <div data-parent="#exampleAccordion" id="collapseExample2" class="collapse">
                                            <input id="anggota_eksternal2" type="text" class="form-control{{ $errors->has('anggota_eksternal2') ? ' is-invalid' : '' }}" name="anggota_eksternal2" value="{{ old('anggota_eksternal2') }}" autofocus>
                                            <input id="anggota_eksternal3" type="text" class="form-control{{ $errors->has('anggota_eksternal3') ? ' is-invalid' : '' }}" name="anggota_eksternal3" value="{{ old('anggota_eksternal3') }}" autofocus>
                                            <input id="anggota_eksternal4" type="text" class="form-control{{ $errors->has('anggota_eksternal4') ? ' is-invalid' : '' }}" name="anggota_eksternal4" value="{{ old('anggota_eksternal4') }}" autofocus>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="admin" class="col-md-4 col-form-label text-md-right">{{ __('Admin') }}</label>
                            <div class="col-md-8">
                                <input id="admin" type="text" class="form-control {{ $errors->has('admin') ? 'is-invalid' : '' }}" name="admin" value="{{ old('admin') }}">

                                @if ($errors->has('admin'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('admin') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="mahasiswa" class="col-md-4 col-form-label text-md-right">{{ __('Mahasiswa') }}</label>
                            <div class="col-md-8">
                                <input id="mahasiswa" type="text" class="form-control {{ $errors->has('mahasiswa') ? 'is-invalid' : '' }}" name="mahasiswa" value="{{ old('mahasiswa') }}">

                                @if ($errors->has('mahasiswa'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mahasiswa') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="alumni" class="col-md-4 col-form-label text-md-right">{{ __('Alumni') }}</label>
                            <div class="col-md-8">
                                <input id="alumni" type="text" class="form-control {{ $errors->has('alumni') ? 'is-invalid' : '' }}" name="alumni" value="{{ old('alumni') }}">

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
                            <label for="judul_pengabmas" class="col-md-4 col-form-label text-md-right">{{ __('Judul Pengabmas*') }}</label>
                            <div class="col-md-8">
                                <textarea id="judul_pengabmas" type="text" class="form-control {{ $errors->has('judul_pengabmas') ? 'is-invalid' : '' }}" name="judul_pengabmas" value="{{ old('judul_pengabmas') }}"></textarea>
                                @if ($errors->has('judul_pengabmas'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('judul_pengabmas') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="file" class="col-md-4 col-form-label text-md-right">{{ __('Berkas*') }}</label>
                            <div class="col-md-8">
                                <input id="file" type="file" class="form-control {{ $errors->has('file') ? 'is-invalid' : '' }}" name="file" value="{{ old('file') }}">
                                @if ($errors->has('file'))
                                <small class="text-muted">
                                    Dokumen Ms. Word (Format : Doc, Docx)
                                </small>
                                <div class="invalid-feedback">
                                    {{ $errors->first('file') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="luaran_wajib" class="col-md-4 col-form-label text-md-right">{{ __('Luaran Wajib*') }}</label>
                            <div class="col-md-8">
                                <select type="select" id="exampleCustomSelect" name="luaran_wajib" class="form-control {{ $errors->has('luaran_wajib') ? 'is-invalid' : '' }}">
                                    <option value="">- Pilih -</option>
                                    <option>Seminar Internasional</option>
                                    <option>Seminar Nasional</option>
                                    <option>Jurnal Internasional</option>
                                    <option>Jurnal Nasional</option>
                                    <option>Buku</option>
                                    <option>Publikasi Media Massa</option>
                                    <option>HAKI</option>
                                    <option>Lainnya</option>
                                </select>
                                @if ($errors->has('luaran_wajib'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('luaran_wajib') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="luaran_tambahan" class="col-md-4 col-form-label text-md-right">{{ __('Luaran Tambahan') }}</label>
                            <div class="col-md-8">
                                <select type="select" id="exampleCustomSelect" name="luaran_tambahan" class="form-control">
                                    <option value="">- Pilih -</option>
                                    <option>Seminar Internasional</option>
                                    <option>Seminar Nasional</option>
                                    <option>Jurnal Internasional</option>
                                    <option>Jurnal Nasional</option>
                                    <option>Buku</option>
                                    <option>Publikasi Media Massa</option>
                                    <option>HAKI</option>
                                    <option>Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="jenis_pengabmas" class="col-md-4 col-form-label text-md-right">{{ __('Jenis Pengabmas*') }}</label>
                            <div class="col-md-8">
                                <select type="select" id="exampleCustomSelect" name="jenis_pengabmas" class="form-control {{ $errors->has('jenis_pengabmas') ? 'is-invalid' : '' }}">
                                    <option value="">- Pilih -</option>
                                    <option value="1">Mandiri</option>
                                    <option value="2">Hibah PT</option>
                                </select>
                                @if ($errors->has('jenis_pengabmas'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('jenis_pengabmas') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="biaya_pengabmas" class="col-md-4 col-form-label text-md-right">{{ __('Biaya Pengabmas') }}</label>
                            <div class="col-md-8">
                                <div id="exampleAccordion" data-children=".item">
                                    <div class="item">
                                        <button type="button" aria-expanded="false" aria-controls="exampleAccordion2" data-toggle="collapse" href="#collapseExample6" class="btn btn-success btn-sm">Mandiri</button>
                                        <button type="button" aria-expanded="false" aria-controls="exampleAccordion2" data-toggle="collapse" href="#collapseExample4" class="btn btn-primary btn-sm">Hibah PT</button>
                                        <button type="button" aria-expanded="false" aria-controls="exampleAccordion2" data-toggle="collapse" href="#collapseExample5" class="btn btn-primary btn-danger btn-sm">Hibah Luar</button>
                                        <div data-parent="#exampleAccordion" id="collapseExample6" class="collapse">
                                            <input id="biaya_mandiri" type="text" class="form-control currency {{ $errors->has('biaya_mandiri') ? ' is-invalid' : '' }}" name="biaya_mandiri" value="{{ old('biaya_mandiri') }}" placeholder="Biaya Mandiri" autofocus>

                                            @if ($errors->has('biaya_mandiri'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('biaya_mandiri') }}
                                            </div>
                                            @endif
                                        </div>
                                        <div data-parent="#exampleAccordion" id="collapseExample4" class="collapse">
                                            <input id="biaya_hibah_pt" type="text" class="form-control currency {{ $errors->has('biaya_hibah_pt') ? ' is-invalid' : '' }}" name="biaya_hibah_pt" value="{{ old('biaya_hibah_pt') }}" placeholder="Hibah PT" autofocus>

                                            @if ($errors->has('biaya_hibah_pt'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('biaya_hibah_pt') }}
                                            </div>
                                            @endif
                                        </div>
                                        <div data-parent="#exampleAccordion" id="collapseExample5" class="collapse">
                                            <label>Pemberi Hibah</label>
                                            <input id="pemberi_hibah" type="text" class="form-control {{ $errors->has('pemberi_hibah') ? 'is-invalid' : '' }}" name="pemberi_hibah" value="{{ old('pemberi_hibah') }}" autofocus><br>

                                            <input id="biaya_hibah_luar" type="text" class="form-control currency {{ $errors->has('biaya_hibah_luar') ? 'is-invalid' : '' }}" name="biaya_hibah_luar" value="{{ old('biaya_hibah_luar') }}" placeholder="Hibah Luar PT" autofocus>

                                            @if ($errors->has('biaya_hibah_luar'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('biaya_hibah_luar') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tahun_pelaksanaan" class="col-md-4 col-form-label text-md-right">{{ __('Tahun Pelaksanaan') }}</label>
                            <div class="col-md-8">
                                <input id="tahun_pelaksanaan" type="text" class="form-control {{ $errors->has('tahun_pelaksanaan') ? 'is-invalid' : '' }}" name="tahun_pelaksanaan" value="{{ old('tahun_pelaksanaan') }}" autofocus>
                                @if ($errors->has('tahun_pelaksanaan'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('tahun_pelaksanaan') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <h4 class="mb-2">Tempat Pengabdian Masyarakat</h4>
                <p class="mb-3 text-muted">
                    Isi jika Pengabdian Masyarakat sudah di tentukan
                </p>
                <div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="nama_institusi" class="col-md-4 col-form-label text-md-right">{{ __('Nama Tempat') }}</label>
                                <div class="col-md-8">
                                    <input id="nama_institusi" type="text" class="form-control{{ $errors->has('nama_institusi') ? ' is-invalid' : '' }}" name="nama_institusi" value="{{ old('nama_institusi') }}" autofocus>
                                    @if ($errors->has('nama_institusi'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('nama_institusi') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="alamat" class="col-md-4 col-form-label text-md-right">{{ __('Alamat') }}</label>
                                <div class="col-md-8">
                                    <textarea id="alamat" type="text" class="form-control{{ $errors->has('alamat') ? ' is-invalid' : '' }}" name="alamat" value="{{ old('alamat') }}"></textarea>
                                    @if ($errors->has('alamat'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('alamat') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="position-relative row form-check">
                        <div class="col-sm-2 offset-sm-10">
                            <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane tabs-animation fade" id="tab-content-2" role="tabpanel">
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="mb-3">Download Format Usulan</h4>
                @foreach($unduh as $row)
                <a target="_blank" href="{{asset('/assets/file/usulan-pengabmas')}}/{{$row->file}}">
                    <button class="btn btn-primary"><i class="fa fa-download"></i> Unduh Format Usulan Pengabmas</button>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
@foreach($usulan as $row)
<div class="modal fade" id="myModal1{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                Hapus Data ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a title="Hapus" href="{!! url('/'.$row->id.'/delete-usulan-pengabmas') !!}" class="btn btn-danger"><i class="fa fa-trash"></i> Ok</a>
            </div>
        </div>
    </div>
</div>
@endforeach
@foreach($usulan as $row)
{!! Form::model($row, ['files'=>true, 'url' => ['/update-usulan-pengabmas', $row->id]]) !!}
<div class="modal fade" id="largeModal{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Usulan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <h5 class="mb-3">
                        Data Usulan
                    </h5>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="nama_ketua" class="col-md-3 col-form-label text-md-right">{{ __('Nama Ketua*') }}</label>
                            <div class="col-md-8">
                                <select type="select" name="nama_ketua" class="select2 {{ $errors->has('nama_ketua') ? 'is-invalid' : '' }}">
                                    <option value="">- Pilih Ketua -</option>
                                    @foreach($dosen as $d)
                                    <option value="{{$d->nik}}" <?php if ($row->nama_ketua == $d->nik) { ?> <?php echo 'selected'; ?> <?php } ?>>{{$d->name}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('nama_ketua'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nama_ketua') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="anggota_internal1" class="col-md-3 col-form-label text-md-right">{{ __('Anggota Internal') }}</label>
                            <div class="col-md-8">
                                <select type="select" name="anggota_internal1" class="select2">
                                    <option value="">- Pilih Anggota -</option>
                                    @foreach($dosen as $d)
                                    <option value="{{$d->nik}}" <?php if ($row->anggota_internal1 == $d->nik) { ?> <?php echo 'selected'; ?> <?php } ?>>{{$d->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="anggota_internal" class="col-md-3 col-form-label text-md-right"></label>
                            <div class="col-md-8">
                                <div id="exampleAccordion" data-children=".item">
                                    <div class="item">
                                        <button type="button" aria-expanded="false" aria-controls="exampleAccordion2" data-toggle="collapse" href="#collapseExample3" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></button>
                                        <div data-parent="#exampleAccordion" id="collapseExample3" class="collapse">
                                            <select type="select" name="anggota_internal2" class="form-control">
                                                <option value="">- Pilih Anggota -</option>
                                                @foreach($dosen as $row)
                                                <option value="{{$row->name}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                            <select type="select" name="anggota_internal3" class="form-control">
                                                <option value="">- Pilih Anggota -</option>
                                                @foreach($dosen as $row)
                                                <option value="{{$row->name}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                            <select type="select" name="anggota_internal4" class="form-control">
                                                <option value="">- Pilih Anggota -</option>
                                                @foreach($dosen as $row)
                                                <option value="{{$row->name}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @foreach($usulan as $row)
                        <div class="form-group row">
                            <label for="anggota_eksternal1" class="col-md-3 col-form-label text-md-right">{{ __('Anggota Eksternal') }}</label>
                            <div class="col-md-8">
                                <input id="anggota_eksternal1" type="text" class="form-control {{ $errors->has('anggota_eksternal1') ? 'is-invalid' : '' }}" name="anggota_eksternal1" value="{{ $row->anggota_eksternal1 }}" autofocus>
                            </div>
                            <label for="anggota_eksternal" class="col-md-3 col-form-label text-md-right"></label>
                            <div class="col-md-8">
                                <div id="exampleAccordion" data-children=".item">
                                    <div class="item">
                                        <button type="button" aria-expanded="false" aria-controls="exampleAccordion2" data-toggle="collapse" href="#collapseExample2" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></button>
                                        <div data-parent="#exampleAccordion" id="collapseExample2" class="collapse">
                                            <input id="anggota_eksternal2" type="text" class="form-control{{ $errors->has('anggota_eksternal2') ? ' is-invalid' : '' }}" name="anggota_eksternal2" value="{{ $row->anggota_eksternal2 }}" autofocus>
                                            <input id="anggota_eksternal3" type="text" class="form-control{{ $errors->has('anggota_eksternal3') ? ' is-invalid' : '' }}" name="anggota_eksternal3" value="{{ $row->anggota_eksternal3 }}" autofocus>
                                            <input id="anggota_eksternal4" type="text" class="form-control{{ $errors->has('anggota_eksternal4') ? ' is-invalid' : '' }}" name="anggota_eksternal4" value="{{ $row->anggota_eksternal4 }}" autofocus>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="admin" class="col-md-3 col-form-label text-md-right">{{ __('Admin') }}</label>
                            <div class="col-md-8">
                                <input id="admin" type="text" class="form-control {{ $errors->has('admin') ? 'is-invalid' : '' }}" name="admin" value="{{ $row->admin }}" autofocus>

                                @if ($errors->has('admin'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('admin') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="mahasiswa" class="col-md-3 col-form-label text-md-right">{{ __('Mahasiswa') }}</label>
                            <div class="col-md-8">
                                <input id="mahasiswa" type="text" class="form-control {{ $errors->has('mahasiswa') ? 'is-invalid' : '' }}" name="mahasiswa" value="{{ $row->mahasiswa }}" autofocus>

                                @if ($errors->has('mahasiswa'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mahasiswa') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="alumni" class="col-md-3 col-form-label text-md-right">{{ __('Alumni') }}</label>
                            <div class="col-md-8">
                                <input id="alumni" type="text" class="form-control {{ $errors->has('alumni') ? 'is-invalid' : '' }}" name="alumni" value="{{ $row->alumni }}" autofocus>

                                @if ($errors->has('alumni'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('alumni') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="judul_pengabmas" class="col-md-3 col-form-label text-md-right">{{ __('Judul Pengabmas*') }}</label>
                            <div class="col-md-8">
                                <textarea id="judul_pengabmas" type="text" class="form-control {{ $errors->has('judul_pengabmas') ? 'is-invalid' : '' }}" name="judul_pengabmas" required autofocus>{{ $row->judul_pengabmas }}</textarea>
                                @if ($errors->has('judul_pengabmas'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('judul_pengabmas') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="file" class="col-md-3 col-form-label text-md-right">{{ __('Berkas*') }}</label>
                            <div class="col-md-8">
                                <input id="file" type="file" class="form-control {{ $errors->has('file') ? 'is-invalid' : '' }}" name="file" value="{{ $row->file }}" autofocus>
                                @if ($errors->has('file'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('file') }}
                                </div>
                                @endif
                                <small class="text-muted">
                                    Dokumen Ms. Word (Format : Doc, Docx)
                                </small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="luaran_wajib" class="col-md-3 col-form-label text-md-right">{{ __('Luaran Wajib*') }}</label>
                            <div class="col-md-8">
                                <select type="select" id="exampleCustomSelect" name="luaran_wajib" class="form-control {{ $errors->has('luaran_wajib') ? 'is-invalid' : '' }}">
                                    <option value="">- Pilih -</option>
                                    <option <?php if ($row->luaran_wajib == 'Seminar Internasional') { ?> <?php echo 'selected'; ?> <?php } ?>>Seminar Internasional</option>
                                    <option <?php if ($row->luaran_wajib == 'Seminar Nasional') { ?> <?php echo 'selected'; ?> <?php } ?>>Seminar Nasional</option>
                                    <option <?php if ($row->luaran_wajib == 'Jurnal Internasional') { ?> <?php echo 'selected'; ?> <?php } ?>>Jurnal Internasional</option>
                                    <option <?php if ($row->luaran_wajib == 'Jurnal Nasional') { ?> <?php echo 'selected'; ?> <?php } ?>>Jurnal Nasional</option>
                                    <option <?php if ($row->luaran_wajib == 'Buku') { ?> <?php echo 'selected'; ?> <?php } ?>>Buku</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="jenis_pengabmas" class="col-md-3 col-form-label text-md-right">{{ __('Jenis Pengabmas*') }}</label>
                            <div class="col-md-8">
                                <select type="select" id="exampleCustomSelect" name="jenis_pengabmas" class="form-control">
                                    <option value="">- Pilih -</option>
                                    <option value="1" <?php if ($row->jenis_pengabmas == '1') { ?> <?php echo 'selected'; ?> <?php } ?>>Mandiri</option>
                                    <option value="2" <?php if ($row->jenis_pengabmas == '2') { ?> <?php echo 'selected'; ?> <?php } ?>>Hibah PT</option>
                                </select>
                                @if ($errors->has('jenis_pengabmas'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('jenis_pengabmas') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="biaya_pengabmas" class="col-md-3 col-form-label text-md-right">{{ __('Biaya Pengabmas') }}</label>
                            <div class="col-md-8">
                                <div id="exampleAccordion" data-children=".item">
                                    <div class="item">
                                        <button type="button" aria-expanded="false" aria-controls="exampleAccordion2" data-toggle="collapse" href="#collapseExample4" class="btn btn-primary btn-sm">Hibah PT</button>
                                        <button type="button" aria-expanded="false" aria-controls="exampleAccordion2" data-toggle="collapse" href="#collapseExample5" class="btn btn-primary btn-danger btn-sm">Hibah Luar</button>
                                        <div data-parent="#exampleAccordion" id="collapseExample4" class="collapse">
                                            <input id="biaya_hibah_pt" type="text" class="form-control currency {{ $errors->has('biaya_hibah_pt') ? ' is-invalid' : '' }}" name="biaya_hibah_pt" value="{{ $row->biaya_hibah_pt }}" autofocus>

                                            @if ($errors->has('biaya_hibah_pt'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('biaya_hibah_pt') }}
                                            </div>
                                            @endif
                                        </div>
                                        <div data-parent="#exampleAccordion" id="collapseExample5" class="collapse">
                                            <label>Pemberi Hibah</label>
                                            <input id="pemberi_hibah" type="text" class="form-control{{ $errors->has('pemberi_hibah') ? ' is-invalid' : '' }}" name="pemberi_hibah" value="{{ $row->pemberi_hibah }}" autofocus><br>

                                            <input id="biaya_hibah_luar" type="text" class="form-control currency {{ $errors->has('biaya_hibah_luar') ? ' is-invalid' : '' }}" name="biaya_hibah_luar" value="{{ $row->biaya_hibah_luar }}" autofocus>

                                            @if ($errors->has('biaya_hibah_luar'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('biaya_hibah_luar') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tahun_pelaksanaan" class="col-md-3 col-form-label text-md-right">{{ __('Tahun Pelaksanaan') }}</label>
                            <div class="col-md-8">
                                <input id="tahun_pelaksanaan" type="text" class="form-control{{ $errors->has('tahun_pelaksanaan') ? ' is-invalid' : '' }}" name="tahun_pelaksanaan" value="{{ $row->tahun_pelaksanaan }}" autofocus>
                                @if ($errors->has('tahun_pelaksanaan'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('tahun_pelaksanaan') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <h5 class="mb-3">
                        Tempat Pengabmas
                    </h5>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="nama_institusi" class="col-md-3 col-form-label text-md-right">{{ __('Tempat Pengabmas') }}</label>
                            <div class="col-md-8">
                                <input id="nama_institusi" type="text" class="form-control{{ $errors->has('nama_institusi') ? ' is-invalid' : '' }}" name="nama_institusi" value="{{ $row->nama_institusi }}" autofocus>
                                @if ($errors->has('nama_institusi'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nama_institusi') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="alamat" class="col-md-3 col-form-label text-md-right">{{ __('Alamat') }}</label>
                            <div class="col-md-8">
                                <textarea id="alamat" type="text" class="form-control{{ $errors->has('alamat') ? ' is-invalid' : '' }}" name="alamat">{{ $row->alamat }}</textarea>
                                @if ($errors->has('alamat'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('alamat') }}
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
        </div>
    </div>
</div>
{!! Form::close() !!}
@endforeach
@endsection