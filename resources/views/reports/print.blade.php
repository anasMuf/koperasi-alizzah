<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        @media print {
            html, body {
                width: 210mm;
                height: 297mm;
            }
            /* ... the rest of the rules ... */
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .report {
            /* max-width: 800px; */
            /* margin: 20px auto; */
            padding: 20px;
            /* border: 1px solid #ccc; */
            background-color: #fff;
        }

        h1 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 5px;
        }

        h1 span {
            color: #0095da;
        }

        h2 {
            text-align: center;
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        p {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table tr td,
        table tr th{
            border-top: 1px solid #cbcbcb;
            border-bottom: 1px solid #cbcbcb;
        }
        table thead tr th{
            padding: 20px 0;
        }

        table td {
            padding: 8px;
            font-size: 14px;
            color: #333;
        }

        table .keterangan {
            text-align: left;
        }

        table .total {
            text-align: right;
        }

        table .section {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: left;
        }

        table tr td:last-child {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="report">
        <h1>Laporan <span>Arus Kas</span></h1>
        <h2>Kopersai Al-Izzah</h2>
        <p>Periode {{ $data['periode'] }}</p>
        <table>
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th class="keterangan"><h5><strong>SALDO AWAL PERIODE</strong></h5></th>
                    <th class="total">{{ (int)$data['saldo_awal_periode'] }}</th>
                </tr>
                <tr>
                    <th class="section" colspan="2"><h6><strong>A. Arus Kas Kegiatan Operasional</strong></h6></th>
                </tr>
                @if ($data['arus_kas_operasional']['penerimaan'])
                <tr>
                    <td class="keterangan">Penerimaan</td>
                    <td class="total">{{ (int)$data['arus_kas_operasional']['penerimaan'] }}</td>
                </tr>
                @endif
                @if ($data['arus_kas_operasional']['piutang'])
                <tr>
                    <td class="keterangan">Piutang</td>
                    <td class="total">{{ (int)$data['arus_kas_operasional']['piutang'] }}</td>
                </tr>
                @endif
                @if ($data['arus_kas_operasional']['pengeluaran'])
                <tr>
                    <td class="keterangan">(Pengeluaran)</td>
                    <td class="total">{{ -1 * (int)$data['arus_kas_operasional']['pengeluaran'] }}</td>
                </tr>
                @endif
                @if ($data['arus_kas_operasional']['hutang'])
                <tr>
                    <td class="keterangan">(Hutang)</td>
                    <td class="total">{{ -1 * (int)$data['arus_kas_operasional']['hutang'] }}</td>
                </tr>
                @endif
                <tr>
                    <td class="keterangan">Total Operasional</td>
                    <td class="total">{{ (int)$data['arus_kas_operasional']['total_operasional'] }}</td>
                </tr>
                <tr>
                    <th class="keterangan"><h5><strong>PERGERAKAN KAS</strong></h5></th>
                    <th class="total">{{ (int)$data['pergerakan_kas'] }}</th>
                </tr>
                <tr>
                    <th class="keterangan"><h5><strong>SALDO AKHIR PERIODE</strong></h5></th>
                    <th class="total">{{ (int)$data['saldo_akhir_periode'] }}</th>
                </tr>
            </tbody>
        </table>
    </div>
    <script>
        const totalEl = document.getElementsByClassName('total')

        Array.from(totalEl).forEach(element => {
            let amount = element.innerHTML
            element.innerHTML = formatRibu(amount)
        });

        function formatRibu(nominal){
            return new Intl.NumberFormat('id-ID').format(nominal)
        }

        window.print()
    </script>
</body>
</html>
