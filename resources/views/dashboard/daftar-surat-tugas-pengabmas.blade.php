@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-body">
                <a href="{{url('/download-all-surat-tugas-pengabmas')}}" target="_blank">
                    <button type="button" class="btn mr-2 mb-3 btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-download"></i> Download All</button>
                </a>
                <br>
                <div class="table-responsive">
                    <table id="example" class="mb-0 table table-sm table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center" width="10%">Nomor Surat</th>
                                <th class="text-center">Nama Ketua</th>
                                <th class="text-center">Judul pengabmas</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($surattugas as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td>{{$value->no_surat}}</td>
                                <td>{{$value->nama_ketua}}</td>
                                <td>{{$value->judul_pengabmas}}</td>
                                <td align="center"><a title="Download Surat Tugas Pengabmas" href="{!! url('/'.$value->id.'/download-surat-tugas-pengabmas') !!}" target="_blank"><button class="btn btn-success">
                                            <i class="fa fa-download"></i></button></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>
@endsection