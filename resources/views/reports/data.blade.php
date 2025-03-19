<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th style="width: 5%">No.</th>
            <th style="width: 15%"></th>
            @foreach($months as $month)
                <th class="text-center">
                    {{ ucfirst($month->name_month) }}<br>(Rp)
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach(['pemasukan', 'pengeluaran', 'stock', 'aset'] as $type)
            @if(isset($ledgersByCategory[$type]))
                <tr>
                    <th colspan="{{ count($months) + 2 }}" class="text-uppercase">{{ $type }}</th>
                </tr>

                @php $counter = 1; @endphp
                @foreach($ledgersByCategory[$type]['categories'] as $categoryId => $category)
                    <tr>
                        <td>{{ $counter++ }}</td>
                        <td>{{ $category['name'] }}</td>
                        @foreach($months as $month)
                            <td class="text-right">
                                @if($category['monthly_values'][$month->id] > 0)
                                    Rp{{ number_format($category['monthly_values'][$month->id], 0, ',', '.') }}
                                @elseif($category['monthly_values'][$month->id] < 0)
                                    -Rp{{ number_format(abs($category['monthly_values'][$month->id]), 0, ',', '.') }}
                                @else
                                    Rp0
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach

                <!-- Row for totals -->
                <tr class="font-weight-bold">
                    <td colspan="2">Total {{ ucfirst($type) }}</td>
                    @foreach($months as $month)
                        <td class="text-right">
                            @if($ledgersByCategory[$type]['monthly_totals'][$month->id] > 0)
                                Rp{{ number_format($ledgersByCategory[$type]['monthly_totals'][$month->id], 0, ',', '.') }}
                            @elseif($ledgersByCategory[$type]['monthly_totals'][$month->id] < 0)
                                -Rp{{ number_format(abs($ledgersByCategory[$type]['monthly_totals'][$month->id]), 0, ',', '.') }}
                            @else
                                Rp0
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
