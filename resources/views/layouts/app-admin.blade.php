<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ env('APP_NAME') }} - Dashboard</title>
    <meta name="description" content="{{ env('APP_DESCRIPTION') }}">
    <link rel="shortcut icon" href="{{ asset('assets/image/logo/logo.png') }}">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}" />
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('assets/css/adminlte.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">
    <!-- dataTables -->
    <link href="{{asset('plugins/datatables/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet">
    <!-- jQuery -->
    <script type="text/javascript" src="{{asset('assets/js/jquery.min.js') }}"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{asset('assets/image/logo/logo.png')}}" alt="AdminLTELogo" height="60" width="60">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                @if(Auth::user()->level != 4 && Auth::user()->level != 5)
                <li class="nav-item">
                    <a class="nav-link" data-toggle="dropdown" href="#" title="Data Usulan Baru Hari Ini">
                        <i class="far fa-bell fa-lg"></i>
                        <span class="badge badge-danger navbar-badge">
                            <?php
                            $usulan_penelitian = DB::select('select count(id) as jumlah from tb_usulan_penelitian where status = "pengajuan"');
                            foreach ($usulan_penelitian as $key => $value) {
                                $penelitian = $value->jumlah;
                            }
                            ?>
                            <?php
                            $usulan_pengabmas = DB::select('select count(id) as jumlah from tb_usulan_pengabmas where status = "pengajuan"');
                            foreach ($usulan_pengabmas as $key => $value) {
                                $pengabmas = $value->jumlah;
                            }
                            ?>
                            <?php
                            $total = $penelitian + $pengabmas;
                            echo "<strong>$total</strong>";
                            ?>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header"><?= $total ?> Notifikasi Baru</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            Penelitian
                            <span class="float-right text-muted text-sm"><?= $penelitian ?></span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            Pengabmas
                            <span class="float-right text-muted text-sm"><?= $pengabmas ?></span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>
                @endif

                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <img width="20" class="rounded-circle" src="{{asset('/assets/image/foto')}}/{{Auth::user()->foto}}" alt=""> {{Auth::user()->email}}
                        <i class="fa fa-angle-down pl-3"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <h6 tabindex="-1" class="dropdown-header">Accounts</h6>
                        <a href="{{url('/account')}}" class="text-decoration-none">
                            <button type="button" tabindex="0" class="dropdown-item"><i class="fas fa-user-circle"></i> &nbsp;User Account</button>
                        </a>
                        <a href="{{ route('logout') }}" class="text-decoration-none" onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                            <button type="button" tabindex="0" class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i> &nbsp;{{ __('Logout') }}
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </button>
                        </a>
                        <div tabindex="-1" class="dropdown-divider"></div>
                        <h6 tabindex="-1" class="dropdown-header">Login as</h6>
                        @if((Auth::user()->pin == 2 and Auth::user()->level == 2)
                        or (Auth::user()->pin == '' and Auth::user()->level == 5))
                        <a href="{{url('/change-access')}}" class="text-decoration-none">
                            <button type="button" tabindex="0" class="dropdown-item">Dosen
                            </button>
                        </a>
                        @endif
                        @if((Auth::user()->pin == 1 and Auth::user()->level == 1))
                        <a href="{{url('/change-access')}}" class="text-decoration-none">
                            <button type="button" tabindex="0" class="dropdown-item">Dosen
                            </button>
                        </a>
                        <a href="{{url('/change-access2')}}" class="text-decoration-none">
                            <button type="button" tabindex="0" class="dropdown-item">Reviewer
                            </button>
                        </a>
                        @endif

                        @if((Auth::user()->pin == 3 and Auth::user()->level == 5))
                        <a href="{{url('/change-access2')}}" class="text-decoration-none">
                            <button type="button" tabindex="0" class="dropdown-item">Dosen
                            </button>
                        </a>
                        @endif

                        @if((Auth::user()->pin == 1 and Auth::user()->level == 5))
                        <a href="{{url('/change-access2')}}" class="text-decoration-none">
                            <button type="button" tabindex="0" class="dropdown-item">Dosen
                            </button>
                        </a>
                        @endif
                        @if((Auth::user()->pin == 3 and Auth::user()->level == 3))
                        <a href="{{url('/change-access')}}" class="text-decoration-none">
                            <button type="button" tabindex="0" class="dropdown-item">Dosen
                            </button>
                        </a>
                        <a href="{{url('/change-access2')}}" class="text-decoration-none">
                            <button type="button" tabindex="0" class="dropdown-item">Reviewer
                            </button>
                        </a>
                        @endif
                        @if(Auth::user()->pin == 1 and Auth::user()->level == 4)
                        <a href="{{url('/change-access')}}" class="text-decoration-none">
                            <button type="button" tabindex="0" class="dropdown-item">Admin
                            </button>
                        </a>
                        <a href="{{url('/change-access2')}}" class="text-decoration-none">
                            <button type="button" tabindex="0" class="dropdown-item">Reviewer
                            </button>
                        </a>
                        @endif
                        @if(Auth::user()->pin == 2 and Auth::user()->level == 4)
                        <a href="{{url('/change-access')}}" class="text-decoration-none">
                            <button type="button" tabindex="0" class="dropdown-item">Pimpinan
                            </button>
                        </a>
                        @endif
                        @if(Auth::user()->pin == 3 and Auth::user()->level == 4)
                        <a href="{{url('/change-access')}}" class="text-decoration-none">
                            <button type="button" tabindex="0" class="dropdown-item">Operator
                            </button>
                        </a>
                        <a href="{{url('/change-access2')}}" class="text-decoration-none">
                            <button type="button" tabindex="0" class="dropdown-item">Reviewer
                            </button>
                        </a>
                        @endif
                        @if(Auth::user()->pin == '' and Auth::user()->level == 4)
                        <a href="{{url('/change-access')}}" class="text-decoration-none">
                            <button type="button" tabindex="0" class="dropdown-item">Reviewer
                            </button>
                        </a>
                        @endif
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{url('/dashboard')}}" class="brand-link">
                <img src="{{asset('assets/image/logo/logo.png')}}" alt="{{ env('APP_NAME') }}" class="brand-image" style="opacity: .8">
                <span class="brand-text font-weight-light">{{ env('APP_NAME') }}</span>
            </a>
            <div class="sidebar">
                <div class="scrollbar-sidebar">
                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <li class="nav-header">DASHBOARD</li>
                            <li class="nav-item">
                                <a href="{{url('/dashboard')}}" class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-th-large"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            @if(Auth::user()->level == 4)
                            <li class="nav-item">
                                <a href="{{url('/inbox')}}" class="nav-link {{ Request::is('inbox*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-inbox"></i>
                                    <p>Inbox</p>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->level != 5)
                            <li class="nav-header">DOSEN</li>
                            <li class="nav-item">
                                <a href="{{url('/dosen')}}" class="nav-link {{ Request::is('dosen*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-user-friends"></i>
                                    <p>Data Dosen</p>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->level == 4 )
                            <li class="nav-header">UPLOAD USULAN</li>
                            <li class="nav-item">
                                <a href="{{url('/usulan-penelitian')}}" class="nav-link {{ Request::is('usulan-penelitian*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-file-upload"></i>
                                    <p>Usulan Penelitian</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/usulan-pengabmas')}}" class="nav-link {{ Request::is('usulan-pengabmas*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-file-upload"></i>
                                    <p>Usulan Pengabmas</p>
                                </a>
                            </li>
                            <li class="nav-header">LAPORAN</li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-pen-square"></i>
                                    <p>
                                        Laporan Penelitian
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{url('/laporan-kemajuan-penelitian')}}" class="nav-link {{ Request::is('laporan-kemajuan-penelitian*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Laporan Kemajuan</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{url('/laporan-akhir-penelitian')}}" class="nav-link {{ Request::is('laporan-akhir-penelitian*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Laporan Akhir</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-pen-square"></i>
                                    <p>
                                        Laporan Pengabmas
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{url('/laporan-kemajuan-pengabmas')}}" class="nav-link {{ Request::is('laporan-kemajuan-pengabmas*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Laporan Kemajuan</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{url('/laporan-akhir-pengabmas')}}" class="nav-link {{ Request::is('laporan-akhir-pengabmas*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Laporan Akhir</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/hasil-review')}}" class="nav-link {{ Request::is('hasil-review*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tasks"></i>
                                    <p>Hasil Review</p>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->level == 4 )
                            <li class="nav-header">LOGBOOK</li>
                            <li class="nav-item">
                                <a href="{{url('/lookbook-penelitian')}}" class="nav-link {{ Request::is('lookbook-penelitian*') ? 'active' : '' }}">
                                    <i class="nav-icon far fa-edit"></i>
                                    <p>Logbook Penelitian</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/lookbook-pengabmas')}}" class="nav-link {{ Request::is('lookbook-pengabmas*') ? 'active' : '' }}">
                                    <i class="nav-icon far fa-edit"></i>
                                    <p>Logbook Pengabmas</p>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->level == 5)
                            <li class="nav-item">
                                <a href="{{url('/plot-reviewer-usulan')}}" class="nav-link {{ Request::is('plot-reviewer-usulan*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-share"></i>
                                    <p>Review Usulan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/plot-reviewer-kemajuan')}}" class="nav-link {{ Request::is('plot-reviewer-kemajuan*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-share"></i>
                                    <p>Review Laporan Kemajuan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/plot-reviewer-akhir')}}" class="nav-link {{ Request::is('plot-reviewer-akhir*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-share"></i>
                                    <p>Review Laporan Akhir</p>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->level != 4 and Auth::user()->level != 5)
                            <li class="nav-header">REVIEWER</li>
                            <li class="nav-item">
                                <a href="{{url('/reviewer-eksternal')}}" class="nav-link {{ Request::is('reviewer-eksternal*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-user-friends"></i>
                                    <p>Data Reviewer</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon far fa-share-square"></i>
                                    <p>
                                        Plot Reviewer
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{url('/plot-reviewer-usulan')}}" class="nav-link {{ Request::is('plot-reviewer-usulan*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Plot Reviewer Usulan</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{url('/plot-reviewer-kemajuan')}}" class="nav-link {{ Request::is('plot-reviewer-kemajuan*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Plot Reviewer Kemajuan</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{url('/plot-reviewer-akhir')}}" class="nav-link {{ Request::is('plot-reviewer-akhir*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Plot Reviewer Akhir</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/hasil-review')}}" class="nav-link {{ Request::is('hasil-review*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tasks"></i>
                                    <p>Hasil Review</p>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->level != 4 and Auth::user()->level != 5)
                            <li class="nav-header">LAPORAN</li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-clipboard-list"></i>
                                    <p>
                                        Laporan Penelitian
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{url('/konfirmasi-usulan-penelitian')}}" class="nav-link {{ Request::is('konfirmasi-usulan-penelitian*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Konfirmasi Usulan Baru</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{url('/konfirmasi-kemajuan-penelitian')}}" class="nav-link {{ Request::is('konfirmasi-kemajuan-penelitian*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Konfirmasi Laporan Kemajuan</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{url('/konfirmasi-akhir-penelitian')}}" class="nav-link {{ Request::is('konfirmasi-akhir-penelitian*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Konfirmasi Laporan Akhir</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-clipboard-list"></i>
                                    <p>
                                        Laporan Pengabmas
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{url('/konfirmasi-usulan-pengabmas')}}" class="nav-link {{ Request::is('konfirmasi-usulan-pengabmas*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Konfirmasi Usulan Baru</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{url('/konfirmasi-kemajuan-pengabmas')}}" class="nav-link {{ Request::is('konfirmasi-kemajuan-pengabmas*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Konfirmasi Laporan Kemajuan</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{url('/konfirmasi-akhir-pengabmas')}}" class="nav-link {{ Request::is('konfirmasi-akhir-pengabmas*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Konfirmasi Laporan Akhir</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-envelope"></i>
                                    <p>
                                        Surat Tugas
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{url('/surat-tugas-penelitian')}}" class="nav-link {{ Request::is('surat-tugas-penelitian*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Surat Tugas Penelitian</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{url('/surat-tugas-pengabmas')}}" class="nav-link {{ Request::is('surat-tugas-pengabmas*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Surat Tugas Pengabmas</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif
                            @if(Auth::user()->level != 4 and Auth::user()->level != 5)
                            <li class="nav-header">PENGATURAN</li>
                            @endif
                            @if(Auth::user()->level == 1)
                            <li class="nav-item">
                                <a href="{{url('/manajemen-user')}}" class="nav-link {{ Request::is('manajemen-user*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Data Users</p>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->level != 4 and Auth::user()->level != 5)
                            <li class="nav-item">
                                <a href="{{url('/pengaturan')}}" class="nav-link {{ Request::is('pengaturan*') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-download"></i>
                                    <p>Pengaturan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/manajemen-berita')}}" class="nav-link {{ Request::is('manajemen-berita*') ? 'active' : '' }}">
                                    <i class="nav-icon far fa-newspaper"></i>
                                    <p>Berita</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/petunjuk')}}" class="nav-link {{ Request::is('petunjuk*') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-edit"></i>
                                    <p>Bantuan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/unduhan')}}" class="nav-link {{ Request::is('unduhan*') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-download"></i>
                                    <p>Unduhan</p>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->level == 1)
                            <li class="nav-item">
                                <a href="{{url('/histori-akses')}}" class="nav-link {{ Request::is('histori-akses*') ? 'active' : '' }}">
                                    <i class="nav-icon far fa-file-alt"></i>
                                    <p>Log Akses</p>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <h1 class="m-0" style="font-size:34px"><?= $title; ?></h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
                                <li class="breadcrumb-item active"><?= $title; ?></li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </section>
            </div>

        </div>
        <!-- ./wrapper -->

        <!-- jQuery UI 1.11.4 -->
        <script type="text/javascript" src="{{asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
            $.widget.bridge('uibutton', $.ui.button)
        </script>
        <!-- Bootstrap 4 -->
        <script type="text/javascript" src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- ChartJS -->
        <script type="text/javascript" src="{{asset('plugins/chart.js/Chart.min.js') }}"></script>
        <!-- Sparkline -->
        <script type="text/javascript" src="{{asset('plugins/sparklines/sparkline.js') }}"></script>
        <!-- jQuery Knob Chart -->
        <script type="text/javascript" src="{{asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
        <!-- daterangepicker -->
        <script type="text/javascript" src="{{asset('plugins/moment/moment.min.js') }}"></script>
        <script type="text/javascript" src="{{asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <script type="text/javascript" src="{{asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
        <!-- Select2 -->
        <script type="text/javascript" src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
        <!-- Summernote -->
        <script type="text/javascript" src="{{asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
        <!-- overlayScrollbars -->
        <script type="text/javascript" src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script type="text/javascript" src="{{asset('assets/js/adminlte.js') }}"></script>
        <!-- Custom scripts -->
        <script type="text/javascript" src="{{asset('assets/diagram/highcharts.js') }}"></script>
        <script type="text/javascript" src="{{asset('plugins/datatables/js/jquery.dataTables.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('plugins/datatables/js/dataTables.bootstrap5.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('assets/js/money.js') }}"></script>
        <script type="text/javascript">
            $(".alert-dismissible").fadeTo(4000, 500).slideUp(500, function() {
                $(".alert-dismissible").alert('close');
            });

            $(function() {
                //Initialize Select2 Elements
                $('.select2').select2({
                    theme: 'bootstrap4'
                })

                //Initialize Select2 Elements
                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                })
            })
            $(document).ready(function() {
                // Summernote
                $('#summernote').summernote()
            })
            $(document).ready(function() {
                $('#example').DataTable();
            });
            $(document).ready(function() {
                $('#example1').DataTable();
            });
            $(document).ready(function() {
                $('#example2').DataTable();
            });
            $(document).ready(function() {
                $('#example3').DataTable();
            });
            $(document).ready(function() {
                $('#example4').DataTable();
            });
            $(document).ready(function() {
                $('#example5').DataTable();
            });
            $(document).ready(function() {
                $('#example6').DataTable();
            });
            $(document).ready(function() {
                $('.currency').maskMoney({
                    prefix: 'Rp. ',
                    thousands: '.',
                    decimal: ',',
                    precision: 0
                });
            });
        </script>
        <script type="text/javascript">
            var readFoto = function(event) {
                var input = event.target;

                var reader = new FileReader();
                reader.onload = function() {
                    var dataURL = reader.result;
                    var output = document.getElementById('output');
                    output.src = dataURL;
                };
                reader.readAsDataURL(input.files[0]);
            };
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#datepicker').daterangepicker({
                    singleDatePicker: true,
                    locale: {
                        format: 'YYYY-MM-DD'
                    },
                    calender_style: "picker_1"
                });
            });
        </script>
        @yield('js')
</body>

</html>
@yield('modal')