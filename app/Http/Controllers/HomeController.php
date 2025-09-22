<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Unduh;
use DB;
use Form;
use Input;
use Excel;
use Session;
use Redirect;

class HomeController extends Controller
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
    public function halamanunduh()
    {
        $data = Unduh::orderBy('tanggal','DESC')->get();
        return view('unduh', compact('data'));
    }
}
