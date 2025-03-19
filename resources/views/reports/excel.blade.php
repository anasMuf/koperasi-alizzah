<table>
    <thead>
        <tr>
            <th>Keterangan</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Periode</td>
            <td>{{ $data['periode'] }}</td>
        </tr>
        <tr>
            <td><h5><strong>SALDO AWAL PERIODE</strong></h5></td>
            <td>{{ (int)$data['saldo_awal_periode'] }}</td>
        </tr>
        <tr>
            <td colspan="2"><h6><strong>A. Arus Kas Kegiatan Operasional</strong></h6></td>
        </tr>
        @if ($data['arus_kas_operasional']['penerimaan'])
        <tr>
            <td>Penerimaan</td>
            <td>{{ (int)$data['arus_kas_operasional']['penerimaan'] }}</td>
        </tr>
        @endif
        @if ($data['arus_kas_operasional']['piutang'])
        <tr>
            <td>Piutang</td>
            <td>{{ (int)$data['arus_kas_operasional']['piutang'] }}</td>
        </tr>
        @endif
        @if ($data['arus_kas_operasional']['pengeluaran'])
        <tr>
            <td>(Pengeluaran)</td>
            <td>{{ -1 * (int)$data['arus_kas_operasional']['pengeluaran'] }}</td>
        </tr>
        @endif
        @if ($data['arus_kas_operasional']['hutang'])
        <tr>
            <td>(Hutang)</td>
            <td>{{ -1 * (int)$data['arus_kas_operasional']['hutang'] }}</td>
        </tr>
        @endif
        <tr>
            <td>Total Operasional</td>
            <td>{{ (int)$data['arus_kas_operasional']['total_operasional'] }}</td>
        </tr>
        <tr>
            <td><h5><strong>PERGERAKAN KAS</strong></h5></td>
            <td>{{ (int)$data['pergerakan_kas'] }}</td>
        </tr>
        <tr>
            <td><h5><strong>SALDO AKHIR PERIODE</strong></h5></td>
            <td>{{ (int)$data['saldo_akhir_periode'] }}</td>
        </tr>
    </tbody>
</table>
