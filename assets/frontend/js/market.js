'use strict';

/****************************************************************
 *                                                              *
 *                        Market Script                         *
 *                                                              *
 ****************************************************************/

(function (CONSTANTS,$,fx,echarts) {

    var Market = {
        elements: {
            table: null,
            form: null,
            market_cap_slider: null,
            price_slider: null,
            volume_slider: null,
            reset_sliders: null,
            coins_selection: null,
            coins_clear: null,
            save_checkbox: null
        },
        currency_url: null,
        sliders_alias: {
            market_cap: 'mc',
            price: 'p',
            volume: 'v'
        },
        sliders: {},
        search_params: {
            c: null,
            mcf: null,
            mct: null,
            pf: null,
            pt: null,
            vf: null,
            vt: null
        },
        form_params: {
            save: false
        },
        save_cookie: 'ct_market_search_save',

        price_spans: {},

        init: function () {
            this.buildCharts();
            this.initDataTable();
            this.initSearchForm();
        },

        buildCharts: function() {
            if (!CONSTANTS.slugs || !CONSTANTS.slugs.length) return;

            var data = {slugs: CONSTANTS.slugs.join(',')}

            $.getJSON(CONSTANTS.urls.api + '/charts_7d', data, function (charts) {
                Object.keys(charts).forEach(slug => {
                    var elem_id = 'chart-'+slug,
                        data = charts[slug];


                    if(!data.series.length) return;

                    var series = data.series.map(function (serie) {
                        return {
                            type: 'line',
                            showSymbol: false,
                            animation: false,
                            lineStyle: {
                                color: serie.color,
                                width: 1
                            },
                            areaStyle: {
                                color: serie.color,
                                opacity: 0.5
                            },
                            data: serie.data
                        }
                    });


                    var chart = echarts.init(document.getElementById(elem_id), {width: 200});
                    chart.setOption({
                        xAxis: {
                            show: false,
                            type: 'category',
                            data: data.x
                        },
                        yAxis: {
                            type: 'value',
                            min: 'dataMin',
                            max: 'dataMax',
                            show: false
                        },
                        series: series
                    });
                })
            })
        },

        // init DataTable with horizontal scroll
        initDataTable: function () {
            this.elements.table.DataTable({
                paging: false,
                scrollX: true,
                searching: false,
                ordering: false,
                info: false
            });
        },

        initSearchForm: function() {
            var elements = this.elements,
                form = elements.form,
                form_params = Market.form_params;


            this.initSave();
            this.initSearchSliders();
            this.initCoinSelection();

            form.find('.ui.accordion').accordion();

            form.find('.submit-button')
                .on('click', function () {
                    Market.search();
                });

        },

        initCoinSelection: function () {
            var elements = this.elements;

            if (CONSTANTS.params.c) {
                $.getJSON(CONSTANTS.urls.api + '/coins/search', {c: CONSTANTS.params.c || []}, function (res) {
                    if (res && res.success && Array.isArray(res.results)) {
                        res.results.forEach(item => item.selected = true)
                        Market.initCoinSelectionDropdown(res.results)
                    }
                })
            } else {
                Market.initCoinSelectionDropdown([])
            }

            // clear coin selection event
            elements.coins_clear.on('click', function () {
                elements.coins_selection.dropdown('clear');
                Market.search_params.c = null;
            });
        },

        initCoinSelectionDropdown: function(values) {
            this.elements.coins_selection.dropdown({
                values: values,
                clearable: true,
                forceSelection: false,
                apiSettings: {
                    url: CONSTANTS.urls.api + '/coins/search?q={query}'
                },
                onChange: function (value) {
                    if(value.length && value !== Market.search_params.c) Market.search_params.c = value;
                }
            })
        },

        _initCoinSelection: function () {
            var elements = this.elements;

            this.coinList(function (data) {
                var selected = CONSTANTS.params.c;

                // selects all coins provided by URL
                if(Array.isArray(selected)) {
                    data.forEach(function (coin) {
                        if(selected.indexOf(coin.value) !== -1)
                            coin.selected = true;
                    });
                }

                // init dropdown with all active coins
                elements.coins_selection.dropdown({
                    values: data,
                    clearable: true,
                    forceSelection: false,
                    onChange: function (value) {
                        if(value.length && value !== Market.search_params.c) Market.search_params.c = value;
                    }
                })
            });

            // clear coin selection event
            elements.coins_clear.on('click', function () {
                elements.coins_selection.dropdown('clear');
                Market.search_params.c = null;
            });

        },

        initSearchSliders: function () {
            var elements = this.elements;

            // for each slider
            Object.keys(this.sliders_alias).forEach(function (name) {
                var slider_elem = name + '_slider',
                    slider_options = CONSTANTS[slider_elem],
                    // handler for update search params
                    onUpdate = function (obj) {
                        Market.setSearchFrom(name, obj.min === obj.from ? null : obj.from_value);
                        Market.setSearchTo(name, obj.max === obj.to ? null : obj.to_value);
                    },
                    options = {
                        values: slider_options.values,
                        onUpdate: onUpdate,
                        onChange: onUpdate
                    };

                // default values

                if(slider_options.hasOwnProperty('from')) {
                    options.from = slider_options.from;
                    Market.setSearchFrom(name, slider_options.values[slider_options.from]);
                }

                if(slider_options.hasOwnProperty('to')) {
                    options.to = slider_options.to;
                    Market.setSearchTo(name, slider_options.values[slider_options.to]);
                }

                // init slider
                elements[slider_elem].ionRangeSlider(options);

                Market.sliders[name] = elements[slider_elem].data('ionRangeSlider'); // keep slider instance
            });

            elements.reset_sliders.on('click', function () {
                Object.keys(Market.sliders).forEach(function (name) {
                    Market.sliders[name].update({to: null, from: null});
                });
            });
        },

        initSave: function () {
            // toggling with replace save cookie
            this.elements.save_checkbox
                .checkbox({
                    onChecked: function () {
                        Market.setSave(true);
                    },
                    onUnchecked: function () {
                        Market.setSave(false);
                    }
                })
                .checkbox(this.getSaveCookie() ? 'check' : 'uncheck'); // initial value
        },

        setSearchFrom: function(name, value) {
            this.search_params[this.sliders_alias[name]+'f'] = value;
        },

        setSearchTo: function(name, value) {
            this.search_params[this.sliders_alias[name]+'t'] = value;
        },

        setSave: function (value) {
            $.cookie(this.save_cookie, value, {path: '/'});
            this.form_params.save = value;
        },

        getSaveCookie: function() {
            return $.cookie(this.save_cookie) === 'true';
        },

        search: function () {
            var query = [];

            // build URL params
            Object.keys(this.search_params).forEach(function (param) {
                var value = Market.search_params[param];
                if(value !== null) query.push(param + '=' + value);
            });

            query.push('order=' + CONSTANTS.params.order);
            query.push('desc=' + (CONSTANTS.params.order ? 1:0));

            // redirect with new URL params
            location.href = CONSTANTS.urls.market_page + '?' + query.join('&') ;
        },

        coinList: function (callback) {
            var url = CONSTANTS.urls.api + '/coins/list';
            $.getJSON(url, function (data) {
                data && callback(data);
            })
        }
    };


    $(function () {
        var elements = Market.elements;

        // collect DOM elements

        elements.table = $('#market-table');
        elements.form = $('#market-form');
        elements.market_cap_slider = $('#market-cap-slider');
        elements.price_slider = $('#price-slider');
        elements.volume_slider = $('#volume-slider');
        elements.reset_sliders = $('#reset-sliders')
        elements.coins_selection = $('#coins-selection');
        elements.coins_clear = $('#coins-clear');
        elements.save_checkbox = $('#save-checkbox');

        Market.init();
    });

})(window.CoinTableConstants,jQuery,fx,echarts);