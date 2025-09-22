<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('otentikasi');
// });

Route::get('/', 'IndexController@index');
Route::get('/berita', 'IndexController@berita');
Route::get('/berita/detail_berita/{id}', 'IndexController@detail_berita');
Route::get('/halaman-unduh', 'IndexController@halamanunduh');

Auth::routes();

Route::get('downloadusulanpenelitianExcel/{type}', 'AdminController@downloadusulanpenelitian');
Route::get('downloadusulanpengabmasExcel/{type}', 'AdminController@downloadusulanpengabmas');

Route::get('downloadkemajuanpenelitianExcel/{type}', 'AdminController@downloadkemajuanpenelitian');
Route::get('downloadkemajuanpengabmasExcel/{type}', 'AdminController@downloadkemajuanpengabmas');

Route::get('downloadakhirpenelitianExcel/{type}', 'AdminController@downloadakhirpenelitian');
Route::get('downloadakhirpengabmasExcel/{type}', 'AdminController@downloadakhirpengabmas');

Route::get('downloadreviewerusulanpenelitianExcel/{type}', 'AdminController@downloadreviewerusulanpenelitian');
Route::get('downloadreviewerusulanpengabmasExcel/{type}', 'AdminController@downloadreviewerusulanpengabmas');

Route::get('downloadreviewerkemajuanpenelitianExcel/{type}', 'AdminController@downloadreviewerkemajuanpenelitian');
Route::get('downloadreviewerkemajuanpengabmasExcel/{type}', 'AdminController@downloadreviewerkemajuanpengabmas');

Route::get('/account', 'AdminController@account');
Route::post('/update-account/{id}', 'AdminController@updateaccount');
Route::get('/ubah-password', 'AdminController@resetpassword');
Route::post('/update-password/{id}', 'AdminController@updatepassword');

Route::get('/change-access', 'AdminController@changeaccess');
Route::get('/change-access2', 'AdminController@changeaccess2');

Route::get('/dashboard', 'AdminController@dashboard');
Route::get('/dosen', 'AdminController@dosen');
Route::get('/{id}/delete-dosen', 'AdminController@deletedosen');
Route::post('/update-dosen/{id}', 'AdminController@updatedosen');
Route::post('/simpan-dosen', 'AdminController@simpandosen');

Route::post('/update-account/{id}', 'AdminController@updateaccount');

Route::get('/usulan-penelitian', 'AdminController@usulanpenelitian');
Route::get('/{id}/delete-usulan-penelitian', 'AdminController@deleteusulanpenelitian');
Route::post('/simpan-usulan-penelitian', 'AdminController@simpanusulanpenelitian');
Route::post('/update-usulan-penelitian/{id}', 'AdminController@updateusulanpenelitian');
Route::get('/{id}/lihat-penilaian-usulan-penelitian', 'AdminController@lihatpenilaianusulanpenelitian');
Route::get('/{id}/lihat-penilaian-penelitian', 'AdminController@lihatpenilaianpenelitian');

Route::get('/usulan-pengabmas', 'AdminController@usulanpengabmas');
Route::get('/{id}/delete-usulan-pengabmas', 'AdminController@deleteusulanpengabmas');
Route::post('/simpan-usulan-pengabmas', 'AdminController@simpanusulanpengabmas');
Route::post('/update-usulan-pengabmas/{id}', 'AdminController@updateusulanpengabmas');
Route::get('/{id}/lihat-penilaian-usulan-pengabmas', 'AdminController@lihatpenilaianusulanpengabmas');
Route::get('/{id}/lihat-penilaian-pengabmas', 'AdminController@lihatpenilaianpengabmas');

Route::get('/unduhan', 'AdminController@unduhan');
Route::get('/{id}/delete-unduhan', 'AdminController@deleteunduhan');
Route::post('/update-unduhan/{id}', 'AdminController@updateunduhan');
Route::post('/simpan-unduhan', 'AdminController@simpanunduhan');

Route::get('/petunjuk', 'AdminController@petunjuk');
Route::post('/update-petunjuk/{id}', 'AdminController@updatepetunjuk');

Route::get('/laporan-kemajuan-penelitian', 'AdminController@laporankemajuanpenelitian');
Route::get('/{id}/delete-laporan-kemajuan-penelitian', 'AdminController@deletelaporankemajuanpenelitian');
Route::post('/simpan-laporan-kemajuan-penelitian', 'AdminController@simpanlaporankemajuanpenelitian');

Route::get('/laporan-akhir-penelitian', 'AdminController@laporanakhirpenelitian');
Route::get('/{id}/delete-laporan-akhir-penelitian', 'AdminController@deletelaporanakhirpenelitian');
Route::post('/simpan-laporan-akhir-penelitian', 'AdminController@simpanlaporanakhirpenelitian');

Route::get('/laporan-kemajuan-pengabmas', 'AdminController@laporankemajuanpengabmas');
Route::get('/{id}/delete-laporan-kemajuan-pengabmas', 'AdminController@deletelaporankemajuanpengabmas');
Route::post('/simpan-laporan-kemajuan-pengabmas', 'AdminController@simpanlaporankemajuanpengabmas');

Route::get('/laporan-akhir-pengabmas', 'AdminController@laporanakhirpengabmas');
Route::get('/{id}/delete-laporan-akhir-pengabmas', 'AdminController@deletelaporanakhirpengabmas');
Route::post('/simpan-laporan-akhir-pengabmas', 'AdminController@simpanlaporanakhirpengabmas');

Route::get('/manajemen-user', 'AdminController@manajemenuser');
Route::get('/{id}/delete-manajemen-user', 'AdminController@deletemanajemenuser');
Route::post('/update-manajemen-user/{id}', 'AdminController@updatemanajemenuser');
Route::post('/simpan-manajemen-user', 'AdminController@simpanmanajemenuser');

Route::get('/manajemen-berita', 'AdminController@manajemenberita');
Route::get('/{id}/delete-manajemen-berita', 'AdminController@deletemanajemenberita');
Route::post('/update-manajemen-berita/{id}', 'AdminController@updatemanajemenberita');
Route::post('/simpan-manajemen-berita', 'AdminController@simpanmanajemenberita');

Route::get('/histori-akses', 'AdminController@historiakses');
Route::get('/delete-histori', 'AdminController@deletehistori');

Route::get('/konfirmasi-usulan-penelitian', 'AdminController@konfirmasiusulanpenelitian');
Route::post('/simpan-konfirmasi-usulan-penelitian/{id}', 'AdminController@simpankonfirmasiusulanpenelitian');
Route::get('/{id}/batal-usulan-penelitian-diterima', 'AdminController@batalusulanpenelitianditerima');
Route::get('/{id}/batal-usulan-penelitian-ditolak', 'AdminController@batalusulanpenelitianditolak');

Route::post('/update-dana-hibah-pt-penelitian/{id}', 'AdminController@updatedanahibahptpenelitian');
Route::post('/update-dana-hibah-pt-pengabmas/{id}', 'AdminController@updatedanahibahptpengabmas');

Route::get('/konfirmasi-usulan-pengabmas', 'AdminController@konfirmasiusulanpengabmas');
Route::post('/simpan-konfirmasi-usulan-pengabmas/{id}', 'AdminController@simpankonfirmasiusulanpengabmas');
Route::get('/{id}/batal-usulan-pengabmas-diterima', 'AdminController@batalusulanpengabmasditerima');
Route::get('/{id}/batal-usulan-pengabmas-ditolak', 'AdminController@batalusulanpengabmasditolak');

Route::get('/konfirmasi-kemajuan-penelitian', 'AdminController@konfirmasikemajuanpenelitian');
Route::get('/{id}/check-kemajuan-penelitian', 'AdminController@checkkemajuanpenelitian');

Route::get('/konfirmasi-kemajuan-pengabmas', 'AdminController@konfirmasikemajuanpengabmas');
Route::get('/{id}/check-kemajuan-pengabmas', 'AdminController@checkkemajuanpengabmas');

Route::get('/konfirmasi-akhir-penelitian', 'AdminController@konfirmasiakhirpenelitian');
Route::get('/{id}/check-akhir-penelitian', 'AdminController@checkakhirpenelitian');

Route::get('/konfirmasi-akhir-pengabmas', 'AdminController@konfirmasiakhirpengabmas');
Route::get('/{id}/check-akhir-pengabmas', 'AdminController@checkakhirpengabmas');

Route::get('/plot-reviewer-usulan', 'AdminController@plotreviewer');
Route::get('/{id}/delete-plot-reviewer-penelitian', 'AdminController@deleteplotreviewerpenelitian');
Route::post('/simpan-plot-reviewer-penelitian', 'AdminController@simpanplotreviewerpenelitian');
Route::get('/{id}/delete-plot-reviewer-pengabmas', 'AdminController@deleteplotreviewerpengabmas');
Route::post('/simpan-plot-reviewer-pengabmas', 'AdminController@simpanplotreviewerpengabmas');

Route::get('/plot-reviewer-kemajuan', 'AdminController@plotreviewerkemajuan');
Route::get('/{id}/delete-plot-reviewer-kemajuan-penelitian', 'AdminController@deleteplotreviewerkemajuanpenelitian');
Route::post('/simpan-plot-reviewer-kemajuan-penelitian', 'AdminController@simpanplotreviewerkemajuanpenelitian');
Route::get('/{id}/delete-plot-reviewer-kemajuan-pengabmas', 'AdminController@deleteplotreviewerkemajuanpengabmas');
Route::post('/simpan-plot-reviewer-kemajuan-pengabmas', 'AdminController@simpanplotreviewerkemajuanpengabmas');

Route::get('/plot-reviewer-akhir', 'AdminController@plotreviewerakhir');
Route::get('/{id}/delete-plot-reviewer-akhir-penelitian', 'AdminController@deleteplotreviewerakhirpenelitian');
Route::post('/simpan-plot-reviewer-akhir-penelitian', 'AdminController@simpanplotreviewerakhirpenelitian');
Route::get('/{id}/delete-plot-reviewer-akhir-pengabmas', 'AdminController@deleteplotreviewerakhirpengabmas');
Route::post('/simpan-plot-reviewer-akhir-pengabmas', 'AdminController@simpanplotreviewerakhirpengabmas');

Route::get('/reviewer-eksternal', 'AdminController@reviewereksternal');
Route::get('/{id}/delete-reviewer-eksternal', 'AdminController@deletereviewereksternal');
Route::post('/update-reviewer-eksternal/{id}', 'AdminController@updatereviewereksternal');
Route::post('/simpan-reviewer-eksternal', 'AdminController@simpanreviewereksternal');

Route::get('/{id}/tanggapan-usulan-penelitian', 'AdminController@tanggapanusulanpenelitian');
Route::post('/simpan-tanggapan-usulan-penelitian', 'AdminController@simpantanggapanusulanpenelitian');
Route::get('/{id}/delete-tanggapan-usulan-penelitian', 'AdminController@deletetanggapanusulanpenelitian');
Route::get('/{id}/tanggapan-usulan-pengabmas', 'AdminController@tanggapanusulanpengabmas');
Route::post('/simpan-tanggapan-usulan-pengabmas', 'AdminController@simpantanggapanusulanpengabmas');
Route::get('/{id}/delete-tanggapan-usulan-pengabmas', 'AdminController@deletetanggapanusulanpengabmas');
Route::get('/{id}/tanggapan-kemajuan-penelitian', 'AdminController@tanggapankemajuanpenelitian');
Route::post('/simpan-tanggapan-kemajuan-penelitian', 'AdminController@simpantanggapankemajuanpenelitian');
Route::get('/{id}/tanggapan-kemajuan-pengabmas', 'AdminController@tanggapankemajuanpengabmas');
Route::post('/simpan-tanggapan-kemajuan-pengabmas', 'AdminController@simpantanggapankemajuanpengabmas');

Route::get('/hasil-review', 'AdminController@hasilreview');
Route::get('/{id}/tanggapan-akhir-penelitian', 'AdminController@tanggapanakhirpenelitian');
Route::post('/simpan-tanggapan-akhir-penelitian', 'AdminController@simpantanggapanakhirpenelitian');
Route::get('/{id}/tanggapan-akhir-pengabmas', 'AdminController@tanggapanakhirpengabmas');
Route::post('/simpan-tanggapan-akhir-pengabmas', 'AdminController@simpantanggapanakhirpengabmas');

Route::get('/{id}/delete-tanggapan-kemajuan-pengabmas', 'AdminController@deletetanggapankemajuanpengabmas');
Route::get('/{id}/delete-tanggapan-kemajuan-penelitian', 'AdminController@deletetanggapankemajuanpenelitian');
Route::get('/{id}/delete-tanggapan-akhir-pengabmas', 'AdminController@deletetanggapanakhirpengabmas');
Route::get('/{id}/delete-tanggapan-akhir-penelitian', 'AdminController@deletetanggapanakhirpenelitian');

Route::get('/lookbook-penelitian', 'AdminController@lookbookpenelitian');
Route::get('/{id}/delete-lookbook-penelitian', 'AdminController@deletelookbookpenelitian');
Route::post('/simpan-lookbook-penelitian', 'AdminController@simpanlookbookpenelitian');

Route::get('/{id}/buat-surat-tugas-penelitian', 'AdminController@buatsurattugaspenelitian');
Route::get('/{id}/delete-surat-tugas-penelitian', 'AdminController@deletesurattugaspenelitian');
Route::post('/simpan-surat-tugas-penelitian', 'AdminController@simpansurattugaspenelitian');
Route::get('/{id}/download-surat-tugas-penelitian', 'AdminController@downloadsurattugaspenelitian');
Route::get('/{id}/surat-tugas-penelitian', 'AdminController@surattugaspenelitian');
Route::get('/{id}/surat-tugas-pengabmas', 'AdminController@surattugaspengabmas');

Route::get('/lookbook-pengabmas', 'AdminController@lookbookpengabmas');
Route::get('/{id}/delete-lookbook-pengabmas', 'AdminController@deletelookbookpengabmas');
Route::post('/simpan-lookbook-pengabmas', 'AdminController@simpanlookbookpengabmas');

Route::get('/{id}/buat-surat-tugas-pengabmas', 'AdminController@buatsurattugaspengabmas');
Route::get('/{id}/delete-surat-tugas-pengabmas', 'AdminController@deletesurattugaspengabmas');
Route::post('/simpan-surat-tugas-pengabmas', 'AdminController@simpansurattugaspengabmas');
Route::get('/{id}/download-surat-tugas-pengabmas', 'AdminController@downloadsurattugaspengabmas');

Route::get('/surat-tugas-penelitian', 'AdminController@daftarsurattugaspenelitian');
Route::get('/download-all-surat-tugas-penelitian', 'AdminController@downloadallsurattugaspenelitian');

Route::get('/surat-tugas-pengabmas', 'AdminController@daftarsurattugaspengabmas');
Route::get('/download-all-surat-tugas-pengabmas', 'AdminController@downloadallsurattugaspengabmas');

Route::get('/pengaturan', 'AdminController@pengaturan');
Route::post('/update-pengaturan/{id}', 'AdminController@updatepengaturan');

Route::get('/inbox', 'AdminController@inbox');