<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Image;
use Mail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'foto' => 'required|max:2048|mimes:jpg,png,jpeg',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|min:4|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'nik' => 'required|string|min:4|unique:users',
            // 'jafung' => 'required',
            'fakultas' => 'required',
            'program_studi' => 'required',
            'institusi' => 'required',
            'no_handphone' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $request = app('request');

        $ucapan = 'Selamat anda berhasil terdaftar di ASIPP. Akun terdaftar dengan username: "' . $data['username'] . '" dan password : "' . $data['password'] . '"';

        if (env('MAIL_HOST') != 'mail.yourdomain.id' || env('MAIL_USERNAME') != 'noreply@yourdomain.id') {
            try {
                Mail::send('email', array('pesan' => $ucapan), function ($pesan) use ($request) {
                    $pesan->to($request->email, 'Konfirmasi Pendaftaran ASIPP')->subject('Konfirmasi Pendaftaran ASIPP');
                    $pesan->from(env('MAIL_USERNAME', 'MAIL_FROM_ADDRESS'), 'Konfirmasi Pendaftaran ASIPP');
                });
            } catch (Exception $e) {
                return response(['status' => false, 'errors' => $e->getMessage()]);
            }
        }

        $foto = $request->file('foto');
        $destinationPath = public_path('assets/image/foto/');
        $inputfoto = $data['nik'] . '.' . $foto->getClientOriginalExtension();
        $foto->move($destinationPath, $inputfoto);

        return User::create([
            'foto' => $inputfoto,
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'password_view' => $data['password'],
            'nidn' => $data['nidn'],
            'nik' => $data['nik'],
            'jafung' => $_POST['jafung'],
            'fakultas' => $data['fakultas'],
            'program_studi' => $data['program_studi'],
            'institusi' => $data['institusi'],
            'no_handphone' => $data['no_handphone'],
            'level' => $data['level'],
        ]);

        // Level 1 = Admin, Level 2 = Pimpinan, Level 3 = Operator dan Dosen, Level 4 = Dosen, Level 5 = Reviewer
    }
}
