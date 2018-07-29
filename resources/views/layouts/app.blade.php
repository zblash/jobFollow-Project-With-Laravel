<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>İş Takip - @yield('pageTitle')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset('public/theme/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('public/theme/bower_components/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('public/theme/bower_components/Ionicons/css/ionicons.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('public/theme/dist/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('public/theme/plugins/selector/css/multi-select.css') }}">
  <link rel="stylesheet" href="{{ asset('public/theme/bower_components/select2/dist/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/theme/plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ asset('public/theme/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/theme/plugins/timepicker/bootstrap-timepicker.min.css') }}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ asset('public/theme/dist/css/skins/_all-skins.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/theme/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/theme/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="{{ asset('public/theme/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  
  @yield('css')
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="../../index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>I</b>T</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Is Takip</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
         
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="hidden-xs">{{ Auth::user()->name }}</span>
            </a>
        <ul class="dropdown-menu">
              
             <li class="user-footer">
                  <a href="{{ route('auth.logout.get') }}" class="btn btn-default btn-flat">Çıkış Yap</a>
                
              </li>
            
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
 
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">NAVIGASYON</li>
       <li>
          <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> <span>Anasayfa</span>
            
          </a>
        </li>
        <li class="treeview menu-open">
          <a href="#">
            <i class="fa fa-users"></i>
            <span>Müşteri İşlemleri</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" style="display: block">
            <li><a href="{{ route('customersPage') }}"><i class="fa fa-circle-o"></i> Müşteriler</a></li>
            <li><a href="{{ route('customeraddg') }}"><i class="fa fa-circle-o"></i> Müşteri Ekle</a></li>
          </ul>
        </li>
         <li class="treeview menu-open">
          <a href="#">
            <i class="fa fa-taxi"></i>
            <span>Randevu İşlemleri</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" style="display: block">
            <li><a href="{{ route('appointmentsPage') }}"><i class="fa fa-circle-o"></i> Randevular</a></li>
            <li><a href="{{ route('pastappointmentsPage') }}"><i class="fa fa-circle-o"></i> Geçmiş Randevular</a></li>
            <li><a href="{{ route('cancelledappointments') }}"><i class="fa fa-circle-o"></i> İptal Edilen Randevular</a></li>
            <li><a href="{{ route('newappointmentPage') }}"><i class="fa fa-circle-o"></i> Randevu Oluştur</a></li>
            <li><a href="{{ route('plannedappointmentsPage') }}"><i class="fa fa-circle-o"></i> Planlı Randevular</a></li>
            <li><a href="{{ route('newplannedappointmentPage') }}"><i class="fa fa-circle-o"></i> Planlı Randevu Oluştur</a></li>
          
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-user-secret"></i>
            <span>Personel İşlemleri</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('employeesPage') }}"><i class="fa fa-circle-o"></i> Personel Listesi</a></li>
            <li><a href="{{ route('newemployeePage') }}"><i class="fa fa-circle-o"></i> Personel Ekle</a></li>
            <li><a href="{{ route('driversPage') }}"><i class="fa fa-circle-o"></i> Şoför Listesi</a></li>
            <li><a href="{{ route('newdriverPage') }}"><i class="fa fa-circle-o"></i> Şoför Ekle</a></li>
            <li><a href="{{ route('employeesPayPage') }}"><i class="fa fa-circle-o"></i> Personel Ödemeleri</a></li>
          </ul>
        </li>
                <li class="treeview">
          <a href="#">
            <i class="fa fa-bolt"></i>
            <span>Hizmet Grupları</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('servicesPage') }}"><i class="fa fa-circle-o"></i> Hizmet Grupları</a></li>
            <li><a href="{{ route('newservicePage') }}"><i class="fa fa-circle-o"></i> Hizmet Grubu Ekle</a></li>
          </ul>
        </li>
         <li class="treeview">
          <a href="#">
            <i class="fa fa-bolt"></i>
            <span>Yetkili İşlemleri</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('users') }}"><i class="fa fa-circle-o"></i> Yetkililer</a></li>
            <li><a href="{{ route('addUser') }}"><i class="fa fa-circle-o"></i> Yetkili Ekle</a></li>
          </ul>
        </li>
         <li class="treeview">
          <a href="#">
            <i class="fa fa-bolt"></i>
            <span>Kampanya İşlemleri</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('campaignsPage') }}"><i class="fa fa-circle-o"></i> Kampanyalar</a></li>
            <li><a href="{{ route('pastcampaignsPage') }}"><i class="fa fa-circle-o"></i> Geçmiş Kampanyalar</a></li>
            <li><a href="{{ route('newcampaignPage') }}"><i class="fa fa-circle-o"></i> Kampanya Oluştur</a></li>
          </ul>
        </li>
        <li>
          <a href="{{ route('profitsPage') }}">
            <i class="fa fa-money"></i> <span>Kazançlar</span>
            
          </a>
        </li>
       
        <li class="treeview">
          <a href="#">
            <i class="fa fa-bolt"></i>
            <span>Memnuniyet Sistemi</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('satisfactionsPage') }}"><i class="fa fa-circle-o"></i> Memnuniyet Sistemi</a></li>
            <li><a href="{{ route('satisfactionReports') }}"><i class="fa fa-circle-o"></i> Raporlar</a></li>
          </ul>
        </li>
        <li>
          <a href="{{ route('sendSms') }}">
            <i class="fa fa-bell-o"></i> <span>Toplu SMS</span>
            
          </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      
        @yield('content')
         @include('layouts/notifications')
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      Created By <b>zblash</b>
    </div>
    <strong>Copyright &copy; Is Takip.</strong>
  </footer>

  <!-- Control Sidebar -->
 
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<script src="{{ asset('public/theme/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('public/theme/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- FastClick -->

@yield('js')

<script src="{{ asset('public/theme/bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('public/theme/dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('public/theme/dist/js/demo.js') }}"></script>
<script src="{{ asset('public/theme/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- iCheck 1.0.1 -->
       <script type="text/javascript">
            $(function () {

          $('#modal').modal('show');

            })
        </script>
</body>
</html>
