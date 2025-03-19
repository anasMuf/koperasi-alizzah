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

        table .total,.text-end {
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
        <h1>Data <span>Jurnal Transaksi</span></h1>
        <h2>Kopersai Al-Izzah</h2>
        <p>Periode {{ $periode }}</p>
        {{-- <pre>
            @php
                var_dump($params['type_transaksi'])
            @endphp
        </pre> --}}
        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">Tanggal</th>
                    <th>Keterangan</th>
                    <th>Tipe Transaksi</th>
                    @if (empty($params['type_transaksi']) || $params['type_transaksi'] === 'pemasukan')
                    <th>Debit</th>
                    @endif
                    @if (empty($params['type_transaksi']) || $params['type_transaksi'] === 'pengeluaran')
                    <th>Kredit</th>
                    @endif
                    @if ($params['type_transaksi'] !== 'pemasukan' && $params['type_transaksi'] !== 'pengeluaran')
                    <th>Saldo</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php
                    $debit = 0;
                    $credit = 0;
                @endphp
                @foreach ($data as $key => $item)
                @php
                    $debit+=$item->debit;
                    $credit+=$item->credit;
                @endphp
                <tr style="{{ ($key === 23) ? 'page-break-after: auto' : '' }}">
                    <td>{{ \Carbon\Carbon::parse($item->trx_date)->isoFormat('DD MMM YYYY') }}</td>
                    <td class="keterangan">
                        @php
                            if($item->refrence === 'transaksi umum'){
                                echo $item->description;
                            }elseif($item->refrence === 'SALDO'){
                                echo 'Penambahan Saldo';
                            }else{
                                if(ctype_alpha(substr($item->refrence, 0, 1))){
                                    $prefix = substr($item->refrence, 0, 2);
                                    if($prefix === 'PN' || $prefix === 'PS'){
                                        $textArr = [];
                                        foreach (\App\Models\PurchaseDetail::where('invoice',$item->refrence)->with('product_variant.product')->get() as $value) {
                                            $textArr[] = $value->product_variant->product->name.' - '.$value->product_variant->name;
                                        }
                                        echo 'Pembelian: '.implode(',',$textArr);
                                    }elseif($prefix === 'OR'){
                                        $textArr = [];
                                        foreach (\App\Models\OrderDetail::where('invoice',$item->refrence)->with('product_variant.product')->get() as $value) {
                                            $textArr[] = $value->product_variant->product->name.' - '.$value->product_variant->name;
                                        }
                                        echo 'Penjualan: '.implode(',',$textArr);
                                    }
                                }else{
                                    if($item->description === 'bayar hutang'){
                                        $textArr = [];
                                        foreach (\App\Models\PurchasePayment::where('id',$item->refrence)->with(['purchase.vendor'])->get() as $value) {
                                            $textArr[] = $value->purchase->vendor->name;
                                        }
                                        echo 'Bayar hutang ke: '.implode(',',$textArr);
                                    }elseif($item->description === 'piutang anggota'){
                                        $textArr = [];
                                        foreach (\App\Models\ReceivablesMember::where('id',$item->refrence)->with(['member'])->get() as $value) {
                                            $textArr[] = $value->member->name;
                                        }
                                        echo 'Piutang Anggota: '.implode(',',$textArr);
                                    }elseif($item->description === 'bayar piutang'){
                                        $textArr = [];
                                        foreach (\App\Models\ReceivablesMemberPayment::where('id',$item->refrence)->with(['receivables_member.member'])->get() as $value) {
                                            $textArr[] = $value->receivables_member->member->name;
                                        }
                                        echo 'Bayar Piutang: '.implode(',',$textArr);
                                    }
                                }
                            }
                        @endphp
                    </td>
                    <td class="tipe">{{ ucfirst($item->type) }}</td>
                    @if (empty($params['type_transaksi']) || $params['type_transaksi'] === 'pemasukan')
                    <td class="total">{{ $item->debit > 0 ? $item->debit : '' }}</td>
                    @endif
                    @if (empty($params['type_transaksi']) || $params['type_transaksi'] === 'pengeluaran')
                    <td class="total min">{{ $item->credit > 0 ? $item->credit : '' }}</td>
                    @endif
                    @if ($params['type_transaksi'] !== 'pemasukan' && $params['type_transaksi'] !== 'pengeluaran')
                    <td class="total {{ $item->final < 0 ? 'min' : '' }}">{{ $item->final }}</td>
                    @endif
                </tr>
                @endforeach
                <tr>
                    <th style="text-align: end" colspan="3">TOTAL</th>
                    @if (empty($params['type_transaksi']) || $params['type_transaksi'] === 'pemasukan')
                    <td class="{{ ($debit == 0) ? 'text-end' : 'total' }}">{{ ($debit == 0) ? '-' : $debit }}</td>
                    @endif
                    @if (empty($params['type_transaksi']) || $params['type_transaksi'] === 'pengeluaran')
                    <td class="{{ ($credit == 0) ? 'text-end' : 'total' }}">{{ ($credit == 0) ? '-' : $credit }}</td>
                    @endif
                    @if ($params['type_transaksi'] !== 'pemasukan' && $params['type_transaksi'] !== 'pengeluaran')
                    <td class="{{ ($debit == 0 || $credit == 0) ? 'text-end' : 'total' }}">{{ ($debit == 0 || $credit == 0) ? '-' : $total = $debit-$credit }}</td>
                    @endif
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
            element.innerHTML =  amount == 0 ? '' : formatRibu(amount)
        });

        // Array.from(document.getElementsByClassName('min')).forEach(element => {
        //     let amount = element.innerHTML
        //     element.innerHTML = amount != '' ? '('+amount+')' : ''
        // });

        function formatRibu(nominal){
            return new Intl.NumberFormat('id-ID').format(nominal)
        }

        window.print()
    </script>
</body>
</html>
