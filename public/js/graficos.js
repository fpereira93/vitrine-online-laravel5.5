
Highcharts.chart('grafico_container', {
    chart: {
        type: 'pie',
        options3d: {
            enabled: false,
            alpha: 45,
        }
    },
    title: {
        text: 'Tipos de resíduos'
    },

    subtitle: {
        text: 'Resíduos gerados na UNESP Rio Claro contabilizados em litros por ano.'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            innerSize: 100,
            depth: 45,
            dataLabels: {
                enabled: true,
                format: '{point.name}'
            }
        }
    },
    series: [{
        type: 'pie',
        name: 'Percentual',
        data: [
            ['Orgânico', 93.0],
            ['Resíduos Biológicos', 3.0],
            {
                name: 'Plástico',
                y: 2.0,
                sliced: false,
                selected: false
            },
            ['Serragem', 1.0],
            ['Papelão', 1.0],
        ]
    }]
});

Highcharts.chart('grafico_container1', {
    chart: {
        type: 'pie',
        options3d: {
            enabled: false,
            alpha: 45,
        }
    },
    title: {
        text: 'Resíduos por Unidade'
    },
    subtitle: {
        text: 'Resíduos gerados na UNESP Rio Claro contabilizados em unidades por ano.'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            innerSize: 100,
            depth: 45,
            dataLabels: {
                enabled: true,
                format: '{point.name}'
            }
        }
    },
    series: [{
        type: 'pie',
        name: 'Percentual',
        data: [
            ['Copos descartáveis', 79.0],
            ['Bituca de Cigarro', 20.0],
            {
                name: 'Pilhas',
                y: 1.0,
                sliced: false,
                selected: false
            },
            ['Vidraria laboratório', 0.0],
            ['Cartucho', 0.0],
            ['Demais vidros', 0.0],
        ]
    }]
});

Highcharts.chart('grafico_container2', {
    chart: {
        type: 'pie',
        options3d: {
            enabled: false,
            alpha: 45,
        }
    },
    title: {
        text: 'Resíduos por Instituto'
    },

    subtitle: {
        text: 'Geração de Resíduo Orgânico por instituto da UNESP Rio Claro'
    },

    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            innerSize: 100,
            depth: 45,
            dataLabels: {
                enabled: true,
                format: '{point.name}'
            }
        }
    },
    series: [{
        type: 'pie',
        name: 'Percentual',
        data: [
            {
                name: 'IB (L/Ano)',
                y: 77.0,
                sliced: false,
                selected: false,
            },
            ['IGCE(L/ano)', 23.0],
        ]
    }]
});

Highcharts.chart('grafico_container3', {
    chart: {
        type: 'pie',
        options3d: {
            enabled: false,
            alpha: 45,
        }
    },
    title: {
        text: 'Plástico por Instituto'
    },

    subtitle: {
        text: 'Geração de Plástico por instituto da UNESP Rio Claro'
    },

    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            innerSize: 100,
            depth: 45,
            dataLabels: {
                enabled: true,
                format: '{point.name}'
            }
        }
    },
    series: [{
        type: 'pie',
        name: 'Percentual',
        data: [
            {
                name: 'IB (L/Ano)',
                y: 20.0,
                sliced: false,
                selected: false,
            },
            ['IGCE(L/ano)', 80.0],
        ]
    }]
});