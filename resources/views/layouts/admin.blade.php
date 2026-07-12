<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <title>@yield('title', 'Admin') - Bintang Sport Center</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4ff;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .sidebar {
            width: 260px;
            background: white;
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 20px rgba(0,0,0,0.06);
            z-index: 100;
            transition: width 0.3s ease;
            overflow: hidden;
        }

        .sidebar.collapsed { width: 70px; }

        .sidebar-brand {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #f0f4ff;
            min-height: 80px;
        }

        .sidebar.collapsed .sidebar-brand {
            justify-content: center;
            padding: 20px 10px;
        }

        .sidebar-brand img {
            height: 40px;
            min-width: 40px;
        }

        .toggle-icon {
            margin-left: auto;
            cursor: pointer;
            color: #64748b;
            padding: 4px 8px;
            border-radius: 8px;
            transition: all 0.2s;
            min-width: 30px;
        }

        .toggle-icon:hover { color: #1565C0; background: #f0f4ff; }

        .sidebar.collapsed .toggle-icon {
            display: none;
        }

        .sidebar-menu {
            flex: 1;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 10px;
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            white-space: nowrap;
            position: relative;
        }

        .sidebar.collapsed .sidebar-item {
            justify-content: center;
            padding: 12px;
        }

        .sidebar-item:hover { background: #f0f4ff; color: #1565C0; }

       .sidebar-item.active {
            background: #e8f0fe;
            color: #1565C0;
            font-weight: 600;
            border-left: none;
        }

        .sidebar-item.active::before {
            content: '';
            position: absolute;
            left: -9px;
            top: 0;
            height: 100%;
            width: 4px;
            background: #1565C0;
            border-radius: 0 4px 4px 0;
        }

        .sidebar-item i {
            width: 20px;
            font-size: 16px;
            min-width: 20px;
        }

        .sidebar-item span { transition: opacity 0.2s ease; }

        .sidebar.collapsed .sidebar-item span {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar.collapsed .sidebar-item::after {
            content: attr(data-title);
            position: absolute;
            left: 70px;
            background: #1a3a8f;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 13px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
            z-index: 999;
        }

        .sidebar.collapsed .sidebar-item:hover::after { opacity: 1; }

        .sidebar-logout {
            padding: 16px 12px;
            border-top: 1px solid #f0f4ff;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 12px 16px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .sidebar.collapsed .btn-logout {
            justify-content: center;
            padding: 12px;
        }

        .sidebar.collapsed .btn-logout span {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .btn-logout:hover { background: #b91c1c; color: white; }

        .main-content {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
            overflow-x: hidden;
            min-width: 0; 
        }

        .main-content.collapsed { margin-left: 70px; }

        .topbar {
            background: white;
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 99;
            min-height: 80px;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .topbar-user .avatar {
            width: 38px; height: 38px;
            background: #1565C0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .topbar-user .info .name {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a2e;
        }

        .topbar-user .info .role {
            font-size: 12px;
            color: #64748b;
        }

        .page-content { padding: 32px; flex: 1; }

        .page-header { margin-bottom: 28px; }

        .page-header h1 {
            font-size: 24px;
            font-weight: 800;
            color: #1a1a2e;
            margin-bottom: 4px;
        }

        .page-header p {
            font-size: 14px;
            color: #64748b;
            margin: 0;
        }

    
        .card-custom {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            border: none;
            padding: 0;
            overflow: hidden;
        }

        .alert { border-radius: 10px; font-size: 14px; }

        .table th {
            font-size: 13px;
            font-weight: 600;
            color: #f3f7fc;
            background: #1565C0;
            border-bottom: 2px solid #f0f4ff;
            padding: 12px 16px;
        }

        .table td {
            font-size: 14px;
            color: #1515a7;
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f8faff;
        }
        .table td, .table th {
            border-right: 1px solid #e8f0fe !important;
            
        }

        .table thead th {
            border-right: none !important;
        }

        .table td:last-child, .table th:last-child {
            border-right: none !important;
        }

       .table td {
            font-size: 14px;
            color: #1a1a2e;
            padding: 8px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #dbdee4 !important;
        }
        .table tr:last-child td { border-bottom: none; }

        .badge-aktif {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 1.5px solid #2e7d32;
        }

        .badge-nonaktif {
            background: #f5f5f5;
            color: #757575;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 1.5px solid #757575;
        }

        .btn-edit {
            background: #e8f0fe;
            color: #1565C0;
            border: none;
            width: 34px; height: 34px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-edit:hover { background: #1565C0; color: white; }

        .btn-hapus {
            background: #fce4ec;
            color: #c62828;
            border: none;
            width: 34px; height: 34px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .btn-hapus:hover { background: #c62828; color: white; }

        .btn-primary-custom {
            background: linear-gradient(135deg, #1565C0, #1976D2);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(21,101,192,0.3);
            color: white;
        }

        .form-control, .form-select {
            border: 1.5px solid #e0e7ff;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 14px;
            background: #f8faff;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #1565C0;
            box-shadow: 0 0 0 4px rgba(21,101,192,0.1);
            background: white;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
        }

        .sidebar.collapsed .sidebar-brand img {
            display: none;
        }

        .sidebar.collapsed .toggle-icon {
            display: flex;
            margin-left: 0;
            justify-content: center;
            width: 100%;
        }

        .btn-detail {
            background: #fff3e0;
            color: #e65100;
            border: none;
            width: 34px; height: 34px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-detail:hover { background: #e65100; color: white; }

        .jadwal-table-wrap {
            scrollbar-width: none; 
        }

        .jadwal-table-wrap::-webkit-scrollbar {
            display: none; 
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('img/logo.png') }}" alt="Logo">
        <div class="toggle-icon" id="toggleSidebar">
            <i class="fas fa-table-columns" id="toggleIcon"></i>
        </div>
    </div>

    <div class="sidebar-menu">
        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            data-title="Dashboard">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.lapangan.index') }}"
            class="sidebar-item {{ request()->routeIs('admin.lapangan.*') ? 'active' : '' }}"
            data-title="Kelola Lapangan">
            <i class="fas fa-table-tennis-paddle-ball"></i>
            <span>Kelola Lapangan</span>
        </a>
        <a href="{{ route('admin.promo.index') }}"
            class="sidebar-item {{ request()->routeIs('admin.promo.*') ? 'active' : '' }}"
            data-title="Kelola Promo">
            <i class="fas fa-tag"></i>
            <span>Kelola Promo</span>
        </a>
        <a href="{{ route('admin.jadwal.index') }}"
            class="sidebar-item {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}"
            data-title="Jadwal Ketersediaan">
            <i class="fas fa-calendar-alt"></i>
            <span>Jadwal Ketersediaan</span>
        </a>
        <a href="{{ route('admin.laporan.index') }}"
            class="sidebar-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}"
            data-title="Laporan Rekapitulasi">
            <i class="fas fa-chart-bar"></i>
            <span>Laporan Rekapitulasi</span>
        </a>
    </div>

    <div class="sidebar-logout">
        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
            @csrf
            <button type="button" class="btn-logout" onclick="konfirmasiLogout()">
                <i class="fas fa-right-from-bracket"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>

<!-- MAIN -->
<div class="main-content" id="mainContent">
    <div class="topbar">
        <div class="topbar-user">
            <div class="info text-end">
                <div class="name">Admin Bintang Sport</div>
                <div class="role">Administrator</div>
            </div>
            <div class="avatar">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div style="display: flex; align-items: center; justify-content: flex-end; font-size: 13px; color: #64748b; font-weight: 500;">
            <i class="fas fa-calendar-alt" style="color: #1565C0; margin-right: 6px;"></i>
            <span id="topbarDate"></span>
        </div>
        @yield('content')
    </div>
</div>

<script>
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');

    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('collapsed');
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    });

    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        sidebar.classList.add('collapsed');
        mainContent.classList.add('collapsed');
    }
    function konfirmasiLogout() {
        Swal.fire({
            title: 'Keluar dari sistem?',
            text: 'Apakah Anda yakin ingin logout?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal',
            borderRadius: '16px',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logoutForm').submit();
            }
        });
    }
</script>

<script>
function updateDateTime() {
    const now = new Date();
    const bulan = ['Januari','Februari','Maret','April','Mei','Juni',
                   'Juli','Agustus','September','Oktober','November','Desember'];
    const tgl = now.getDate();
    const bln = bulan[now.getMonth()].toUpperCase();
    const thn = now.getFullYear();
    const jam = String(now.getHours()).padStart(2,'0');
    const mnt = String(now.getMinutes()).padStart(2,'0');
    const dtk = String(now.getSeconds()).padStart(2,'0');
    document.getElementById('topbarDate').textContent = `${tgl} ${bln} ${thn} ${jam}:${mnt}:${dtk}`;
}
updateDateTime();
setInterval(updateDateTime, 1000);
</script>
@stack('scripts')
</body>
</html>