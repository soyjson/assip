@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0">
            <strong>Ploting Reviewer Penelitian</strong>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
            <strong>Ploting Reviewer Pengabdian Masyarakat</strong>
        </a>
    </li>
</ul>
@if(Auth::user()->level == 5)
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="card mb-3">
            <div class="card-body">
                <div class="col-md-12">
                    <h5 class="card-title">DATA PLOTING REVIEW PENELITIAN</h5>
                    <form action="{{url('downloadreviewerusulanpenelitianExcel/csv')}}" method="GET" target="_blank">
                        <button class="btn btn-primary float-right mb-3">Download</button>
                    </form>
                    @if(Auth::user()->level == 5)
                    <div class="table-responsive">
                        <table id="example1" class="mb-0 table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Pengusul</th>
                                    <th class="text-center">Judul Penelitian</th>
                                    <th class="text-center">File</th>
                                    <th class="text-center">Tanggal Usulan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reviewerpenelitian as $key => $value)
                                <tr>
                                    <td align="center">{{$key+1}}</td>
                                    <td>{{$value->name}}</td>
                                    <td>{{$value->judul_penelitian}}</td>
                                    <td align="center"><a target="_blank" href="{{asset('/assets/file/usulan-penelitian')}}/{{$value->file}}"><i class="fa fa-download"></i></a></td>
                                    <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                                    <td align="center">
                                        <a title="Beri Tanggapan" href="{!! url('/'.$value->id.'/tanggapan-usulan-penelitian') !!}"><i class="fa fa-pen"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
        <div class="card mb-3">
            <div class="card-body">
                <div class="col-md-12">
                    <h5 class="card-title">DATA PLOTING REVIEW PENGABDIAN MASYARAKAT</h5>
                    <form action="{{url('downloadreviewerusulanpengabmasExcel/csv')}}" method="GET" target="_blank">
                        <button class="btn btn-primary float-right mb-3">Download</button>
                    </form>

                    @if(Auth::user()->level == 5)
                    <div class="table-responsive mt-3">
                        <table id="example4" class="mb-0 table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Pengusul</th>
                                    <th class="text-center">Judul Pengabmas</th>
                                    <th class="text-center">File</th>
                                    <th class="text-center">Tanggal Usulan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reviewerpengabmas as $key => $value)
                                <tr>
                                    <td align="center">{{$key+1}}</td>
                                    <td>{{$value->name}}</td>
                                    <td>{{$value->judul_pengabmas}}</td>
                                    <td align="center"><a target="_blank" href="{{asset('/assets/file/usulan-pengabmas')}}/{{$value->file}}"><i class="fa fa-download"></i></a></td>
                                    <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                                    <td align="center">
                                        <a title="Beri Tanggapan" href="{!! url('/'.$value->id.'/tanggapan-usulan-pengabmas') !!}"><i class="fa fa-pen"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!--  -->

@if(Auth::user()->level != 5)
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="card mb-3">
            <div class="card-body">
                @if(Auth::user()->level != 4 and Auth::user()->level != 5)

                <h5 class="mb-3">Atur Ploting Reviewer Penelitian</h5>
                {!! Form::open(['url' => ['/simpan-plot-reviewer-penelitian']]) !!}
                <div class="form-group row">
                    <label for="judul_penelitian" class="col-md-3 col-form-label">{{ __('Judul Penelitian*') }}</label>
                    <div class="col-md-9">
                        <select type="select" name="judul_penelitian" class="form-control select2{{ $errors->has('judul_penelitian') ? ' is-invalid' : '' }}" style="width: 100%;">
                            <option value="">- Pilih -</option>
                            @foreach($usulanpenelitian as $row)
                            <option value="{{$row->id_usulan}}">{{$row->judul_penelitian}}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('judul_penelitian'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('judul_penelitian') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="reviewer1" class="col-md-3 col-form-label">{{ __('Reviewer 1') }}</label>
                    <div class="col-md-9">
                        <select type="select" name="reviewer1" class="form-control select2" style="width: 100%;">
                            <option value="">- Pilih -</option>
                            @foreach($reviewer as $row)
                            <option>{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="reviewer2" class="col-md-3 col-form-label">{{ __('Reviewer 2') }}</label>
                    <div class="col-md-9">
                        <select type="select" name="reviewer2" class="form-control select2" style="width: 100%;">
                            <option value="">- Pilih -</option>
                            @foreach($reviewer as $row)
                            <option>{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label"></label>
                    <div class="col-md-9">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                {!!Form::close()!!}
                @endif
                <hr />
                @if(Auth::user()->level != 4 and Auth::user()->level != 5)
                <div class="table-responsive">
                    <table id="example2" class="mb-0 table table-sm table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama Pengusul</th>
                                <th class="text-center">Judul Penelitian</th>
                                <th class="text-center">Reviewer</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plotpenelitian as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td>{{$value->name}}</td>
                                <td>{{$value->judul_penelitian}}</td>
                                <td width="30%">
                                    <table class="mb-0 table table-sm table-bordered table-hover table-striped">
                                        <tr>
                                            <th width="40%">Reviewer 1</th>
                                            <td width="60%">{{$value->reviewer1}}</td>
                                        </tr>
                                        <tr>
                                            <th>Reviewer 2</th>
                                            <td>{{$value->reviewer2}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                                <td align="center">
                                    <a title="Hapus" href="#" type="button" class="btn btn-danger btn-sm tooltipku" data-toggle="modal" data-target="#myModal1{{$value->id}}"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @elseif(Auth::user()->level == 4)
                <div class="table-responsive">
                    <table id="example3" class="mb-0 table table-sm table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Judul Penelitian</th>
                                <th class="text-center">Reviewer</th>
                                <th class="text-center">Tanggal Ploting</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plotdosenpenelitian as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td>{{$value->judul_penelitian}}</td>
                                <td width="30%">
                                    <table class="mb-0 table table-sm table-bordered table-hover table-striped">
                                        <tr>
                                            <th width="40%">Reviewer 1</th>
                                            <td width="60%">{{$value->reviewer1}}</td>
                                        </tr>
                                        <tr>
                                            <th>Reviewer 2</th>
                                            <td>{{$value->reviewer2}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
        <div class="card mb-3">
            <div class="card-body">
                @if(Auth::user()->level != 4 and Auth::user()->level != 5)
                <h5 class="mb-3">Atur Ploting Reviewer Pengabdian Masyarakat</h5>
                {!! Form::open(['url' => ['/simpan-plot-reviewer-pengabmas']]) !!}
                <div class="form-group row">
                    <label for="judul_pengabmas" class="col-md-3 col-form-label">{{ __('Judul Pengabmas*') }}</label>
                    <div class="col-md-9">
                        <select type="select" name="judul_pengabmas" class="form-control select2{{ $errors->has('judul_pengabmas') ? ' is-invalid' : '' }}" style="width: 100%;">
                            <option value="">- Pilih -</option>
                            @foreach($usulanpengabmas as $row)
                            <option value="{{$row->id_usulan}}">{{$row->judul_pengabmas}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('judul_pengabmas'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('judul_pengabmas') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="reviewer1" class="col-md-3 col-form-label">{{ __('Reviewer 1') }}</label>
                    <div class="col-md-9">
                        <select type="select" name="reviewer1" class="form-control select2" style="width: 100%;">
                            <option value="">- Pilih -</option>
                            @foreach($reviewer as $row)
                            <option>{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="reviewer2" class="col-md-3 col-form-label">{{ __('Reviewer 2') }}</label>
                    <div class="col-md-9">
                        <select type="select" name="reviewer2" class="form-control select2" style="width: 100%;">
                            <option value="">- Pilih -</option>
                            @foreach($reviewer as $row)
                            <option>{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label"></label>
                    <div class="col-md-9">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                {!!Form::close()!!}
                @endif
                <hr />
                @if(Auth::user()->level != 4 and Auth::user()->level != 5)
                <div class="table-responsive">
                    <table id="example5" class="mb-0 table table-sm table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama Pengusul</th>
                                <th class="text-center">Judul Pengabmas</th>
                                <th class="text-center">Reviewer</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plotpengabmas as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td>{{$value->name}}</td>
                                <td>{{$value->judul_pengabmas}}</td>
                                <td width="30%">
                                    <table class="mb-0 table table-sm table-bordered table-hover table-striped">
                                        <tr>
                                            <th width="40%">Reviewer 1</th>
                                            <td width="60%">{{$value->reviewer1}}</td>
                                        </tr>
                                        <tr>
                                            <th>Reviewer 2</th>
                                            <td>{{$value->reviewer2}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                                <td align="center">
                                    <a title="Hapus" href="#" type="button" class="btn btn-danger btn-sm tooltipku" data-toggle="modal" data-target="#myModal1{{$value->id}}"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @elseif(Auth::user()->level == 4)
                <div class="table-responsive">
                    <table id="example6" class="mb-0 table table-sm table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Judul Penelitian</th>
                                <th class="text-center">Reviewer</th>
                                <th class="text-center">Tanggal Ploting</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plotdosenpengabmas as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td>{{$value->judul_penelitian}}</td>
                                <td width="30%">
                                    <table class="mb-0 table table-sm table-bordered table-hover table-striped">
                                        <tr>
                                            <th width="40%">Reviewer 1</th>
                                            <td width="60%">{{$value->reviewer1}}</td>
                                        </tr>
                                        <tr>
                                            <th>Reviewer 2</th>
                                            <td>{{$value->reviewer2}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td align="center">{{date_format(date_create($value->tanggal),"d F Y")}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('modal')
@foreach($plotpenelitian as $row)
<div class="modal fade" id="myModal1{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                Hapus Data ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a title="Hapus" href="{!! url('/'.$row->id.'/delete-plot-reviewer-penelitian') !!}" class="btn btn-danger"><i class="fa fa-trash"></i> Ok</a>
            </div>
        </div>
    </div>
</div>
@endforeach
@foreach($plotpengabmas as $row)
<div class="modal fade" id="myModal1{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                Hapus Data ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a title="Hapus" href="{!! url('/'.$row->id.'/delete-plot-reviewer-pengabmas') !!}" class="btn btn-danger"><i class="fa fa-trash"></i> Ok</a>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection