<aside class="main-sidebar elevation-4 sidebar-light-orange">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
      <img src="{{ asset($setting->path_logo) }}" alt="{{ $setting->nama_perusahaan }}" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{ $setting->nama_perusahaan }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset(auth()->user()->profile_photo_path) }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="{{ url('/') }}" class="d-block">{{ auth()->user()->name }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
               
              </p>
            </a>
           
          </li>
        
          @if (auth()->user()->level == 0)

          
          <li class="nav-header">DATA MASTER</li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Data Karyawan
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('agent.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Jabatan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('pangkalan.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kelengkapan Berkas</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('pangkalan.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kontrak Kerja</p>
                </a>
              </li>
             
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Data Wilayah
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('kecamatan.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kecamatan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('desa.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Desa/Kelurahan</p>
                </a>
              </li>
            
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-id-card"></i>
              <p>
                Data Pengguna
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('pelanggan.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pengguna</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('pekerjaan.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pekerjaan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('penghasilan.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Penghasilan</p>
                </a>
              </li>
             
            </ul>
          </li>
        
          <li class="nav-header">RIWAYAT</li>
          <li class="nav-item">
            <a href="{{ route('transaksi.index') }}" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                Transaksi Pengguna
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('distribusi.index') }}" class="nav-link">
              <i class="nav-icon far fa-edit"></i>
              <p>
                Distribusi Agen
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('laporan.index') }}" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Laporan Stock
              </p>
            </a>
          </li>
        
          <li class="nav-header">SYSTEM</li>
          <li class="nav-item">
            <a href="{{ route('setting.index') }}" class="nav-link">
              <i class="nav-icon far fa-circle text-warning"></i>
              <p>Pengaturan</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('slider.index') }}" class="nav-link">
              <i class="nav-icon far fa-circle text-primary"></i>
              <p>Pengumuman</p>
            </a>
          </li>

          

          <li class="nav-item">
            <a href="{{ route('user.index') }}" class="nav-link">
              <i class="nav-icon far fa-circle text-dark"></i>
              <p>Hak Akses</p>
            </a>
          </li>


          @elseif(auth()->user()->level == 3)

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-id-card"></i>
              <p>
                Data Pengguna
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('pelanggan.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pengguna</p>
                </a>
              </li>
            </ul>
          </li>


          <li class="nav-header">RIWAYAT</li>
          <li class="nav-item">
            <a href="{{ route('transaksi.index') }}" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                Transaksi Pengguna
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('distribusi.index') }}" class="nav-link">
              <i class="nav-icon far fa-edit"></i>
              <p>
                Distribusi Agen
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('laporan.index') }}" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Laporan Stock
              </p>
            </a>
          </li>



          @endif
          <li class="nav-item">
            <a href="{{ route('user.profile') }}" class="nav-link">
              <i class="nav-icon far fa-circle text-orange"></i>
              <p>Account</p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="#" class="nav-link" onclick="document.getElementById('logout-form').submit()">
              <i class="nav-icon far fa-circle text-danger"></i>
              <p>Logout</p>
            </a>
          </li>
          <form action="{{ route('logout') }}" method="post" id="logout-form" style="display: none;">
            @csrf
            
          </form>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>