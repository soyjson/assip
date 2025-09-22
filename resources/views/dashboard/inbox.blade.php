@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0">
            <strong>Penelitian</strong>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
            <strong>Pengabdian Masyarakat</strong>
        </a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="mb-3">Data Usulan Penelitian Terkait</h4>
                <div class="table-responsive">
                    <table id="example" class="mb-0 table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Judul</th>
                                <th class="text-center">Dosen Pengusul</th>
                                <th class="text-center">Nama Ketua</th>
                                <th class="text-center">Anggota Internal</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usulanpenelitian as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td width="40%">{{$value->judul_penelitian}}</td>
                                <td align="center">{{$value->dosen_pengusul}}</td>
                                <td align="center">{{$value->nama_ketua ?? "-"}}</td>
                                <td>
                                    <ol class="pl-3">
                                        <li>{{$value->anggota_internal1 ?? "-"}}</li>
                                        <li>{{$value->anggota_internal2 ?? "-"}}</li>
                                        <li>{{$value->anggota_internal3 ?? "-"}}</li>
                                        <li>{{$value->anggota_internal4 ?? "-"}}</li>
                                    </ol>
                                </td>
                                <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                                <td align="center">{{$value->status}}</td>
                                <td align="center">
                                    <a title="Lihat Penilaian" href="{!! url('/'.$value->id_usulan.'/lihat-penilaian-usulan-penelitian') !!}" target="_blank"><button class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></button></a>
                                    @if($value->status == 'pengajuan')

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
                <h4 class="mb-3">Data Usulan PengabMas Terkait</h4>
                <div class="table-responsive">
                    <table id="example1" class="mb-0 table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Judul</th>
                                <th class="text-center">Dosen Pengusul</th>
                                <th class="text-center">Nama Ketua</th>
                                <th class="text-center">Anggota Internal</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usulanpengabmas as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td width="40%">{{$value->judul_pengabmas}}</td>
                                <td align="center">{{$value->dosen_pengusul}}</td>
                                <td align="center">{{$value->nama_ketua ?? "-"}}</td>
                                <td>
                                    <ol class="pl-3">
                                        <li>{{$value->anggota_internal1 ?? "-"}}</li>
                                        <li>{{$value->anggota_internal2 ?? "-"}}</li>
                                        <li>{{$value->anggota_internal3 ?? "-"}}</li>
                                        <li>{{$value->anggota_internal4 ?? "-"}}</li>
                                    </ol>
                                </td>
                                <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                                <td align="center">{{$value->status}}</td>
                                <td align="center">
                                    <a title="Lihat Penilaian" href="{!! url('/'.$value->id_usulan.'/lihat-penilaian-usulan-pengabmas') !!}" target="_blank"><button class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></button></a>
                                    @if($value->status == 'pengajuan')

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
</div>


@endsection