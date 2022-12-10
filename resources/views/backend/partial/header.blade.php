<nav class="main-header navbar navbar-expand navbar-light bg-orange">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
     

      <!-- Messages Dropdown Menu -->
    
     
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">{{ $riwayat->count() }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">Riwayat Pemgambilan Tabung</span>
          <div class="dropdown-divider"></div>
          @foreach ($riwayat as $row)
          <a href="{{ route('transaksi.index') }}" class="dropdown-item">
            <i class="fas fa-id-card mr-2"></i> {{ $row->pelanggan->nama_pelanggan ?? ''}}
            <span class="float-right text-muted text-sm">{{ $row->tanggal_ambil}}</span>
          </a>
          @endforeach
          
         
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    
    </ul>
  </nav>