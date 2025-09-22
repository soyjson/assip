@extends('layouts.app')
@extends('layouts.alert')
@section('content')
<!-- Jumbotron -->
<div id="intro" class="p-5 text-center bg-image" style="background-image: url('./assets/image/body/4117072.jpg') !important;background-position: center;background-repeat: no-repeat;-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;">
    <div class="mask" style="background-color: rgba(0, 0, 0, 0.4);">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="text-white">
                <h1 class="mb-0">Berita</h1>
            </div>
        </div>
    </div>
</div>
<!-- Jumbotron -->
<div class="card mb-3">
    <div class="card-body">
        <div class="table-responsive">
            <table id="table" class="table table-bordered table-hover table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col" width="50">No</th>
                        <th scope="col" width="50">Gambar</th>
                        <th scope="col">Judul</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $row)
                    <tr>
                        <th scope="row">{{$key+1}}</th>
                        <td><img src="{{asset('/assets/image/postingan')}}/{{$row->gambar}}" class="img-fluid" alt="{{$row->judul}}" width="60" /></td>
                        <td>
                            <a href="{!! url('/berita/detail_berita/'.$row->url.'') !!}" class="h5 fw-normal" title="{{$row->judul}}">{{$row->judul}}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection