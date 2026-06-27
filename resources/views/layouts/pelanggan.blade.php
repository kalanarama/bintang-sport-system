<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bintang Sport Center')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Instrument Sans', sans-serif;
        }
        :root {
            --primary: #0151D5;
            --secondary: #03D1FE;
            --tertiary: #0040A2;
            --neutral: #848484;
            --black: #000000;
            --navy: #001848;
            --white: #ffffff;
            --neutral2: #F6FAFF;
        }
        .navbar {
            background-color: #ffffff !important;
            border-bottom: 1px solid #e0e0e0;
            padding: 12px 0;
        }
        .navbar-nav .nav-link {
            color: #000000 !important;
            font-weight: 500;
            font-size: 14px;
            padding: 0 16px !important;
        }
        .navbar-nav .nav-link:hover {
            color: #0151D5 !important;
        }
        .navbar-nav .nav-link.active {
            color: #0151D5 !important;
            font-weight: 600;
        }
        .btn-booking {
            background-color: #0151D5;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 14px;
        }
        .btn-booking:hover {
            background-color: #0040A2;
            color: #ffffff;
        }
        body {
            background-color: #F6FAFF;
        }
    </style>
    @stack('styles')
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            {{-- Logo --}}
            <a class="navbar-brand" href="{{ route('jadwal.public') }}">
                <img src="{{ asset('img/logo.png') }}" alt="Bintang Sport Center" height="40">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('jadwal.public') ? 'active' : '' }}" 
                           href="{{ route('jadwal.public') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('jadwal.public') ? 'active' : '' }}" 
                           href="{{ route('jadwal.public') }}">Lapangan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('jadwal.public') ? 'active' : '' }}" 
                           href="{{ route('jadwal.public') }}">Jadwal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('booking.cek') ? 'active' : '' }}" 
                           href="{{ route('booking.cek') }}">Riwayat</a>
                    </li>
                </ul>
            </div>

            {{-- Tombol Booking --}}
            <a href="{{ route('booking.create') }}" class="btn btn-booking">
                Booking Sekarang
            </a>
        </div>
    </nav>

    {{-- Flash Message --}}
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    {{-- Content --}}
    @yield('content')

    {{-- Footer --}}
    <footer style="background-color: #001848;" class="text-white text-center py-4 mt-5">
        <p class="mb-0" style="font-size: 14px;">© 2026 Bintang Sport Center. All rights reserved.</p>
    </footer>

    @stack('scripts')
</body>
</html>