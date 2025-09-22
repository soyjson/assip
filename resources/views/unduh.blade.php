@extends('layouts.app')
@extends('layouts.alert')
@section('content')
<!-- Jumbotron -->
<div id="intro" class="p-5 text-center bg-image" style="background-image: url('./assets/image/body/4117072.jpg') !important;background-position: center;background-repeat: no-repeat;-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;">
    <div class="mask" style="background-color: rgba(0, 0, 0, 0.4);">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="text-white">
                <h1 class="mb-0">Unduhan</h1>
            </div>
        </div>
    </div>
</div>
<!-- Jumbotron -->
<div class="pt-3">
    <div class="card mb-3">
        <div class="card-body">
            <div class="table-responsive">
                <table id="table" class="mb-0 table table-bordered table-hover table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="row" class="text-center">No</th>
                            <th scope="row">Keterangan</th>
                            <th scope="row">Tanggal Upload</th>
                            <th scope="row">File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $key => $value)
                        <tr>
                            <th scope="row" class="text-center">{{$key+1}}</th>
                            <td>{{$value->keterangan}}</td>
                            <td>{{$value->tanggal}}</td>
                            <td align="center"><a href="{{asset('assets/file/unduhan')}}/{{$value->file}}"><button class="btn btn-warning"><i class="fa fa-download"></i></button></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection