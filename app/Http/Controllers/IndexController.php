<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Unduh;
use App\Berita;
use DB;
use Form;
use Input;
use Excel;
use Session;
use Redirect;

class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Berita::orderBy('tanggal','DESC')->get();
        return view('home', compact('data'));
    }

    public function halamanunduh()
    {
        $data = Unduh::orderBy('tanggal','DESC')->get();
        return view('unduh', compact('data'));
    }

    public function berita()
    {
        $data = Berita::orderBy('tanggal','DESC')->get();
        return view('berita', compact('data'));
    }

    public function detail_berita($id)
    {
        date_default_timezone_set('Asia/Jakarta');
        $data = DB::Select('select tb_berita.*, users.name, users.foto from tb_berita JOIN users ON tb_berita.id_user = users.nik where tb_berita.url = "'.$id.'"');
        foreach ($data as $key => $value) {
            $view = $value->view + 1;
        }
        DB::update('update tb_berita set view = "'.$view.'" where url = "'.$id.'"');
        return view('detail_berita', compact('data'));
    }
}
