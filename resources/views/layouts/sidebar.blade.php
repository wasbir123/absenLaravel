<section class="sidebar">
      <!-- Sidebar user panel -->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">BERANDA</li>
        <!-- <li class="menu-sidebar {{ (Request::path() == 'admin') ? 'active' : '' }}"><a href="{{ url('/') }}"><i class="fa fa-fw fa-home"></i> Home</span></a></li> -->
        <!-- <li class="menu-sidebar {{ (Request::path() == 'data') ? 'active' : '' }}"><a href="{{ url('data') }}"><i class="fa fa-fw fa-user"></i> master data</span></a></li> -->

        @if(\Auth::user()->level == 'admin')
        <li class="menu-sidebar {{ (Request::path() == 'karyawan') ? 'active' : '' }}"><a href="{{ url('karyawan') }}"><i class="fa fa-calendar"></i> Karyawan</span></a></li>

        <li class="menu-sidebar {{ (Request::path() == 'jam') ? 'active' : '' }}"><a href="{{ url('jam') }}"><i class="fa fa-calendar"></i> Jam Kerja</span></a></li>
        @endif

        <li class="treeview">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Absensi</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">

            <li><a href="{{ url('absen') }}"><i class="fa fa-circle-o"></i> Absensi</a></li>

            <li><a href="{{ url('absen/history') }}"><i class="fa fa-circle-o"></i> History Absensi Ku Ini</a></li>
            @if(\Auth::user()->level == 'admin')
            <li><a href="{{ url('izin') }}"><i class="fa fa-circle-o"></i> Izin Tidak Hadir</a></li>
            @endif
          </ul>
        </li>

        <!-- <li class="header">OTHER</li> -->

        <!--<li class="menu-sidebar"><a href="{{ url('/profile') }}"><span class="glyphicon glyphicon-log-out"></span> Update Profile</span></a></li>-->
        @if(\Auth::user()->level == 'admin')
        <li class="menu-sidebar"><a href="{{ url('/atasan') }}"><span class="glyphicon glyphicon-log-out"></span> Data Lainnya</span></a></li>
        @endif
        <li class="menu-sidebar"><a href="{{ url('/keluar') }}"><span class="glyphicon glyphicon-log-out"></span> Logout</span></a></li>


      </ul>
    </section>