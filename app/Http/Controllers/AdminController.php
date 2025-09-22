<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Unduh;
use App\UsulanPenelitian;
use App\UsulanPengabmas;
use App\Petunjuk;
use App\LaporanKemajuanPenelitian;
use App\LaporanAkhirPenelitian;
use App\LaporanKemajuanPengabmas;
use App\LaporanAkhirPengabmas;
use App\Berita;
use App\History;
use App\ReviewerPenelitian;
use App\ReviewerPengabmas;
use App\TanggapanPenelitian;
use App\TanggapanPengabmas;
use App\LookBookPenelitian;
use App\LookBookPengabmas;
use App\SuratTugasPenelitian;
use App\SuratTugasPengabmas;
use App\Pengaturan;
use DB;
use PDF;
use Auth;
use Input;
use Image;
use File;
use Session;
use Mail;
use DateTime;
use Excel;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->pengaturan = DB::table('tb_pengaturan')->where('id', '1')->first();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function account()
    {
        $data = User::all();
        $histori = DB::select('select * from tb_histori_akses where user = "' . Auth::user()->username . '" order by created_at DESC');
        return view('dashboard.account', compact('data', 'histori'))
            ->with('title', 'Akun Saya');
    }

    // public function updateaccount(Request $request, $id)
    // {
    //     $data = User::find($id);
    //     $data->name = $request->name;
    //     $data->email = $request->email;
    //     $data->no_handphone = $request->no_handphone;
    //     $data->username = $request->username;
    //     $data->save();
    //     Session::flash('sukses', 'Data berhasil di simpan');
    //     return back();
    // }

    public function resetpassword()
    {
        return view('dashboard.ubah-password')
            ->with('title', 'Ubah Password');
    }

    public function updatepassword($id)
    {
        $id = Auth::user()->id;
        $password = $_POST['password'];
        $update = DB::update('update users SET password = "' . bcrypt($password) . '", password_view = "' . ($password) . '" where id = "' . $id . '" ');
        Session::flash('sukses', 'Password berhasil diubah');
        return back();
    }

    public function dashboard()
    {
        $history = new History();
        $history->name = Auth::user()->name;
        $history->user = Auth::user()->username;
        $history->email = Auth::user()->email;
        $history->save();
        $usulan_penelitian = DB::select('select status, count(id) as jumlah from tb_usulan_penelitian group by status order by status DESC');
        $penelitian_selesai = DB::select('select count(id) as jumlah from tb_laporan_akhir_penelitian');

        $usulan_pengabmas = DB::select('select status, count(id) as jumlah from tb_usulan_pengabmas group by status order by status DESC');
        $pengabmas_selesai = DB::select('select count(id) as jumlah from tb_laporan_akhir_pengabmas');

        $usulan_penelitian_baru = DB::select('select users.name, users.foto, penelitian.judul_penelitian, penelitian.tanggal 
            from users
            JOIN
            (select judul_penelitian, tanggal, id_dosen from tb_usulan_penelitian where tb_usulan_penelitian.status = "pengajuan")
            as penelitian ON users.nik = penelitian.id_dosen
            group by penelitian.tanggal
            ORDER By penelitian.tanggal DESC
            ');

        $usulan_pengabmas_baru = DB::select('select users.name, users.foto, pengabmas.judul_pengabmas, pengabmas.tanggal 
            from users
            JOIN
            (select judul_pengabmas, tanggal, id_dosen from tb_usulan_pengabmas where tb_usulan_pengabmas.status = "pengajuan")
            as pengabmas ON users.nik = pengabmas.id_dosen
            group by pengabmas.tanggal
            ORDER By pengabmas.tanggal DESC
            ');

        $tampil_usulan_penelitian = DB::select("select count(id_usulan) as total_usulan from tb_usulan_penelitian group by year(tanggal)");
        $tampil_usulan_penelitian = array_column($tampil_usulan_penelitian, 'total_usulan');

        $penelitian_diterima = DB::select("select count(id_usulan) as total_usulan from tb_usulan_penelitian where status = 'di terima' group by year(tanggal)");
        $penelitian_diterima = array_column($penelitian_diterima, 'total_usulan');

        $penelitian_ditolak = DB::select("select count(id_usulan) as total_usulan from tb_usulan_penelitian where status = 'di tolak' group by year(tanggal)");
        $penelitian_ditolak = array_column($penelitian_ditolak, 'total_usulan');

        $tampil_usulan_pengabmas = DB::select("select count(id_usulan) as total_usulan from tb_usulan_pengabmas group by year(tanggal)");
        $tampil_usulan_pengabmas = array_column($tampil_usulan_pengabmas, 'total_usulan');

        $pengabmas_diterima = DB::select("select count(id_usulan) as total_usulan from tb_usulan_pengabmas where status = 'di terima' group by year(tanggal)");
        $pengabmas_diterima = array_column($pengabmas_diterima, 'total_usulan');

        $pengabmas_ditolak = DB::select("select count(id_usulan) as total_usulan from tb_usulan_pengabmas where status = 'di tolak' group by year(tanggal)");
        $pengabmas_ditolak = array_column($pengabmas_ditolak, 'total_usulan');

        $tgl = DB::select("select YEAR(tanggal) as tgl from tb_usulan_penelitian group by year(tanggal)");
        $tgl = array_column($tgl, 'tgl');

        $usulan_penelitian_dosen = DB::select('select status, count(id) as jumlah from tb_usulan_penelitian where id_dosen = "' . Auth::user()->nik . '" group by status');
        $penelitian_selesai_dosen = DB::select('select count(id) as jumlah from tb_laporan_akhir_penelitian where id_dosen = "' . Auth::user()->nik . '"');

        $usulan_pengabmas_dosen = DB::select('select status, count(id) as jumlah from tb_usulan_pengabmas where id_dosen = "' . Auth::user()->nik . '" group by status');
        $pengabmas_selesai_dosen = DB::select('select count(id) as jumlah from tb_laporan_akhir_pengabmas where id_dosen = "' . Auth::user()->nik . '"');

        return view('dashboard.dashboard', compact('usulan_penelitian', 'penelitian_selesai', 'usulan_pengabmas', 'pengabmas_selesai', 'usulan_penelitian_baru', 'usulan_pengabmas_baru', 'usulan_penelitian_dosen', 'penelitian_selesai_dosen', 'usulan_pengabmas_dosen', 'pengabmas_selesai_dosen'))
            ->with('tampil_usulan_penelitian', json_encode($tampil_usulan_penelitian, JSON_NUMERIC_CHECK))
            ->with('penelitian_diterima', json_encode($penelitian_diterima, JSON_NUMERIC_CHECK))
            ->with('penelitian_ditolak', json_encode($penelitian_ditolak, JSON_NUMERIC_CHECK))
            ->with('tampil_usulan_pengabmas', json_encode($tampil_usulan_pengabmas, JSON_NUMERIC_CHECK))
            ->with('pengabmas_diterima', json_encode($pengabmas_diterima, JSON_NUMERIC_CHECK))
            ->with('pengabmas_ditolak', json_encode($pengabmas_ditolak, JSON_NUMERIC_CHECK))
            ->with('tgl', json_encode($tgl, JSON_NUMERIC_CHECK))
            ->with('title', 'Dashboard');
    }

    public function dosen()
    {
        $usulan_penelitian = DB::select('select status, count(id) as jumlah from tb_usulan_penelitian where id_dosen = "' . Auth::user()->nik . '" group by status');
        $penelitian_selesai = DB::select('select count(id) as jumlah from tb_laporan_akhir_penelitian where id_dosen = "' . Auth::user()->nik . '"');

        $usulan_pengabmas = DB::select('select status, count(id) as jumlah from tb_usulan_pengabmas where id_dosen = "' . Auth::user()->nik . '" group by status');
        $pengabmas_selesai = DB::select('select count(id) as jumlah from tb_laporan_akhir_pengabmas where id_dosen = "' . Auth::user()->nik . '"');

        $data = DB::select('select * from users where level = 4');
        $data = DB::select('select users.*, penelitian.jumlah as jml_penelitian, pengabmas.jumlah as jml_pengabmas
            FROM users
            LEFT JOIN
            (select id_dosen, status, count(id) as jumlah from tb_usulan_penelitian group by id_dosen) as penelitian
            ON users.nik = penelitian.id_dosen
            LEFT JOIN
            (select id_dosen, status, count(id) as jumlah from tb_usulan_pengabmas group by id_dosen) as pengabmas
            ON users.nik = pengabmas.id_dosen
            WHERE users.level != 5 and users.level != 1
            GROUP by users.id
            ORDER by users.nik
            ');
        return view('dashboard.dosen', compact('data', 'usulan_penelitian', 'penelitian_selesai', 'usulan_pengabmas', 'pengabmas_selesai'))
            ->with('title', 'Dosen');
    }

    public function simpandosen(Request $request)
    {
        $this->validate($request, [
            'foto' => 'required|max:2048|mimes:jpg,png,jpeg',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|min:4|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'nik' => 'required|string|min:4|unique:users',
            'jafung' => 'required',
            'fakultas' => 'required',
            'program_studi' => 'required',
            'no_handphone' => 'required',
        ]);

        $foto = $request->file('foto');
        $destinationPath = public_path() . '/assets/image/foto/';
        $inputfoto = $request->nik . '.' . $foto->getClientOriginalExtension();
        $foto->move($destinationPath, $inputfoto);

        $user = new User();
        $user->foto = $inputfoto;
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->password_view = $request->password;
        $user->nik = $request->nik;
        $user->jafung = $request->jafung;
        $user->fakultas = $request->fakultas;
        $user->program_studi = $request->program_studi;
        $user->no_handphone = $request->no_handphone;
        $user->level = 4;
        $user->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function deletedosen($id)
    {
        $data = User::find($id);
        $target = $data['foto'];

        if ($target != 'default.png') {
            unlink(public_path() . '/assets/image/foto/' . $target);
        }

        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function updateaccount(Request $request)
    {
        $this->validate($request, [
            //'nik' => 'unique:users',
        ]);

        if (empty($request->foto)) {
            $inputfoto = Auth::user()->foto;
        } else {
            $foto = $request->file('foto');
            $destinationPath = public_path() . '/assets/image/foto/';
            $inputfoto = $request->nik . '.' . $foto->getClientOriginalExtension();
            $foto->move($destinationPath, $inputfoto);
        }

        $user = User::find(Auth::user()->id);
        $user->foto = $inputfoto;
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->password_view = $request->password;
        $user->nidn = $request->nidn;
        $user->nik = $request->nik;
        $user->jafung = $request->jafung;
        $user->fakultas = $request->fakultas;
        $user->program_studi = $request->program_studi;
        $user->institusi  = $request->institusi;
        $user->level = $request->level;
        $user->no_handphone = $request->no_handphone;
        $user->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }


    public function updatedosen(Request $request, $id)
    {

        $user = User::find($id);
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->password_view = $request->password;
        $user->nik = $request->nik;
        $user->jafung = $request->jafung;
        $user->fakultas = $request->fakultas;
        $user->program_studi = $request->program_studi;
        $user->no_handphone = $request->no_handphone;
        $user->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function usulanpenelitian()
    {
        $dosen = DB::select('select * from users where level = 4');
        $usulan = DB::select('select * from tb_usulan_penelitian where id_dosen = "' . Auth::user()->nik . '" order by tanggal DESC');
        $unduh = DB::select('select * from tb_unduh where id_status = 1');
        return view('dashboard.usulan-penelitian', compact('dosen', 'usulan', 'unduh'))
            ->with('title', 'Usulan Penelitian');
    }

    public function simpanusulanpenelitian(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $cek = DB::select("SELECT * FROM tb_usulan_penelitian where DATE(created_at)=CURDATE()");
        foreach ($cek as $key => $value) {
            $id_usulan = $value->id_usulan;
        }

        if (empty($cek)) {
            $kode = Date('Ymd') . "0001";
        } else {
            $kode = $id_usulan + 1.;
        }

        $this->validate($request, [
            'file' => 'required|mimes:doc,docx|max:5120',
            'nama_ketua' => 'required',
            'judul_penelitian' => 'required',
            'luaran_wajib' => 'required',
            'jenis_penelitian' => 'required',
        ]);

        $file   = $request->file('file');
        $ext    =  $file->getClientOriginalExtension();
        $newName = $kode . "." . $ext;
        $file->move(public_path() . '/assets/file/usulan-penelitian/', $newName);

        if (empty($request->biaya_hibah_pt)) {
            $biaya_hibah_pt = 0;
        } else {
            $biaya_hibah_pt1 = str_replace("Rp. ", "", $request->biaya_hibah_pt);
            $biaya_hibah_pt  = str_replace(".", "", $biaya_hibah_pt1);
        }

        if (empty($request->biaya_hibah_luar)) {
            $biaya_hibah_luar = 0;
        } else {
            $biaya_hibah_luar1 = str_replace("Rp. ", "", $request->biaya_hibah_luar);
            $biaya_hibah_luar  = str_replace(".", "", $biaya_hibah_luar1);
        }

        $tanggal = Date('Y-m-d');
        if (empty($request->tahun_pelaksanaan)) {
            $tahun = Date('Y');
        } else {
            $tahun = $request->tahun_pelaksanaan;
        }

        if ($request->jenis_penelitian == 1) {
            $status = 'di terima';
        } else {
            $status = 'pengajuan';
        }


        $penelitian = new UsulanPenelitian();
        $penelitian->id_usulan            = $kode;
        $penelitian->nama_ketua           = $request->nama_ketua;
        $penelitian->anggota_internal1    = $_POST['anggota_internal1'];
        $penelitian->anggota_internal2    = $_POST['anggota_internal2'];
        $penelitian->anggota_internal3    = $_POST['anggota_internal3'];
        $penelitian->anggota_internal4    = $_POST['anggota_internal4'];
        $penelitian->anggota_eksternal1   = $_POST['anggota_eksternal1'];
        $penelitian->anggota_eksternal2   = $_POST['anggota_eksternal2'];
        $penelitian->anggota_eksternal3   = $_POST['anggota_eksternal3'];
        $penelitian->anggota_eksternal4   = $_POST['anggota_eksternal4'];
        $penelitian->mahasiswa            = $_POST['mahasiswa'];
        $penelitian->alumni               = $_POST['alumni'];
        $penelitian->admin                = $_POST['admin'];
        $penelitian->luaran_wajib         = $_POST['luaran_wajib'];
        $penelitian->luaran_tambahan      = $_POST['luaran_tambahan'];
        $penelitian->pemberi_hibah        = $_POST['pemberi_hibah'];
        $penelitian->judul_penelitian     = $request->judul_penelitian;
        $penelitian->file                 = $newName;
        $penelitian->jenis_penelitian     = $_POST['jenis_penelitian'];
        $penelitian->nama_institusi       = $_POST['nama_institusi'];
        $penelitian->alamat               = $_POST['alamat'];
        $penelitian->tahun_pelaksanaan    = $tahun;
        $penelitian->id_dosen             = Auth::user()->nik;
        $penelitian->biaya_hibah_pt       = $biaya_hibah_pt;
        $penelitian->biaya_hibah_luar     = $biaya_hibah_luar;
        $penelitian->tanggal              = $tanggal;
        $penelitian->status               = $status;
        $penelitian->user_confirm         = '0';
        $penelitian->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function updateusulanpenelitian(Request $request, $id)
    {
        date_default_timezone_set('Asia/Jakarta');

        $cek = DB::select("SELECT * FROM tb_usulan_penelitian where id=$id");
        foreach ($cek as $key => $value) {
            $id_usulan = $value->id_usulan;
        }
        $kode = $id_usulan;

        $this->validate($request, [
            'nama_ketua' => 'required',
            'judul_penelitian' => 'required',
            'luaran_wajib' => 'required',
            'jenis_penelitian' => 'required',
        ]);

        if ($request->file) {
            $get = DB::select('select * from tb_usulan_penelitian where id = "' . $id . '"');
            foreach ($get as $key => $value) {
                $target = $value->file;
            }

            if (file_exists(public_path() . '/assets/file/usulan-penelitian/' . $target)) {
                unlink(public_path() . '/assets/file/usulan-penelitian/' . $target);
            } else {
                # code...
            }

            $file   = $request->file('file');
            $ext    =  $file->getClientOriginalExtension();
            $newName = $kode . "." . $ext;
            $file->move(public_path() . '/assets/file/usulan-penelitian/', $newName);
        } else {
            $get = DB::select('select * from tb_usulan_penelitian where id = "' . $id . '"');
            foreach ($get as $key => $value) {
                $newName = $value->file;
            }
        }

        if (empty($request->biaya_hibah_pt)) {
            $biaya_hibah_pt = 0;
        } else {
            $biaya_hibah_pt1 = str_replace("Rp. ", "", $request->biaya_hibah_pt);
            $biaya_hibah_pt  = str_replace(".", "", $biaya_hibah_pt1);
        }

        if (empty($request->biaya_hibah_luar)) {
            $biaya_hibah_luar = 0;
        } else {
            $biaya_hibah_luar1 = str_replace("Rp. ", "", $request->biaya_hibah_luar);
            $biaya_hibah_luar  = str_replace(".", "", $biaya_hibah_luar1);
        }

        $tanggal = Date('Y-m-d');
        if (empty($request->tahun_pelaksanaan)) {
            $tahun = Date('Y');
        } else {
            $tahun = $request->tahun_pelaksanaan;
        }

        $penelitian = UsulanPenelitian::find($id);
        $penelitian->nama_ketua           = $request->nama_ketua;
        $penelitian->anggota_internal1    = $_POST['anggota_internal1'];
        $penelitian->anggota_internal2    = $_POST['anggota_internal2'];
        $penelitian->anggota_internal3    = $_POST['anggota_internal3'];
        $penelitian->anggota_internal4    = $_POST['anggota_internal4'];
        $penelitian->anggota_eksternal1   = $_POST['anggota_eksternal1'];
        $penelitian->anggota_eksternal2   = $_POST['anggota_eksternal2'];
        $penelitian->anggota_eksternal3   = $_POST['anggota_eksternal3'];
        $penelitian->anggota_eksternal4   = $_POST['anggota_eksternal4'];
        $penelitian->mahasiswa            = $_POST['mahasiswa'];
        $penelitian->alumni               = $_POST['alumni'];
        $penelitian->admin                = $_POST['admin'];
        $penelitian->pemberi_hibah        = $_POST['pemberi_hibah'];
        $penelitian->judul_penelitian     = $request->judul_penelitian;
        $penelitian->file                 = $newName;
        $penelitian->jenis_penelitian     = $_POST['jenis_penelitian'];
        $penelitian->nama_institusi       = $_POST['nama_institusi'];
        $penelitian->alamat               = $_POST['alamat'];
        $penelitian->tahun_pelaksanaan    = $tahun;
        $penelitian->id_dosen             = Auth::user()->nik;
        $penelitian->biaya_hibah_pt       = $biaya_hibah_pt;
        $penelitian->biaya_hibah_luar     = $biaya_hibah_luar;
        $penelitian->tanggal              = $tanggal;
        $penelitian->status              = 'pengajuan';
        $penelitian->user_confirm          = '0';
        $penelitian->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function deleteusulanpenelitian($id)
    {

        $get = DB::select('select * from tb_usulan_penelitian where id = "' . $id . '"');
        foreach ($get as $key => $value) {
            $target = $value->file;
        }

        if (file_exists(public_path() . '/assets/file/usulan-penelitian/' . $target)) {
            unlink(public_path() . '/assets/file/usulan-penelitian/' . $target);
        } else {
            # code...
        }

        $data = UsulanPenelitian::find($id);
        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function lihatpenilaianusulanpenelitian($id)
    {
        $pengaturan = $this->pengaturan;

        $data = DB::select('select * from tb_tanggapan_penelitian where id_usulan = "' . $id . '"');

        if (empty($data)) {
            Session::flash('gagal', 'Belum ada penilaian');
            return back();
        } else {
            $reviewer = DB::select('select users.name 
            from users
            JOIN tb_tanggapan_penelitian ON users.nik = tb_tanggapan_penelitian.id_reviewer
            where tb_tanggapan_penelitian.id_usulan = "' . $id . '"
            GROUP By tb_tanggapan_penelitian.id_reviewer');
            foreach ($data as $key => $value) {
                $skor_1 = $value->nilai_1;
                $skor_2 = $value->nilai_2;
                $skor_3 = $value->nilai_3;
                $skor_4 = $value->nilai_4;
                $skor_5 = $value->nilai_5;

                $nilai_1 = $value->nilai_1 * 25;
                $nilai_2 = $value->nilai_2 * 20;
                $nilai_3 = $value->nilai_3 * 20;
                $nilai_4 = $value->nilai_4 * 25;
                $nilai_5 = $value->nilai_5 * 10;

                $total_nilai = $nilai_1 + $nilai_2 + $nilai_3 + $nilai_4 + $nilai_5;
            }
            $usulan = DB::select('select tb_usulan_penelitian.*, users.*, u0.name as nama_ketua, u1.name as anggota_internal1, u2.name as anggota_internal2, u3.name as anggota_internal3, u4.name as anggota_internal4 from tb_usulan_penelitian 
            LEFT JOIN users ON tb_usulan_penelitian.id_dosen = users.nik
            LEFT JOIN users u0 ON tb_usulan_penelitian.nama_ketua = u0.nik
            LEFT JOIN users u1 ON tb_usulan_penelitian.anggota_internal1 = u1.nik
            LEFT JOIN users u2 ON tb_usulan_penelitian.anggota_internal2 = u2.nik
            LEFT JOIN users u3 ON tb_usulan_penelitian.anggota_internal3 = u3.nik
            LEFT JOIN users u4 ON tb_usulan_penelitian.anggota_internal4 = u4.nik
            where tb_usulan_penelitian.id_usulan = "' . $id . '" GROUP BY tb_usulan_penelitian.id_usulan');

            $pdf = PDF::loadView('dashboard.lihat-penilaian-usulan-penelitian', compact('data', 'usulan', 'nilai_1', 'nilai_2', 'nilai_3', 'nilai_4', 'nilai_5', 'skor_1', 'skor_2', 'skor_3', 'skor_4', 'skor_5', 'reviewer', 'total_nilai', 'pengaturan'));
            $pdf->setPaper('A4', 'potrait');
            return $pdf->stream('Penilaian Usulan Penelitian.pdf');
        }
    }

    public function lihatpenilaianpenelitian($id)
    {
        $pengaturan = $this->pengaturan;

        $data = DB::select('select * from tb_tanggapan_penelitian where id = "' . $id . '"');

        if (empty($data)) {
            Session::flash('gagal', 'Belum ada penilaian');
            return back();
        } else {
            $reviewer = DB::select('select users.name 
            from users
            JOIN tb_tanggapan_penelitian ON users.nik = tb_tanggapan_penelitian.id_reviewer
            where tb_tanggapan_penelitian.id = "' . $id . '"
            GROUP By tb_tanggapan_penelitian.id_reviewer');
            foreach ($data as $key => $value) {
                $skor_1 = $value->nilai_1;
                $skor_2 = $value->nilai_2;
                $skor_3 = $value->nilai_3;
                $skor_4 = $value->nilai_4;
                $skor_5 = $value->nilai_5;
                $skor_6 = $value->nilai_6;

                $nilai_1 = $value->nilai_1 * 30;
                $nilai_2 = $value->nilai_2 * 30;
                $nilai_3 = $value->nilai_3 * 25;
                $nilai_4 = $value->nilai_4 * 5;
                $nilai_5 = $value->nilai_5 * 5;
                $nilai_6 = $value->nilai_6 * 5;

                $total_nilai = $nilai_1 + $nilai_2 + $nilai_3 + $nilai_4 + $nilai_5 + $nilai_6;
            }
            $usulan = DB::select('select tb_usulan_penelitian.*, users.* 
            from tb_usulan_penelitian 
            LEFT JOIN users ON tb_usulan_penelitian.id_dosen = users.nik
            LEFT JOIN tb_tanggapan_penelitian ON tb_usulan_penelitian.id_usulan = tb_tanggapan_penelitian.id_usulan
            where tb_tanggapan_penelitian.id = "' . $id . '"');

            $pdf = PDF::loadView('dashboard.lihat-penilaian-penelitian', compact('data', 'usulan', 'nilai_1', 'nilai_2', 'nilai_3', 'nilai_4', 'nilai_5', 'skor_1', 'skor_2', 'skor_3', 'skor_4', 'skor_5', 'skor_6', 'reviewer', 'total_nilai', 'pengaturan'));
            $pdf->setPaper('A4', 'potrait');
            return $pdf->stream('Penilaian Usulan Penelitian.pdf');
        }
    }

    public function lihatpenilaianpengabmas($id)
    {
        $pengaturan = $this->pengaturan;

        $data = DB::select('select * from tb_tanggapan_pengabmas where id = "' . $id . '"');

        if (empty($data)) {
            Session::flash('gagal', 'Belum ada penilaian');
            return back();
        } else {
            $reviewer = DB::select('select users.name 
            from users
            JOIN tb_tanggapan_pengabmas ON users.nik = tb_tanggapan_pengabmas.id_reviewer
            where tb_tanggapan_pengabmas.id = "' . $id . '"
            GROUP By tb_tanggapan_pengabmas.id_reviewer');
            foreach ($data as $key => $value) {
                $skor_1 = $value->nilai_1;
                $skor_2 = $value->nilai_2;
                $skor_3 = $value->nilai_3;
                $skor_4 = $value->nilai_4;
                $skor_5 = $value->nilai_5;
                $skor_6 = $value->nilai_6;

                $nilai_1 = $value->nilai_1 * 30;
                $nilai_2 = $value->nilai_2 * 30;
                $nilai_3 = $value->nilai_3 * 25;
                $nilai_4 = $value->nilai_4 * 5;
                $nilai_5 = $value->nilai_5 * 5;
                $nilai_6 = $value->nilai_6 * 5;

                $total_nilai = $nilai_1 + $nilai_2 + $nilai_3 + $nilai_4 + $nilai_5 + $nilai_6;
            }
            $usulan = DB::select('select tb_usulan_pengabmas.*, users.* 
            from tb_usulan_pengabmas 
            LEFT JOIN users ON tb_usulan_pengabmas.id_dosen = users.nik
            LEFT JOIN tb_tanggapan_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_tanggapan_pengabmas.id_usulan
            where tb_tanggapan_pengabmas.id = "' . $id . '"');

            $pdf = PDF::loadView('dashboard.lihat-penilaian-pengabmas', compact('data', 'usulan', 'nilai_1', 'nilai_2', 'nilai_3', 'nilai_4', 'nilai_5', 'skor_1', 'skor_2', 'skor_3', 'skor_4', 'skor_5', 'skor_6', 'reviewer', 'total_nilai', 'pengaturan'));
            $pdf->setPaper('A4', 'potrait');
            return $pdf->stream('Penilaian Usulan pengabmas.pdf');
        }
    }

    public function usulanpengabmas()
    {
        $dosen = DB::select('select * from users where level = 4');
        $usulan = DB::select('select * from tb_usulan_pengabmas where id_dosen = "' . Auth::user()->nik . '" order by tanggal DESC');
        $unduh = DB::select('select * from tb_unduh where id_status = 2');
        return view('dashboard.usulan-pengabmas', compact('dosen', 'usulan', 'unduh'))
            ->with('title', 'Usulan Pengabmas');
    }

    public function simpanusulanpengabmas(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $cek = DB::select("SELECT * FROM tb_usulan_pengabmas where DATE(created_at)=CURDATE()");
        foreach ($cek as $key => $value) {
            $id_usulan = $value->id_usulan;
        }

        if (empty($cek)) {
            $kode = Date('Ymd') . "0001";
        } else {
            $kode = $id_usulan + 1.;
        }

        $this->validate($request, [
            'file' => 'required|mimes:doc,docx|max:5120',
            'nama_ketua' => 'required',
            'judul_pengabmas' => 'required',
            'luaran_wajib' => 'required',
            'jenis_pengabmas' => 'required',
        ]);

        $file   = $request->file('file');
        $ext    =  $file->getClientOriginalExtension();
        $newName = $kode . "." . $ext;
        $file->move(public_path() . '/assets/file/usulan-pengabmas/', $newName);

        if (empty($request->biaya_hibah_pt)) {
            $biaya_hibah_pt = 0;
        } else {
            $biaya_hibah_pt1 = str_replace("Rp. ", "", $request->biaya_hibah_pt);
            $biaya_hibah_pt  = str_replace(".", "", $biaya_hibah_pt1);
        }

        if (empty($request->biaya_hibah_luar)) {
            $biaya_hibah_luar = 0;
        } else {
            $biaya_hibah_luar1 = str_replace("Rp. ", "", $request->biaya_hibah_luar);
            $biaya_hibah_luar  = str_replace(".", "", $biaya_hibah_luar1);
        }

        $tanggal = Date('Y-m-d');
        if (empty($request->tahun_pelaksanaan)) {
            $tahun = Date('Y');
        } else {
            $tahun = $request->tahun_pelaksanaan;
        }

        if ($request->jenis_pengabmas == 1) {
            $status = 'di terima';
        } else {
            $status = 'pengajuan';
        }


        $pengabmas = new UsulanPengabmas();
        $pengabmas->id_usulan            = $kode;
        $pengabmas->nama_ketua           = $request->nama_ketua;
        $pengabmas->anggota_internal1   = $_POST['anggota_internal1'];
        $pengabmas->anggota_internal2    = $_POST['anggota_internal2'];
        $pengabmas->anggota_internal3    = $_POST['anggota_internal3'];
        $pengabmas->anggota_internal4    = $_POST['anggota_internal4'];
        $pengabmas->anggota_eksternal1   = $_POST['anggota_eksternal1'];
        $pengabmas->anggota_eksternal2   = $_POST['anggota_eksternal2'];
        $pengabmas->anggota_eksternal3   = $_POST['anggota_eksternal3'];
        $pengabmas->anggota_eksternal4   = $_POST['anggota_eksternal4'];
        $pengabmas->mahasiswa            = $_POST['mahasiswa'];
        $pengabmas->alumni               = $_POST['alumni'];
        $pengabmas->admin                = $_POST['admin'];
        $pengabmas->luaran_wajib         = $_POST['luaran_wajib'];
        $pengabmas->luaran_tambahan      = $_POST['luaran_tambahan'];
        $pengabmas->pemberi_hibah        = $_POST['pemberi_hibah'];
        $pengabmas->judul_pengabmas     = $request->judul_pengabmas;
        $pengabmas->file                 = $newName;
        $pengabmas->jenis_pengabmas     = $_POST['jenis_pengabmas'];
        $pengabmas->nama_institusi       = $_POST['nama_institusi'];
        $pengabmas->alamat               = $_POST['alamat'];
        $pengabmas->tahun_pelaksanaan    = $tahun;
        $pengabmas->id_dosen             = Auth::user()->nik;
        $pengabmas->biaya_hibah_pt       = $biaya_hibah_pt;
        $pengabmas->biaya_hibah_luar     = $biaya_hibah_luar;
        $pengabmas->tanggal              = $tanggal;
        $pengabmas->status               = $status;
        $pengabmas->user_confirm         = '0';
        $pengabmas->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function updateusulanpengabmas(Request $request, $id)
    {
        date_default_timezone_set('Asia/Jakarta');

        $cek = DB::select("SELECT * FROM tb_usulan_pengabmas where id=$id");
        foreach ($cek as $key => $value) {
            $id_usulan = $value->id_usulan;
        }
        $kode = $id_usulan;

        $this->validate($request, [
            'nama_ketua' => 'required',
            'judul_pengabmas' => 'required',
            'luaran_wajib' => 'required',
            'jenis_pengabmas' => 'required',
        ]);

        if ($request->file) {
            $get = DB::select('select * from tb_usulan_pengabmas where id = "' . $id . '"');
            foreach ($get as $key => $value) {
                $target = $value->file;
            }

            if (file_exists(public_path() . '/assets/file/usulan-pengabmas/' . $target)) {
                unlink(public_path() . '/assets/file/usulan-pengabmas/' . $target);
            } else {
                # code...
            }

            $file   = $request->file('file');
            $ext    =  $file->getClientOriginalExtension();
            $newName = $kode . "." . $ext;
            $file->move(public_path() . '/assets/file/usulan-pengabmas/', $newName);
        } else {
            $get = DB::select('select * from tb_usulan_pengabmas where id = "' . $id . '"');
            foreach ($get as $key => $value) {
                $newName = $value->file;
            }
        }

        if (empty($request->biaya_hibah_pt)) {
            $biaya_hibah_pt = 0;
        } else {
            $biaya_hibah_pt1 = str_replace("Rp. ", "", $request->biaya_hibah_pt);
            $biaya_hibah_pt  = str_replace(".", "", $biaya_hibah_pt1);
        }

        if (empty($request->biaya_hibah_luar)) {
            $biaya_hibah_luar = 0;
        } else {
            $biaya_hibah_luar1 = str_replace("Rp. ", "", $request->biaya_hibah_luar);
            $biaya_hibah_luar  = str_replace(".", "", $biaya_hibah_luar1);
        }

        $tanggal = Date('Y-m-d');
        if (empty($request->tahun_pelaksanaan)) {
            $tahun = Date('Y');
        } else {
            $tahun = $request->tahun_pelaksanaan;
        }

        $pengabmas = UsulanPengabmas::find($id);
        $pengabmas->nama_ketua           = $request->nama_ketua;
        $pengabmas->anggota_internal1    = $_POST['anggota_internal1'];
        $pengabmas->anggota_internal2    = $_POST['anggota_internal2'];
        $pengabmas->anggota_internal3    = $_POST['anggota_internal3'];
        $pengabmas->anggota_internal4    = $_POST['anggota_internal4'];
        $pengabmas->anggota_eksternal1   = $_POST['anggota_eksternal1'];
        $pengabmas->anggota_eksternal2   = $_POST['anggota_eksternal2'];
        $pengabmas->anggota_eksternal3   = $_POST['anggota_eksternal3'];
        $pengabmas->anggota_eksternal4   = $_POST['anggota_eksternal4'];
        $pengabmas->mahasiswa            = $_POST['mahasiswa'];
        $pengabmas->alumni               = $_POST['alumni'];
        $pengabmas->admin                = $_POST['admin'];
        $pengabmas->pemberi_hibah        = $_POST['pemberi_hibah'];
        $pengabmas->judul_pengabmas     = $request->judul_pengabmas;
        $pengabmas->file                 = $newName;
        $pengabmas->jenis_pengabmas     = $_POST['jenis_pengabmas'];
        $pengabmas->nama_institusi       = $_POST['nama_institusi'];
        $pengabmas->alamat               = $_POST['alamat'];
        $pengabmas->tahun_pelaksanaan    = $tahun;
        $pengabmas->id_dosen             = Auth::user()->nik;
        $pengabmas->biaya_hibah_pt       = $biaya_hibah_pt;
        $pengabmas->biaya_hibah_luar     = $biaya_hibah_luar;
        $pengabmas->tanggal              = $tanggal;
        $pengabmas->status              = 'pengajuan';
        $pengabmas->user_confirm          = '0';
        $pengabmas->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function deleteusulanpengabmas($id)
    {

        $get = DB::select('select * from tb_usulan_pengabmas where id = "' . $id . '"');
        foreach ($get as $key => $value) {
            $target = $value->file;
        }

        if (file_exists(public_path() . '/assets/file/usulan-pengabmas/' . $target)) {
            unlink(public_path() . '/assets/file/usulan-pengabmas/' . $target);
        } else {
            # code...
        }

        $data = UsulanPengabmas::find($id);
        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function unduhan()
    {
        $data = DB::select('select * from tb_unduh order by tanggal DESC');
        return view('dashboard.unduhan', compact('data'))
            ->with('title', 'Unduhan');
    }

    public function simpanunduhan(Request $request)
    {

        $this->validate($request, [
            'file' => 'required|mimes:doc,docx,pdf|max:5120',
        ]);

        $file   = $request->file('file');
        $ext    =  $file->getClientOriginalExtension();
        $newName = $request->keterangan . "." . $ext;
        $file->move(public_path() . '/assets/file/unduhan/', $newName);

        $tanggal = Date('Y-m-d');

        $unduhan = new Unduh();
        $unduhan->tanggal              = $tanggal;
        $unduhan->file                 = $newName;
        $unduhan->keterangan           = $request->keterangan;
        $unduhan->id_status            = $request->id_status;
        $unduhan->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function deleteunduhan($id)
    {

        $get = DB::select('select * from tb_unduh where id = "' . $id . '"');
        foreach ($get as $key => $value) {
            $target = $value->file;
        }

        if (file_exists(public_path() . '/assets/file/unduhan/' . $target)) {
            unlink(public_path() . '/assets/file/unduhan/' . $target);
        } else {
            # code...
        }

        $data = Unduh::find($id);
        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function petunjuk()
    {
        $data = DB::select('select * from tb_petunjuk');
        foreach ($data as $key => $value) {
            $petunjuk = $value->petunjuk;
        }
        return view('dashboard.petunjuk', compact('data', 'petunjuk'))
            ->with('title', 'Petunjuk');
    }

    public function updatepetunjuk(Request $request, $id)
    {
        $data = Petunjuk::find($id);
        $data->petunjuk = $request->petunjuk;
        $data->save();
        Session::flash('sukses', 'Data berhasil di ubah');
        return back();
    }

    public function laporankemajuanpenelitian()
    {
        $usulan = DB::select('select * from tb_usulan_penelitian where id_dosen = "' . Auth::user()->nik . '" and status = "di terima"');
        $laporan = DB::select('select users.name as nama_ketua, tb_usulan_penelitian.jenis_penelitian, tb_usulan_penelitian.judul_penelitian, tb_laporan_kemajuan_penelitian.tanggal, tb_laporan_kemajuan_penelitian.jenis_berkas, tb_laporan_kemajuan_penelitian.file, tb_laporan_kemajuan_penelitian.id, tb_laporan_kemajuan_penelitian.url
        FROM tb_usulan_penelitian
        JOIN tb_laporan_kemajuan_penelitian ON tb_usulan_penelitian.id_dosen = tb_laporan_kemajuan_penelitian.id_dosen
        JOIN users ON tb_usulan_penelitian.nama_ketua = users.nik
        WHERE tb_laporan_kemajuan_penelitian.id_dosen = "' . Auth::user()->nik . '"
        GROUP By tb_laporan_kemajuan_penelitian.id
        ORDER by tb_laporan_kemajuan_penelitian.tanggal DESC
        ');
        return view('dashboard.laporan-kemajuan-penelitian', compact('usulan', 'laporan'))
            ->with('title', 'Laporan Kemajuan Penelitian');
    }

    public function simpanlaporankemajuanpenelitian(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $this->validate($request, [
            'file' => 'required|mimes:doc,docx|max:5120',
        ]);

        $time = Date('YmdHis');

        $file       = $request->file('file');
        $ext        = $file->getClientOriginalExtension();
        $newName    = $time . "." . $ext;
        $file->move(public_path() . '/assets/file/laporan-kemajuan-penelitian/', $newName);

        $tanggal = Date('Y-m-d');

        $penelitian = new LaporanKemajuanPenelitian();
        $penelitian->judul_penelitian          = $request->judul_penelitian;
        $penelitian->presentase_kemajuan       = $request->presentase_kemajuan;
        $penelitian->file                      = $newName;
        $penelitian->url                       = $_POST['url'];
        $penelitian->jenis_berkas              = $request->jenis_berkas;
        $penelitian->id_dosen                  = Auth::user()->nik;
        $penelitian->tanggal                   = $tanggal;
        $penelitian->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function deletelaporankemajuanpenelitian($id)
    {

        $get = DB::select('select * from tb_laporan_kemajuan_penelitian where id = "' . $id . '"');
        foreach ($get as $key => $value) {
            $target = $value->file;
        }

        if (file_exists(public_path() . '/assets/file/laporan-kemajuan-penelitian/' . $target)) {
            unlink(public_path() . '/assets/file/laporan-kemajuan-penelitian/' . $target);
        } else {
            # code...
        }

        $data = laporanKemajuanPenelitian::find($id);
        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function laporankemajuanpengabmas()
    {
        $usulan = DB::select('select * from tb_usulan_pengabmas where id_dosen = "' . Auth::user()->nik . '" and status = "di terima"');
        $laporan = DB::select('select users.name as nama_ketua, tb_usulan_pengabmas.jenis_pengabmas, tb_usulan_pengabmas.judul_pengabmas, tb_laporan_kemajuan_pengabmas.tanggal, tb_laporan_kemajuan_pengabmas.jenis_berkas, tb_laporan_kemajuan_pengabmas.file, tb_laporan_kemajuan_pengabmas.id, tb_laporan_kemajuan_pengabmas.url
        FROM tb_usulan_pengabmas
        JOIN tb_laporan_kemajuan_pengabmas ON tb_usulan_pengabmas.id_dosen = tb_laporan_kemajuan_pengabmas.id_dosen
        JOIN users ON tb_usulan_pengabmas.nama_ketua = users.nik
        WHERE tb_laporan_kemajuan_pengabmas.id_dosen = "' . Auth::user()->nik . '"
        GROUP By tb_laporan_kemajuan_pengabmas.id
        ORDER by tb_laporan_kemajuan_pengabmas.tanggal DESC
        ');
        return view('dashboard.laporan-kemajuan-pengabmas', compact('usulan', 'laporan'))
            ->with('title', 'Laporan Kemajuan Pengabmas');
    }

    public function simpanlaporankemajuanpengabmas(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $this->validate($request, [
            'file' => 'required|mimes:doc,docx|max:5120',
        ]);

        $time = Date('YmdHis');

        $file       = $request->file('file');
        $ext        = $file->getClientOriginalExtension();
        $newName    = $time . "." . $ext;
        $file->move(public_path() . '/assets/file/laporan-kemajuan-pengabmas/', $newName);

        $tanggal = Date('Y-m-d');

        $pengabmas = new LaporanKemajuanPengabmas();
        $pengabmas->judul_pengabmas          = $request->judul_pengabmas;
        $pengabmas->presentase_kemajuan       = $request->presentase_kemajuan;
        $pengabmas->file                      = $newName;
        $pengabmas->url                       = $_POST['url'];
        $pengabmas->jenis_berkas              = $request->jenis_berkas;
        $pengabmas->id_dosen                  = Auth::user()->nik;
        $pengabmas->tanggal                   = $tanggal;
        $pengabmas->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function deletelaporankemajuanpengabmas($id)
    {

        $get = DB::select('select * from tb_laporan_kemajuan_pengabmas where id = "' . $id . '"');
        foreach ($get as $key => $value) {
            $target = $value->file;
        }

        if (file_exists(public_path() . '/assets/file/laporan-kemajuan-pengabmas/' . $target)) {
            unlink(public_path() . '/assets/file/laporan-kemajuan-pengabmas/' . $target);
        } else {
            # code...
        }

        $data = laporanKemajuanPengabmas::find($id);
        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function laporanakhirpenelitian()
    {
        $usulan = DB::select('select * from tb_usulan_penelitian where id_dosen = "' . Auth::user()->nik . '" and status = "di terima"');
        $laporan = DB::select('select users.name as nama_ketua, tb_usulan_penelitian.jenis_penelitian, tb_usulan_penelitian.judul_penelitian, tb_laporan_akhir_penelitian.tanggal, tb_laporan_akhir_penelitian.jenis_luaran, tb_laporan_akhir_penelitian.file, tb_laporan_akhir_penelitian.luaran, tb_laporan_akhir_penelitian.lama_penelitian, tb_laporan_akhir_penelitian.lama_penelitian_riil, tb_laporan_akhir_penelitian.url, tb_laporan_akhir_penelitian.id
        FROM tb_usulan_penelitian
        JOIN tb_laporan_akhir_penelitian ON tb_usulan_penelitian.id_dosen = tb_laporan_akhir_penelitian.id_dosen
        JOIN users ON tb_usulan_penelitian.nama_ketua = users.nik
        WHERE tb_laporan_akhir_penelitian.id_dosen = "' . Auth::user()->nik . '"
        GROUP by tb_laporan_akhir_penelitian.id
        ORDER by tb_laporan_akhir_penelitian.tanggal DESC
        ');
        return view('dashboard.laporan-akhir-penelitian', compact('usulan', 'laporan'))
            ->with('title', 'Laporan Akhir Penelitian');
    }

    public function simpanlaporanakhirpenelitian(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $this->validate($request, [
            'file' => 'required|mimes:doc,docx|max:5120',
            'luaran' => 'required|mimes:doc,docx|max:5120',
        ]);

        $time = Date('YmdHis');

        $file       = $request->file('file');
        $ext        = $file->getClientOriginalExtension();
        $newName    = $time . "." . $ext;
        $file->move(public_path() . '/assets/file/laporan-akhir-penelitian/', $newName);

        $luaran       = $request->file('luaran');
        $extluaran        = $luaran->getClientOriginalExtension();
        $newluaran    = $time . "." . 'luaran' . "." . $extluaran;
        $luaran->move(public_path() . '/assets/file/laporan-akhir-penelitian/', $newluaran);

        $get_tanggal = DB::select('select tanggal from tb_usulan_penelitian where id_usulan = "' . $request->judul_penelitian . '"');
        foreach ($get_tanggal as $key => $value) {
            $tgl_usulan = new DateTime($value->tanggal);
        }

        $tanggal = Date('Y-m-d');

        $tanggal_sekarang = new DateTime();

        $lama_penelitian_riil = $tanggal_sekarang->diff($tgl_usulan)->format('%m');

        $penelitian = new LaporanAkhirPenelitian();
        $penelitian->judul_penelitian          = $request->judul_penelitian;
        $penelitian->lama_penelitian           = $request->lama_penelitian;
        $penelitian->lama_penelitian_riil      = $lama_penelitian_riil;
        $penelitian->file                      = $newName;
        $penelitian->jenis_luaran              = $_POST['jenis_luaran'];
        $penelitian->jenis_luaran1              = $_POST['jenis_luaran1'];
        $penelitian->jenis_luaran2              = $_POST['jenis_luaran2'];
        $penelitian->jenis_luaran3              = $_POST['jenis_luaran3'];
        $penelitian->url                        = $_POST['url'];
        $penelitian->luaran                      = $newluaran;
        $penelitian->id_dosen                  = Auth::user()->nik;
        $penelitian->tanggal                   = $tanggal;
        $penelitian->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function deletelaporanakhirpenelitian($id)
    {

        $get = DB::select('select * from tb_laporan_akhir_penelitian where id = "' . $id . '"');
        foreach ($get as $key => $value) {
            $target = $value->file;
        }

        if (file_exists(public_path() . '/assets/file/laporan-akhir-penelitian/' . $target)) {
            unlink(public_path() . '/assets/file/laporan-akhir-penelitian/' . $target);
        } else {
            # code...
        }

        $data = laporanAkhirPenelitian::find($id);
        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function laporanakhirpengabmas()
    {
        $usulan = DB::select('select * from tb_usulan_pengabmas where id_dosen = "' . Auth::user()->nik . '" and status = "di terima"');

        $laporan = DB::select('select users.name as nama_ketua, tb_usulan_pengabmas.jenis_pengabmas, tb_usulan_pengabmas.judul_pengabmas, tb_laporan_akhir_pengabmas.tanggal, tb_laporan_akhir_pengabmas.jenis_luaran, tb_laporan_akhir_pengabmas.file, tb_laporan_akhir_pengabmas.luaran, tb_laporan_akhir_pengabmas.lama_pengabmas, tb_laporan_akhir_pengabmas.lama_pengabmas_riil, tb_laporan_akhir_pengabmas.url, tb_laporan_akhir_pengabmas.id
        FROM tb_usulan_pengabmas
        JOIN tb_laporan_akhir_pengabmas ON tb_laporan_akhir_pengabmas.judul_pengabmas = tb_usulan_pengabmas.id_usulan
        JOIN users ON tb_usulan_pengabmas.nama_ketua = users.nik
        WHERE tb_laporan_akhir_pengabmas.id_dosen = "' . Auth::user()->nik . '"
        GROUP by tb_laporan_akhir_pengabmas.id
        ORDER by tb_laporan_akhir_pengabmas.tanggal DESC
        ');
        return view('dashboard.laporan-akhir-pengabmas', compact('usulan', 'laporan'))
            ->with('title', 'Laporan Akhir Pengabmas');
    }

    public function simpanlaporanakhirpengabmas(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $this->validate($request, [
            'file' => 'required|mimes:doc,docx|max:5120',
            'luaran' => 'required|mimes:doc,docx|max:5120',
        ]);

        $time = Date('YmdHis');

        $file       = $request->file('file');
        $ext        = $file->getClientOriginalExtension();
        $newName    = $time . "." . $ext;
        $file->move(public_path() . '/assets/file/laporan-akhir-pengabmas/', $newName);

        $luaran       = $request->file('luaran');
        $extluaran        = $luaran->getClientOriginalExtension();
        $newluaran    = $time . "." . 'luaran' . "." . $extluaran;
        $luaran->move(public_path() . '/assets/file/laporan-akhir-pengabmas/', $newluaran);

        $get_tanggal = DB::select('select tanggal from tb_usulan_pengabmas where id_usulan = "' . $request->judul_pengabmas . '"');
        foreach ($get_tanggal as $key => $value) {
            $tgl_usulan = new DateTime($value->tanggal);
        }

        $tanggal = Date('Y-m-d');

        $tanggal_sekarang = new DateTime();

        $lama_pengabmas_riil = $tanggal_sekarang->diff($tgl_usulan)->format('%m');

        $pengabmas = new LaporanAkhirPengabmas();
        $pengabmas->judul_pengabmas          = $request->judul_pengabmas;
        $pengabmas->lama_pengabmas           = $request->lama_pengabmas;
        $pengabmas->lama_pengabmas_riil      = $lama_pengabmas_riil;
        $pengabmas->file                     = $newName;
        $pengabmas->jenis_luaran             = $_POST['jenis_luaran'];
        $pengabmas->jenis_luaran1            = $_POST['jenis_luaran1'];
        $pengabmas->jenis_luaran2            = $_POST['jenis_luaran2'];
        $pengabmas->jenis_luaran3            = $_POST['jenis_luaran3'];
        $pengabmas->url                      = $_POST['url'];
        $pengabmas->luaran                     = $newluaran;
        $pengabmas->id_dosen                 = Auth::user()->nik;
        $pengabmas->tanggal                  = $tanggal;
        $pengabmas->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function deletelaporanakhirpengabmas($id)
    {

        $get = DB::select('select * from tb_laporan_akhir_pengabmas where id = "' . $id . '"');
        foreach ($get as $key => $value) {
            $target = $value->file;
        }

        if (file_exists(public_path() . '/assets/file/laporan-akhir-pengabmas/' . $target)) {
            unlink(public_path() . '/assets/file/laporan-akhir-pengabmas/' . $target);
        } else {
            # code...
        }

        $data = laporanAkhirPengabmas::find($id);
        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function manajemenuser()
    {
        $data = DB::select('select * from users order by level ASC');
        return view('dashboard.manajemen-user', compact('data'))
            ->with('title', 'Manajemen Users');
    }

    public function simpanmanajemenuser(Request $request)
    {
        $this->validate($request, [
            'foto' => 'required|mimes:jpg,png,jpeg|max:2048',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|min:4|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'nik' => 'required|string|min:4',
            'jafung' => 'required',
            'fakultas' => 'required',
            'program_studi' => 'required',
            'no_handphone' => 'required',
        ]);

        $foto = $request->file('foto');
        $destinationPath = public_path() . '/assets/image/foto/';
        $inputfoto = $request->nik . '.' . $foto->getClientOriginalExtension();
        $foto->move($destinationPath, $inputfoto);

        $user = new User();
        $user->foto = $inputfoto;
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->password_view = $request->password;
        $user->nik = $request->nik;
        $user->jafung = $request->jafung;
        $user->fakultas = $request->fakultas;
        $user->program_studi = $request->program_studi;
        $user->no_handphone = $request->no_handphone;
        $user->level = $request->level;
        $user->save();

        if (env('MAIL_HOST') != 'mail.yourdomain.id' || env('MAIL_USERNAME') != 'noreply@yourdomain.id') {
            $ucapan = 'Anda telah di daftarkan di ASIPP. Akun terdaftar dengan username: "' . $request->username . '" dan password : "' . $request->password . '"';

            try {
                Mail::send('email', array('pesan' => $ucapan), function ($pesan) use ($request) {
                    $pesan->to($request->email, 'Konfirmasi Pendaftaran ASIPP')->subject('Konfirmasi Pendaftaran ASIPP');
                    $pesan->from(env('MAIL_USERNAME', 'MAIL_FROM_ADDRESS'), 'Konfirmasi Pendaftaran ASIPP');
                });
            } catch (Exception $e) {
                return response(['status' => false, 'errors' => $e->getMessage()]);
            }
        }

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function deletemanajemenuser($id)
    {
        $data = User::find($id);
        $target = $data['foto'];

        if ($target != 'default.png') {
            unlink(public_path() . '/assets/image/foto/' . $target);
        }

        $data = User::find($id);
        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function updatemanajemenuser(Request $request, $id)
    {
        $this->validate($request, [
            'nik' => 'required',
        ]);

        $user = User::find($id);
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->password_view = $request->password;
        $user->nik = $request->nik;
        $user->jafung = $request->jafung;
        $user->fakultas = $request->fakultas;
        $user->program_studi = $request->program_studi;
        $user->no_handphone = $request->no_handphone;
        $user->level = $request->level;
        $user->pin = $request->level;
        $user->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function manajemenberita()
    {
        $data = DB::select('select tb_berita.*, users.name
        from users 
        JOIN tb_berita ON tb_berita.id_user = users.nik
        GROUP by tb_berita.id
        order by tb_berita.tanggal DESC');
        return view('dashboard.manajemen-berita', compact('data'))
            ->with('title', 'Manajemen Berita');
    }

    public function simpanmanajemenberita(Request $request)
    {
        $this->validate($request, [
            'judul' => 'required',
            'gambar' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'isi' => 'required'
        ]);

        $foto = $request->file('gambar');
        $destinationPath = public_path() . '/assets/image/postingan/';
        $inputfoto = rand(11111, 99999) . '.' . $foto->getClientOriginalExtension();
        $foto->move($destinationPath, $inputfoto);

        $tanggal = Date('Y-m-d');

        $berita = new Berita();
        $berita->gambar = $inputfoto;
        $berita->judul = $request->judul;
        $berita->url = str_replace(" ", "_", $request->judul);
        $berita->isi = $request->isi;
        $berita->tanggal = $tanggal;
        $berita->id_user = Auth::user()->nik;
        $berita->view = 0;
        $berita->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function deletemanajemenberita($id)
    {
        $data = Berita::find($id);
        $target = $data['gambar'];

        if (file_exists(public_path() . '/assets/image/postingan/' . $target)) {
            unlink(public_path() . '/assets/image/postingan/' . $target);
        }

        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function updatemanajemenberita(Request $request, $id)
    {
        $berita = berita::find($id);
        $berita->gambar = $input['gambar'];
        $berita->judul = $request->judul;
        $berita->url = str_replace(" ", "_", $request->judul);
        $berita->isi = $request->isi;
        $berita->tanggal = $tanggal;
        $berita->id_user = Auth::user()->nik;
        $berita->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function historiakses()
    {
        $data = DB::select('select * from tb_histori_akses order by created_at DESC');
        return view('dashboard.histori-akses', compact('data'))
            ->with('title', 'Histori Akses');
    }

    public function deletehistori()
    {
        DB::delete('delete from tb_histori_akses');
        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function deletetanggapanusulanpenelitian($id)
    {
        $data = DB::table('tb_tanggapan_penelitian')->where('id_usulan', '=', $id)->orWhere('jenis_laporan', '=', "usulan");
        $cekfile = $data->first();
        $target = $cekfile->file;

        if (file_exists(public_path() . '/assets/file/tanggapan-usulan-penelitian/' . $target)) {
            unlink(public_path() . '/assets/file/tanggapan-usulan-penelitian/' . $target);
        }

        $data->delete();
        Session::flash('sukses', 'Nilai berhasil di batalkan');
        return back();
    }

    public function deletetanggapanusulanpengabmas($id)
    {
        $data = DB::table('tb_tanggapan_pengabmas')->where('id_usulan', '=', $id)->orWhere('jenis_laporan', '=', "usulan");
        $cekfile = $data->first();
        $target = $cekfile->file;

        if (file_exists(public_path() . '/assets/file/tanggapan-usulan-pengabmas/' . $target)) {
            unlink(public_path() . '/assets/file/tanggapan-usulan-pengabmas/' . $target);
        }

        $data->delete();
        Session::flash('sukses', 'Nilai berhasil di batalkan');
        return back();
    }

    public function deletetanggapankemajuanpenelitian($id)
    {
        DB::delete('delete from tb_tanggapan_penelitian where id_usulan = "' . $id . '" and jenis_laporan = "kemajuan"');
        Session::flash('sukses', 'Nilai berhasil di batalkan');
        return back();
    }

    public function deletetanggapankemajuanpengabmas($id)
    {
        DB::delete('delete from tb_tanggapan_pengabmas where id_usulan = "' . $id . '" and jenis_laporan = "kemajuan"');
        Session::flash('sukses', 'Nilai berhasil di batalkan');
        return back();
    }

    public function deletetanggapanakhirpenelitian($id)
    {
        DB::delete('delete from tb_tanggapan_penelitian where id_usulan = "' . $id . '" and jenis_laporan = "akhir"');
        Session::flash('sukses', 'Nilai berhasil di batalkan');
        return back();
    }

    public function deletetanggapanakhirpengabmas($id)
    {
        DB::delete('delete from tb_tanggapan_pengabmas where id_usulan = "' . $id . '" and jenis_laporan = "akhir"');
        Session::flash('sukses', 'Nilai berhasil di batalkan');
        return back();
    }

    public function downloadusulanpenelitian(Request $request, $type)
    {

        $data = DB::select('select users.nik as "NIK", users.name as "Nama", users.email as "Email", users.fakultas as "Fakultas", users.program_studi as "Prodi", tb_usulan_penelitian.judul_penelitian as "Judul", tb_usulan_penelitian.file as "File", tb_usulan_penelitian.luaran_wajib as "Luaran Wajib", tb_usulan_penelitian.luaran_tambahan as "Luaran Tambahan", tb_usulan_penelitian.biaya_hibah_pt as "Biaya PT", tb_usulan_penelitian.biaya_hibah_luar as "Hibah Luar",  tb_usulan_penelitian.jenis_penelitian as "Jenis Penelitian", tb_usulan_penelitian.tanggal as "Tanggal", tb_usulan_penelitian.status as "Status"
        FROM users
        JOIN tb_usulan_penelitian ON users.nik = tb_usulan_penelitian.id_dosen
        group by tb_usulan_penelitian.id_usulan
        order by tb_usulan_penelitian.tanggal DESC');
        $data = array_map(function ($value) {
            return (array)$value;
        }, $data);

        return Excel::create('Usulan Penelitian', function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->mergeCells('A1:N1');
                $sheet->row(1, function ($row) {
                    $row->setFontFamily('Calibri');
                    $row->setFontSize(15);
                    $row->setFontWeight('bold');
                });
                $sheet->row(1, array('DATA USULAN PENELITIAN'));
                $sheet->row(2, function ($row) {
                    $row->setFontFamily('Calibri');
                    $row->setAlignment('center');
                    $row->setFontSize(15);
                    $row->setFontWeight('bold');
                });
                // $sheet->fromArray($data);
                $sheet->appendRow(array_keys($data[0]));
                $sheet->row($sheet->getHighestRow(), function ($row) {
                    $row->setFontWeight('bold');
                    $row->setFontColor('#000000');
                    $row->setBackground('#f1f4f6');
                });
                foreach ($data as $datasiswa) {
                    $sheet->appendRow($datasiswa);
                    $sheet->row($sheet->getHighestRow(), function ($row) {
                        $row->setAlignment('left');
                        $row->setBorder('solid');
                    });
                }
            });
        })->download($type);
    }

    public function konfirmasiusulanpenelitian()
    {
        $usulanbaru = DB::select('select users.nik, users.name, users.email, users.fakultas, users.program_studi, tb_usulan_penelitian.judul_penelitian, tb_usulan_penelitian.file, tb_usulan_penelitian.tanggal, tb_usulan_penelitian.status, tb_usulan_penelitian.id, tb_usulan_penelitian.biaya_hibah_pt, tb_usulan_penelitian.biaya_hibah_luar,  tb_usulan_penelitian.jenis_penelitian, tb_usulan_penelitian.id_usulan
        FROM users
        JOIN tb_usulan_penelitian ON users.nik = tb_usulan_penelitian.id_dosen
        where tb_usulan_penelitian.status = "pengajuan"
        group by tb_usulan_penelitian.id_usulan
        order by tb_usulan_penelitian.tanggal DESC');
        $usulanditerima = DB::select('select users.nik, users.name, users.fakultas, users.program_studi, tb_usulan_penelitian.judul_penelitian, tb_usulan_penelitian.file, tb_usulan_penelitian.tanggal, tb_usulan_penelitian.status, tb_usulan_penelitian.id, tb_usulan_penelitian.biaya_hibah_pt, tb_usulan_penelitian.biaya_hibah_luar,  tb_usulan_penelitian.jenis_penelitian, tb_usulan_penelitian.id_usulan
        FROM users
        JOIN tb_usulan_penelitian ON users.nik = tb_usulan_penelitian.id_dosen
        where tb_usulan_penelitian.status = "di terima"
        group by tb_usulan_penelitian.id_usulan
        order by tb_usulan_penelitian.tanggal DESC');
        $usulanditolak = DB::select('select users.nik, users.name, users.fakultas, users.program_studi, tb_usulan_penelitian.judul_penelitian, tb_usulan_penelitian.file, tb_usulan_penelitian.tanggal, tb_usulan_penelitian.status, tb_usulan_penelitian.id, tb_usulan_penelitian.biaya_hibah_pt, tb_usulan_penelitian.biaya_hibah_luar,  tb_usulan_penelitian.jenis_penelitian, tb_usulan_penelitian.id_usulan
        FROM users
        JOIN tb_usulan_penelitian ON users.nik = tb_usulan_penelitian.id_dosen
        where tb_usulan_penelitian.status = "di tolak"
        group by tb_usulan_penelitian.id_usulan
        order by tb_usulan_penelitian.tanggal DESC');

        return view('dashboard.konfirmasi-usulan-penelitian', compact('usulanbaru', 'usulanditerima', 'usulanditolak'))
            ->with('title', 'Konfirmasi Usulan Penelitian');
    }

    public function simpankonfirmasiusulanpenelitian(Request $request, $id)
    {

        $data = UsulanPenelitian::find($id);
        $data->status = $request->status;
        $data->save();


        if (env('MAIL_HOST') != 'mail.yourdomain.id' || env('MAIL_USERNAME') != 'noreply@yourdomain.id') {
            if ($request->status == 'di terima') {
                $ucapan = 'Selamat usulan penelitian anda dengan judul "' . $request->judul_penelitian . '" di terima, silahkan lanjutkan ke tahap berikutnya';
            } else {
                $ucapan = 'Maaf usulan penelitian anda dengan judul "' . $request->judul_penelitian . '" di tolak';
            }
            //
            try {
                Mail::send('email', array('pesan' => $ucapan), function ($pesan) use ($request) {
                    $pesan->to($request->email, 'Konfirmasi Usulan Penelitian')->subject('Konfirmasi Usulan Penelitian');
                    $pesan->from(env('MAIL_USERNAME', 'MAIL_FROM_ADDRESS'), 'Konfirmasi Usulan Penelitian');
                });
            } catch (Exception $e) {
                return response(['status' => false, 'errors' => $e->getMessage()]);
            }
            //
        }

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function batalusulanpenelitianditerima($id)
    {

        $data = UsulanPenelitian::find($id);
        $data->status = 'pengajuan';
        $data->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function batalusulanpenelitianditolak($id)
    {

        $data = UsulanPenelitian::find($id);
        $data->status = 'pengajuan';
        $data->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function downloadusulanpengabmas(Request $request, $type)
    {
        $data = DB::select('select users.nik as "NIK", users.name as "Nama", users.email as "Email", users.fakultas as "Fakultas", users.program_studi as "Prodi", tb_usulan_pengabmas.judul_pengabmas as "Judul", tb_usulan_pengabmas.file as "File", tb_usulan_pengabmas.luaran_wajib as "Luaran Wajib", tb_usulan_pengabmas.luaran_tambahan as "Luaran Tambahan", tb_usulan_pengabmas.biaya_hibah_pt as "Biaya PT", tb_usulan_pengabmas.biaya_hibah_luar as "Hibah Luar",  tb_usulan_pengabmas.jenis_pengabmas as "Jenis pengabmas", tb_usulan_pengabmas.tanggal as "Tanggal", tb_usulan_pengabmas.status as "Status"
        FROM users
        JOIN tb_usulan_pengabmas ON users.nik = tb_usulan_pengabmas.id_dosen
        group by tb_usulan_pengabmas.id_usulan
        order by tb_usulan_pengabmas.tanggal DESC');
        $data = array_map(function ($value) {
            return (array)$value;
        }, $data);
        return Excel::create('Usulan pengabmas', function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->mergeCells('A1:N1');
                $sheet->row(1, function ($row) {
                    $row->setFontFamily('Calibri');
                    $row->setFontSize(15);
                    $row->setFontWeight('bold');
                });
                $sheet->row(1, array('DATA USULAN PENGABMAS'));
                $sheet->row(2, function ($row) {
                    $row->setFontFamily('Calibri');
                    $row->setAlignment('center');
                    $row->setFontSize(15);
                    $row->setFontWeight('bold');
                });
                // $sheet->fromArray($data);
                $sheet->appendRow(array_keys($data[0]));
                $sheet->row($sheet->getHighestRow(), function ($row) {
                    $row->setFontWeight('bold');
                    $row->setFontColor('#000000');
                    $row->setBackground('#f1f4f6');
                });
                foreach ($data as $datasiswa) {
                    $sheet->appendRow($datasiswa);
                    $sheet->row($sheet->getHighestRow(), function ($row) {
                        $row->setAlignment('left');
                        $row->setBorder('solid');
                    });
                }
            });
        })->download($type);
    }

    public function konfirmasiusulanpengabmas()
    {
        $usulanbaru = DB::select('select users.nik, users.name, users.email, users.fakultas, users.program_studi, tb_usulan_pengabmas.judul_pengabmas, tb_usulan_pengabmas.file, tb_usulan_pengabmas.tanggal, tb_usulan_pengabmas.status, tb_usulan_pengabmas.id, tb_usulan_pengabmas.biaya_hibah_pt, tb_usulan_pengabmas.biaya_hibah_luar,  tb_usulan_pengabmas.jenis_pengabmas, tb_usulan_pengabmas.id_usulan
        FROM users
        JOIN tb_usulan_pengabmas ON users.nik = tb_usulan_pengabmas.id_dosen
        where tb_usulan_pengabmas.status = "pengajuan"
        group by tb_usulan_pengabmas.id_usulan
        order by tb_usulan_pengabmas.tanggal DESC');
        $usulanditerima = DB::select('select users.nik, users.name, users.fakultas, users.program_studi, tb_usulan_pengabmas.judul_pengabmas, tb_usulan_pengabmas.file, tb_usulan_pengabmas.tanggal, tb_usulan_pengabmas.status, tb_usulan_pengabmas.id, tb_usulan_pengabmas.biaya_hibah_pt, tb_usulan_pengabmas.biaya_hibah_luar,  tb_usulan_pengabmas.jenis_pengabmas, tb_usulan_pengabmas.id_usulan
        FROM users
        JOIN tb_usulan_pengabmas ON users.nik = tb_usulan_pengabmas.id_dosen
        where tb_usulan_pengabmas.status = "di terima"
        group by tb_usulan_pengabmas.id_usulan
        order by tb_usulan_pengabmas.tanggal DESC');
        $usulanditolak = DB::select('select users.nik, users.name, users.fakultas, users.program_studi, tb_usulan_pengabmas.judul_pengabmas, tb_usulan_pengabmas.file, tb_usulan_pengabmas.tanggal, tb_usulan_pengabmas.status, tb_usulan_pengabmas.id, tb_usulan_pengabmas.biaya_hibah_pt, tb_usulan_pengabmas.biaya_hibah_luar,  tb_usulan_pengabmas.jenis_pengabmas, tb_usulan_pengabmas.id_usulan
        FROM users
        JOIN tb_usulan_pengabmas ON users.nik = tb_usulan_pengabmas.id_dosen
        where tb_usulan_pengabmas.status = "di tolak"
        group by tb_usulan_pengabmas.id_usulan
        order by tb_usulan_pengabmas.tanggal DESC');

        return view('dashboard.konfirmasi-usulan-pengabmas', compact('usulanbaru', 'usulanditerima', 'usulanditolak'))
            ->with('title', 'Konfirmasi Usulan Pengabmas');
    }

    public function simpankonfirmasiusulanpengabmas(Request $request, $id)
    {

        $data = Usulanpengabmas::find($id);
        $data->status = $request->status;
        $data->save();

        if (env('MAIL_HOST') != 'mail.yourdomain.id' || env('MAIL_USERNAME') != 'noreply@yourdomain.id') {
            if ($request->status == 'di terima') {
                $ucapan = 'Selamat usulan pengabdian masyarakat anda';
            } else {
                $ucapan = 'Maaf usulan pengabdian masyarakat anda';
            }
            //
            try {
                Mail::send('email', array('pesan' => $ucapan . " " . $request->status), function ($pesan) use ($request) {
                    $pesan->to($request->email, 'Konfirmasi Usulan Pengabdian Masyarakat')->subject('Konfirmasi Usulan Pengabdian Masyarakat');
                    $pesan->from(env('MAIL_USERNAME', 'MAIL_FROM_ADDRESS'), 'Konfirmasi Usulan Pengabdian Masyarakat');
                });
            } catch (Exception $e) {
                return response(['status' => false, 'errors' => $e->getMessage()]);
            }
            //
        }

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function batalusulanpengabmasditerima($id)
    {

        $data = Usulanpengabmas::find($id);
        $data->status = 'pengajuan';
        $data->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function batalusulanpengabmasditolak($id)
    {

        $data = Usulanpengabmas::find($id);
        $data->status = 'pengajuan';
        $data->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function downloadkemajuanpenelitian(Request $request, $type)
    {
        $data = DB::select('select users.nik as "NIK", users.name as "Nama", users.fakultas as "Fakultas", users.program_studi  as "Prodi", tb_usulan_penelitian.judul_penelitian as "Judul",  tb_laporan_kemajuan_penelitian.presentase_kemajuan as "Presentase Kemajuan", tb_laporan_kemajuan_penelitian.file as "File", tb_laporan_kemajuan_penelitian.jenis_berkas as "Jenis Berkas", tb_laporan_kemajuan_penelitian.tanggal as "Tanggal", tb_laporan_kemajuan_penelitian.status as "Status"
        FROM users
        JOIN tb_laporan_kemajuan_penelitian ON users.nik = tb_laporan_kemajuan_penelitian.id_dosen
        JOIN tb_usulan_penelitian ON tb_laporan_kemajuan_penelitian.judul_penelitian = tb_usulan_penelitian.id_usulan
        group by tb_laporan_kemajuan_penelitian.id
        order by tb_laporan_kemajuan_penelitian.tanggal DESC');

        $data = array_map(function ($value) {
            return (array)$value;
        }, $data);
        return Excel::create('Laporan Kemajuan Penelitian', function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->mergeCells('A1:J1');
                $sheet->row(1, function ($row) {
                    $row->setFontFamily('Calibri');
                    $row->setFontSize(15);
                    $row->setFontWeight('bold');
                });
                $sheet->row(1, array('DATA LAPORAN KEMAJUAN PENELITIAN'));
                $sheet->row(2, function ($row) {
                    $row->setFontFamily('Calibri');
                    $row->setAlignment('center');
                    $row->setFontSize(15);
                    $row->setFontWeight('bold');
                });
                // $sheet->fromArray($data);
                $sheet->appendRow(array_keys($data[0]));
                $sheet->row($sheet->getHighestRow(), function ($row) {
                    $row->setFontWeight('bold');
                    $row->setFontColor('#000000');
                    $row->setBackground('#f1f4f6');
                });
                foreach ($data as $datasiswa) {
                    $sheet->appendRow($datasiswa);
                    $sheet->row($sheet->getHighestRow(), function ($row) {
                        $row->setAlignment('left');
                        $row->setBorder('solid');
                    });
                }
            });
        })->download($type);
    }

    public function konfirmasikemajuanpenelitian()
    {
        $kemajuanbaru = DB::select('select users.nik, users.name, users.fakultas, users.program_studi, tb_usulan_penelitian.judul_penelitian,  tb_laporan_kemajuan_penelitian.presentase_kemajuan, tb_laporan_kemajuan_penelitian.file, tb_laporan_kemajuan_penelitian.tanggal, tb_laporan_kemajuan_penelitian.status, tb_laporan_kemajuan_penelitian.jenis_berkas, tb_laporan_kemajuan_penelitian.id
        FROM users
        JOIN tb_laporan_kemajuan_penelitian ON users.nik = tb_laporan_kemajuan_penelitian.id_dosen
        JOIN tb_usulan_penelitian ON tb_laporan_kemajuan_penelitian.judul_penelitian = tb_usulan_penelitian.id_usulan
        where tb_laporan_kemajuan_penelitian.status = ""
        group by tb_laporan_kemajuan_penelitian.id
        order by tb_laporan_kemajuan_penelitian.tanggal DESC');

        $kemajuandilihat = DB::select('select users.nik, users.name, users.fakultas, users.program_studi, tb_usulan_penelitian.judul_penelitian, tb_laporan_kemajuan_penelitian.presentase_kemajuan, tb_laporan_kemajuan_penelitian.file, tb_laporan_kemajuan_penelitian.tanggal, tb_laporan_kemajuan_penelitian.status, tb_laporan_kemajuan_penelitian.jenis_berkas, tb_laporan_kemajuan_penelitian.id
        FROM users
        JOIN tb_laporan_kemajuan_penelitian ON users.nik = tb_laporan_kemajuan_penelitian.id_dosen
        JOIN tb_usulan_penelitian ON tb_laporan_kemajuan_penelitian.judul_penelitian = tb_usulan_penelitian.id_usulan
        where tb_laporan_kemajuan_penelitian.status = "di lihat"
        group by tb_laporan_kemajuan_penelitian.id
        order by tb_laporan_kemajuan_penelitian.tanggal DESC');

        return view('dashboard.konfirmasi-kemajuan-penelitian', compact('kemajuanbaru', 'kemajuandilihat'))
            ->with('title', 'Konfirmasi Kemajuan Penelitian');
    }


    public function checkkemajuanpenelitian($id)
    {

        $data = LaporanKemajuanPenelitian::find($id);
        $data->status = "di lihat";
        $data->save();

        Session::flash('sukses', 'Data selesai di lihat');
        return back();
    }

    public function downloadkemajuanpengabmas(Request $request, $type)
    {
        $data = DB::select('select users.nik as "NIK", users.name as "Nama", users.fakultas as "Fakultas", users.program_studi  as "Prodi", tb_usulan_pengabmas.judul_pengabmas as "Judul",  tb_laporan_kemajuan_pengabmas.presentase_kemajuan as "Presentase Kemajuan", tb_laporan_kemajuan_pengabmas.file as "File", tb_laporan_kemajuan_pengabmas.jenis_berkas as "Jenis Berkas", tb_laporan_kemajuan_pengabmas.tanggal as "Tanggal", tb_laporan_kemajuan_pengabmas.status as "Status"
        FROM users
        JOIN tb_laporan_kemajuan_pengabmas ON users.nik = tb_laporan_kemajuan_pengabmas.id_dosen
        JOIN tb_usulan_pengabmas ON tb_laporan_kemajuan_pengabmas.judul_pengabmas = tb_usulan_pengabmas.id_usulan
        group by tb_laporan_kemajuan_pengabmas.id
        order by tb_laporan_kemajuan_pengabmas.tanggal DESC');

        $data = array_map(function ($value) {
            return (array)$value;
        }, $data);
        return Excel::create('Laporan Kemajuan pengabmas', function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->mergeCells('A1:J1');
                $sheet->row(1, function ($row) {
                    $row->setFontFamily('Calibri');
                    $row->setFontSize(15);
                    $row->setFontWeight('bold');
                });
                $sheet->row(1, array('DATA LAPORAN KEMAJUAN PENGABMAS'));
                $sheet->row(2, function ($row) {
                    $row->setFontFamily('Calibri');
                    $row->setAlignment('center');
                    $row->setFontSize(15);
                    $row->setFontWeight('bold');
                });
                // $sheet->fromArray($data);
                $sheet->appendRow(array_keys($data[0]));
                $sheet->row($sheet->getHighestRow(), function ($row) {
                    $row->setFontWeight('bold');
                    $row->setFontColor('#000000');
                    $row->setBackground('#f1f4f6');
                });
                foreach ($data as $datasiswa) {
                    $sheet->appendRow($datasiswa);
                    $sheet->row($sheet->getHighestRow(), function ($row) {
                        $row->setAlignment('left');
                        $row->setBorder('solid');
                    });
                }
            });
        })->download($type);
    }

    public function konfirmasikemajuanpengabmas()
    {
        $kemajuanbaru = DB::select('select users.nik, users.name, users.fakultas, users.program_studi, tb_usulan_pengabmas.judul_pengabmas,  tb_laporan_kemajuan_pengabmas.presentase_kemajuan, tb_laporan_kemajuan_pengabmas.file, tb_laporan_kemajuan_pengabmas.tanggal, tb_laporan_kemajuan_pengabmas.status, tb_laporan_kemajuan_pengabmas.jenis_berkas, tb_laporan_kemajuan_pengabmas.id
        FROM users
        JOIN tb_laporan_kemajuan_pengabmas ON users.nik = tb_laporan_kemajuan_pengabmas.id_dosen
        JOIN tb_usulan_pengabmas ON tb_laporan_kemajuan_pengabmas.judul_pengabmas = tb_usulan_pengabmas.id_usulan
        where tb_laporan_kemajuan_pengabmas.status = ""
        group by tb_laporan_kemajuan_pengabmas.id
        order by tb_laporan_kemajuan_pengabmas.tanggal DESC');

        $kemajuandilihat = DB::select('select users.nik, users.name, users.fakultas, users.program_studi, tb_usulan_pengabmas.judul_pengabmas, tb_laporan_kemajuan_pengabmas.presentase_kemajuan, tb_laporan_kemajuan_pengabmas.file, tb_laporan_kemajuan_pengabmas.tanggal, tb_laporan_kemajuan_pengabmas.status, tb_laporan_kemajuan_pengabmas.jenis_berkas, tb_laporan_kemajuan_pengabmas.id
        FROM users
        JOIN tb_laporan_kemajuan_pengabmas ON users.nik = tb_laporan_kemajuan_pengabmas.id_dosen
        JOIN tb_usulan_pengabmas ON tb_laporan_kemajuan_pengabmas.judul_pengabmas = tb_usulan_pengabmas.id_usulan
        where tb_laporan_kemajuan_pengabmas.status = "di lihat"
        group by tb_laporan_kemajuan_pengabmas.id
        order by tb_laporan_kemajuan_pengabmas.tanggal DESC');

        return view('dashboard.konfirmasi-kemajuan-pengabmas', compact('kemajuanbaru', 'kemajuandilihat'))
            ->with('title', 'Konfirmasi Kemajuan Pengabmas');
    }


    public function checkkemajuanpengabmas($id)
    {

        $data = LaporanKemajuanPengabmas::find($id);
        $data->status = "di lihat";
        $data->save();

        Session::flash('sukses', 'Data selesai di lihat');
        return back();
    }

    public function downloadakhirpenelitian(Request $request, $type)
    {
        $data = DB::select('select users.nik as "NIK", users.name as "Nama", users.fakultas as "Fakultas", users.program_studi  as "Prodi", tb_usulan_penelitian.judul_penelitian as "Judul", tb_laporan_akhir_penelitian.lama_penelitian as "Lama Penelitian", tb_laporan_akhir_penelitian.file as "File", tb_laporan_akhir_penelitian.jenis_luaran as "Luaran Wajib", tb_laporan_akhir_penelitian.jenis_luaran1 as "Luaran Tambahan 1", tb_laporan_akhir_penelitian.jenis_luaran2 as "Luaran Tambahan 2", tb_laporan_akhir_penelitian.jenis_luaran3 as "Luaran Tambahan 3", tb_laporan_akhir_penelitian.tanggal as "Tanggal", tb_laporan_akhir_penelitian.status as "Status"
        FROM users
        JOIN tb_laporan_akhir_penelitian ON users.nik = tb_laporan_akhir_penelitian.id_dosen
        JOIN tb_usulan_penelitian ON tb_laporan_akhir_penelitian.judul_penelitian = tb_usulan_penelitian.id_usulan
        where tb_laporan_akhir_penelitian.status = ""
        group by tb_laporan_akhir_penelitian.id
        order by tb_laporan_akhir_penelitian.tanggal DESC');

        $data = array_map(function ($value) {
            return (array)$value;
        }, $data);
        return Excel::create('Laporan Akhir Penelitian', function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->mergeCells('A1:M1');
                $sheet->row(1, function ($row) {
                    $row->setFontFamily('Calibri');
                    $row->setFontSize(15);
                    $row->setFontWeight('bold');
                });
                $sheet->row(1, array('DATA LAPORAN AKHIR PENELITIAN'));
                $sheet->row(2, function ($row) {
                    $row->setFontFamily('Calibri');
                    $row->setAlignment('center');
                    $row->setFontSize(15);
                    $row->setFontWeight('bold');
                });
                // $sheet->fromArray($data);
                $sheet->appendRow(array_keys($data[0]));
                $sheet->row($sheet->getHighestRow(), function ($row) {
                    $row->setFontWeight('bold');
                    $row->setFontColor('#000000');
                    $row->setBackground('#f1f4f6');
                });
                foreach ($data as $datasiswa) {
                    $sheet->appendRow($datasiswa);
                    $sheet->row($sheet->getHighestRow(), function ($row) {
                        $row->setAlignment('left');
                        $row->setBorder('solid');
                    });
                }
            });
        })->download($type);
    }

    public function konfirmasiakhirpenelitian()
    {
        $akhirbaru = DB::select('select users.nik, users.name, users.fakultas, users.program_studi, tb_usulan_penelitian.judul_penelitian,  tb_laporan_akhir_penelitian.lama_penelitian, tb_laporan_akhir_penelitian.file, tb_laporan_akhir_penelitian.tanggal, tb_laporan_akhir_penelitian.status, tb_laporan_akhir_penelitian.jenis_luaran, tb_laporan_akhir_penelitian.id
        FROM users
        JOIN tb_laporan_akhir_penelitian ON users.nik = tb_laporan_akhir_penelitian.id_dosen
        JOIN tb_usulan_penelitian ON tb_laporan_akhir_penelitian.judul_penelitian = tb_usulan_penelitian.id_usulan
        where tb_laporan_akhir_penelitian.status = ""
        group by tb_laporan_akhir_penelitian.id
        order by tb_laporan_akhir_penelitian.tanggal DESC');

        $akhirdilihat = DB::select('select users.nik, users.name, users.fakultas, users.program_studi, tb_usulan_penelitian.judul_penelitian, tb_laporan_akhir_penelitian.lama_penelitian, tb_laporan_akhir_penelitian.file, tb_laporan_akhir_penelitian.tanggal, tb_laporan_akhir_penelitian.status, tb_laporan_akhir_penelitian.jenis_luaran, tb_laporan_akhir_penelitian.id
        FROM users
        JOIN tb_laporan_akhir_penelitian ON users.nik = tb_laporan_akhir_penelitian.id_dosen
        JOIN tb_usulan_penelitian ON tb_laporan_akhir_penelitian.judul_penelitian = tb_usulan_penelitian.id_usulan
        where tb_laporan_akhir_penelitian.status = "di lihat"
        group by tb_laporan_akhir_penelitian.id
        order by tb_laporan_akhir_penelitian.tanggal DESC');

        return view('dashboard.konfirmasi-akhir-penelitian', compact('akhirbaru', 'akhirdilihat'))
            ->with('title', 'Konfirmasi Akhir Penelitian');
    }


    public function checkakhirpenelitian($id)
    {

        $data = LaporanAkhirPenelitian::find($id);
        $data->status = "di lihat";
        $data->save();

        Session::flash('sukses', 'Data selesai di lihat');
        return back();
    }

    public function downloadakhirpengabmas(Request $request, $type)
    {
        $data = DB::select('select users.nik as "NIK", users.name as "Nama", users.fakultas as "Fakultas", users.program_studi  as "Prodi", tb_usulan_pengabmas.judul_pengabmas as "Judul", tb_laporan_akhir_pengabmas.lama_pengabmas as "Lama pengabmas", tb_laporan_akhir_pengabmas.file as "File", tb_laporan_akhir_pengabmas.jenis_luaran as "Luaran Wajib", tb_laporan_akhir_pengabmas.jenis_luaran1 as "Luaran Tambahan 1", tb_laporan_akhir_pengabmas.jenis_luaran2 as "Luaran Tambahan 2", tb_laporan_akhir_pengabmas.jenis_luaran3 as "Luaran Tambahan 3", tb_laporan_akhir_pengabmas.tanggal as "Tanggal", tb_laporan_akhir_pengabmas.status as "Status"
        FROM users
        JOIN tb_laporan_akhir_pengabmas ON users.nik = tb_laporan_akhir_pengabmas.id_dosen
        JOIN tb_usulan_pengabmas ON tb_laporan_akhir_pengabmas.judul_pengabmas = tb_usulan_pengabmas.id_usulan
        where tb_laporan_akhir_pengabmas.status = ""
        group by tb_laporan_akhir_pengabmas.id
        order by tb_laporan_akhir_pengabmas.tanggal DESC');

        $data = array_map(function ($value) {
            return (array)$value;
        }, $data);
        return Excel::create('Laporan Akhir pengabmas', function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->mergeCells('A1:M1');
                $sheet->row(1, function ($row) {
                    $row->setFontFamily('Calibri');
                    $row->setFontSize(15);
                    $row->setFontWeight('bold');
                });
                $sheet->row(1, array('DATA LAPORAN AKHIR PENGABMAS'));
                $sheet->row(2, function ($row) {
                    $row->setFontFamily('Calibri');
                    $row->setAlignment('center');
                    $row->setFontSize(15);
                    $row->setFontWeight('bold');
                });
                // $sheet->fromArray($data);
                $sheet->appendRow(array_keys($data[0]));
                $sheet->row($sheet->getHighestRow(), function ($row) {
                    $row->setFontWeight('bold');
                    $row->setFontColor('#000000');
                    $row->setBackground('#f1f4f6');
                });
                foreach ($data as $datasiswa) {
                    $sheet->appendRow($datasiswa);
                    $sheet->row($sheet->getHighestRow(), function ($row) {
                        $row->setAlignment('left');
                        $row->setBorder('solid');
                    });
                }
            });
        })->download($type);
    }

    public function konfirmasiakhirpengabmas()
    {
        $akhirbaru = DB::select('select users.nik, users.name, users.fakultas, users.program_studi, tb_usulan_pengabmas.judul_pengabmas,  tb_laporan_akhir_pengabmas.lama_pengabmas, tb_laporan_akhir_pengabmas.file, tb_laporan_akhir_pengabmas.tanggal, tb_laporan_akhir_pengabmas.status, tb_laporan_akhir_pengabmas.jenis_luaran, tb_laporan_akhir_pengabmas.id
        FROM users
        JOIN tb_laporan_akhir_pengabmas ON users.nik = tb_laporan_akhir_pengabmas.id_dosen
        JOIN tb_usulan_pengabmas ON tb_laporan_akhir_pengabmas.judul_pengabmas = tb_usulan_pengabmas.id_usulan
        where tb_laporan_akhir_pengabmas.status = ""
        group by tb_laporan_akhir_pengabmas.id
        order by tb_laporan_akhir_pengabmas.tanggal DESC');

        $akhirdilihat = DB::select('select users.nik, users.name, users.fakultas, users.program_studi, tb_usulan_pengabmas.judul_pengabmas, tb_laporan_akhir_pengabmas.lama_pengabmas, tb_laporan_akhir_pengabmas.file, tb_laporan_akhir_pengabmas.tanggal, tb_laporan_akhir_pengabmas.status, tb_laporan_akhir_pengabmas.jenis_luaran, tb_laporan_akhir_pengabmas.id
        FROM users
        JOIN tb_laporan_akhir_pengabmas ON users.nik = tb_laporan_akhir_pengabmas.id_dosen
        JOIN tb_usulan_pengabmas ON tb_laporan_akhir_pengabmas.judul_pengabmas = tb_usulan_pengabmas.id_usulan
        where tb_laporan_akhir_pengabmas.status = "di lihat"
        group by tb_laporan_akhir_pengabmas.id
        order by tb_laporan_akhir_pengabmas.tanggal DESC');

        return view('dashboard.konfirmasi-akhir-pengabmas', compact('akhirbaru', 'akhirdilihat'))
            ->with('title', 'Konfirmasi Akhir Pengabmas');
    }


    public function checkakhirpengabmas($id)
    {

        $data = LaporanAkhirPengabmas::find($id);
        $data->status = "di lihat";
        $data->save();

        Session::flash('sukses', 'Data selesai di lihat');
        return back();
    }

    public function downloadreviewerusulanpenelitian(Request $request, $type)
    {
        $data = DB::select('select users.name, users.nik, tb_usulan_penelitian.judul_penelitian, tb_reviewer_penelitian.* 
    from users
    JOIN tb_usulan_penelitian ON users.nik = tb_usulan_penelitian.id_dosen
    JOIN tb_reviewer_penelitian ON tb_usulan_penelitian.id_usulan = tb_reviewer_penelitian.id_usulan
    WHERE tb_reviewer_penelitian.jenis = 1
    GROUP by tb_reviewer_penelitian.id
    ORDER by tb_reviewer_penelitian.tanggal DESC');

        $data = array_map(function ($value) {
            return (array)$value;
        }, $data);
        return Excel::create('Plot Reviewer Usulan Penelitian', function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download($type);
    }

    public function downloadreviewerusulanpengabmas(Request $request, $type)
    {
        $data = DB::select('select users.name, users.nik, tb_usulan_pengabmas.judul_pengabmas, tb_reviewer_pengabmas.* 
    from users
    JOIN tb_usulan_pengabmas ON users.nik = tb_usulan_pengabmas.id_dosen
    JOIN tb_reviewer_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_reviewer_pengabmas.id_usulan
    WHERE tb_reviewer_pengabmas.jenis = 1
    GROUP by tb_reviewer_pengabmas.id
    ORDER by tb_reviewer_pengabmas.tanggal DESC');

        $data = array_map(function ($value) {
            return (array)$value;
        }, $data);
        return Excel::create('Plot Reviewer Usulan Pengabmas', function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download($type);
    }

    public function plotreviewer()
    {
        $reviewerpenelitian = DB::select('select users.name, users.nik, tb_usulan_penelitian.judul_penelitian, tb_usulan_penelitian.file, tb_usulan_penelitian.id_usulan, tb_reviewer_penelitian.* 
        from users
        JOIN tb_usulan_penelitian ON users.nik = tb_usulan_penelitian.id_dosen
        JOIN tb_reviewer_penelitian ON tb_usulan_penelitian.id_usulan = tb_reviewer_penelitian.id_usulan
        WHERE
        tb_reviewer_penelitian.jenis_laporan = "usulan" and
        tb_reviewer_penelitian.reviewer1 = "' . Auth::user()->name . '" or tb_reviewer_penelitian.reviewer2 = "' . Auth::user()->name . '"
        GROUP by tb_reviewer_penelitian.id
        ORDER by tb_reviewer_penelitian.tanggal DESC');

        $reviewerpengabmas = DB::select('select users.name, users.nik, tb_usulan_pengabmas.judul_pengabmas, tb_usulan_pengabmas.file, tb_usulan_pengabmas.id_usulan, tb_reviewer_pengabmas.* 
        from users
        JOIN tb_usulan_pengabmas ON users.nik = tb_usulan_pengabmas.id_dosen
        JOIN tb_reviewer_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_reviewer_pengabmas.id_usulan
        WHERE 
        tb_reviewer_pengabmas.jenis_laporan = "usulan" and
        tb_reviewer_pengabmas.reviewer1 = "' . Auth::user()->name . '" or tb_reviewer_pengabmas.reviewer2 = "' . Auth::user()->name . '"
        GROUP by tb_reviewer_pengabmas.id
        ORDER by tb_reviewer_pengabmas.tanggal DESC');

        $plotpenelitian = DB::select('select users.name, users.nik, tb_usulan_penelitian.judul_penelitian, tb_usulan_penelitian.id_usulan, tb_reviewer_penelitian.* 
        from users
        JOIN tb_usulan_penelitian ON users.nik = tb_usulan_penelitian.id_dosen
        JOIN tb_reviewer_penelitian ON tb_usulan_penelitian.id_usulan = tb_reviewer_penelitian.id_usulan
        WHERE tb_reviewer_penelitian.jenis = 1
        GROUP by tb_reviewer_penelitian.id
        ORDER by tb_reviewer_penelitian.tanggal DESC');

        $plotpengabmas = DB::select('select users.name, users.nik, tb_usulan_pengabmas.judul_pengabmas, tb_usulan_pengabmas.id_usulan, tb_reviewer_pengabmas.* 
        from users
        JOIN tb_usulan_pengabmas ON users.nik = tb_usulan_pengabmas.id_dosen
        JOIN tb_reviewer_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_reviewer_pengabmas.id_usulan
        WHERE tb_reviewer_pengabmas.jenis = 1
        GROUP by tb_reviewer_pengabmas.id
        ORDER by tb_reviewer_pengabmas.tanggal DESC');

        $plotdosenpenelitian = DB::select('select users.name, users.email, users.nik, tb_usulan_penelitian.judul_penelitian, tb_usulan_penelitian.id_usulan, tb_reviewer_penelitian.* 
        from users
        JOIN tb_usulan_penelitian ON users.nik = tb_usulan_penelitian.id_dosen
        JOIN tb_reviewer_penelitian ON tb_usulan_penelitian.id_usulan = tb_reviewer_penelitian.id_usulan
        WHERE users.nik = "' . Auth::user()->nik . '" GROUP by tb_reviewer_penelitian.id
        ORDER by tb_reviewer_penelitian.tanggal DESC');

        $plotdosenpengabmas = DB::select('select users.name, users.email, users.nik, tb_usulan_pengabmas.judul_pengabmas, tb_usulan_pengabmas.id_usulan, tb_reviewer_pengabmas.* 
        from users
        JOIN tb_usulan_pengabmas ON users.nik = tb_usulan_pengabmas.id_dosen
        JOIN tb_reviewer_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_reviewer_pengabmas.id_usulan
        WHERE users.nik = "' . Auth::user()->nik . '" GROUP by tb_reviewer_pengabmas.id
        ORDER by tb_reviewer_pengabmas.tanggal DESC');

        $usulanpenelitian = DB::select('select id_usulan, judul_penelitian from tb_usulan_penelitian where status = "pengajuan" order by judul_penelitian ASC');

        $usulanpengabmas = DB::select('select id_usulan, judul_pengabmas from tb_usulan_pengabmas where status = "pengajuan" order by judul_pengabmas ASC');

        $reviewer = DB::select('select name, nik from users order by name ASC');

        return view('dashboard.plot-reviewer', compact('usulanpenelitian', 'usulanpengabmas', 'plotpenelitian', 'plotpengabmas', 'reviewer', 'plotdosenpenelitian', 'plotdosenpengabmas', 'reviewerpenelitian', 'reviewerpengabmas'))
            ->with('title', 'Plot Reviewer Usulan');
    }

    public function simpanplotreviewerpenelitian(Request $request)
    {
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

        $this->validate($request, [
            'judul_penelitian' => 'required',
        ]);

        $tanggal = Date('Y-m-d');

        $data = new ReviewerPenelitian();
        $data->id_usulan = $request->judul_penelitian;
        $data->reviewer1 = $request->reviewer1;
        $data->reviewer2 = $_POST['reviewer2'];
        $data->tanggal = $tanggal;
        $data->status = 'proses reviewer';
        $data->jenis_laporan = 'usulan';
        $data->jenis = 1;
        $data->save();

        if (env('MAIL_HOST') != 'mail.yourdomain.id' || env('MAIL_USERNAME') != 'noreply@yourdomain.id') {
            $judul = DB::select('select judul_penelitian from tb_usulan_penelitian where id_usulan = "' . $request->judul_penelitian . '"');
            foreach ($judul as $key => $value) {
                $judul1 = $value->judul_penelitian;
            }

            $email1 = DB::select('select email from users where name = "' . $request->reviewer1 . '"');
            foreach ($email1 as $key => $value) {
                $reviewer1 = $value->email;
            }
            $email2 = DB::select('select email from users where name = "' . $request->reviewer2 . '"');
            foreach ($email2 as $key => $value) {
                $reviewer2 = $value->email;
            }

            $ucapan = 'Permohonan review usulan penelitian dengan judul "' . $judul1 . '" ';

            //
            try {
                Mail::send('email', array('pesan' => $ucapan), function ($pesan) use ($reviewer1) {
                    $pesan->to($reviewer1, 'Permohonan Review Usulan Penelitian')->subject('Permohonan Review Usulan Penelitian');
                    $pesan->from(env('MAIL_USERNAME', 'MAIL_FROM_ADDRESS'), 'Permohonan Review Usulan Penelitian');
                });
            } catch (Exception $e) {
                return response(['status' => false, 'errors' => $e->getMessage()]);
            }

            if (empty($request->reviewer2)) {
                # code...
            } else {
                try {
                    Mail::send('email', array('pesan' => $ucapan), function ($pesan) use ($reviewer2) {
                        $pesan->to($reviewer2, 'Permohonan Review Usulan Penelitian')->subject('Permohonan Review Usulan Penelitian');
                        $pesan->from(env('MAIL_USERNAME', 'MAIL_FROM_ADDRESS'), 'Permohonan Review Usulan Penelitian');
                    });
                } catch (Exception $e) {
                    return response(['status' => false, 'errors' => $e->getMessage()]);
                }
            }
        }

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function deleteplotreviewerpenelitian($id)
    {

        $data = ReviewerPenelitian::find($id);
        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function downloadreviewerkemajuanpenelitian(Request $request, $type)
    {
        $data = DB::select('select users.name, users.nik, tb_usulan_penelitian.judul_penelitian, tb_usulan_penelitian.id_usulan, tb_reviewer_penelitian.* 
    from users
    JOIN tb_usulan_penelitian ON users.nik = tb_usulan_penelitian.id_dosen
    JOIN tb_reviewer_penelitian ON tb_usulan_penelitian.id_usulan = tb_reviewer_penelitian.id_usulan
    WHERE tb_reviewer_penelitian.jenis = 2
    GROUP by tb_reviewer_penelitian.id
    ORDER by tb_reviewer_penelitian.tanggal DESC');

        $data = array_map(function ($value) {
            return (array)$value;
        }, $data);
        return Excel::create('Plot Reviewer Kemajuan Penelitian', function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download($type);
    }

    public function downloadreviewerkemajuanpengabmas(Request $request, $type)
    {
        $data = DB::select('select users.name, users.nik, tb_usulan_pengabmas.judul_pengabmas, tb_usulan_pengabmas.id_usulan, tb_reviewer_pengabmas.* 
    from users
    JOIN tb_usulan_pengabmas ON users.nik = tb_usulan_pengabmas.id_dosen
    JOIN tb_reviewer_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_reviewer_pengabmas.id_usulan
    WHERE tb_reviewer_pengabmas.jenis = 2
    GROUP by tb_reviewer_pengabmas.id
    ORDER by tb_reviewer_pengabmas.tanggal DESC');

        $data = array_map(function ($value) {
            return (array)$value;
        }, $data);
        return Excel::create('Plot Reviewer Kemajuan Pengabmas', function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download($type);
    }

    public function plotreviewerkemajuan()
    {
        $reviewerpenelitian = DB::select('select users.name, users.nik, tb_usulan_penelitian.judul_penelitian, tb_laporan_kemajuan_penelitian.file, tb_usulan_penelitian.id_usulan, tb_reviewer_penelitian.* 
        from users
        LEFT JOIN tb_usulan_penelitian ON users.nik = tb_usulan_penelitian.id_dosen
        LEFT JOIN tb_laporan_kemajuan_penelitian ON tb_usulan_penelitian.id_usulan = tb_laporan_kemajuan_penelitian.judul_penelitian
        LEFT JOIN tb_reviewer_penelitian ON tb_usulan_penelitian.id_usulan = tb_reviewer_penelitian.id_usulan
        WHERE 
        tb_reviewer_penelitian.jenis_laporan = "kemajuan" and
        tb_reviewer_penelitian.reviewer1 = "' . Auth::user()->name . '" or tb_reviewer_penelitian.reviewer2 = "' . Auth::user()->name . '"
        GROUP by tb_reviewer_penelitian.id
        ORDER by tb_reviewer_penelitian.tanggal DESC');

        $reviewerpengabmas = DB::select('select users.name, users.nik, tb_usulan_pengabmas.judul_pengabmas, tb_laporan_kemajuan_pengabmas.file, tb_usulan_pengabmas.id_usulan, tb_reviewer_pengabmas.* 
        from users
        LEFT JOIN tb_usulan_pengabmas ON users.nik = tb_usulan_pengabmas.id_dosen
        LEFT JOIN tb_laporan_kemajuan_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_laporan_kemajuan_pengabmas.judul_pengabmas
        LEFT JOIN tb_reviewer_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_reviewer_pengabmas.id_usulan
        WHERE 
        tb_reviewer_pengabmas.jenis_laporan = "kemajuan" and
        tb_reviewer_pengabmas.reviewer1 = "' . Auth::user()->name . '" or tb_reviewer_pengabmas.reviewer2 = "' . Auth::user()->name . '"
        GROUP by tb_reviewer_pengabmas.id
        ORDER by tb_reviewer_pengabmas.tanggal DESC');

        $plotpenelitian = DB::select('select users.name, users.nik, tb_usulan_penelitian.judul_penelitian, tb_usulan_penelitian.id_usulan, tb_reviewer_penelitian.* 
        from users
        JOIN tb_usulan_penelitian ON users.nik = tb_usulan_penelitian.id_dosen
        JOIN tb_reviewer_penelitian ON tb_usulan_penelitian.id_usulan = tb_reviewer_penelitian.id_usulan
        WHERE tb_reviewer_penelitian.jenis_laporan = "kemajuan" and tb_reviewer_penelitian.jenis = 2
        GROUP by tb_reviewer_penelitian.id
        ORDER by tb_reviewer_penelitian.tanggal DESC');

        $plotpengabmas = DB::select('select users.name, users.nik, tb_usulan_pengabmas.judul_pengabmas, tb_usulan_pengabmas.id_usulan, tb_reviewer_pengabmas.* 
        from users
        JOIN tb_usulan_pengabmas ON users.nik = tb_usulan_pengabmas.id_dosen
        JOIN tb_reviewer_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_reviewer_pengabmas.id_usulan
        WHERE tb_reviewer_pengabmas.jenis_laporan = "kemajuan" and  tb_reviewer_pengabmas.jenis = 2
        GROUP by tb_reviewer_pengabmas.id
        ORDER by tb_reviewer_pengabmas.tanggal DESC');

        $plotdosenpenelitian = DB::select('select users.name, users.nik, tb_usulan_penelitian.judul_penelitian, tb_usulan_penelitian.id_usulan, tb_reviewer_penelitian.* 
        from users
        JOIN tb_usulan_penelitian ON users.nik = tb_usulan_penelitian.id_dosen
        JOIN tb_reviewer_penelitian ON tb_usulan_penelitian.id_usulan = tb_reviewer_penelitian.id_usulan
        WHERE users.nik = "' . Auth::user()->nik . '" and tb_reviewer_penelitian.jenis_laporan = "kemajuan" GROUP by tb_reviewer_penelitian.id
        ORDER by tb_reviewer_penelitian.tanggal DESC');

        $plotdosenpengabmas = DB::select('select users.name, users.nik, tb_usulan_pengabmas.judul_pengabmas, tb_usulan_pengabmas.id_usulan, tb_reviewer_pengabmas.* 
        from users
        JOIN tb_usulan_pengabmas ON users.nik = tb_usulan_pengabmas.id_dosen
        JOIN tb_reviewer_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_reviewer_pengabmas.id_usulan
        WHERE users.nik = "' . Auth::user()->nik . '" and tb_reviewer_pengabmas.jenis_laporan = "kemajuan" GROUP by tb_reviewer_pengabmas.id
        ORDER by tb_reviewer_pengabmas.tanggal DESC');

        $usulanpenelitian = DB::select('select tb_usulan_penelitian.id_usulan, tb_usulan_penelitian.judul_penelitian, users.name
        from tb_usulan_penelitian
        LEFT JOIN users ON tb_usulan_penelitian.id_dosen = users.nik
        JOIN tb_laporan_akhir_penelitian ON tb_usulan_penelitian.id_usulan = tb_laporan_akhir_penelitian.judul_penelitian
        WHERE tb_usulan_penelitian.status = "di terima" and tb_usulan_penelitian.id_usulan NOT IN (SELECT id_usulan FROM tb_reviewer_penelitian where jenis_laporan = "kemajuan") order by tb_usulan_penelitian.judul_penelitian ASC');

        $usulanpengabmas = DB::select('select tb_usulan_pengabmas.id_usulan, tb_usulan_pengabmas.judul_pengabmas, users.name
        from tb_usulan_pengabmas
        LEFT JOIN users ON tb_usulan_pengabmas.id_dosen = users.nik
        JOIN tb_laporan_akhir_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_laporan_akhir_pengabmas.judul_pengabmas
        WHERE tb_usulan_pengabmas.status = "di terima" and tb_usulan_pengabmas.id_usulan NOT IN (SELECT id_usulan FROM tb_reviewer_pengabmas where jenis_laporan = "kemajuan") order by tb_usulan_pengabmas.judul_pengabmas ASC');

        $reviewer = DB::select('select name, nik from users order by name ASC');

        return view('dashboard.plot-reviewer-kemajuan', compact('usulanpenelitian', 'usulanpengabmas', 'plotpenelitian', 'plotpengabmas', 'reviewer', 'plotdosenpenelitian', 'plotdosenpengabmas', 'reviewerpenelitian', 'reviewerpengabmas'))
            ->with('title', 'Plot Reviewer Kemajuan');
    }

    public function simpanplotreviewerkemajuanpenelitian(Request $request)
    {

        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

        $this->validate($request, [
            'judul_penelitian' => 'required',
        ]);

        $tanggal = Date('Y-m-d');

        $data = new ReviewerPenelitian();
        $data->id_usulan = $request->judul_penelitian;
        $data->reviewer1 = $request->reviewer1;
        $data->reviewer2 = $_POST['reviewer2'];
        $data->tanggal = $tanggal;
        $data->status = 'proses reviewer';
        $data->jenis_laporan = 'kemajuan';
        $data->jenis = 2;
        $data->save();

        //     $judul = DB::select('select judul_penelitian from tb_usulan_penelitian where id_usulan = "'.$request->judul_penelitian.'"');
        //     foreach ($judul as $key => $value) {
        //         $judul1 = $value->judul_penelitian;
        //     }

        //     $email1= DB::select('select email from users where name = "'.$request->reviewer1.'"');
        //     foreach ($email1 as $key => $value) {
        //      $reviewer1 = $value->email;
        //  }
        //  $email2= DB::select('select email from users where name = "'.$request->reviewer2.'"');
        //  foreach ($email2 as $key => $value) {
        //      $reviewer2 = $value->email;
        //  }

        //  $ucapan = 'Permohonan review Laporan Kemajuan Penelitian dengan judul "'.$judul1.'" sudah bisa di cek di sistem';

        //     //
        //  try{
        //     Mail::send('email', array('pesan' => $ucapan) , function($pesan) use($reviewer1){
        //         $pesan->to($reviewer1,'Permohonan Review Laporan Kemajuan Penelitian')->subject('Permohonan Review Laporan Kemajuan Penelitian');
        //         $pesan->from(env('MAIL_USERNAME','MAIL_FROM_ADDRESS'),'Permohonan Review Laporan Kemajuan Penelitian');
        //     });
        // }catch (Exception $e){
        //     return response (['status' => false,'errors' => $e->getMessage()]);
        // }

        // if (empty($request->reviewer2)) {
        //     # code...
        // } else {
        //     try{
        //         Mail::send('email', array('pesan' => $ucapan) , function($pesan) use($reviewer2){
        //             $pesan->to($reviewer2,'Permohonan Review Laporan Kemajuan Penelitian')->subject('Permohonan Review Laporan Kemajuan Penelitian');
        //             $pesan->from(env('MAIL_USERNAME','MAIL_FROM_ADDRESS'),'Permohonan Review Laporan Kemajuan Penelitian');
        //         });
        //     }catch (Exception $e){
        //         return response (['status' => false,'errors' => $e->getMessage()]);
        //     }
        // }

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function deleteplotreviewerkemajuanpenelitian($id)
    {

        $data = ReviewerPenelitian::find($id);
        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function simpanplotreviewerkemajuanpengabmas(Request $request)
    {

        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

        $this->validate($request, [
            'judul_pengabmas' => 'required',
        ]);

        $tanggal = Date('Y-m-d');

        $data = new ReviewerPengabmas();
        $data->id_usulan = $request->judul_pengabmas;
        $data->reviewer1 = $request->reviewer1;
        $data->reviewer2 = $_POST['reviewer2'];
        $data->tanggal = $tanggal;
        $data->status = 'proses reviewer';
        $data->jenis_laporan = 'kemajuan';
        $data->jenis = 2;
        $data->save();

        //     $judul = DB::select('select judul_Pengabmas from tb_usulan_pengabmas where id_usulan = "'.$request->judul_pengabmas.'"');
        //     foreach ($judul as $key => $value) {
        //         $judul1 = $value->judul_Pengabmas;
        //     }

        //     $email1= DB::select('select email from users where name = "'.$request->reviewer1.'"');
        //     foreach ($email1 as $key => $value) {
        //      $reviewer1 = $value->email;
        //  }
        //  $email2= DB::select('select email from users where name = "'.$request->reviewer2.'"');
        //  foreach ($email2 as $key => $value) {
        //      $reviewer2 = $value->email;
        //  }

        //  $ucapan = 'Permohonan review Laporan Kemajuan Pengabdian Masyarakat dengan judul "'.$judul1.'" sudah bisa di cek di sistem';

        //     //
        //  try{
        //     Mail::send('email', array('pesan' => $ucapan) , function($pesan) use($reviewer1){
        //         $pesan->to($reviewer1,'Permohonan Review Laporan Kemajuan Pengabdian Masyarakat')->subject('Permohonan Review Laporan Kemajuan Pengabdian Masyarakat');
        //         $pesan->from(env('MAIL_USERNAME','MAIL_FROM_ADDRESS'),'Permohonan Review Laporan Kemajuan Pengabdian Masyarakat');
        //     });
        // }catch (Exception $e){
        //     return response (['status' => false,'errors' => $e->getMessage()]);
        // }

        // if (empty($request->reviewer2)) {
        //     # code...
        // } else {
        //     try{
        //         Mail::send('email', array('pesan' => $ucapan) , function($pesan) use($reviewer2){
        //             $pesan->to($reviewer2,'Permohonan Review Laporan Kemajuan Pengabdian Masyarakat')->subject('Permohonan Review Laporan Kemajuan Pengabdian Masyarakat');
        //             $pesan->from(env('MAIL_USERNAME','MAIL_FROM_ADDRESS'),'Permohonan Review Laporan Kemajuan Pengabdian Masyarakat');
        //         });
        //     }catch (Exception $e){
        //         return response (['status' => false,'errors' => $e->getMessage()]);
        //     }
        // }

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function deleteplotreviewerkemajuanpengabmas($id)
    {

        $data = ReviewerPengabmas::find($id);
        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function plotreviewerakhir()
    {
        $reviewerpenelitian = DB::select('select users.name, users.nik, tb_usulan_penelitian.judul_penelitian, tb_laporan_akhir_penelitian.file, tb_usulan_penelitian.id_usulan, tb_reviewer_penelitian.* 
        from users
        LEFT JOIN tb_usulan_penelitian ON users.nik = tb_usulan_penelitian.id_dosen
        LEFT JOIN tb_laporan_akhir_penelitian ON tb_usulan_penelitian.id_usulan = tb_laporan_akhir_penelitian.judul_penelitian
        LEFT JOIN tb_reviewer_penelitian ON tb_usulan_penelitian.id_usulan = tb_reviewer_penelitian.id_usulan
        WHERE 
        tb_reviewer_penelitian.jenis_laporan = "akhir" and
        tb_reviewer_penelitian.reviewer1 = "' . Auth::user()->name . '" or tb_reviewer_penelitian.reviewer2 = "' . Auth::user()->name . '"
        GROUP by tb_reviewer_penelitian.id
        ORDER by tb_reviewer_penelitian.tanggal DESC');

        $reviewerpengabmas = DB::select('select users.name, users.nik, tb_usulan_pengabmas.judul_pengabmas, tb_laporan_akhir_pengabmas.file, tb_usulan_pengabmas.id_usulan, tb_reviewer_pengabmas.* 
        from users
        LEFT JOIN tb_usulan_pengabmas ON users.nik = tb_usulan_pengabmas.id_dosen
        LEFT JOIN tb_laporan_akhir_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_laporan_akhir_pengabmas.judul_pengabmas
        LEFT JOIN tb_reviewer_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_reviewer_pengabmas.id_usulan
        WHERE 
        tb_reviewer_pengabmas.jenis_laporan = "akhir" and
        tb_reviewer_pengabmas.reviewer1 = "' . Auth::user()->name . '" or tb_reviewer_pengabmas.reviewer2 = "' . Auth::user()->name . '"
        GROUP by tb_reviewer_pengabmas.id
        ORDER by tb_reviewer_pengabmas.tanggal DESC');

        $plotpenelitian = DB::select('select users.name, users.nik, tb_usulan_penelitian.judul_penelitian, tb_usulan_penelitian.id_usulan, tb_reviewer_penelitian.* 
        from users
        JOIN tb_usulan_penelitian ON users.nik = tb_usulan_penelitian.id_dosen
        JOIN tb_reviewer_penelitian ON tb_usulan_penelitian.id_usulan = tb_reviewer_penelitian.id_usulan
        WHERE  tb_reviewer_penelitian.jenis_laporan = "akhir" and tb_reviewer_penelitian.jenis = 2
        GROUP by tb_reviewer_penelitian.id
        ORDER by tb_reviewer_penelitian.tanggal DESC');

        $plotpengabmas = DB::select('select users.name, users.nik, tb_usulan_pengabmas.judul_pengabmas, tb_usulan_pengabmas.id_usulan, tb_reviewer_pengabmas.* 
        from users
        JOIN tb_usulan_pengabmas ON users.nik = tb_usulan_pengabmas.id_dosen
        JOIN tb_reviewer_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_reviewer_pengabmas.id_usulan
        WHERE tb_reviewer_pengabmas.jenis_laporan = "akhir" and tb_reviewer_pengabmas.jenis = 2
        GROUP by tb_reviewer_pengabmas.id
        ORDER by tb_reviewer_pengabmas.tanggal DESC');

        $plotdosenpenelitian = DB::select('select users.name, users.nik, tb_usulan_penelitian.judul_penelitian, tb_usulan_penelitian.id_usulan, tb_reviewer_penelitian.* 
        from users
        JOIN tb_usulan_penelitian ON users.nik = tb_usulan_penelitian.id_dosen
        JOIN tb_reviewer_penelitian ON tb_usulan_penelitian.id_usulan = tb_reviewer_penelitian.id_usulan
        WHERE users.nik = "' . Auth::user()->nik . '" and tb_reviewer_penelitian.jenis_laporan = "akhir" GROUP by tb_reviewer_penelitian.id
        ORDER by tb_reviewer_penelitian.tanggal DESC');

        $plotdosenpengabmas = DB::select('select users.name, users.nik, tb_usulan_pengabmas.judul_pengabmas, tb_usulan_pengabmas.id_usulan, tb_reviewer_pengabmas.* 
        from users
        JOIN tb_usulan_pengabmas ON users.nik = tb_usulan_pengabmas.id_dosen
        JOIN tb_reviewer_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_reviewer_pengabmas.id_usulan
        WHERE users.nik = "' . Auth::user()->nik . '" and tb_reviewer_pengabmas.jenis_laporan = "akhir" GROUP by tb_reviewer_pengabmas.id
        ORDER by tb_reviewer_pengabmas.tanggal DESC');

        $usulanpenelitian = DB::select('select tb_usulan_penelitian.id_usulan, tb_usulan_penelitian.judul_penelitian, users.name
        from tb_usulan_penelitian
        LEFT JOIN users ON tb_usulan_penelitian.id_dosen = users.nik
        JOIN tb_laporan_akhir_penelitian ON tb_usulan_penelitian.id_usulan = tb_laporan_akhir_penelitian.judul_penelitian
        WHERE tb_usulan_penelitian.status = "di terima" and tb_usulan_penelitian.id_usulan NOT IN (SELECT id_usulan FROM tb_reviewer_penelitian where jenis_laporan = "akhir") order by tb_usulan_penelitian.judul_penelitian ASC');

        $usulanpengabmas = DB::select('select tb_usulan_pengabmas.id_usulan, tb_usulan_pengabmas.judul_pengabmas, users.name
        from tb_usulan_pengabmas
        LEFT JOIN users ON tb_usulan_pengabmas.id_dosen = users.nik
        JOIN tb_laporan_akhir_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_laporan_akhir_pengabmas.judul_pengabmas
        WHERE tb_usulan_pengabmas.status = "di terima" and tb_usulan_pengabmas.id_usulan NOT IN (SELECT id_usulan FROM tb_reviewer_pengabmas where jenis_laporan = "akhir") order by tb_usulan_pengabmas.judul_pengabmas ASC');

        $reviewer = DB::select('select name, nik from users order by name ASC');

        return view('dashboard.plot-reviewer-akhir', compact('usulanpenelitian', 'usulanpengabmas', 'plotpenelitian', 'plotpengabmas', 'reviewer', 'plotdosenpenelitian', 'plotdosenpengabmas', 'reviewerpenelitian', 'reviewerpengabmas'))
            ->with('title', 'Plot Reviewer Akhir');
    }

    public function simpanplotreviewerakhirpenelitian(Request $request)
    {

        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

        $this->validate($request, [
            'judul_penelitian' => 'required',
        ]);

        $tanggal = Date('Y-m-d');

        $data = new ReviewerPenelitian();
        $data->id_usulan = $request->judul_penelitian;
        $data->reviewer1 = $request->reviewer1;
        $data->reviewer2 = $_POST['reviewer2'];
        $data->tanggal = $tanggal;
        $data->status = 'proses reviewer';
        $data->jenis_laporan = 'akhir';
        $data->jenis = 2;
        $data->save();

        //     $judul = DB::select('select judul_penelitian from tb_usulan_penelitian where id_usulan = "'.$request->judul_penelitian.'"');
        //     foreach ($judul as $key => $value) {
        //         $judul1 = $value->judul_penelitian;
        //     }

        //     $email1= DB::select('select email from users where name = "'.$request->reviewer1.'"');
        //     foreach ($email1 as $key => $value) {
        //      $reviewer1 = $value->email;
        //  }
        //  $email2= DB::select('select email from users where name = "'.$request->reviewer2.'"');
        //  foreach ($email2 as $key => $value) {
        //      $reviewer2 = $value->email;
        //  }

        //  $ucapan = 'Permohonan review Laporan akhir Penelitian dengan judul "'.$judul1.'" sudah bisa di cek di sistem';

        //     //
        //  try{
        //     Mail::send('email', array('pesan' => $ucapan) , function($pesan) use($reviewer1){
        //         $pesan->to($reviewer1,'Permohonan Review Laporan akhir Penelitian')->subject('Permohonan Review Laporan akhir Penelitian');
        //         $pesan->from(env('MAIL_USERNAME','MAIL_FROM_ADDRESS'),'Permohonan Review Laporan akhir Penelitian');
        //     });
        // }catch (Exception $e){
        //     return response (['status' => false,'errors' => $e->getMessage()]);
        // }

        // if (empty($request->reviewer2)) {
        //     # code...
        // } else {
        //     try{
        //         Mail::send('email', array('pesan' => $ucapan) , function($pesan) use($reviewer2){
        //             $pesan->to($reviewer2,'Permohonan Review Laporan akhir Penelitian')->subject('Permohonan Review Laporan akhir Penelitian');
        //             $pesan->from(env('MAIL_USERNAME','MAIL_FROM_ADDRESS'),'Permohonan Review Laporan akhir Penelitian');
        //         });
        //     }catch (Exception $e){
        //         return response (['status' => false,'errors' => $e->getMessage()]);
        //     }
        // }

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function deleteplotreviewerakhirpenelitian($id)
    {

        $data = ReviewerPenelitian::find($id);
        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function simpanplotreviewerakhirpengabmas(Request $request)
    {

        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

        $this->validate($request, [
            'judul_pengabmas' => 'required',
        ]);

        $tanggal = Date('Y-m-d');

        $data = new ReviewerPengabmas();
        $data->id_usulan = $request->judul_pengabmas;
        $data->reviewer1 = $request->reviewer1;
        $data->reviewer2 = $_POST['reviewer2'];
        $data->tanggal = $tanggal;
        $data->status = 'proses reviewer';
        $data->jenis_laporan = 'akhir';
        $data->jenis = 2;
        $data->save();

        //     $judul = DB::select('select judul_Pengabmas from tb_usulan_pengabmas where id_usulan = "'.$request->judul_pengabmas.'"');
        //     foreach ($judul as $key => $value) {
        //         $judul1 = $value->judul_Pengabmas;
        //     }

        //     $email1= DB::select('select email from users where name = "'.$request->reviewer1.'"');
        //     foreach ($email1 as $key => $value) {
        //      $reviewer1 = $value->email;
        //  }
        //  $email2= DB::select('select email from users where name = "'.$request->reviewer2.'"');
        //  foreach ($email2 as $key => $value) {
        //      $reviewer2 = $value->email;
        //  }

        //  $ucapan = 'Permohonan review Laporan akhir Pengabdian Masyarakat dengan judul "'.$judul1.'" sudah bisa di cek di sistem';

        //     //
        //  try{
        //     Mail::send('email', array('pesan' => $ucapan) , function($pesan) use($reviewer1){
        //         $pesan->to($reviewer1,'Permohonan Review Laporan akhir Pengabdian Masyarakat')->subject('Permohonan Review Laporan akhir Pengabdian Masyarakat');
        //         $pesan->from(env('MAIL_USERNAME','MAIL_FROM_ADDRESS'),'Permohonan Review Laporan akhir Pengabdian Masyarakat');
        //     });
        // }catch (Exception $e){
        //     return response (['status' => false,'errors' => $e->getMessage()]);
        // }

        // if (empty($request->reviewer2)) {
        //     # code...
        // } else {
        //     try{
        //         Mail::send('email', array('pesan' => $ucapan) , function($pesan) use($reviewer2){
        //             $pesan->to($reviewer2,'Permohonan Review Laporan akhir Pengabdian Masyarakat')->subject('Permohonan Review Laporan akhir Pengabdian Masyarakat');
        //             $pesan->from(env('MAIL_USERNAME','MAIL_FROM_ADDRESS'),'Permohonan Review Laporan akhir Pengabdian Masyarakat');
        //         });
        //     }catch (Exception $e){
        //         return response (['status' => false,'errors' => $e->getMessage()]);
        //     }
        // }

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function deleteplotreviewerakhirpengabmas($id)
    {

        $data = ReviewerPengabmas::find($id);
        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function simpanplotreviewerpengabmas(Request $request)
    {

        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

        $this->validate($request, [
            'judul_pengabmas' => 'required',
        ]);

        $tanggal = Date('Y-m-d');

        $data = new ReviewerPengabmas();
        $data->id_usulan = $request->judul_pengabmas;
        $data->reviewer1 = $request->reviewer1;
        $data->reviewer2 = $_POST['reviewer2'];
        $data->tanggal = $tanggal;
        $data->status = 'proses reviewer';
        $data->jenis_laporan = 'usulan';
        $data->jenis = 1;
        $data->save();

        if (env('MAIL_HOST') != 'mail.yourdomain.id' || env('MAIL_USERNAME') != 'noreply@yourdomain.id') {
            $judul = DB::select('select judul_pengabmas from tb_usulan_pengabmas where id_usulan = "' . $request->judul_pengabmas . '"');
            foreach ($judul as $key => $value) {
                $judul1 = $value->judul_pengabmas;
            }

            $email1 = DB::select('select email from users where name = "' . $request->reviewer1 . '"');
            foreach ($email1 as $key => $value) {
                $reviewer1 = $value->email;
            }
            $email2 = DB::select('select email from users where name = "' . $request->reviewer2 . '"');
            foreach ($email2 as $key => $value) {
                $reviewer2 = $value->email;
            }

            $ucapan = 'Permohonan review usulan Pengabdian Masyarakat dengan judul "' . $judul1 . '" sudah bisa di cek di sistem';
            //
            try {
                Mail::send('email', array('pesan' => $ucapan), function ($pesan) use ($reviewer1) {
                    $pesan->to($reviewer1, 'Permohonan Review Usulan Pengabdian Masyarakat')->subject('Permohonan Review Usulan Pengabdian Masyarakat');
                    $pesan->from(env('MAIL_USERNAME', 'MAIL_FROM_ADDRESS'), 'Permohonan Review Usulan Pengabdian Masyarakat');
                });
            } catch (Exception $e) {
                return response(['status' => false, 'errors' => $e->getMessage()]);
            }

            if (empty($request->reviewer2)) {
                # code...
            } else {
                try {
                    Mail::send('email', array('pesan' => $ucapan), function ($pesan) use ($reviewer2) {
                        $pesan->to($reviewer2, 'Permohonan Review Usulan Pengabdian Masyarakat')->subject('Permohonan Review Usulan Pengabdian Masyarakat');
                        $pesan->from(env('MAIL_USERNAME', 'MAIL_FROM_ADDRESS'), 'Permohonan Review Usulan Pengabdian Masyarakat');
                    });
                } catch (Exception $e) {
                    return response(['status' => false, 'errors' => $e->getMessage()]);
                }
            }
        }

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function deleteplotreviewerpengabmas($id)
    {

        $data = ReviewerPengabmas::find($id);
        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function reviewereksternal()
    {
        $data = DB::select('select * from users where level = 5 order by level ASC');
        return view('dashboard.reviewer-eksternal', compact('data'))
            ->with('title', 'Reviewer');
    }

    public function simpanreviewereksternal(Request $request)
    {
        $this->validate($request, [
            'foto' => 'required|mimes:jpg,png,jpeg|max:2048',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|min:4|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'nik' => 'required|string|min:4|unique:users',
            'jafung' => 'required',
            'institusi' => 'required',
            'fakultas' => 'required',
            'program_studi' => 'required',
            'no_handphone' => 'required',
        ]);

        $foto = $request->file('foto');
        $destinationPath = public_path() . '/assets/image/foto/';
        $inputfoto = $request->nik . '.' . $foto->getClientOriginalExtension();
        $foto->move($destinationPath, $inputfoto);

        $user = new User();
        $user->foto = $inputfoto;
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->password_view = $request->password;
        $user->nik = $request->nik;
        $user->jafung = $request->jafung;
        $user->institusi = $request->institusi;
        $user->fakultas = $request->fakultas;
        $user->program_studi = $request->program_studi;
        $user->no_handphone = $request->no_handphone;
        $user->level = 5;
        $user->save();

        if (env('MAIL_HOST') != 'mail.yourdomain.id' || env('MAIL_USERNAME') != 'noreply@yourdomain.id') {
            $ucapan = 'Anda telah di daftarkan di ASIPP. Akun terdaftar dengan username: "' . $request->username . '" dan password : "' . $request->password . '"';

            try {
                Mail::send('email', array('pesan' => $ucapan), function ($pesan) use ($request) {
                    $pesan->to($request->email, 'Konfirmasi Pendaftaran ASIPP')->subject('Konfirmasi Pendaftaran ASIPP');
                    $pesan->from(env('MAIL_USERNAME', 'MAIL_FROM_ADDRESS'), 'Konfirmasi Pendaftaran ASIPP');
                });
            } catch (Exception $e) {
                return response(['status' => false, 'errors' => $e->getMessage()]);
            }
        }

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function deletereviewereksternal($id)
    {
        $data = User::find($id);
        $target = $data['foto'];

        if ($target != 'default.png') {
            unlink(public_path() . '/assets/image/foto/' . $target);
        }

        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function updatereviewereksternal(Request $request, $id)
    {

        $user = User::find($id);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        if ($request->password) :
            $user->password = Hash::make($request->password);
            $user->password_view = $request->password;
        endif;
        $user->nik = $request->nik;
        $user->jafung = $request->jafung;
        $user->institusi = $request->institusi;
        $user->fakultas = $request->fakultas;
        $user->program_studi = $request->program_studi;
        $user->no_handphone = $request->no_handphone;
        $user->level = 5;
        $user->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function tanggapanusulanpenelitian($id)
    {

        $data = DB::select('select tb_usulan_penelitian.id_usulan, tb_usulan_penelitian.judul_penelitian 
        from tb_usulan_penelitian
        JOIN tb_reviewer_penelitian ON tb_usulan_penelitian.id_usulan = tb_reviewer_penelitian.id_usulan
        where tb_reviewer_penelitian.id = "' . $id . '" and tb_reviewer_penelitian.jenis_laporan = "usulan"');

        foreach ($data as $key => $value) {
            $id_usulan = $value->id_usulan;
        }

        $cekdata = DB::select('select * from tb_tanggapan_penelitian where jenis_laporan = "usulan" and id_usulan = "' . $id_usulan . '" and id_reviewer = "' . Auth::user()->nik . '"');
        return view('dashboard.tanggapan-penelitian', compact('data', 'cekdata', 'id_usulan'))
            ->with('title', 'Tanggapan Penelitian');
    }

    public function simpantanggapanusulanpenelitian(Request $request)
    {

        date_default_timezone_set('Asia/Jakarta');

        $getidjudul = DB::select("SELECT * FROM tb_usulan_penelitian where judul_penelitian = '" . $request->judul . "'");
        foreach ($getidjudul as $key => $value) {
            $id_usulan = $value->id_usulan;
        }

        $this->validate($request, [
            'file' => 'mimes:doc,docx|max:5120',
        ]);

        if (empty($request->file)) {
            $newName = 0;
        } else {
            $file   = $request->file('file');
            $ext    =  $file->getClientOriginalExtension();
            $newName = date('dmYHis') . "." . $ext;
            $file->move(public_path() . '/assets/file/tanggapan-usulan-penelitian/', $newName);
        }

        $tanggal = Date('Y-m-d');

        $penelitian = new TanggapanPenelitian();
        $penelitian->id_usulan            = $id_usulan;
        $penelitian->id_reviewer          = Auth::user()->nik;
        $penelitian->tanggapan            = $request->tanggapan;
        $penelitian->nilai_1              = $request->nilai_1;
        $penelitian->nilai_2              = $request->nilai_2;
        $penelitian->nilai_3              = $request->nilai_3;
        $penelitian->nilai_4              = $request->nilai_4;
        $penelitian->nilai_5              = $request->nilai_5;
        $penelitian->nilai_6              = 0;
        $penelitian->file                 = $newName;
        $penelitian->tanggal              = $tanggal;
        $penelitian->status               = 'confirm';
        $penelitian->jenis_laporan        = 'usulan';
        $penelitian->save();

        // $getdata = DB::select('select users.email, tb_usulan_penelitian.judul_penelitian 
        //     from tb_usulan_penelitian 
        //     JOIN users ON tb_usulan_penelitian.id_dosen = users.nik
        //     where tb_usulan_penelitian.id_usulan = "'.$id_usulan.'"');

        // foreach ($getdata as $key => $value) {
        //     $judul = $value->judul_penelitian;
        //     $email = $value->email;
        // }

        // $ucapan = 'Penilaian Usulan Penelitian anda dengan judul "'.$judul.'" sudah bisa di cek di sistem';

        // try{
        //     Mail::send('email', array('pesan' => $ucapan) , function($pesan) use($email){
        //         $pesan->to($email,'Catatan Penilaian Usulan Penelitian')->subject('Catatan Penilaian Usulan Penelitian');
        //         $pesan->from(env('MAIL_USERNAME','MAIL_FROM_ADDRESS'),'Catatan Penilaian Usulan Penelitian');
        //     });
        // }catch (Exception $e){
        //     return response (['status' => false,'errors' => $e->getMessage()]);
        // }

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function tanggapanusulanpengabmas($id)
    {

        $data = DB::select('select tb_usulan_pengabmas.id_usulan, tb_usulan_pengabmas.judul_pengabmas 
        from tb_usulan_pengabmas
        JOIN tb_reviewer_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_reviewer_pengabmas.id_usulan
        where tb_reviewer_pengabmas.jenis_laporan = "usulan" and tb_reviewer_pengabmas.id = "' . $id . '" ');

        foreach ($data as $key => $value) {
            $id_usulan = $value->id_usulan;
        }

        $cekdata = DB::select('select * from tb_tanggapan_pengabmas where jenis_laporan = "usulan" and id_usulan = "' . $id_usulan . '" and id_reviewer = "' . Auth::user()->nik . '"');
        return view('dashboard.tanggapan-pengabmas', compact('data', 'cekdata', 'id_usulan'))
            ->with('title', 'Tanggapan Pengabmas');
    }

    public function simpantanggapanusulanpengabmas(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $getidjudul = DB::select("SELECT * FROM tb_usulan_pengabmas where judul_pengabmas = '" . $request->judul . "'");

        foreach ($getidjudul as $key => $value) {
            $id_usulan = $value->id_usulan;
        }

        $this->validate($request, [
            'file' => 'mimes:doc,docx|max:5120',
        ]);

        if (empty($request->file)) {
            $newName = 0;
        } else {
            $file   = $request->file('file');
            $ext    =  $file->getClientOriginalExtension();
            $newName = date('dmYHis') . "." . $ext;
            $file->move(public_path() . '/assets/file/tanggapan-usulan-pengabmas/', $newName);
        }

        $tanggal = Date('Y-m-d');

        $pengabmas = new TanggapanPengabmas();
        $pengabmas->id_usulan            = $id_usulan;
        $pengabmas->id_reviewer          = Auth::user()->nik;
        $pengabmas->tanggapan            = $request->tanggapan;
        $pengabmas->nilai_1              = $request->nilai_1;
        $pengabmas->nilai_2              = $request->nilai_2;
        $pengabmas->nilai_3              = $request->nilai_3;
        $pengabmas->nilai_4              = $request->nilai_4;
        $pengabmas->nilai_5              = $request->nilai_5;
        $pengabmas->nilai_6              = $request->nilai_6;
        $pengabmas->file                 = $newName;
        $pengabmas->tanggal              = $tanggal;
        $pengabmas->status               = 'confirm';
        $pengabmas->jenis_laporan        = 'usulan';
        $pengabmas->save();

        // $getdata = DB::select('select users.email, tb_usulan_pengabmas.judul_pengabmas 
        //     from tb_usulan_pengabmas 
        //     JOIN users ON tb_usulan_pengabmas.id_dosen = users.nik
        //     where tb_usulan_pengabmas.id_usulan = "'.$id_usulan.'"');

        // foreach ($getdata as $key => $value) {
        //     $judul = $value->judul_pengabmas;
        //     $email = $value->email;
        // }

        // $ucapan = 'Penilaian Usulan Pengabdian Masyarakat anda dengan judul "'.$judul.'" sudah bisa di cek di sistem';

        // try{
        //     Mail::send('email', array('pesan' => $ucapan) , function($pesan) use($email){
        //         $pesan->to($email,'Catatan Penilaian Usulan Pengabdian Masyarakat')->subject('Catatan Penilaian Usulan Pengabdian Masyarakat');
        //         $pesan->from(env('MAIL_USERNAME','MAIL_FROM_ADDRESS'),'Catatan Penilaian Usulan Pengabdian Masyarakat');
        //     });
        // }catch (Exception $e){
        //     return response (['status' => false,'errors' => $e->getMessage()]);
        // }

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function lihatpenilaianusulanpengabmas($id)
    {
        $pengaturan = $this->pengaturan;

        $data = DB::select('select * from tb_tanggapan_pengabmas where jenis_laporan = "usulan" and id_usulan = "' . $id . '"');
        if (empty($data)) {
            Session::flash('gagal', 'Belum ada penilaian');
            return back();
        } else {
            $reviewer = DB::select('select users.name 
            from users
            JOIN tb_tanggapan_pengabmas ON users.nik = tb_tanggapan_pengabmas.id_reviewer
            where tb_tanggapan_pengabmas.jenis_laporan = "usulan" and  tb_tanggapan_pengabmas.id_usulan = "' . $id . '"
            GROUP By tb_tanggapan_pengabmas.id_reviewer');
            foreach ($data as $key => $value) {
                $skor_1 = $value->nilai_1;
                $skor_2 = $value->nilai_2;
                $skor_3 = $value->nilai_3;
                $skor_4 = $value->nilai_4;
                $skor_5 = $value->nilai_5;
                $skor_6 = $value->nilai_6;

                $nilai_1 = $value->nilai_1 * 20;
                $nilai_2 = $value->nilai_2 * 15;
                $nilai_3 = $value->nilai_3 * 20;
                $nilai_4 = $value->nilai_4 * 15;
                $nilai_5 = $value->nilai_5 * 10;
                $nilai_6 = $value->nilai_6 * 20;

                $total_nilai = $nilai_1 + $nilai_2 + $nilai_3 + $nilai_4 + $nilai_5 + $nilai_6;
            }
            $usulan = DB::select('select tb_usulan_pengabmas.*, users.*, u0.name as nama_ketua, u1.name as anggota_internal1, u2.name as anggota_internal2, u3.name as anggota_internal3, u4.name as anggota_internal4 from tb_usulan_pengabmas 
            LEFT JOIN users ON tb_usulan_pengabmas.id_dosen = users.nik
            LEFT JOIN users u0 ON tb_usulan_pengabmas.nama_ketua = u0.nik
            LEFT JOIN users u1 ON tb_usulan_pengabmas.anggota_internal1 = u1.nik
            LEFT JOIN users u2 ON tb_usulan_pengabmas.anggota_internal2 = u2.nik
            LEFT JOIN users u3 ON tb_usulan_pengabmas.anggota_internal3 = u3.nik
            LEFT JOIN users u4 ON tb_usulan_pengabmas.anggota_internal4 = u4.nik
            where tb_usulan_pengabmas.id_usulan = "' . $id . '"
            GROUP By tb_usulan_pengabmas.id_usulan');

            $pdf = PDF::loadView('dashboard.lihat-penilaian-usulan-pengabmas', compact('data', 'usulan', 'nilai_1', 'nilai_2', 'nilai_3', 'nilai_4', 'nilai_5', 'nilai_6', 'skor_1', 'skor_2', 'skor_3', 'skor_4', 'skor_5', 'skor_6', 'reviewer', 'total_nilai', 'pengaturan'));
            $pdf->setPaper('A4', 'potrait');
            return $pdf->stream('Penilaian Usulan Pengabdian Masyarakat.pdf');
        }
    }

    public function tanggapankemajuanpenelitian($id)
    {

        $data = DB::select('select tb_usulan_penelitian.id_usulan, tb_usulan_penelitian.judul_penelitian 
        from tb_usulan_penelitian
        JOIN tb_reviewer_penelitian ON tb_usulan_penelitian.id_usulan = tb_reviewer_penelitian.id_usulan
        where tb_reviewer_penelitian.id = "' . $id . '" and tb_reviewer_penelitian.jenis_laporan = "kemajuan"');

        foreach ($data as $key => $value) {
            $id_usulan = $value->id_usulan;
        }

        $cekdata = DB::select('select * from tb_tanggapan_penelitian where jenis_laporan = "kemajuan" and id_usulan = "' . $id_usulan . '" and id_reviewer = "' . Auth::user()->nik . '"');
        return view('dashboard.tanggapan-kemajuan-penelitian', compact('data', 'cekdata', 'id_usulan'))
            ->with('title', 'Tanggapan Kemajuan Penelitian');
    }

    public function simpantanggapankemajuanpenelitian(Request $request)
    {

        date_default_timezone_set('Asia/Jakarta');

        $getidjudul = DB::select("SELECT * FROM tb_usulan_penelitian where judul_penelitian = '" . $request->judul . "'");
        foreach ($getidjudul as $key => $value) {
            $id_usulan = $value->id_usulan;
        }

        $this->validate($request, [
            'file' => 'mimes:doc,docx|max:5120',
        ]);

        if (empty($request->file)) {
            $newName = 0;
        } else {
            $file   = $request->file('file');
            $ext    =  $file->getClientOriginalExtension();
            $newName = date('dmYHis') . "." . $ext;
            $file->move(public_path() . '/assets/file/tanggapan-usulan-penelitian/', $newName);
        }

        $tanggal = Date('Y-m-d');

        $penelitian = new TanggapanPenelitian();
        $penelitian->id_usulan            = $id_usulan;
        $penelitian->id_reviewer          = Auth::user()->nik;
        $penelitian->tanggapan            = $request->tanggapan;
        $penelitian->nilai_1              = $request->nilai_1;
        $penelitian->nilai_2              = $request->nilai_2;
        $penelitian->nilai_3              = $request->nilai_3;
        $penelitian->nilai_4              = $request->nilai_4;
        $penelitian->nilai_5              = $request->nilai_5;
        $penelitian->nilai_6              = $request->nilai_6;
        $penelitian->file                 = $newName;
        $penelitian->tanggal              = $tanggal;
        $penelitian->status               = 'confirm';
        $penelitian->jenis_laporan        = 'kemajuan';
        $penelitian->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function tanggapankemajuanpengabmas($id)
    {

        $data = DB::select('select tb_usulan_pengabmas.id_usulan, tb_usulan_pengabmas.judul_pengabmas 
        from tb_usulan_pengabmas
        JOIN tb_reviewer_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_reviewer_pengabmas.id_usulan
        where tb_reviewer_pengabmas.jenis_laporan = "kemajuan" and tb_reviewer_pengabmas.id = "' . $id . '" ');

        foreach ($data as $key => $value) {
            $id_usulan = $value->id_usulan;
        }

        $cekdata = DB::select('select * from tb_tanggapan_pengabmas where jenis_laporan = "kemajuan" and id_usulan = "' . $id_usulan . '" and id_reviewer = "' . Auth::user()->nik . '"');
        return view('dashboard.tanggapan-kemajuan-pengabmas', compact('data', 'cekdata', 'id_usulan'))
            ->with('title', 'Tanggapan Kemajuan Pengabmas');
    }

    public function simpantanggapankemajuanpengabmas(Request $request)
    {

        date_default_timezone_set('Asia/Jakarta');

        $getidjudul = DB::select("SELECT * FROM tb_usulan_pengabmas where judul_pengabmas = '" . $request->judul . "'");
        foreach ($getidjudul as $key => $value) {
            $id_usulan = $value->id_usulan;
        }

        $this->validate($request, [
            'file' => 'mimes:doc,docx|max:5120',
        ]);

        if (empty($request->file)) {
            $newName = 0;
        } else {
            $file   = $request->file('file');
            $ext    =  $file->getClientOriginalExtension();
            $newName = date('dmYHis') . "." . $ext;
            $file->move(public_path() . '/assets/file/tanggapan-usulan-pengabmas/', $newName);
        }

        $tanggal = Date('Y-m-d');

        $pengabmas = new TanggapanPengabmas();
        $pengabmas->id_usulan            = $id_usulan;
        $pengabmas->id_reviewer          = Auth::user()->nik;
        $pengabmas->tanggapan            = $request->tanggapan;
        $pengabmas->nilai_1              = $request->nilai_1;
        $pengabmas->nilai_2              = $request->nilai_2;
        $pengabmas->nilai_3              = $request->nilai_3;
        $pengabmas->nilai_4              = $request->nilai_4;
        $pengabmas->nilai_5              = $request->nilai_5;
        $pengabmas->nilai_6              = $request->nilai_6;
        $pengabmas->file                 = $newName;
        $pengabmas->tanggal              = $tanggal;
        $pengabmas->status               = 'confirm';
        $pengabmas->jenis_laporan        = 'kemajuan';
        $pengabmas->save();

        // $getdata = DB::select('select users.email, tb_usulan_pengabmas.judul_pengabmas 
        //     from tb_usulan_pengabmas 
        //     JOIN users ON tb_usulan_pengabmas.id_dosen = users.nik
        //     where tb_usulan_pengabmas.id_usulan = "'.$id_usulan.'"');

        // foreach ($getdata as $key => $value) {
        //     $judul = $value->judul_pengabmas;
        //     $email = $value->email;
        // }

        // $ucapan = 'Penilaian Usulan Pengabdian Masyarakat anda dengan judul "'.$judul.'" sudah bisa di cek di sistem';

        // try{
        //     Mail::send('email', array('pesan' => $ucapan) , function($pesan) use($email){
        //         $pesan->to($email,'Catatan Penilaian Usulan Pengabdian Masyarakat')->subject('Catatan Penilaian Usulan Pengabdian Masyarakat');
        //         $pesan->from(env('MAIL_USERNAME','MAIL_FROM_ADDRESS'),'Catatan Penilaian Usulan Pengabdian Masyarakat');
        //     });
        // }catch (Exception $e){
        //     return response (['status' => false,'errors' => $e->getMessage()]);
        // }

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function lihatpenilaiankemajuanpengabmas($id)
    {
        $pengaturan = $this->pengaturan;

        $data = DB::select('select * from tb_tanggapan_pengabmas where jenis_laporan = "kemajuan" and id_usulan = "' . $id . '"');
        if (empty($data)) {
            Session::flash('gagal', 'Belum ada penilaian');
            return back();
        } else {
            $reviewer = DB::select('select users.name 
            from users
            JOIN tb_tanggapan_pengabmas ON users.nik = tb_tanggapan_pengabmas.id_reviewer
            where tb_tanggapan_pengabmas.jenis_laporan = "kemajuan" and  tb_tanggapan_pengabmas.id_usulan = "' . $id . '"
            GROUP By tb_tanggapan_pengabmas.id_reviewer');
            foreach ($data as $key => $value) {
                $skor_1 = $value->nilai_1;
                $skor_2 = $value->nilai_2;
                $skor_3 = $value->nilai_3;
                $skor_4 = $value->nilai_4;
                $skor_5 = $value->nilai_5;
                $skor_6 = $value->nilai_6;

                $nilai_1 = $value->nilai_1 * 20;
                $nilai_2 = $value->nilai_2 * 15;
                $nilai_3 = $value->nilai_3 * 20;
                $nilai_4 = $value->nilai_4 * 15;
                $nilai_5 = $value->nilai_5 * 10;
                $nilai_6 = $value->nilai_6 * 20;

                $total_nilai = $nilai_1 + $nilai_2 + $nilai_3 + $nilai_4 + $nilai_5 + $nilai_6;
            }
            $usulan = DB::select('select tb_usulan_pengabmas.*, users.* 
            from tb_usulan_pengabmas 
            LEFT JOIN users ON tb_usulan_pengabmas.id_dosen = users.nik
            where tb_usulan_pengabmas.id_usulan = "' . $id . '"
            GROUP By tb_usulan_pengabmas.id_usulan');

            $pdf = PDF::loadView('dashboard.lihat-penilaian-usulan-pengabmas', compact('data', 'usulan', 'nilai_1', 'nilai_2', 'nilai_3', 'nilai_4', 'nilai_5', 'nilai_6', 'skor_1', 'skor_2', 'skor_3', 'skor_4', 'skor_5', 'skor_6', 'reviewer', 'total_nilai', 'pengaturan'));
            $pdf->setPaper('A4', 'potrait');
            return $pdf->stream('Penilaian Usulan Pengabdian Masyarakat.pdf');
        }
    }

    public function tanggapanakhirpenelitian($id)
    {

        $data = DB::select('select tb_usulan_penelitian.id_usulan, tb_usulan_penelitian.judul_penelitian 
        from tb_usulan_penelitian
        JOIN tb_reviewer_penelitian ON tb_usulan_penelitian.id_usulan = tb_reviewer_penelitian.id_usulan
        where tb_reviewer_penelitian.id = "' . $id . '" and tb_reviewer_penelitian.jenis_laporan = "akhir"');

        foreach ($data as $key => $value) {
            $id_usulan = $value->id_usulan;
        }

        $cekdata = DB::select('select * from tb_tanggapan_penelitian where jenis_laporan = "akhir" and id_usulan = "' . $id_usulan . '" and id_reviewer = "' . Auth::user()->nik . '"');
        return view('dashboard.tanggapan-akhir-penelitian', compact('data', 'cekdata', 'id_usulan'))
            ->with('title', 'Tanggapan Akhir Penelitian');
    }

    public function simpantanggapanakhirpenelitian(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $getidjudul = DB::select("SELECT * FROM tb_usulan_penelitian where judul_penelitian = '" . $request->judul . "'");
        foreach ($getidjudul as $key => $value) {
            $id_usulan = $value->id_usulan;
        }

        $this->validate($request, [
            'file' => 'mimes:doc,docx|max:5120',
        ]);

        if (empty($request->file)) {
            $newName = 0;
        } else {
            $file   = $request->file('file');
            $ext    =  $file->getClientOriginalExtension();
            $newName = date('dmYHis') . "." . $ext;
            $file->move(public_path() . '/assets/file/tanggapan-usulan-penelitian/', $newName);
        }

        $tanggal = Date('Y-m-d');

        $penelitian = new TanggapanPenelitian();
        $penelitian->id_usulan            = $id_usulan;
        $penelitian->id_reviewer          = Auth::user()->nik;
        $penelitian->tanggapan            = $request->tanggapan;
        $penelitian->nilai_1              = $request->nilai_1;
        $penelitian->nilai_2              = $request->nilai_2;
        $penelitian->nilai_3              = $request->nilai_3;
        $penelitian->nilai_4              = $request->nilai_4;
        $penelitian->nilai_5              = $request->nilai_5;
        $penelitian->nilai_6              = $request->nilai_6;
        $penelitian->file                 = $newName;
        $penelitian->tanggal              = $tanggal;
        $penelitian->status               = 'confirm';
        $penelitian->jenis_laporan        = 'akhir';
        $penelitian->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function tanggapanakhirpengabmas($id)
    {

        $data = DB::select('select tb_usulan_pengabmas.id_usulan, tb_usulan_pengabmas.judul_pengabmas 
        from tb_usulan_pengabmas
        JOIN tb_reviewer_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_reviewer_pengabmas.id_usulan
        where tb_reviewer_pengabmas.jenis_laporan = "akhir" and tb_reviewer_pengabmas.id = "' . $id . '" ');

        foreach ($data as $key => $value) {
            $id_usulan = $value->id_usulan;
        }

        $cekdata = DB::select('select * from tb_tanggapan_pengabmas where jenis_laporan = "akhir" and id_usulan = "' . $id_usulan . '" and id_reviewer = "' . Auth::user()->nik . '"');
        return view('dashboard.tanggapan-akhir-pengabmas', compact('data', 'cekdata', 'id_usulan'))
            ->with('title', 'Tanggapan Akhir Pengabmas');
    }

    public function simpantanggapanakhirpengabmas(Request $request)
    {

        date_default_timezone_set('Asia/Jakarta');

        $getidjudul = DB::select("SELECT * FROM tb_usulan_pengabmas where judul_pengabmas = '" . $request->judul . "'");
        foreach ($getidjudul as $key => $value) {
            $id_usulan = $value->id_usulan;
        }

        $this->validate($request, [
            'file' => 'mimes:doc,docx|max:5120',
        ]);

        if (empty($request->file)) {
            $newName = 0;
        } else {
            $file   = $request->file('file');
            $ext    =  $file->getClientOriginalExtension();
            $newName = date('dmYHis') . "." . $ext;
            $file->move(public_path() . '/assets/file/tanggapan-usulan-pengabmas/', $newName);
        }

        $tanggal = Date('Y-m-d');

        $pengabmas = new TanggapanPengabmas();
        $pengabmas->id_usulan            = $id_usulan;
        $pengabmas->id_reviewer          = Auth::user()->nik;
        $pengabmas->tanggapan            = $request->tanggapan;
        $pengabmas->nilai_1              = $request->nilai_1;
        $pengabmas->nilai_2              = $request->nilai_2;
        $pengabmas->nilai_3              = $request->nilai_3;
        $pengabmas->nilai_4              = $request->nilai_4;
        $pengabmas->nilai_5              = $request->nilai_5;
        $pengabmas->nilai_6              = $request->nilai_6;
        $pengabmas->file                 = $newName;
        $pengabmas->tanggal              = $tanggal;
        $pengabmas->status               = 'confirm';
        $pengabmas->jenis_laporan        = 'akhir';
        $pengabmas->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function lihatpenilaianakhirpengabmas($id)
    {
        $pengaturan = $this->pengaturan;

        $data = DB::select('select * from tb_tanggapan_pengabmas where jenis_laporan = "akhir" and id_usulan = "' . $id . '"');
        if (empty($data)) {
            Session::flash('gagal', 'Belum ada penilaian');
            return back();
        } else {
            $reviewer = DB::select('select users.name 
            from users
            JOIN tb_tanggapan_pengabmas ON users.nik = tb_tanggapan_pengabmas.id_reviewer
            where tb_tanggapan_pengabmas.jenis_laporan = "akhir" and  tb_tanggapan_pengabmas.id_usulan = "' . $id . '"
            GROUP By tb_tanggapan_pengabmas.id_reviewer');
            foreach ($data as $key => $value) {
                $skor_1 = $value->nilai_1;
                $skor_2 = $value->nilai_2;
                $skor_3 = $value->nilai_3;
                $skor_4 = $value->nilai_4;
                $skor_5 = $value->nilai_5;
                $skor_6 = $value->nilai_6;

                $nilai_1 = $value->nilai_1 * 20;
                $nilai_2 = $value->nilai_2 * 15;
                $nilai_3 = $value->nilai_3 * 20;
                $nilai_4 = $value->nilai_4 * 15;
                $nilai_5 = $value->nilai_5 * 10;
                $nilai_6 = $value->nilai_6 * 20;

                $total_nilai = $nilai_1 + $nilai_2 + $nilai_3 + $nilai_4 + $nilai_5 + $nilai_6;
            }
            $usulan = DB::select('select tb_usulan_pengabmas.*, users.* 
            from tb_usulan_pengabmas 
            LEFT JOIN users ON tb_usulan_pengabmas.id_dosen = users.nik
            where tb_usulan_pengabmas.id_usulan = "' . $id . '"
            GROUP By tb_usulan_pengabmas.id_usulan');

            $pdf = PDF::loadView('dashboard.lihat-penilaian-akhir-pengabmas', compact('data', 'usulan', 'nilai_1', 'nilai_2', 'nilai_3', 'nilai_4', 'nilai_5', 'nilai_6', 'skor_1', 'skor_2', 'skor_3', 'skor_4', 'skor_5', 'skor_6', 'reviewer', 'total_nilai', 'pengaturan'));
            $pdf->setPaper('A4', 'potrait');
            return $pdf->stream('Penilaian Usulan Pengabdian Masyarakat.pdf');
        }
    }

    public function hasilreview()
    {

        $penelitian = DB::select('select tb_tanggapan_penelitian.id, tb_tanggapan_penelitian.id_usulan, tb_usulan_penelitian.judul_penelitian, users.name, tb_tanggapan_penelitian.tanggapan, tb_tanggapan_penelitian.file, tb_reviewer_penelitian.reviewer1, tb_reviewer_penelitian.reviewer2, tb_tanggapan_penelitian.jenis_laporan, tb_tanggapan_penelitian.tanggal, SUM((tb_tanggapan_penelitian.nilai_1*30) + (tb_tanggapan_penelitian.nilai_2*30) + (tb_tanggapan_penelitian.nilai_3*25) + (tb_tanggapan_penelitian.nilai_4*5) + (tb_tanggapan_penelitian.nilai_5*5) + (tb_tanggapan_penelitian.nilai_6*5)) as nilai
        From tb_tanggapan_penelitian
        LEFT JOIN tb_usulan_penelitian ON tb_tanggapan_penelitian.id_usulan = tb_usulan_penelitian.id_usulan
        LEFT JOIN users ON tb_usulan_penelitian.id_dosen = users.nik
        LEFT JOIN 
        (SELECT reviewer1, reviewer2, id_usulan from tb_reviewer_penelitian group by id_usulan) as tb_reviewer_penelitian ON tb_usulan_penelitian.id_usulan = tb_reviewer_penelitian.id_usulan
        Group by tb_tanggapan_penelitian.id
        ORDER by tb_tanggapan_penelitian.tanggal DESC
        ');
        // dd($penelitian);

        $pengabmas = DB::select('select tb_tanggapan_pengabmas.id, tb_tanggapan_pengabmas.id_usulan, tb_usulan_pengabmas.judul_pengabmas, users.name, tb_tanggapan_pengabmas.tanggapan, tb_tanggapan_pengabmas.file, tb_reviewer_pengabmas.reviewer1, tb_reviewer_pengabmas.reviewer2, tb_tanggapan_pengabmas.jenis_laporan, tb_tanggapan_pengabmas.tanggal, SUM((tb_tanggapan_pengabmas.nilai_1*30) + (tb_tanggapan_pengabmas.nilai_2*30) + (tb_tanggapan_pengabmas.nilai_3*25) + (tb_tanggapan_pengabmas.nilai_4*5) + (tb_tanggapan_pengabmas.nilai_5*5) + (tb_tanggapan_pengabmas.nilai_6*5)) as nilai
        From tb_tanggapan_pengabmas
        LEFT JOIN tb_usulan_pengabmas ON tb_tanggapan_pengabmas.id_usulan = tb_usulan_pengabmas.id_usulan
        LEFT JOIN users ON tb_usulan_pengabmas.id_dosen = users.nik
        LEFT JOIN 
        (SELECT reviewer1, reviewer2, id_usulan from tb_reviewer_pengabmas group by id_usulan) as tb_reviewer_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_reviewer_pengabmas.id_usulan
        Group by tb_tanggapan_pengabmas.id
        ORDER by tb_tanggapan_pengabmas.tanggal DESC
        ');

        $penelitianpersonal = DB::select('select tb_tanggapan_penelitian.id, tb_tanggapan_penelitian.id_usulan, tb_usulan_penelitian.judul_penelitian, users.name, tb_tanggapan_penelitian.tanggapan, tb_tanggapan_penelitian.file, tb_reviewer_penelitian.reviewer1, tb_reviewer_penelitian.reviewer2, tb_tanggapan_penelitian.jenis_laporan, tb_tanggapan_penelitian.tanggal, SUM((tb_tanggapan_penelitian.nilai_1*30) + (tb_tanggapan_penelitian.nilai_2*30) + (tb_tanggapan_penelitian.nilai_3*25) + (tb_tanggapan_penelitian.nilai_4*5) + (tb_tanggapan_penelitian.nilai_5*5) + (tb_tanggapan_penelitian.nilai_6*5)) as nilai
        From tb_tanggapan_penelitian
        LEFT JOIN tb_usulan_penelitian ON tb_tanggapan_penelitian.id_usulan = tb_usulan_penelitian.id_usulan
        LEFT JOIN users ON tb_usulan_penelitian.id_dosen = users.nik
        LEFT JOIN 
        (SELECT reviewer1, reviewer2, id_usulan from tb_reviewer_penelitian group by id_usulan) as tb_reviewer_penelitian ON tb_usulan_penelitian.id_usulan = tb_reviewer_penelitian.id_usulan
        WHERE users.name = "' . Auth::user()->name . '"
        Group by tb_tanggapan_penelitian.id
        ORDER by tb_tanggapan_penelitian.tanggal DESC
        ');
        //dd($penelitianpersonal);

        $pengabmaspersonal = DB::select('select tb_tanggapan_pengabmas.id, tb_tanggapan_pengabmas.id_usulan, tb_usulan_pengabmas.judul_pengabmas, users.name, tb_tanggapan_pengabmas.tanggapan, tb_tanggapan_pengabmas.file, tb_reviewer_pengabmas.reviewer1, tb_reviewer_pengabmas.reviewer2, tb_tanggapan_pengabmas.jenis_laporan, tb_tanggapan_pengabmas.tanggal, SUM((tb_tanggapan_pengabmas.nilai_1*30) + (tb_tanggapan_pengabmas.nilai_2*30) + (tb_tanggapan_pengabmas.nilai_3*25) + (tb_tanggapan_pengabmas.nilai_4*5) + (tb_tanggapan_pengabmas.nilai_5*5) + (tb_tanggapan_pengabmas.nilai_6*5)) as nilai
        From tb_tanggapan_pengabmas
        LEFT JOIN tb_usulan_pengabmas ON tb_tanggapan_pengabmas.id_usulan = tb_usulan_pengabmas.id_usulan
        LEFT JOIN users ON tb_usulan_pengabmas.id_dosen = users.nik
        LEFT JOIN 
        (SELECT reviewer1, reviewer2, id_usulan from tb_reviewer_pengabmas group by id_usulan) as tb_reviewer_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_reviewer_pengabmas.id_usulan
        WHERE users.name = "' . Auth::user()->name . '"
        Group by tb_tanggapan_pengabmas.id
        ORDER by tb_tanggapan_pengabmas.tanggal DESC
        ');
        //dd($pengabmaspersonal);

        return view('dashboard.hasil-review', compact('penelitian', 'pengabmas', 'penelitianpersonal', 'pengabmaspersonal'))
            ->with('title', 'Hasil Review');
    }

    public function lookbookpenelitian()
    {
        $usulan = DB::select('select * from tb_usulan_penelitian where id_dosen = "' . Auth::user()->nik . '" and status = "di terima" order by tanggal DESC');
        $data = DB::select('select tb_usulan_penelitian.judul_penelitian, tb_lookbook_penelitian.* 
        from tb_lookbook_penelitian
        JOIN tb_usulan_penelitian ON tb_lookbook_penelitian.id_usulan = tb_usulan_penelitian.id_usulan
        WHERE tb_lookbook_penelitian.user = "' . Auth::user()->name . '"
        GROUP By tb_lookbook_penelitian.id 
        Order By tb_usulan_penelitian.tanggal DESC');
        $data_lengkap = DB::select('select tb_usulan_penelitian.judul_penelitian, tb_lookbook_penelitian.* 
        from tb_lookbook_penelitian
        JOIN tb_usulan_penelitian ON tb_lookbook_penelitian.id_usulan = tb_usulan_penelitian.id_usulan
        GROUP By tb_lookbook_penelitian.id 
        Order By tb_usulan_penelitian.tanggal DESC');
        return view('dashboard.lookbook-penelitian', compact('usulan', 'data', 'data_lengkap'))
            ->with('title', 'Logbook Penelitian');
    }

    public function simpanlookbookpenelitian(Request $request)
    {

        date_default_timezone_set('Asia/Jakarta');

        $lookbook = new LookBookPenelitian();
        $lookbook->id_usulan            = $request->id_usulan;
        $lookbook->judul_kegiatan       = $request->judul_kegiatan;
        $lookbook->jenis_kegiatan       = $request->jenis_kegiatan;
        $lookbook->catatan              = $request->catatan;
        $lookbook->user                 = Auth::user()->name;
        $lookbook->tanggal              = $request->tanggal;
        $lookbook->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function deletelookbookpenelitian($id)
    {
        $data = LookBookPenelitian::find($id);
        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function buatsurattugaspenelitian($id)
    {
        $data = DB::select('select tb_usulan_penelitian.*, tb_lookbook_penelitian.id as id_lookbook, u0.name as nama_ketua, u1.name as anggota_internal1, u2.name as anggota_internal2, u3.name as anggota_internal3, u4.name as anggota_internal4
        from tb_usulan_penelitian 
        JOIN tb_lookbook_penelitian ON tb_usulan_penelitian.id_usulan = tb_lookbook_penelitian.id_usulan
        LEFT JOIN users u0 ON tb_usulan_penelitian.nama_ketua = u0.nik
        LEFT JOIN users u1 ON tb_usulan_penelitian.anggota_internal1 = u1.nik
        LEFT JOIN users u2 ON tb_usulan_penelitian.anggota_internal2 = u2.nik
        LEFT JOIN users u3 ON tb_usulan_penelitian.anggota_internal3 = u3.nik
        LEFT JOIN users u4 ON tb_usulan_penelitian.anggota_internal4 = u4.nik
        WHERE tb_lookbook_penelitian.id = "' . $id . '"');
        $surattugas = DB::select('select no_surat, id from tb_surattugas_penelitian where id_lookbook = "' . $id . '"');
        return view('dashboard.surat-tugas-penelitian', compact('data', 'surattugas'))
            ->with('title', 'Surat Tugas Penelitian');
    }

    public function surattugaspenelitian($id)
    {
        $surattugas = DB::select('select no_surat, id from tb_surattugas_penelitian where id_lookbook = "' . $id . '"');
        return view('dashboard.rekap-surat-tugas-penelitian', compact('data', 'surattugas'))
            ->with('title', 'Rekap Surat Tugas Penelitian');
    }

    public function surattugaspengabmas($id)
    {
        $surattugas = DB::select('select no_surat, id from tb_surattugas_pengabmas where id_lookbook = "' . $id . '"');
        return view('dashboard.rekap-surat-tugas-pengabmas', compact('data', 'surattugas'))
            ->with('title', 'Rekap Surat Tugas Pengabmas');
    }

    public function simpansurattugaspenelitian(Request $request)
    {

        date_default_timezone_set('Asia/Jakarta');
        $tanggal = Date('Y-m-d');

        $cek = DB::select("SELECT * FROM tb_surattugas_penelitian where YEAR(created_at)=YEAR(CURDATE())");
        if (empty($cek)) {
            $time = Date('mY');
            $nomor = 'APP/ST/LPPM/1/' . $time . '';
        } else {
            $nomorakhir = DB::select("SELECT count(id) as jumlah FROM tb_surattugas_penelitian where YEAR(created_at)=YEAR(CURDATE())");
            foreach ($nomorakhir as $key => $value) {
                $nilai = $value->jumlah + 1;
            }
            $time = Date('mY');
            $nomor = 'APP/ST/LPPM/' . $nilai . '/' . $time . '';
        }


        $surattugas = new SuratTugasPenelitian();
        $surattugas->id_lookbook          = $request->id_lookbook;
        $surattugas->no_surat          = $nomor;
        $surattugas->nama_ketua           = $request->nama_ketua;
        $surattugas->anggota_internal1    = $_POST['anggota_internal1'];
        $surattugas->anggota_internal2    = $_POST['anggota_internal2'];
        $surattugas->anggota_internal3    = $_POST['anggota_internal3'];
        $surattugas->anggota_internal4    = $_POST['anggota_internal4'];
        $surattugas->anggota_eksternal1   = $_POST['anggota_eksternal1'];
        $surattugas->anggota_eksternal2   = $_POST['anggota_eksternal2'];
        $surattugas->anggota_eksternal3   = $_POST['anggota_eksternal3'];
        $surattugas->anggota_eksternal4   = $_POST['anggota_eksternal4'];
        $surattugas->mahasiswa            = $_POST['mahasiswa'];
        $surattugas->alumni               = $_POST['alumni'];
        $surattugas->admin                = $_POST['admin'];
        $surattugas->afiliasi_internal1    = $_POST['afiliasi_internal1'];
        $surattugas->afiliasi_internal2    = $_POST['afiliasi_internal2'];
        $surattugas->afiliasi_internal3    = $_POST['afiliasi_internal3'];
        $surattugas->afiliasi_internal4    = $_POST['afiliasi_internal4'];
        $surattugas->afiliasi_eksternal1   = $_POST['afiliasi_eksternal1'];
        $surattugas->afiliasi_eksternal2   = $_POST['afiliasi_eksternal2'];
        $surattugas->afiliasi_eksternal3   = $_POST['afiliasi_eksternal3'];
        $surattugas->afiliasi_eksternal4   = $_POST['afiliasi_eksternal4'];
        $surattugas->afiliasi_mahasiswa            = $_POST['afiliasi_mahasiswa'];
        $surattugas->afiliasi_alumni               = $_POST['afiliasi_alumni'];
        $surattugas->afiliasi_admin                = $_POST['afiliasi_admin'];
        $surattugas->judul_penelitian     = $request->judul_penelitian;
        $surattugas->tanggal              = $tanggal;
        $surattugas->afiliasi             = $_POST['afiliasi'];
        $surattugas->tempat               = $request->tempat;
        $surattugas->lama_penelitian      = $request->lama_penelitian;
        $surattugas->transport            = $_POST['transport'];
        $surattugas->keperluan            = $_POST['keperluan'];
        $surattugas->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function downloadsurattugaspenelitian($id)
    {
        $pengaturan = $this->pengaturan;

        $data = DB::select('select * from tb_surattugas_penelitian where id = "' . $id . '"');

        $pdf = PDF::loadView('dashboard.download-surat-tugas-penelitian', compact('data', 'pengaturan'));
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream('Surat Tugas Penelitian.pdf');
    }

    public function deletesurattugaspenelitian($id)
    {
        $data = SuratTugasPenelitian::find($id);
        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function lookbookpengabmas()
    {
        $usulan = DB::select('select * from tb_usulan_pengabmas where id_dosen = "' . Auth::user()->nik . '" and status = "di terima" order by tanggal DESC');
        $data = DB::select('select tb_usulan_pengabmas.judul_pengabmas, tb_lookbook_pengabmas.* 
        from tb_lookbook_pengabmas
        JOIN tb_usulan_pengabmas ON tb_lookbook_pengabmas.id_usulan = tb_usulan_pengabmas.id_usulan
        WHERE tb_lookbook_pengabmas.user = "' . Auth::user()->name . '"
        GROUP By tb_lookbook_pengabmas.id 
        Order By tb_usulan_pengabmas.tanggal DESC');
        $data_lengkap = DB::select('select tb_usulan_pengabmas.judul_pengabmas, tb_lookbook_pengabmas.* 
        from tb_lookbook_pengabmas
        JOIN tb_usulan_pengabmas ON tb_lookbook_pengabmas.id_usulan = tb_usulan_pengabmas.id_usulan
        GROUP By tb_lookbook_pengabmas.id 
        Order By tb_usulan_pengabmas.tanggal DESC');
        return view('dashboard.lookbook-pengabmas', compact('usulan', 'data', 'data_lengkap'))
            ->with('title', 'Logbook Pengabmas');
    }

    public function simpanlookbookpengabmas(Request $request)
    {

        date_default_timezone_set('Asia/Jakarta');

        $lookbook = new LookBookPengabmas();
        $lookbook->id_usulan            = $request->id_usulan;
        $lookbook->judul_kegiatan       = $request->judul_kegiatan;
        $lookbook->jenis_kegiatan       = $request->jenis_kegiatan;
        $lookbook->catatan              = $request->catatan;
        $lookbook->user                 = Auth::user()->name;
        $lookbook->tanggal              = $request->tanggal;
        $lookbook->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function deletelookbookpengabmas($id)
    {
        $data = LookBookPengabmas::find($id);
        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function buatsurattugaspengabmas($id)
    {
        $data = DB::select('select tb_usulan_pengabmas.*, tb_lookbook_pengabmas.id as id_lookbook, u0.name as nama_ketua, u1.name as anggota_internal1, u2.name as anggota_internal2, u3.name as anggota_internal3, u4.name as anggota_internal4 
        from tb_usulan_pengabmas 
        JOIN tb_lookbook_pengabmas ON tb_usulan_pengabmas.id_usulan = tb_lookbook_pengabmas.id_usulan
        LEFT JOIN users u0 ON tb_usulan_pengabmas.nama_ketua = u0.nik
        LEFT JOIN users u1 ON tb_usulan_pengabmas.anggota_internal1 = u1.nik
        LEFT JOIN users u2 ON tb_usulan_pengabmas.anggota_internal2 = u2.nik
        LEFT JOIN users u3 ON tb_usulan_pengabmas.anggota_internal3 = u3.nik
        LEFT JOIN users u4 ON tb_usulan_pengabmas.anggota_internal4 = u4.nik
        WHERE tb_lookbook_pengabmas.id = "' . $id . '"');
        $surattugas = DB::select('select no_surat, id from tb_surattugas_pengabmas where id_lookbook = "' . $id . '"');
        return view('dashboard.surat-tugas-pengabmas', compact('data', 'surattugas'))
            ->with('title', 'Surat Tugas Pengabmas');
    }

    public function simpansurattugaspengabmas(Request $request)
    {

        date_default_timezone_set('Asia/Jakarta');
        $tanggal = Date('Y-m-d');

        $cek = DB::select("SELECT * FROM tb_surattugas_pengabmas where YEAR(created_at)=YEAR(CURDATE())");
        if (empty($cek)) {
            $time = Date('mY');
            $nomor = 'APP/ST/LPPM/1/' . $time . '';
        } else {
            $nomorakhir = DB::select("SELECT count(id) as jumlah FROM tb_surattugas_pengabmas where YEAR(created_at)=YEAR(CURDATE())");
            foreach ($nomorakhir as $key => $value) {
                $nilai = $value->jumlah + 1;
            }
            $time = Date('mY');
            $nomor = 'APP/ST/LPPM/' . $nilai . '/' . $time . '';
        }


        $surattugas = new SuratTugasPengabmas();
        $surattugas->id_lookbook          = $request->id_lookbook;
        $surattugas->no_surat          = $nomor;
        $surattugas->nama_ketua           = $request->nama_ketua;
        $surattugas->anggota_internal1    = $_POST['anggota_internal1'];
        $surattugas->anggota_internal2    = $_POST['anggota_internal2'];
        $surattugas->anggota_internal3    = $_POST['anggota_internal3'];
        $surattugas->anggota_internal4    = $_POST['anggota_internal4'];
        $surattugas->anggota_eksternal1   = $_POST['anggota_eksternal1'];
        $surattugas->anggota_eksternal2   = $_POST['anggota_eksternal2'];
        $surattugas->anggota_eksternal3   = $_POST['anggota_eksternal3'];
        $surattugas->anggota_eksternal4   = $_POST['anggota_eksternal4'];
        $surattugas->mahasiswa            = $_POST['mahasiswa'];
        $surattugas->alumni               = $_POST['alumni'];
        $surattugas->admin                = $_POST['admin'];
        $surattugas->judul_pengabmas     = $request->judul_pengabmas;
        $surattugas->tanggal              = $tanggal;
        $surattugas->tempat               = $request->tempat;
        $surattugas->afiliasi             = $_POST['afiliasi'];
        $surattugas->lama_pengabmas      = $request->lama_pengabmas;
        $surattugas->transport            = $_POST['transport'];
        $surattugas->keperluan            = $_POST['keperluan'];
        $surattugas->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function downloadsurattugaspengabmas($id)
    {
        $pengaturan = $this->pengaturan;

        $data = DB::select('select * from tb_surattugas_pengabmas where id = "' . $id . '"');

        $pdf = PDF::loadView('dashboard.download-surat-tugas-pengabmas', compact('data', 'pengaturan'));
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream('Surat Tugas pengabmas.pdf');
    }

    public function deletesurattugaspengabmas($id)
    {
        $data = SuratTugasPengabmas::find($id);
        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function updatedanahibahptpenelitian(Request $request, $id)
    {
        $biaya_hibah_pt1 = str_replace("Rp. ", "", $request->biaya_hibah_pt);
        $biaya_hibah_pt  = str_replace(".", "", $biaya_hibah_pt1);

        $data = UsulanPenelitian::find($id);
        $data->biaya_hibah_pt = $biaya_hibah_pt;
        $data->save();

        Session::flash('sukses', 'Data berhasil di update');
        return back();
    }

    public function updatedanahibahptpengabmas(Request $request, $id)
    {
        $biaya_hibah_pt1 = str_replace("Rp. ", "", $request->biaya_hibah_pt);
        $biaya_hibah_pt  = str_replace(".", "", $biaya_hibah_pt1);

        $data = UsulanPengabmas::find($id);
        $data->biaya_hibah_pt = $biaya_hibah_pt;
        $data->save();

        Session::flash('sukses', 'Data berhasil di update');
        return back();
    }

    public function changeaccess()
    {
        $cek = DB::select('select level, pin from users where id = "' . Auth::user()->id . '"');
        foreach ($cek as $key => $value) {
            $level = $value->level;
            $pin = $value->pin;
        }
        if ($pin == 1) {
            if ($level == 1) {
                DB::update('update users SET level = 4 where id = "' . Auth::user()->id . '"');
                Session::flash('sukses', 'Hak akses beralih ke Dosen');
            } elseif ($level == 4) {
                DB::update('update users SET level = 1 where id = "' . Auth::user()->id . '"');
                Session::flash('sukses', 'Hak akses beralih ke Admin');
            }
        } elseif ($pin == 2) {
            if ($level == 2) {
                DB::update('update users SET level = 4 where id = "' . Auth::user()->id . '"');
                Session::flash('sukses', 'Hak akses beralih ke Dosen');
            } elseif ($level == 4) {
                DB::update('update users SET level = 2 where id = "' . Auth::user()->id . '"');
                Session::flash('sukses', 'Hak akses beralih ke Pimpinan');
            }
        } elseif ($pin == 3) {
            if ($level == 3) {
                DB::update('update users SET level = 4 where id = "' . Auth::user()->id . '"');
                Session::flash('sukses', 'Hak akses beralih ke Dosen');
            } elseif ($level == 4) {
                DB::update('update users SET level = 3 where id = "' . Auth::user()->id . '"');
                Session::flash('sukses', 'Hak akses beralih ke Operator');
            }
        } elseif ($pin == '') {
            if ($level == 4) {
                DB::update('update users SET level = 5 where id = "' . Auth::user()->id . '"');
                Session::flash('sukses', 'Hak akses beralih ke Reviewer');
            } elseif ($level == 5) {
                DB::update('update users SET level = 4 where id = "' . Auth::user()->id . '"');
                Session::flash('sukses', 'Hak akses beralih ke Dosen');
            }
        }

        return redirect('/dashboard');
    }

    public function changeaccess2()
    {
        $cek = DB::select('select level, pin from users where id = "' . Auth::user()->id . '"');
        foreach ($cek as $key => $value) {
            $level = $value->level;
            $pin = $value->pin;
        }
        if ($pin == 3) {
            if ($level == 4) {
                DB::update('update users SET level = 5 where id = "' . Auth::user()->id . '"');
                Session::flash('sukses', 'Hak akses beralih ke Reviewer');
            } elseif ($level == 3) {
                DB::update('update users SET level = 5 where id = "' . Auth::user()->id . '"');
                Session::flash('sukses', 'Hak akses beralih ke Reviewer');
            } elseif ($level == 5) {
                DB::update('update users SET level = 4 where id = "' . Auth::user()->id . '"');
                Session::flash('sukses', 'Hak akses beralih ke Dosen');
            }
        } elseif ($pin == 1) {
            if ($level == 1) {
                DB::update('update users SET level = 5 where id = "' . Auth::user()->id . '"');
                Session::flash('sukses', 'Hak akses beralih ke Reviewer');
            } elseif ($level == 4) {
                DB::update('update users SET level = 5 where id = "' . Auth::user()->id . '"');
                Session::flash('sukses', 'Hak akses beralih ke Reviewer');
            } elseif ($level == 5) {
                DB::update('update users SET level = 4 where id = "' . Auth::user()->id . '"');
                Session::flash('sukses', 'Hak akses beralih ke Dosen');
            }
        }

        return redirect('/dashboard');
    }

    public function daftarsurattugaspenelitian()
    {
        $surattugas = DB::select('select no_surat, id, nama_ketua, judul_penelitian from tb_surattugas_penelitian order by created_at DESC');
        return view('dashboard.daftar-surat-tugas-penelitian', compact('surattugas'))
            ->with('title', 'Daftar Surat Tugas Penelitian');
    }

    public function downloadallsurattugaspenelitian()
    {
        $pengaturan = $this->pengaturan;

        $data = DB::select('select * from tb_surattugas_penelitian order by created_at DESC');

        $pdf = PDF::loadView('dashboard.download-all-surat-tugas-penelitian', compact('data', 'pengaturan'));
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream('Surat Tugas Penelitian.pdf');
    }

    public function daftarsurattugaspengabmas()
    {
        $surattugas = DB::select('select no_surat, id, nama_ketua, judul_pengabmas from tb_surattugas_pengabmas order by created_at DESC');
        return view('dashboard.daftar-surat-tugas-pengabmas', compact('surattugas'))
            ->with('title', 'Daftar Surat Tugas Pengabmas');
    }

    public function downloadallsurattugaspengabmas()
    {
        $pengaturan = $this->pengaturan;

        $data = DB::select('select * from tb_surattugas_pengabmas order by created_at DESC');

        $pdf = PDF::loadView('dashboard.download-all-surat-tugas-pengabmas', compact('data', 'pengaturan'));
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream('Surat Tugas pengabmas.pdf');
    }

    public function pengaturan()
    {
        $data = DB::select('select * from tb_pengaturan where id="1"');
        return view('dashboard.pengaturan', compact('data'))
            ->with('title', 'Pengaturan');
    }

    public function updatepengaturan(Request $request, $id)
    {
        $this->validate($request, [
            'nama_instansi' => 'required',
            'logo' => 'mimes:png|max:2048',
            'lembaga' => 'required'
        ]);

        if ($logo = $request->file('logo')) {
            $destinationPath = public_path() . '/assets/image/logo/';
            $inputlogo = 'logo' . '.' . $logo->getClientOriginalExtension();
            $logo->move($destinationPath, $inputlogo);

            $data = Pengaturan::find($id);
            $data->logo = 'assets/image/logo/' . $inputlogo;
            $data->nama_instansi = $request->nama_instansi;
            $data->lembaga = $request->lembaga;
            $data->nama_lembaga = $request->nama_lembaga;
            $data->ketua_lembaga = $request->ketua_lembaga;
            $data->ketua_nik = $request->ketua_nik;
            $data->alamat = $request->alamat;
            $data->kota = $request->kota;
            $data->telepon = $request->telepon;
            $data->email = $request->email;
            $data->website = $request->website;
        } else {
            $data = Pengaturan::find($id);
            $data->nama_instansi = $request->nama_instansi;
            $data->lembaga = $request->lembaga;
            $data->nama_lembaga = $request->nama_lembaga;
            $data->ketua_lembaga = $request->ketua_lembaga;
            $data->ketua_nik = $request->ketua_nik;
            $data->alamat = $request->alamat;
            $data->kota = $request->kota;
            $data->telepon = $request->telepon;
            $data->email = $request->email;
            $data->website = $request->website;
        }
        $data->save();
        Session::flash('sukses', 'Data berhasil di ubah');
        return back();
    }

    public function inbox()
    {
        $usulanpenelitian = DB::select('select tb_usulan_penelitian.*, users.name as dosen_pengusul, u0.name as nama_ketua, u1.name as anggota_internal1, u2.name as anggota_internal2, u3.name as anggota_internal3, u4.name as anggota_internal4 from tb_usulan_penelitian 
        LEFT JOIN users ON tb_usulan_penelitian.id_dosen = users.nik
        LEFT JOIN users u0 ON tb_usulan_penelitian.nama_ketua = u0.nik
        LEFT JOIN users u1 ON tb_usulan_penelitian.anggota_internal1 = u1.nik
        LEFT JOIN users u2 ON tb_usulan_penelitian.anggota_internal2 = u2.nik
        LEFT JOIN users u3 ON tb_usulan_penelitian.anggota_internal3 = u3.nik
        LEFT JOIN users u4 ON tb_usulan_penelitian.anggota_internal4 = u4.nik
        where nama_ketua = "' . Auth::user()->nik . '" or anggota_internal1 = "' . Auth::user()->nik . '" or anggota_internal2 = "' . Auth::user()->nik . '" or anggota_internal3 = "' . Auth::user()->nik . '" or anggota_internal4 = "' . Auth::user()->nik . '" order by tanggal DESC');

        $usulanpengabmas = DB::select('select tb_usulan_pengabmas.*, users.name as dosen_pengusul, u0.name as nama_ketua, u1.name as anggota_internal1, u2.name as anggota_internal2, u3.name as anggota_internal3, u4.name as anggota_internal4 from tb_usulan_pengabmas 
        LEFT JOIN users ON tb_usulan_pengabmas.id_dosen = users.nik
        LEFT JOIN users u0 ON tb_usulan_pengabmas.nama_ketua = u0.nik
        LEFT JOIN users u1 ON tb_usulan_pengabmas.anggota_internal1 = u1.nik
        LEFT JOIN users u2 ON tb_usulan_pengabmas.anggota_internal2 = u2.nik
        LEFT JOIN users u3 ON tb_usulan_pengabmas.anggota_internal3 = u3.nik
        LEFT JOIN users u4 ON tb_usulan_pengabmas.anggota_internal4 = u4.nik
        where nama_ketua = "' . Auth::user()->nik . '" or anggota_internal1 = "' . Auth::user()->nik . '" or anggota_internal2 = "' . Auth::user()->nik . '" or anggota_internal3 = "' . Auth::user()->nik . '" or anggota_internal4 = "' . Auth::user()->nik . '" order by tanggal DESC');

        return view('dashboard.inbox', compact('usulanpenelitian', 'usulanpengabmas'))
            ->with('title', 'Inbox');
    }
}
