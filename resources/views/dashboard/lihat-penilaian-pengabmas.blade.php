@extends('layouts.app-pdf')
@section('content')
<style type="text/css">
    td {
        vertical-align: top;
    }
</style>
@if(empty($reviewer))
<div class="alert alert-danger">
    Penilaian Belum di Berikan
</div>
@else
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
<hr style="border: 2px solid; margin-top: -5;">
<center><strong>BORANG MONITORING DAN EVALUASI PENGABDIAN KEPADA MASYARAKAT DOSEN</strong></center><br>
<table width="100%" border="0" style="font-family: Calibri;">
    @foreach($usulan as $row)
    <tr>
        <td width="30%">
            Program Studi
        </td>
        <td width="70%">: {{$row->program_studi}}
        </td>
    </tr>
    <tr>
        <td width="30%" align="padding-top">
            Judul PKM
        </td>
        <td style="padding: 2px;" align="padding-top">: {{$row->judul_pengabmas}}
        </td>
    </tr>
    <tr>
        <td width="30%">
            Peneliti Utama
        </td>
        <td width="70%">: {{$row->nama_ketua}}
        </td>
    </tr>
    <tr>
        <td width="30%">
            Anggota peneliti
        </td>
        <td width="70%">: {{$row->anggota_internal1}} {{$row->anggota_internal2}} {{$row->anggota_internal3}} {{$row->anggota_internal4}} {{$row->anggota_eksternal1}} {{$row->anggota_eksternal2}} {{$row->anggota_eksternal3}} {{$row->anggota_eksternal4}} {{$row->mahasiswa}} {{$row->alumni}} {{$row->admin}}
        </td>
    </tr>
    @endforeach
</table><br>
<table width="90%" border="1" align="center" style="font-family: Calibri; border: 1px solid black;
border-collapse: collapse;">
    <tr>
        <th align="center" style="padding: 5px;">No</th>
        <th align="center" style="padding: 5px;">Komponen Penilaian</th>
        <th align="center" style="padding: 5px;">Bobot (%)</th>
        <th align="center" style="padding: 5px;">Skor</th>
        <th align="center" style="padding: 5px;">Nilai</th>
    </tr>
    <tr>
        <td align="center" style="padding: 5px;">1</td>
        <td style="padding: 5px;">Capaian pengabmas </td>
        <td align="center" style="padding: 5px;">30</td>
        <td align="center" style="padding: 5px;">{{$skor_1}}</td>
        <td align="center" style="padding: 5px;">{{$nilai_1}}</td>
    </tr>
    <tr>
        <td align="center" style="padding: 5px;">2</td>
        <td style="padding: 5px;">Publikasi Ilmiah</td>
        <td align="center" style="padding: 5px;">30</td>
        <td align="center" style="padding: 5px;">{{$skor_2}}</td>
        <td align="center" style="padding: 5px;">{{$nilai_2}}</td>
    </tr>
    <tr>
        <td align="center" style="padding: 5px;">3</td>
        <td style="padding: 5px;">Sebagai pemakalah dalam pertemuan ilmiah </td>
        <td align="center" style="padding: 5px;">25</td>
        <td align="center" style="padding: 5px;">{{$skor_3}}</td>
        <td align="center" style="padding: 5px;">{{$nilai_3}}</td>
    </tr>
    <tr>
        <td align="center" style="padding: 5px;">4</td>
        <td style="padding: 5px;">Hak Kekayaan Intelektual: paten, paten sederhana, hak cipta, merek dagang, desain produksi industry, indikasi geografis, perlindungan varietas tanaman, perlindungan topografi sirkuit terpadu</td>
        <td align="center" style="padding: 5px;">5</td>
        <td align="center" style="padding: 5px;">{{$skor_4}}</td>
        <td align="center" style="padding: 5px;">{{$nilai_4}}</td>
    </tr>
    <tr>
        <td align="center" style="padding: 5px;">5</td>
        <td style="padding: 5px;">Produk/Model/Prototip/Desain/Karya seni/Rekayasa Sosial</td>
        <td align="center" style="padding: 5px;">5</td>
        <td align="center" style="padding: 5px;">{{$skor_5}}</td>
        <td align="center" style="padding: 5px;">{{$nilai_5}}</td>
    </tr>
    <tr>
        <td align="center" style="padding: 5px;">6</td>
        <td style="padding: 5px;">Bahan Ajar</td>
        <td align="center" style="padding: 5px;">5</td>
        <td align="center" style="padding: 5px;">{{$skor_6}}</td>
        <td align="center" style="padding: 5px;">{{$nilai_6}}</td>
    </tr>
    <tr>
        <td align="center" style="padding: 5px;" colspan="4"><strong>TOTAL NILAI</strong></td>
        <td align="center" style="padding: 5px;"><strong>{{$total_nilai}}</strong></td>
    </tr>
</table><br>
<table width="90%" border="0" align="center" style="font-family: Calibri;">
    <tr>
        <td>Setiap kriteria diberi skor : 1, 2, 3, 4, 5, 6 <br>
            (1: sangat buruk sekali; 2: buruk sekali; 3: buruk; 4: baik; 5: baik sekali; 6: istimewa) <br>
            Nilai : Bobot X Skor
        </td>
    </tr>
</table><br>
<table width="90%" border="0" align="center" style="font-family: Calibri;">
    @foreach($data as $row)
    <tr>
        <td width="30%">Komentar Pemantau: </td>
        <td>{!! $row->tanggapan !!}
        </td>
    </tr>
    @endforeach
</table><br>
<h4 style="text-align: right;"><?= $pengaturan->kota ?>, @foreach($data as $row) {{date_format(date_create($row->tanggal),"d F Y")}} </h4>
<h5 style="text-align: right;">Penilai</h5>@endforeach<br><br>
<h4 style="text-align: right;">( @foreach($reviewer as $row) {{$row->name}} @endforeach )</h4>
@endsection
@endif