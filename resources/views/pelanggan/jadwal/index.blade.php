<!DOCTYPE html>
<html>
<head>
    <title>Daftar Jadwal</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Daftar Jadwal Lapangan</h1>
    @if($jadwals->count())
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lapangan</th>
                    <th>Tanggal</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jadwals as $key => $jadwal)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $jadwal->lapangan->nama_lapangan ?? '-' }}</td>
                    <td>{{ $jadwal->tanggal_jadwal }}</td>
                    <td>{{ $jadwal->jam_mulai }}</td>
                    <td>{{ $jadwal->jam_selesai }}</td>
                    <td>{{ $jadwal->status_jadwal }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Belum ada jadwal.</p>
    @endif
</body>
</html>