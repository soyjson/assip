@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0">
            <strong>Daftar Review Penelitian</strong>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
            <strong>Daftar Review Pengabdian Masyarakat</strong>
        </a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="card mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="mb-0 table table-sm table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Pengusul</th>
                                <th class="text-center">Judul Penelitian</th>
                                <th class="text-center">Reviewer</th>
                                <th class="text-center">File Komentar</th>
                                <th class="text-center">Tanggapan</th>
                                <th class="text-center">Nilai</th>
                                <th class="text-center">Jenis Laporan</th>
                                <th class="text-center">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(Auth::user()->level == '4')
                            @foreach($penelitianpersonal as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td>{{$value->name}}</td>
                                <td>{{$value->judul_penelitian}}</td>
                                <td>{{$value->reviewer1}}
                                    <p></p>{{$value->reviewer2}}
                                </td>
                                <td align="center"><a target="_blank" href="{{asset('/assets/file/tanggapan-usulan-penelitian')}}/{{$value->file}}"><i class="fa fa-download"></i></a></td>
                                <td>{!!$value->tanggapan!!}</td>
                                <td>
                                    @if($value->jenis_laporan == 'usulan')
                                    <a title="Lihat Detail Nilai" target="_blank" href="{!! url('/'.$value->id_usulan.'/lihat-penilaian-usulan-penelitian') !!}">
                                        <button class="btn btn-light">
                                            <strong>{!!$value->nilai!!}</strong>
                                        </button>
                                    </a>
                                    @else
                                    <a title="Lihat Detail Nilai" target="_blank" href="{!! url('/'.$value->id_usulan.'/lihat-penilaian-penelitian') !!}">
                                        <button class="btn btn-light">
                                            <strong>{!!$value->nilai!!}</strong>
                                        </button>
                                    </a>
                                    @endif
                                </td>
                                <td align="center">
                                    @if($value->jenis_laporan == 'usulan')
                                    <label class="badge badge-pill badge-primary">{!!$value->jenis_laporan!!}</label>
                                    @elseif($value->jenis_laporan == 'kemajuan')
                                    <label class="badge badge-pill badge-success">{!!$value->jenis_laporan!!}</label>
                                    @elseif($value->jenis_laporan == 'akhir')
                                    <label class="badge badge-pill badge-danger">{!!$value->jenis_laporan!!}</label>
                                    @endif
                                </td>
                                <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                            </tr>
                            @endforeach

                            @else

                            @foreach($penelitian as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td>{{$value->name}}</td>
                                <td>{{$value->judul_penelitian}}</td>
                                <td>{{$value->reviewer1}}
                                    <p></p>{{$value->reviewer2}}
                                </td>
                                <td align="center"><a target="_blank" href="{{asset('/assets/file/tanggapan-usulan-penelitian')}}/{{$value->file}}"><i class="fa fa-download"></i></a></td>
                                <td>{!!$value->tanggapan!!}</td>
                                <td>
                                    @if($value->jenis_laporan == 'usulan')
                                    <a title="Lihat Detail Nilai" target="_blank" href="{!! url('/'.$value->id_usulan.'/lihat-penilaian-usulan-penelitian') !!}">
                                        <button class="btn btn-light">
                                            <strong>{!!$value->nilai!!}</strong>
                                        </button>
                                    </a>
                                    @else
                                    <a title="Lihat Detail Nilai" target="_blank" href="{!! url('/'.$value->id_usulan.'/lihat-penilaian-penelitian') !!}">
                                        <button class="btn btn-light">
                                            <strong>{!!$value->nilai!!}</strong>
                                        </button>
                                    </a>
                                    @endif
                                </td>
                                <td align="center">
                                    @if($value->jenis_laporan == 'usulan')
                                    <label class="badge badge-pill badge-primary">{!!$value->jenis_laporan!!}</label>
                                    @elseif($value->jenis_laporan == 'kemajuan')
                                    <label class="badge badge-pill badge-success">{!!$value->jenis_laporan!!}</label>
                                    @elseif($value->jenis_laporan == 'akhir')
                                    <label class="badge badge-pill badge-danger">{!!$value->jenis_laporan!!}</label>
                                    @endif
                                </td>
                                <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
        <div class="card mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example1" class="mb-0 table table-sm table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Pengusul</th>
                                <th class="text-center">Judul pengabmas</th>
                                <th class="text-center">Reviewer</th>
                                <th class="text-center">File Komentar</th>
                                <th class="text-center">Tanggapan</th>
                                <th class="text-center">Nilai</th>
                                <th class="text-center">Jenis Laporan</th>
                                <th class="text-center">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(Auth::user()->level == '4')
                            @foreach($pengabmaspersonal as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td>{{$value->name}}</td>
                                <td>{{$value->judul_pengabmas}}</td>
                                <td>{{$value->reviewer1}}
                                    <p></p>{{$value->reviewer2}}
                                </td>
                                <td align="center"><a target="_blank" href="{{asset('/assets/file/tanggapan-usulan-pengabmas')}}/{{$value->file}}"><i class="fa fa-download"></i></a></td>
                                <td>{!!$value->tanggapan!!}</td>
                                <td>
                                    @if($value->jenis_laporan == 'usulan')
                                    <a title="Lihat Detail Nilai" target="_blank" href="{!! url('/'.$value->id_usulan.'/lihat-penilaian-usulan-pengabmas') !!}">
                                        <button class="btn btn-light">
                                            <strong>{!!$value->nilai!!}</strong>
                                        </button>
                                    </a>
                                    @else
                                    <a title="Lihat Detail Nilai" target="_blank" href="{!! url('/'.$value->id_usulan.'/lihat-penilaian-pengabmas') !!}">
                                        <button class="btn btn-light">
                                            <strong>{!!$value->nilai!!}</strong>
                                        </button>
                                    </a>
                                    @endif
                                </td>
                                <td align="center">
                                    @if($value->jenis_laporan == 'usulan')
                                    <label class="badge badge-pill badge-primary">{!!$value->jenis_laporan!!}</label>
                                    @elseif($value->jenis_laporan == 'kemajuan')
                                    <label class="badge badge-pill badge-success">{!!$value->jenis_laporan!!}</label>
                                    @elseif($value->jenis_laporan == 'akhir')
                                    <label class="badge badge-pill badge-danger">{!!$value->jenis_laporan!!}</label>
                                    @endif
                                </td>
                                <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                            </tr>
                            @endforeach

                            @else

                            @foreach($pengabmas as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td>{{$value->name}}</td>
                                <td>{{$value->judul_pengabmas}}</td>
                                <td>{{$value->reviewer1}}
                                    <p></p>{{$value->reviewer2}}
                                </td>
                                <td align="center"><a target="_blank" href="{{asset('/assets/file/tanggapan-usulan-pengabmas')}}/{{$value->file}}"><i class="fa fa-download"></i></a></td>
                                <td>{!!$value->tanggapan!!}</td>
                                <td>
                                    @if($value->jenis_laporan == 'usulan')
                                    <a title="Lihat Detail Nilai" target="_blank" href="{!! url('/'.$value->id_usulan.'/lihat-penilaian-usulan-pengabmas') !!}">
                                        <button class="btn btn-light">
                                            <strong>{!!$value->nilai!!}</strong>
                                        </button>
                                    </a>
                                    @else
                                    <a title="Lihat Detail Nilai" target="_blank" href="{!! url('/'.$value->id_usulan.'/lihat-penilaian-pengabmas') !!}">
                                        <button class="btn btn-light">
                                            <strong>{!!$value->nilai!!}</strong>
                                        </button>
                                    </a>
                                    @endif
                                </td>
                                <td align="center">
                                    @if($value->jenis_laporan == 'usulan')
                                    <label class="badge badge-pill badge-primary">{!!$value->jenis_laporan!!}</label>
                                    @elseif($value->jenis_laporan == 'kemajuan')
                                    <label class="badge badge-pill badge-success">{!!$value->jenis_laporan!!}</label>
                                    @elseif($value->jenis_laporan == 'akhir')
                                    <label class="badge badge-pill badge-danger">{!!$value->jenis_laporan!!}</label>
                                    @endif
                                </td>
                                <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection