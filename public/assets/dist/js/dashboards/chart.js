const getData = async function(param) {
    try {
        const response = await $.ajax({
            type: "GET",
            url: url,
            data: param,
            dataType: "json"
        });

        if (response.success) {
            return response.data;
        } else {
            console.log('Gagal mengambil data dari server.');
            return null;
        }
    } catch (error) {
        console.log('Terjadi kesalahan:', error);
        return null;
    }
}

async function penjualanPiutangChart() {
    var penjualanPiutang = $('#penjualanPiutang').get(0).getContext('2d');

    const data = await getData({ data: 'penjualanPiutang' });
    if (!data) return;

    var data_penjualanPiutang = {
        labels: [
            'Penjualan',
            'Piutang',
        ],
        datasets: [
            {
                data: [data.penjualan, data.piutang],
                backgroundColor: ['#00a65a', '#f56954'],
            }
        ]
    };


    var options_penjualanPiutang = {
        maintainAspectRatio: false,
        responsive: true,
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    var datasetIndex = tooltipItem.datasetIndex;
                    var index = tooltipItem.index;
                    var label = data.labels[index];
                    var value = data.datasets[datasetIndex].data[index];

                    return label+': '+formatRibu(value);
                }
            }
        },
        plugins: {
            datalabels: {
                formatter: (value, context) => {
                    let total = context.dataset.data.reduce((a, b) => parseInt(a) + parseInt(b), 0);
                    let percentage = ((value / total) * 100).toFixed(2);
                    return `${percentage}%`;
                },
                color: '#fff',
                font: {
                    weight: 'bold',
                    size: 12
                }
            }
        }
    };

    new Chart(penjualanPiutang, {
        type: 'pie',
        data: data_penjualanPiutang,
        options: options_penjualanPiutang,
        plugins: [ChartDataLabels]
    });
}
async function posisiSaldoChart() {
    var posisiSaldo = $('#posisiSaldo').get(0).getContext('2d');

    const data = await getData({ data: 'posisiSaldo' });
    if (!data) return;

    var saldoValues = data.map(value => value.saldo)
    var maxSaldo = Math.max(...saldoValues);
    var maxY = Math.ceil(maxSaldo) + Math.ceil(maxSaldo)*20/100;

    var data_posisiSaldo = {
        labels: data.map(value => value.month_name),
        datasets: [
            {
                label               : 'Saldo',
                borderColor         : 'rgba(60,141,188,0.8)',
                pointBackgroundColor: 'rgba(60,141,188,0.8)',
                fill                : false,
                lineTension         : 0.3,
                data                : saldoValues,
            },
        ]
    };


    var options_posisiSaldo = {
        maintainAspectRatio: false,
        responsive: true,
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: false,
                    max: maxY,
                    callback: function(value) {
                        return formatRibu(value);
                    }
                }
            }]
        },
        plugins: {
            datalabels: {
                formatter: function(value, context) {
                    return formatRibu(value);
                },
                color: '#333',
                font: {
                    weight: 'bold',
                    size: 12
                },
                anchor: 'end',
                align: 'top',
            }
        },
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    var datasetIndex = tooltipItem.datasetIndex;
                    var index = tooltipItem.index;
                    var label = data.labels[index];
                    var value = data.datasets[datasetIndex].data[index];

                    return label+': '+formatRibu(value);
                }
            }
        },
    };

    new Chart(posisiSaldo, {
        type: 'line',
        data: data_posisiSaldo,
        options: options_posisiSaldo,
        plugins: [ChartDataLabels]
    });
}
async function piutangAnggotaChart() {
    var piutangAnggota = $('#piutangAnggota').get(0).getContext('2d');

    const data = await getData({ data: 'piutangAnggota' });
    if (!data) return;

    var total = data.map(value => value.total);
    var maxTotal = Math.max(...total);
    var maxX = Math.ceil(maxTotal) + Math.ceil(maxTotal) * 20 / 100;


    var data_piutangAnggota = {
        labels: data.map(value => value.name),
        datasets: [
            {
                label               : 'Piutang Terbayar',
                backgroundColor     : 'rgba(60,141,188,0.9)',
                borderColor         : 'rgba(60,141,188,0.8)',
                data                : data.map(value => value.terbayar)
            },
            {
                label               : 'Total Piutang',
                backgroundColor     : 'rgba(210, 214, 222, 1)',
                borderColor         : 'rgba(210, 214, 222, 1)',
                data                : total
            }
        ]
    };

    var options_piutangAnggota = {
        maintainAspectRatio: false,
        responsive: true,
        scales: {
            xAxes: [{
                ticks: {
                    beginAtZero: true,
                    max: maxX,
                    callback: function(value) {
                        return formatRibu(value);
                    }
                },
                // stacked: true
            }],
            yAxes: [{
                // stacked: true,
                ticks: {
                    fontSize: 10,
                    callback: function(value) {
                        return value.length > 10 ? value.slice(0, 10) + '...' : value;
                    },
                    maxRotation: 60,
                    minRotation: 30,
                }
            }]
        },
        plugins: {
            datalabels: {
                formatter: function(value, context) {
                    return formatRibu(value);
                },
                color: '#333',
                font: {
                    weight: 'bold',
                    size: 12
                },
                anchor: 'end',
                align: 'right'
            }
        },
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    var datasetIndex = tooltipItem.datasetIndex;
                    var index = tooltipItem.index;
                    var label = data.datasets[datasetIndex].label;
                    var value = data.datasets[datasetIndex].data[index];

                    // Menampilkan label dengan format Rupiah
                    return label + ': ' + formatRibu(value);
                }
            }
        }
    };

    new Chart(piutangAnggota, {
        type: 'horizontalBar', // Tipe horizontalBar untuk Chart.js v2.9.4
        data: data_piutangAnggota,
        options: options_piutangAnggota,
        plugins: [ChartDataLabels]
    });
}
async function stokBarangChart() {
    var stokBarang = $('#stokBarang').get(0).getContext('2d');

    const data = await getData({ data: 'stokBarang' });
    if (!data) return;

    var stock = data.map(value => value.stock)
    var maxStock = Math.max(...stock);
    var maxY = Math.ceil(Math.ceil(maxStock) + Math.ceil(maxStock)*20/100);


    var data_stokBarang = {
        labels: data.map(value => value.name_product),
        datasets: [
            {
                label               : 'Stok Barang',
                backgroundColor     : 'red',
                borderColor         : 'red',
                data                : stock
            },
        ]
    };

    var options_stokBarang = {
        maintainAspectRatio: false,
        responsive: true,
        scales: {
            yAxes: [{
                max: maxY,
                ticks: {
                    beginAtZero: false,
                    callback: function(value) {
                        return formatRibu(value);
                    }
                }
            }]
        },
        plugins: {
            datalabels: {
                formatter: function(value, context) {
                    return formatRibu(value);
                },
                color: '#333',
                font: {
                    weight: 'bold',
                    size: 12
                },
                anchor: 'end',
                align: 'top',
            }
        },
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    var datasetIndex = tooltipItem.datasetIndex;
                    var index = tooltipItem.index;
                    var label = data.labels[index];
                    var value = data.datasets[datasetIndex].data[index];

                    return label+': '+formatRibu(value);
                }
            }
        },
    };

    new Chart(stokBarang, {
        type: 'bar',
        data: data_stokBarang,
        options: options_stokBarang,
        plugins: [ChartDataLabels]
    });
}
