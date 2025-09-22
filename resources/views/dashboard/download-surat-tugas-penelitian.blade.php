@extends('layouts.app-pdf')
@section('content')
<style type="text/css">
  td {
    vertical-align: top;
  }
</style>
<table width="90%" align="center" style="font-family: Calibri;">
  <tr>
    <td width="20%">
      <img src="{{asset('assets/image/logo/logo.png')}}" width="50%" style="padding-top: 20px;">
    </td>
    <td width="80%">
      <h4 style="padding-bottom:-15px !important;"><?= $pengaturan->nama_lembaga ?></h4>
      <h3 style="padding-bottom:-15px !important;"><?= $pengaturan->nama_instansi ?></h3>
      <p style="font-size:11px;"><?= $pengaturan->alamat ?>, Indonesia. Telp. <?= $pengaturan->telepon ?> - email : <?= $pengaturan->email ?> - website : <?= $pengaturan->website ?></p>
    </td>
  </tr>
</table>
@foreach($data as $row)
<hr style="border: 2px solid; margin-top: -5;">
<center><strong><u>SURAT TUGAS</u><br> NO: {{$row->no_surat}}</strong></center><br><br>
<center><strong>MENUGASKAN</strong></center>
<center>Kepada nama-nama yang tersebut pada lampiran untuk menjalankan tugas sebagai berikut :</center>
<table width="90%" border="1" align="center" style="font-family: Calibri; border: 1px solid black;
border-collapse: collapse;">
  <tr>
    <th align="center" style="padding: 5px;">NO</th>
    <th align="center" style="padding: 5px;">NAMA</th>
    <th align="center" style="padding: 5px;">JABATAN</th>
    <th align="center" style="padding: 5px;">INSTANSI</th>
  </tr>
  <tr>
    <td align="center" style="padding: 5px;">1</td>
    <td style="padding: 5px;">{{$row->nama_ketua}}</td>
    <td align="center" style="padding: 5px;">Ketua</td>
    <td align="center" style="padding: 5px;">{{$row->afiliasi}}
      Jl. Purwokerto
    </td>
  </tr>
  <tr>
    <td align="center" style="padding: 5px;">2</td>
    <td style="padding: 5px;">
      @if(empty($row->anggota_internal1))
      @else
      {{$row->anggota_internal1}}<br>
      @endif
      @if(empty($row->anggota_internal2))
      @else
      {{$row->anggota_internal2}}<br>
      @endif
      @if(empty($row->anggota_internal3))
      @else
      {{$row->anggota_internal3}}<br>
      @endif
      @if(empty($row->anggota_internal4))
      @else
      {{$row->anggota_internal4}}<br>
      @endif
      @if(empty($row->anggota_eksternal1))
      @else
      {{$row->anggota_eksternal1}}<br>
      @endif
      @if(empty($row->anggota_eksternal2))
      @else
      {{$row->anggota_eksternal2}}<br>
      @endif
      @if(empty($row->anggota_eksternal3))
      @else
      {{$row->anggota_eksternal3}}<br>
      @endif
      @if(empty($row->anggota_eksternal4))
      @else
      {{$row->anggota_eksternal4}}<br>
      @endif
      @if(empty($row->mahasiswa))
      @else
      {{$row->mahasiswa}}<br>
      @endif
      @if(empty($row->alumni))
      @else
      {{$row->alumni}}<br>
      @endif
      @if(empty($row->admin))
      @else
      {{$row->admin}}<br>
      @endif
    </td>
    <td align="center" style="padding: 5px;">Anggota</td>
    <td style="padding: 5px;">
      @if(empty($row->afiliasi_internal1))
      @else
      {{$row->afiliasi_internal1}}<br>
      @endif
      @if(empty($row->afiliasi_internal2))
      @else
      {{$row->afiliasi_internal2}}<br>
      @endif
      @if(empty($row->afiliasi_internal3))
      @else
      {{$row->afiliasi_internal3}}<br>
      @endif
      @if(empty($row->afiliasi_internal4))
      @else
      {{$row->afiliasi_internal4}}<br>
      @endif
      @if(empty($row->afiliasi_eksternal1))
      @else
      {{$row->afiliasi_eksternal1}}<br>
      @endif
      @if(empty($row->afiliasi_eksternal2))
      @else
      {{$row->afiliasi_eksternal2}}<br>
      @endif
      @if(empty($row->afiliasi_eksternal3))
      @else
      {{$row->afiliasi_eksternal3}}<br>
      @endif
      @if(empty($row->afiliasi_eksternal4))
      @else
      {{$row->afiliasi_eksternal4}}<br>
      @endif
      @if(empty($row->afiliasi_mahasiswa))
      @else
      {{$row->afiliasi_mahasiswa}}<br>
      @endif
      @if(empty($row->afiliasi_alumni))
      @else
      {{$row->afiliasi_alumni}}<br>
      @endif
      @if(empty($row->afiliasi_admin))
      @else
      {{$row->afiliasi_admin}}<br>
      @endif
    </td>
  </tr>
</table><br>
<table width="90%" border="0" align="center" style="font-family: Calibri; ">
  <tr>
    <td width="10%">
      Keperluan
    </td>
    <td style="padding: 2px;" width="2%">:</td>
    <td style="padding: 2px;" align="padding-top">
      @if(empty($row->keperluan))
      Penelitian “{{$row->judul_penelitian}}"
      @else
      {{$row->keperluan}}
      @endif
    </td>
  </tr>
  <tr>
    <td style="padding: 2px;">
      Tempat
    </td>
    <td style="padding: 2px;">:</td>
    <td style="padding: 2px;">
      {{$row->tempat}}
    </td>
  </tr>
  <tr>
    <td style="padding: 2px;">
      Tanggal
    </td>
    <td style="padding: 2px;">:</td>
    <td style="padding: 2px;">
      {{date_format(date_create($row->tanggal),"d-m-Y")}}
    </td>
  </tr>
  <tr>
    <td style="padding: 2px;">
      Transport
    </td>
    <td style="padding: 2px;">:</td>
    <td style="padding: 2px;">
      {{$row->transport}}
    </td>
  </tr>
</table><br>
<table align="right" width="50%">
  <tr>
    <td style="padding: 2px;" align="center">Dikeluarkan di: <?= $pengaturan->kota ?></td>
  </tr>
  <tr>
    <td style="padding: 2px;" align="center">Pada tanggal: {{date_format(date_create($row->tanggal),"d-m-Y")}}</td>
  </tr>
  <tr>
    <td style="padding-bottom: 70px; padding-top: 2px;" align="center">Ketua <?= $pengaturan->lembaga ?></td>
  </tr>
  <tr>
    <td style="padding: 2px;" align="center"><u><?= $pengaturan->ketua_lembaga ?></u><br>NIK <?= $pengaturan->ketua_nik ?><< /td>
  </tr>
</table>
@endforeach
@endsection