<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Dokumen Kerjasama - STIKes Panti Waluya Malang</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #059669;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0 0 4px 0;
            color: #064e3b;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
            font-size: 10px;
            color: #64748b;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 10px;
            color: #475569;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-aktif { background-color: #d1fae5; color: #065f46; }
        .badge-akan { background-color: #fef3c7; color: #92400e; }
        .badge-kedaluwarsa { background-color: #ffe4e6; color: #9f1239; }
        .badge-perpanjangan { background-color: #e0f2fe; color: #075985; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background-color: #059669; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
            Cetak / Simpan ke PDF
        </button>
    </div>

    <div class="header">
        <h1>STIKes Panti Waluya Malang</h1>
        <p>REKAPITULASI DOKUMEN KERJA SAMA (MoU, MoA, dan IA)</p>
        <p style="font-size: 9px; color: #94a3b8;">Laporan & Visualisasi Data Kerjasama Institusi & Program Studi</p>
    </div>

    <div class="meta-info">
        <div>Dicetak Pada: <strong>{{ $dateGenerated }}</strong></div>
        <div>Total Dokumen Terdaftar: <strong>{{ $cooperations->count() }} Dokumen</strong></div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 22%;">Nama Instansi Mitra</th>
                <th style="width: 8%;">Jenis</th>
                <th style="width: 10%;">Tingkat</th>
                <th style="width: 16%;">Nomor Surat</th>
                <th style="width: 24%;">Judul / Ruang Lingkup</th>
                <th style="width: 10%;">Masa Berlaku</th>
                <th style="width: 6%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cooperations as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $item->partner_name }}</strong></td>
                    <td>{{ $item->document_type }}</td>
                    <td>{{ $item->level }}</td>
                    <td style="font-family: monospace; font-size: 10px;">{{ $item->document_number }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->start_date->format('d/m/Y') }} s/d {{ $item->end_date->format('d/m/Y') }}</td>
                    <td>
                        @if($item->computed_status === 'Aktif')
                            <span class="badge badge-aktif">Aktif</span>
                        @elseif($item->computed_status === 'Akan Berakhir')
                            <span class="badge badge-akan">Akan Berakhir</span>
                        @elseif($item->computed_status === 'Kedaluwarsa')
                            <span class="badge badge-kedaluwarsa">Kedaluwarsa</span>
                        @else
                            <span class="badge badge-perpanjangan">Perpanjangan</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
