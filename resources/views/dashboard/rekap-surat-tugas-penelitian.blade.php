@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')
<div class="tab-content">
    <div class="card mb-3">
        <div class="card-body">
            <h3 class="mb-3">Download Surat Tugas</h3>
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
                            <td align="center"><a title="Download Surat Tugas Penelitian" href="{!! url('/'.$value->id.'/download-surat-tugas-penelitian') !!}" target="_blank"><button class="btn btn-success">
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