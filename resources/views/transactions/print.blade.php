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
        table .tipe {
            text-align: center;
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
        <h1>Data <span>Transaksi Umum</span></h1>
        <h2>Kopersai Al-Izzah</h2>
        <p>Periode {{ $periode }}</p>
        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">Tanggal</th>
                    <th>Keterangan</th>
                    <th>Tipe</th>
                    <th>Nominal</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $debit = 0;
                    $credit = 0;
                @endphp
                @foreach ($data as $key => $item)
                @php
                    $debit += $item->debit;
                    $credit += $item->credit;
                @endphp
                <tr style="{{ ($key === 23) ? 'page-break-after: auto' : '' }}">
                    <td>{{ \Carbon\Carbon::parse($item->trx_date)->isoFormat('DD MMM YYYY') }}</td>
                    <td class="keterangan">{{ $item->description }}</td>
                    <td class="tipe">{{ ucfirst($item->type) }}</td>
                    @if ($item->type == 'pemasukan')
                    <td class="total">{{ $item->debit }}</td>
                    @else
                    <td class="total min">{{ $item->credit }}</td>
                    @endif
                </tr>
                @endforeach
                <tr>
                    <th style="text-align: end" colspan="3">TOTAL</th>
                    <td class="total">{{ $total = $debit-$credit }}</td>
                </tr>
            </tbody>
            <tfoot>
            </tfoot>
        </table>
    </div>
    <script>
        const totalEl = document.getElementsByClassName('total')

        Array.from(totalEl).forEach(element => {
            let amount = element.innerHTML
            element.innerHTML = formatRibu(amount)
        });

        Array.from(document.getElementsByClassName('min')).forEach(element => {
            let amount = element.innerHTML
            element.innerHTML = '('+amount+')'
        });

        function formatRibu(nominal){
            return new Intl.NumberFormat('id-ID').format(nominal)
        }

        window.print()
    </script>
</body>
</html>
