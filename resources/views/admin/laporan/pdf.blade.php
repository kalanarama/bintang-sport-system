<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:11px;
            color:#334155;
            margin:25px;
        }

        h1,h2,h3,h4,h5{
            margin:0;
        }

        /* =========================
           HEADER
        ========================== */

        .header{
            width:100%;
            margin-bottom:25px;
            border-bottom:3px solid #2563eb;
            padding-bottom:12px;
        }

        .header-table{
            width:100%;
            border-collapse:collapse;
        }

        .header-table td{
            border:none;
            vertical-align:middle;
        }

        .logo{
            width:70px;
        }

        .title{
            text-align:center;
        }

        .title h2{
            font-size:22px;
            color:#1e40af;
            margin-bottom:5px;
        }

        .title p{
            font-size:11px;
            color:#64748b;
        }

        .tanggal{
            text-align:right;
            font-size:10px;
            color:#64748b;
        }

        /* =========================
           INFORMASI PERIODE
        ========================== */

        .periode{
            margin-top:18px;
            margin-bottom:20px;
        }

        .periode table{
            width:100%;
            border-collapse:collapse;
        }

        .periode td{
            border:none;
            padding:3px 0;
            font-size:11px;
        }

        .label{
            width:120px;
            font-weight:bold;
            color:#1e3a8a;
        }

        /* =========================
           RINGKASAN
        ========================== */

        .summary{
            width:100%;
            border-collapse:collapse;
            margin-bottom:25px;
        }

        .summary td{
            width:33.33%;
            border:1px solid #dbeafe;
            background:#eff6ff;
            padding:12px;
        }

        .summary-title{
            font-size:10px;
            color:#64748b;
            margin-bottom:6px;
        }

        .summary-value{
            font-size:18px;
            font-weight:bold;
            color:#1d4ed8;
        }

        /* =========================
           TABEL
        ========================== */

        .table{
            width:100%;
            border-collapse:collapse;
        }

        .table thead th{
            background:#2563eb;
            color:#ffffff;
            border:1px solid #2563eb;
            padding:8px;
            font-size:11px;
            text-align:center;
        }

        .table tbody td{
            border:1px solid #d1d5db;
            padding:8px;
            font-size:10px;
        }

        .text-center{
            text-align:center;
        }

        .text-right{
            text-align:right;
        }

        .status-selesai{
            color:#16a34a;
            font-weight:bold;
        }

        .status-menunggu{
            color:#d97706;
            font-weight:bold;
        }

        .status-batal{
            color:#dc2626;
            font-weight:bold;
        }

        .footer{
            margin-top:25px;
            text-align:center;
            color:#94a3b8;
            font-size:10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td width="18%">
                    {{-- Logo --}}
                    {{-- Aktifkan jika file logo ada --}}
                    {{-- <img src="{{ public_path('img/logo.png') }}" class="logo"> --}}
                </td>

                <td class="title">
                    <h2>BINTANG SPORT CENTER</h2>

                    <p>Laporan Rekapitulasi Booking Lapangan</p>
                </td>

                <td width="22%" class="tanggal">

                    Dicetak:
                    <br>

                    {{ now()->format('d M Y') }}

                    <br>

                    {{ now()->format('H:i') }} WIB
                </td>
            </tr>
        </table>
    </div>

    <div class="periode">
        <table>
            <tr>
                <td class="label">Periode</td>
                <td>

                    {{ \Carbon\Carbon::parse($tanggalAwal)->format('d M Y') }}
                    -
                    {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}
                </td>
            </tr>
        </table>
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="summary-title">
                    TOTAL BOOKING
                </div>
                <div class="summary-value">
                    {{ $totalBooking }}
                </div>
            </td>

            <td>
                <div class="summary-title">
                    TOTAL PENDAPATAN
                </div>
                <div class="summary-value">
                    Rp {{ number_format($totalPendapatan,0,',','.') }}
                </div>
            </td>

            <td>
                <div class="summary-title">
                    TOTAL PELANGGAN
                </div>
                <div class="summary-value">
                    {{ $totalPelanggan }}
                </div>
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th width="6%">No</th>
                <th width="18%">Kode Booking</th>
                <th width="13%">Tanggal</th>
                <th>Lapangan</th>
                <th>Pelanggan</th>
                <th width="18%">Total Bayar</th>
                <th width="12%">Status</th>
            </tr>
        </thead>
        <tbody>

        @forelse($bookings as $booking)

    <tr>

        <td class="text-center">
            {{ $loop->iteration }}
        </td>

        <td>
            {{ $booking->kode_booking }}
        </td>

        <td class="text-center">
            {{ $booking->created_at->format('d/m/Y') }}
        </td>

        <td>
            {{ $booking->jadwal->lapangan->nama_lapangan }}
        </td>

        <td>
            {{ $booking->pelanggan->nama_pelanggan }}
        </td>

        <td class="text-right">
            Rp {{ number_format($booking->total_bayar,0,',','.') }}
        </td>

        <td class="text-center">

            @if($booking->status == 'Berhasil')

                <span class="status-selesai">
                    Selesai
                </span>

            @elseif($booking->status == 'Tertunda')

                <span class="status-menunggu">
                    Menunggu
                </span>

            @else

                <span class="status-batal">
                    Batal
                </span>

            @endif

        </td>
    </tr>

    @empty

    <tr>
        <td colspan="7" class="text-center" style="padding:25px;">
            Tidak ada data booking pada periode ini.
        </td>
    </tr>

    @endforelse

        </tbody>
    </table>

    <div class="footer">
        <hr style="margin:25px 0 10px; border:none; border-top:1px solid #d1d5db;">
        <strong>Bintang Sport Center</strong>
        <br>
        Sistem Booking Lapangan Olahraga
        <br><br>
        Laporan ini dibuat secara otomatis oleh sistem.
    </div>

</body>

</html>

