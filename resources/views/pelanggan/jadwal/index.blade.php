<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Lapangan - Bintang Sport Center</title>

    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7fa;
            color: #10275b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* NAVBAR */
        .navbar {
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            padding: 14px 60px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .navbar .logo img {
            height: 44px;
            width: auto;
            display: block;
        }

        .navbar nav {
            display: flex;
            gap: 40px;
        }

        .navbar nav a {
            font-weight: 600;
            font-size: 15px;
            color: #475569;
        }

        .navbar nav a:hover,
        .navbar nav a.active {
            color: #0052cc;
        }

        .navbar .btn-nav {
            background: #0756d9;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            padding: 10px 22px;
            border-radius: 24px;
            white-space: nowrap;
            transition: 0.2s;
            box-shadow: 0 5px 11px rgba(7, 86, 217, 0.28);
        }

        .navbar .btn-nav:hover {
            background: #0348b9;
        }

        /* Content */
        .container{
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 115px 55px 40px;
            flex: 1;
        }

        .section-title{
            font-size: 31px;
            font-weight: 700;
            color: #071f56;
            margin-bottom: 5px;
        }

        .section-subtitle{
            font-size: 16px;
            color: #7b8494;
            font-weight: 600;
            margin-bottom: 45px;
        }

        .kategori{
            display:inline-flex;
            align-items:center;
            gap:8px;
            background:#eef5ff;
            color:#0d5be1;
            padding:8px 18px;
            border-radius:50px;
            font-size:14px;
            font-weight:600;
            margin-bottom:18px;
        }

        .card-body{
            padding:25px;
            text-align:left;
        }

        .card-body h2{
            font-size:28px;
            margin-bottom:15px;
        }

        .price{
            font-size:22px;
            color:#0d5be1;
            font-weight:700;
            margin-bottom:25px;
        }

        .btn-detail{
            display:block;
            width:100%;
            text-align:center;
            padding:15px;
            border-radius:12px;
            background:#0d5be1;
            color:white;
            font-weight:700;
        }

        /* filter */
        .filter-btn i{
            margin-right:8px;
        }

        .filter-btn:hover,
        .filter-btn.active{
            background:#0d5be1;
            color:white;
            border-color:#0d5be1;
        }

        .kategori-wrapper{
            display:flex;
            gap:15px;
            margin:35px 0 45px;
            flex-wrap:wrap;
        }

        .kategori-btn{
            border:none;
            background:#edf4ff;
            color:#0d5be1;
            padding:12px 28px;
            border-radius:40px;
            font-weight:600;
            cursor:pointer;
            transition:.3s;
        }

        .kategori-btn.active,
        .kategori-btn:hover{
            background:#0d5be1;
            color:white;
        }

        /* Grid */
        .lapangan-grid{
            display:grid;
            grid-template-columns:repeat(auto-fill,minmax(320px,1fr));
            gap:35px;
        }

        /* Card */
        .card{
            background:white;
            border-radius:20px;
            overflow:hidden;
            box-shadow:0 8px 25px rgba(0,0,0,.08);
            transition:.3s;
        }

        .card:hover{
            transform:translateY(-6px);
        }

        .card-image{
            height:220px;
            overflow:hidden;
            position:relative;
        }

        .card-image img{
            width:100%;
            height:100%;
            object-fit:cover;
        }

        .card-body{
            padding:24px;
            text-align:center;
        }

        .card-body h2{
            font-size:34px;
            margin-bottom:12px;
        }

        .price{
            color:#0d5be1;
            font-size:23px;
            font-weight:700;
            margin-bottom:22px;
        }

        .price i{
            margin-right:8px;
        }

        .btn-detail{
            display:inline-block;
            background:#0d5be1;
            color:white;
            padding:14px 40px;
            border-radius:12px;
            font-weight:700;
            transition:.3s;
        }

        .btn-detail:hover{
            background:#0047bd;
        }


        .card.nonaktif{
            opacity:.65;
            pointer-events:none;
        }

        .overlay-nonaktif{
            position:absolute;
            inset:0;
            background:rgba(255,255,255,.55);
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .overlay-nonaktif span{
            font-size:52px;
            font-weight:900;
            color:#ff4d4f;
        }

        /* Footer */
        .footer {
            margin-top: 90px;
            background: #031d55;
            color: white;
        }

        .footer-main {
            max-width: 1200px;
            margin: auto;
            padding: 55px 60px;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 60px;
        }

        .footer-logo {
            width: 190px;
            margin-bottom: 18px;
        }

        .footer-desc {
            max-width: 290px;
            color: #b8c5e2;
            font-size: 13px;
            line-height: 1.4;
        }

        .footer h3 {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .footer ul {
            list-style: none;
        }

        .footer li {
            margin-bottom: 14px;
        }

        .footer li a {
            color: #b8c5e2;
            font-size: 13px;
        }

        .footer li a:hover {
            color: white;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.15);
            text-align: center;
            padding: 18px;
            color: #b8c5e2;
            font-size: 12px;
        }

        @media(max-width:900px){

            .footer-main{
                grid-template-columns:1fr;
            }

            .navbar{
                padding:15px 20px;
            }

            .navbar nav{
                display:none;
            }

            .section-title{
                font-size:34px;
            }

        }

    </style>
</head>

<body>

    <header class="navbar">
        <a href="{{ url('/beranda') }}" class="logo">
            <img src="{{ asset('img/logo.png') }}" alt="Bintang Sport">
        </a>

        <nav>
            <a href="{{ url('/beranda') }}">Beranda</a>
            <a href="{{ url('/lapangan') }}">Lapangan</a>
            <a href="{{ route('jadwal.public') }}" class="active" >Jadwal</a>
            <a href="{{ route('booking.cek') }}">Riwayat</a>
        </nav>

        <a href="{{ route('jadwal.public') }}" class="btn-nav">
            Booking Sekarang
        </a>
    </header>

    <div class="container">

        <h1 class="section-title">
            Jadwal Lapangan
        </h1>

        <p class="section-subtitle">
            Pilih lapangan favoritmu dan lihat jadwal yang tersedia.
        </p>

        <div class="kategori-wrapper">

            <button class="kategori-btn active" data-filter="all">
                Semua
            </button>

            @foreach($lapangans->pluck('jenis_lapangan')->unique() as $jenis)

                <button
                    class="kategori-btn"
                    data-filter="{{ strtolower($jenis) }}">

                    {{ $jenis }}

                </button>

            @endforeach

        </div>

            <div class="lapangan-grid">

            @forelse($lapangans as $item)

            <div class="card {{ $item->status_lapangan == 'nonaktif' ? 'nonaktif' : '' }}"
                data-category="{{ strtolower($item->jenis_lapangan) }}">

                <div class="card-image">

                    <img src="{{ asset($item->foto_lapangan) }}"
                            alt="{{ $item->nama_lapangan }}">

                    @if($item->status_lapangan=='nonaktif')
                    <div class="overlay-nonaktif">
                        <span>NON AKTIF</span>
                    </div>
                    @endif

                </div>

                <div class="card-body">

                    <!-- Badge kategori -->
                    <div class="kategori">

                        @switch($item->jenis_lapangan)

                            @case('Futsal')
                            <i class="fa-regular fa-futbol"></i>
                            @break

                            @case('Badminton')
                            <i class="fa-solid fa-table-tennis-paddle-ball"></i>
                            @break

                            @case('Basket')
                            <i class="fa-solid fa-basketball"></i>
                            @break

                        @endswitch

                        {{ $item->jenis_lapangan }}

                    </div>

                    <h2>{{ $item->nama_lapangan }}</h2>

                    <div class="price">
                        Rp {{ number_format($item->harga_lapangan,0,',','.') }}
                        <small>/Jam</small>
                    </div>

                    @if($item->status_lapangan=='aktif')

                    <a href="#" class="btn-detail">
                        Lihat Jadwal
                    </a>

                    @else

                    <button class="btn-detail" disabled
                        style="background:#9ca3af;">
                        Tidak Tersedia
                    </button>

                    @endif

                </div>
            </div>

            @empty

            <h3>Tidak ada data lapangan.</h3>

            @endforelse
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-main">
            <div>
                <img src="{{ asset('img/logo.png') }}" alt="Bintang Sport" class="footer-logo">

                <p class="footer-desc">
                    Lapangan olahraga dengan fasilitas dan kenyamanan yang berkualitas
                    untuk mendukung gaya hidup sehat Anda.
                </p>
            </div>

            <div>
                <h3>Menu Cepat</h3>
                <ul>
                    <li><a href="{{ url('/beranda') }}">Beranda</a></li>
                    <li><a href="{{ url('/lapangan') }}">Lapangan</a></li>
                    <li><a href="{{ route('jadwal.public') }}">Jadwal</a></li>
                    <li><a href="{{ route('booking.cek') }}">Booking Saya</a></li>
                </ul>
            </div>

            <div>
                <h3>Bantuan</h3>
                <ul>
                    <li><a href="{{ route('kebijakan') }}">Kebijakan Privasi</a></li>
                    <li><a href="{{ route('syarat') }}">Syarat & Ketentuan</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            © 2026 Bintang Sport Center. All rights reserved.
        </div>
    </footer>

<!-- Javascript Filter -->
    <script>

        const kategori=document.querySelectorAll(".kategori-btn");
        const card=document.querySelectorAll(".card");

        kategori.forEach(btn=>{

            btn.onclick=function(){

                kategori.forEach(x=>x.classList.remove("active"));
                this.classList.add("active");

                let filter=this.dataset.filter;

                card.forEach(item=>{

                    if(filter=="all"){
                        item.style.display="block";
                    }else{

                        item.style.display=
                        item.dataset.category==filter
                        ?"block":"none";
                    }
                });
            }
        });
    </script>
</body>
</html>