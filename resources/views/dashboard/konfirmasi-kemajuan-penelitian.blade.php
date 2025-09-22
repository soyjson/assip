@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0">
            <strong>Update Laporan Kemajuan</strong>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
            <strong>Laporan telah di Baca</strong>
        </a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="card mb-3">
            <div class="card-body">
                <h3 class="mb-3">Data Laporan Kemajuan Baru</h3>
                <form action="{{url('downloadkemajuanpenelitianExcel/xls')}}" method="GET" target="_blank">
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
                                <th class="text-center">Jenis Berkas</th>
                                <th class="text-center">Presentase Kemajuan</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kemajuanbaru as $key => $value)
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
                                <td>{{$value->judul_penelitian}}</td>
                                <td align="center"><a target="_blank" href="{{asset('/assets/file/laporan-kemajuan-penelitian')}}/{{$value->file}}"><i class="fa fa-download"></i></a></td>
                                <td>{{$value->jenis_berkas}}</td>
                                <td align="center">{{$value->presentase_kemajuan}} %</td>

                                <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                                <td align="center">
                                    <a title="Selesai Baca" href="{!! url('/'.$value->id.'/check-kemajuan-penelitian') !!}" class="btn btn-success btn-sm"><i class="fa fa-check"></i></a>
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
                <h3 class="mb-3">Data Laporan Kemajuan di Lihat</h3>
                <div class="table-responsive">
                    <table id="example1" class="mb-0 table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Pengusul</th>
                                <th class="text-center">Judul</th>
                                <th class="text-center">File</th>
                                <th class="text-center">Jenis Berkas</th>
                                <th class="text-center">Presentase Kemajuan</th>

                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kemajuandilihat as $key => $value)
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
                                <td>{{$value->judul_penelitian}}</td>
                                <td align="center"><a target="_blank" href="{{asset('/assets/file/laporan-kemajuan-penelitian')}}/{{$value->file}}"><i class="fa fa-download"></i></a></td>
                                <td>{{$value->jenis_berkas}}</td>
                                <td align="center">{{$value->presentase_kemajuan}} %</td>

                                <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                                <td align="center">{{$value->status}}</td>
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