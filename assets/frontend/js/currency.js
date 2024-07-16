'use strict';

/****************************************************************
 *                                                              *
 *                        Currency Script                       *
 *                                                              *
 ****************************************************************/

(function (CONSTANTS,$,fx,echarts) {


    //----------------------------------------------------------

    var coin = CONSTANTS.coin;

    var chartOptions = {
        animation: false,
        dataset: {
            source: null
        },
        tooltip: {
            trigger: 'axis',
            axisPointer: {type: 'cross'}
        },
        axisPointer: {
            link: {xAxisIndex: 'all'},
            label: {backgroundColor: '#777'}
        },
        toolbox: {
            feature: {
                restore: {title: ' '},
                saveAsImage: {title: ' '}
            }
        },
        grid: [
            {
                left: '3%',
                right: '4%',
                bottom: 200
            },
            {
                left: '3%',
                right: '4%',
                height: 80,
                bottom: 80
            }
        ],
        dataZoom: [
            {
                type: 'inside',
                xAxisIndex: [0, 1]
            },
            {
                show: true,
                xAxisIndex: [0, 1],
                type: 'slider',
                bottom: 10,
                handleIcon: 'path://M-9.35,34.56V42m0-40V9.5m-2,0h4a2,2,0,0,1,2,2v21a2,2,0,0,1-2,2h-4a2,2,0,0,1-2-2v-21A2,2,0,0,1-11.35,9.5Z',
                handleStyle: {
                    color: '#fff',
                    borderColor: '#ACB8D1'
                }
            }
        ],
        legend: {
            padding: [40, 5, 20, 5],
            data:[CONSTANTS.chart.price.label,CONSTANTS.chart.market_cap.label,CONSTANTS.chart.volume.label]
        },
        xAxis: [
            {
                type: 'category',
                scale: true,
                boundaryGap : false,
                splitLine: {show: false}
            },
            {
                type: 'category',
                gridIndex: 1,
                scale: true,
                boundaryGap : false,
                axisTick: {show: false},
                splitLine: {show: false},
                axisLabel: {show: false}
            }
        ],
        yAxis: [
            {
                position: 'right',
                type: 'value',
                axisTick: { show: false },
                axisLine: {show: false},
                axisLabel: {inside: true},
                splitLine: {
                    show: true,
                    lineStyle: {
                        opacity: 0.5
                    }
                },
                min: 'dataMin'
            },
            {
                type: 'value',
                axisTick: {show: false},
                axisLine: {show: false},
                splitLine: {show: false},
                axisLabel: {
                    inside: true,
                    formatter: function (value) {
                        return Currency.chartYAxisFormatter.format(value) + '\n\n'
                    }
                },
                min: 'dataMin',
            },
            {
                scale: true,
                gridIndex: 1,
                axisLabel: {show: false},
                axisLine: {show: false},
                axisTick: {show: false},
                splitLine: {show: false}
            }
        ],
        series: [
            {
                type:'line',
                name: CONSTANTS.chart.price.label,
                symbol: 'none',
                itemStyle: {color: CONSTANTS.chart.price.color},
                encode: {x: 0, y: 1}
            },
            {
                type:'line',
                name: CONSTANTS.chart.market_cap.label,
                symbol: 'none',
                yAxisIndex: 1,
                itemStyle: {color: CONSTANTS.chart.market_cap.color},
                encode: {x: 0, y: 2}
            },
            {
                type: 'bar',
                name: CONSTANTS.chart.volume.label,
                xAxisIndex: 1,
                yAxisIndex: 2,
                itemStyle: {color: CONSTANTS.chart.volume.color},
                encode: {x: 0, y: 3}
            }
        ]
    }

    if (coin.tracking) {
        chartOptions.legend.data.splice(1,2);
        chartOptions.xAxis.splice(1, 1);
        chartOptions.yAxis.splice(1,2);
        chartOptions.series.splice(1,2);
        chartOptions.dataZoom[0].xAxisIndex = [0];
        chartOptions.dataZoom[1].xAxisIndex = [0];
        chartOptions.grid.splice(1, 1);
        chartOptions.grid[0].bottom = 80;
    }


    var Currency = {

        elements: {
            document: null,
            price: null,
            chart_wrapper: null,
            chart: null,
            chart_menu: null,
            converter: null,
            tickers: null,
            tickers_btn: null
        },

        converter_options: {
            slug: null,
            multiple: 1
        },

        chart: null,

        chartYAxisFormatter: new Intl.NumberFormat(CONSTANTS.lang, {notation:'compact', compactDisplay: 'short', minimumSignificantDigits: 3}),

        chart_datasets: {},

        tickers_control: {
            page: 1,
            items: CONSTANTS.tickers.size,
            enabled: true,
            lock: false,
        },

        init: function () {
            var elements = this.elements;

            if(coin.tracking) { // custom asset corrections
                this.converter_options.slug = coin.tracking[0];
                this.converter_options.multiple = parseFloat(coin.tracking[1]);
            }
            else {
                this.converter_options.slug = CONSTANTS.coin.slug;
            }

            this.initChart();

            elements.document.on('fxready', function () { // fx ready for conversion
                Currency.initConverter();
            });

            if(elements.tickers.length) this.initTickers();
        },

        dateFormat: function (date) {
            return echarts.format.formatTime('yyyy-MM-dd\nhh:mm',new Date(date));
        },

        initChart: function () {
            var elements = this.elements;

            if(!elements.chart.length) return;

            this.chart = echarts.init(elements.chart[0], {width: 'auto', height: 'auto'});

            var active_dataset = this.elements.chart_menu.find('.item.active').data('dataset');

            this.downloadChartData(active_dataset, function (data) {
                chartOptions.dataset.source = data;
                Currency.chart.setOption(chartOptions);
            });

            this.initChartEvents();
        },

        updateChart: function(dataset) {
            if(this.chart_datasets[dataset]) {
                // replace dataset
                this.chart.setOption({
                    dataset: {
                        source: Currency.chart_datasets[dataset]
                    }
                });

                // force reset on zoom
                this.chart.dispatchAction({
                    type: 'dataZoom',
                    start: 0,
                    end: 100
                });
            }
        },

        downloadChartData: function (dataset, callback) {

            // if already downloaded
            if(this.chart_datasets[dataset]) {
                return callback && callback(this.chart_datasets[dataset]);
            }

            var slug = this.converter_options.slug,
                currency = CONSTANTS.chart.currency,
                multiple = Currency.converter_options.multiple,
                url = "https://api.coingecko.com/api/v3/coins/"+slug+"/market_chart?vs_currency="+currency+"&days="+dataset;

            var elements = this.elements;

            elements.chart_wrapper.addClass('loading'); // show loading state

            $.getJSON(url, function (data) {
                const transformed = [];

                const formatter = Intl.NumberFormat('en',{
                    minimumSignificantDigits: 4,
                    maximumSignificantDigits: 6,
                    maximumFractionDigits: 8
                });

                data.prices.forEach((prices, i) => {
                    const date = new Date(prices[0]);
                    transformed.push([
                        echarts.format.formatTime('yyyy-MM-dd hh:mm', date),
                        formatter.format(prices[1] * multiple).replace(',',''),
                        Math.round(data.market_caps[i][1]),
                        Math.round(data.total_volumes[i][1])
                    ])
                })

                Currency.chart_datasets[dataset] = transformed; // save
                callback && callback(transformed);
            }).always(function() {
                elements.chart_wrapper.removeClass('loading'); // disable loading state
            });
        },

        initChartEvents: function () {
            var items = this.elements.chart_menu.find('.item');

            items.click(function () { // dataset switch
                items.removeClass('active');

                var item = $(this),
                    dataset = item.data('dataset');

                Currency.downloadChartData(dataset, function () {
                    Currency.updateChart(dataset);
                });

                item.addClass('active');
            });

            // adjust chart dimensions on screen resizing
            $(window).on('resize', function () {
                if(Currency.chart)
                    Currency.chart.resize({
                        width: 'auto',
                        height: 'auto'
                    });
            });
        },

        initConverter: function () {
            var elements = this.elements,
                converter = elements.converter,
                currency = CONSTANTS.price_currency,
                slug = this.converter_options.slug,
                multiple = this.converter_options.multiple;

            if(!converter.length) return;

            var left = converter.find('.input-left'),
                right = converter.find('.input-right');

            // initial values
            right.val(fx._priceFormat(fx(parseFloat(left.val())).from(slug).to(currency) * multiple));

            // convert on input events
            left.on('change paste keyup', function() {
                var value = parseFloat(left.val());
                right.val(fx._priceFormat(fx(value).from(slug).to(currency) * multiple));
            });

            right.on('change paste keyup', function() {
                var value = parseFloat(right.val());
                left.val(fx._priceFormat(fx(value).from(currency).to(slug) * multiple));
            });

        },

        initTickers: function() {
            var elements = this.elements;

            elements.tickers_btn.click(function() {
                Currency.addTickers();
            });

            Currency.addTickers();
        },

        addTickers: function() {
            var tickers_control = this.tickers_control;
            if (!tickers_control.enabled || tickers_control.lock) return;
            tickers_control.lock = true;


            var elements = this.elements;

            elements.tickers.addClass('loading');

            //var url = "https://api.coingecko.com/api/v3/coins/"+coin.slug+"/tickers?page="+tickers_control.page;
            var url = CONSTANTS.urls.api + '/tickers/' + coin.slug + '/' + tickers_control.page + '/' + tickers_control.items;

            $.getJSON(url, function (data) {
                if (data && data.length) {
                    var rows = '';

                    $.each(data, function(i, entry) {
                        rows += '<tr>';
                        rows += '<td>' + entry.rank + '</td>';
                        rows += '<td>';

                        if (entry.exchange_image) rows += '<img class="ui right spaced avatar image" src="' + entry.exchange_image + '">';

                        if (entry.exchange_url) rows += '<a href="' + entry.exchange_url + '" target="_blank">' + entry.exchange_name + '</a>';
                        else rows += '<span>' + entry.exchange_name + '</span>';

                        rows += '</td>';
                        rows += '<td><a href="' + entry.url + '" target="_blank">' + entry.base + '/' + entry.quote + '</a></td>';
                        rows += '<td class="right aligned">' + entry.last_price + '</td>';
                        rows += '<td class="right aligned">' + entry.volume + '</td>';

                        rows += '</tr>';
                    })

                    elements.tickers.find('table > tbody').append(rows);

                    tickers_control.page++;

                }

                if (!data || data.length < tickers_control.items) {
                    tickers_control.enabled = false;
                    elements.tickers_btn.hide();
                }

            }).always(function() {
                elements.tickers.removeClass('loading'); // disable loading state

                tickers_control.lock = false;
            });
        },

    };

    $(function () {
        var elements = Currency.elements;

        // collect DOM elements

        elements.document = $(document);
        elements.price = $('#currency-price');
        elements.chart_wrapper = $('#currency-chart-wrapper');
        elements.chart = $('#currency-chart');
        elements.chart_menu = $('#currency-chart-menu');
        elements.converter = $('#currency-converter');
        elements.tickers = $('#currency-tickers');
        elements.tickers_btn = $('#currency-tickers-load-btn');

        Currency.init();
    });


})(window.CoinTableConstants,jQuery,fx,echarts);
