@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')

@if(Auth::user()->level == 1)
@if (env('MAIL_HOST') == 'mail.yourdomain.id')
<div class="alert alert-warning" role="alert">
    <i class="fas fa-exclamation-triangle text-danger"></i> Tidak dapat mengirimkan Email notifikasi, Mohon atur pengaturan Email SMTP (Email Host, Port, Username, Password) di file <strong>.env</strong> terlebih dahulu. File <strong>.env</strong> terletak di direktori paling luar project laravel. <a href="https://laravel.com/docs/5.8/configuration" class="text-dark" target="_blank">Selengkapnya</a>
</div>
@endif
@endif

@if(Auth::user()->level == 1)
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="small-box bg-gradient-info d-flex align-items-baseline" style="height: 128px;">
                    <div class="inner">
                        <h5 class="font-weight-bold">Usulan Penelitian</h5>
                        <ul class="nav flex-column">
                            @foreach($usulan_penelitian as $row)
                            <li class="nav-item">
                                Usulan {{$row->status}}
                                @if($row->status == 'pengajuan')
                                <span class="float-right badge bg-primary">{{$row->jumlah}}</span>
                                @elseif($row->status == 'di terima')
                                <span class="float-right badge bg-success">{{$row->jumlah}}</span>
                                @else
                                <span class="float-right badge bg-danger">{{$row->jumlah}}</span>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="icon">
                        <i class="far fa-bookmark"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="small-box bg-gradient-warning d-flex align-items-baseline" style="height: 128px;">
                    <div class="inner">
                        <h5 class="font-weight-bold">Usulan Pengabmas</h5>
                        <ul class="nav flex-column">
                            @foreach($usulan_pengabmas as $row)
                            <li class="nav-item">
                                Usulan {{$row->status}}
                                @if($row->status == 'pengajuan')
                                <span class="float-right badge bg-primary">{{$row->jumlah}}</span>
                                @elseif($row->status == 'di terima')
                                <span class="float-right badge bg-success">{{$row->jumlah}}</span>
                                @else
                                <span class="float-right badge bg-danger">{{$row->jumlah}}</span>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="icon">
                        <i class="far fa-bookmark"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="small-box bg-gradient-success d-flex align-items-baseline" style="height: 128px;">
                    <div class="inner">
                        <h5 class="font-weight-bold">Penelitian Selesai</h5>
                        <ul class="nav flex-column">
                            @foreach($penelitian_selesai as $row)
                            <li class="nav-item">
                                Penelitian selesai
                                <span class="float-right badge bg-dark">{{$row->jumlah}}</span>
                            </li>
                            @endforeach
                        </ul>

                    </div>
                    <div class="icon">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="small-box bg-gradient-success d-flex align-items-baseline" style="height: 128px;">
                    <div class="inner">
                        <h5 class="font-weight-bold">Pengabmas Selesai</h5>
                        <ul class="nav flex-column">
                            @foreach($pengabmas_selesai as $row)
                            <li class="nav-item">Pengabmas selesai
                                <span class="float-right badge bg-dark">{{$row->jumlah}}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card mb-3">
                    <div class="card-header-tab card-header-tab-animation card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list mr-1"></i> Usulan Baru
                        </h3>
                        <div class="card-tools">
                            <ul class="nav nav-pills ml-auto">
                                <li class="nav-item"><a class="nav-link active" href="#tab-eg1-0" data-toggle="tab">Penelitian</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tab-eg1-1" data-toggle="tab">Pengabmas</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-eg1-0" role="tabpanel">
                                <ul class="products-list product-list-in-card pl-2 pr-2">
                                    @foreach($usulan_penelitian_baru as $key => $value)
                                    <li class="item">
                                        <div class="product-img">
                                            @if($value->foto)
                                            <img src="{{asset('/assets/image/foto')}}/{{$value->foto}}" width="50" class="img-size-50" alt="Foto">
                                            @else
                                            <img src="{{asset('/assets/image/foto/default.png')}}" width="50" class="img-size-50" alt="Foto">
                                            @endif
                                        </div>
                                        <div class="product-info">
                                            <span class="float-right">{{date_format(date_create($value->tanggal),"d F Y")}}</span>
                                            <h6 class="mb-0">{{$value->judul_penelitian}}</h6>
                                            <p>{{$value->name}}</p>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="tab-pane" id="tab-eg1-1" role="tabpanel">
                                <ul class="products-list product-list-in-card pl-2 pr-2">
                                    @foreach($usulan_pengabmas_baru as $key => $value)
                                    <li class="item">
                                        <div class="product-img">
                                            @if($value->foto)
                                            <img src="{{asset('/assets/image/foto')}}/{{$value->foto}}" width="50" class="img-size-50" alt="Foto">
                                            @else
                                            <img src="{{asset('/assets/image/foto/default.png')}}" width="50" class="img-size-50" alt="Foto">
                                            @endif
                                        </div>
                                        <div class="product-info">
                                            <span class="float-right">{{date_format(date_create($value->tanggal),"d F Y")}}</span>
                                            <h6 class="mb-0">{{$value->judul_pengabmas}}</h6>
                                            <p>{{$value->name}}</p>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 card">
                    <div class="card-header-tab card-header-tab-animation card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar mr-1"></i> Grafik Tahunan
                        </h3>
                        <div class="card-tools">
                            <ul class="nav nav-pills ml-auto">
                                <li class="nav-item"><a class="nav-link active" href="#tab-eg5-0" data-toggle="tab">Usulan Penelitian</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tab-eg5-1" data-toggle="tab">Usulan Pengabmas</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-eg5-0" role="tabpanel">
                                <div id="usulan_penelitian"></div>
                            </div>
                            <div class="tab-pane" id="tab-eg5-1" role="tabpanel">
                                <div id="usulan_pengabmas"></div>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tabs-eg-77">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@elseif(Auth::user()->level == 4)
<div class="row">
    <div class="col-md-6 col-xl-6">
        <div class="card bg-gradient-info">
            <div class="card-body">
                <h4 class="mb-3">Usulan Penelitian</h4>
                @foreach($usulan_penelitian_dosen as $row)
                <h5>Usulan {{$row->status}} : {{$row->jumlah}}</h5>
                @endforeach
                @foreach($penelitian_selesai_dosen as $row)
                <h5>Penelitian Selesai : {{$row->jumlah}}</h5>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-6">
        <div class="card bg-gradient-warning">
            <div class="card-body">
                <h4 class="mb-3">Usulan Pengabdian Masyarakat</h4>
                @foreach($usulan_pengabmas_dosen as $row)
                <h5>Usulan {{$row->status}} : {{$row->jumlah}}</h5>
                @endforeach
                @foreach($pengabmas_selesai_dosen as $row)
                <h5>Pengabmas Selesai : {{$row->jumlah}}</h5>
                @endforeach
            </div>
        </div>
    </div>
</div>
@else
<div class="mb-3 card">
    <div class="card-header-tab card-header-tab-animation card-header">
        <h3 class="card-title">
            <i class="fas fa-chart-bar mr-1"></i> Grafik Tahunan
        </h3>
        <div class="card-tools">
            <ul class="nav nav-pills ml-auto">
                <li class="nav-item"><a class="nav-link active" href="#tab-eg5-0" data-toggle="tab">Usulan Penelitian</a></li>
                <li class="nav-item"><a class="nav-link" href="#tab-eg5-1" data-toggle="tab">Usulan Pengabmas</a></li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane active" id="tab-eg5-0" role="tabpanel">
                <div id="usulan_penelitian"></div>
            </div>
            <div class="tab-pane" id="tab-eg5-1" role="tabpanel">
                <div id="usulan_pengabmas"></div>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="tabs-eg-77">

            </div>
        </div>
    </div>
</div>
@endif

<script type="text/javascript">
    $(function() {
        var data_pengajuan = <?php echo $tampil_usulan_penelitian; ?>;
        var data_diterima = <?php echo $penelitian_diterima; ?>;
        var data_ditolak = <?php echo $penelitian_ditolak; ?>;
        var data_tgl = <?php echo $tgl; ?>;
        $('#usulan_penelitian').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Grafik Usulan Penelitian'
            },
            xAxis: {
                categories: data_tgl
            },
            yAxis: {
                title: {
                    text: 'Usulan'
                }
            },
            series: [{
                    name: 'Pengajuan',
                    data: data_pengajuan,
                    color: '#0679f9'
                },

                {
                    name: 'Di terima',
                    data: data_diterima,
                    color: '#28a745'
                },

                {
                    name: 'Di tolak',
                    data: data_ditolak,
                    color: '#dc3545'
                }
            ]
        });
    });
</script>
<script type="text/javascript">
    $(function() {
        var data_pengajuan = <?php echo $tampil_usulan_pengabmas; ?>;
        var data_diterima = <?php echo $pengabmas_diterima; ?>;
        var data_ditolak = <?php echo $pengabmas_ditolak; ?>;
        var data_tgl = <?php echo $tgl; ?>;
        $('#usulan_pengabmas').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Grafik Usulan pengabmas'
            },
            xAxis: {
                categories: data_tgl
            },
            yAxis: {
                title: {
                    text: 'Usulan'
                }
            },
            series: [{
                    name: 'Pengajuan',
                    data: data_pengajuan,
                    color: '#0679f9'
                },

                {
                    name: 'Di terima',
                    data: data_diterima,
                    color: '#28a745'
                },

                {
                    name: 'Di tolak',
                    data: data_ditolak,
                    color: '#dc3545'
                }
            ]
        });
    });
</script>
@endsection