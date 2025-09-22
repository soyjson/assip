@extends('layouts.app')
@extends('layouts.alert')
@section('content')
@foreach($data as $row)
<!-- Jumbotron -->
<div id="intro" class="p-5 text-center bg-image" style="background-image: url('../../assets/image/body/4117072.jpg');height: 200px;">
    <div class="mask" style="background-color: rgba(0, 0, 0, 0.4);">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="text-white mx-3">
                <h1 class="mb-0 h2">{!! $row->judul !!}</h1>
            </div>
        </div>
    </div>
</div>
<!-- Jumbotron -->
<div class="card mb-3">
    <div class="card-body p-4">
        <section class="border-bottom mb-4">
            @if($row->foto)
            <img src="{{asset('/assets/image/foto')}}/{{$row->foto}}" class="rounded shadow-sm me-2 mb-2" height="35" alt="Foto" loading="lazy" />
            @else
            <img src="{{asset('/assets/image/foto/default.png')}}" class="rounded shadow-sm me-2 mb-2" height="35" alt="Foto" loading="lazy" />
            @endif
            <strong>Penulis: </strong> {{$row->name}} |
            <strong>Waktu: </strong> {{date_format(date_create($row->created_at),"d F Y H:i:s")}} |
            <strong>Dilihat: </strong> {{$row->view}} Kali
        </section>
        <img src="{{asset('/assets/image/postingan')}}/{{$row->gambar}}" class="img-fluid" alt="{{$row->judul}}" />
        <section class="mx-3 p-2">
            <div class="lh-lg">
                {!! $row->isi !!}
            </div>
        </section>
    </div>
</div>
@endforeach
@endsection