'use strict';

/****************************************************************
 *                                                              *
 *                        Frontend Script                       *
 *                                                              *
 ****************************************************************/

(function (CONSTANTS,$,fx) {

    // price format for better display
    fx._priceFormat = function (value) {
        value = parseFloat(value);

        var exp = Math.log10(value),
            price = 0;

        if(exp >= 4) price = value.toFixed(0);
        else if (exp >= 3) price = value.toFixed(1);
        else if (exp >= 2) price = value.toFixed(2);
        else if (exp >= 1) price = value.toFixed(2);
        else if (exp >= 0) price = value.toFixed(3);
        else if (exp >= -1) price = value.toFixed(4);
        else if (exp >= -2) price = value.toFixed(5);
        else if (exp >= -3) price = value.toFixed(6);
        else if (exp >= -4) price = value.toFixed(7);
        else if (exp >= -5) price = value.toFixed(8);
        else if (exp >= -6) price = value.toFixed(8);
        else if (exp >= -7) price = value.toFixed(8);
        else if (exp >= -8) price = value.toFixed(8);

        return price;
    };

    var Frontend = {
        elements: {
            document: null,
            window: null,
            sidebar_menu: null,
            top_menu: null,
            menu_toggle: null,
            price_currency: null,
            language: null,
            donation_box: null,
            slide_up: null,
            gdpr_message: null
        },

        init: function () {
            var elements = this.elements;

            var menu_toggle_icon = elements.menu_toggle.find('i');

            // switch icon of top menu
            elements.sidebar_menu
                .sidebar({
                    onShow: function () {
                        menu_toggle_icon.attr('class', 'close icon');
                    },
                    onHide: function () {
                        menu_toggle_icon.attr('class', 'sidebar icon');
                    }
                });

            // show/hide sidebar
            elements.menu_toggle
                .click(function () {
                    elements.sidebar_menu.sidebar('toggle');
                });



            this.downloadRates(function (rates) {

                // find current and select
                rates.some(function (rate) {
                    if(rate.value === CONSTANTS.price_currency) {
                        rate.selected = true;
                        return true;
                    }
                });

                // price currency dropdown initialization
                elements.price_currency
                    .dropdown({
                        forceSelection: false,
                        values: rates,
                        onChange: function (value) {
                            if(value && CONSTANTS.price_currency !== value) {
                                $.cookie('ct_price_currency', value, {expires: 60, path: '/'});
                                location.reload(true);
                            }
                        }
                    });
            });

            elements.language
                .dropdown({
                    forceSelection: false,
                    onChange: function (value) {
                        $.cookie('ct_language', value, {expires: 60, path: '/'});
                        location.reload(true);
                    }
                });


            this.initSlideUp();
            this.initDonation();
            this.initGDPR();

            // load rates
            this.setRates();
        },

        initSlideUp: function () {
            var elements = this.elements;

            // slide up on click event
            elements.slide_up
                .click(function () {
                    $("html, body").animate({ scrollTop: 80 }, "slow"); // go smoothly
                });

            // show slide up button after scrolling at least the screen height
            elements.document.scroll(function () {
                (elements.document.scrollTop() > elements.window.height()) ?
                    elements.slide_up.show() :
                    elements.slide_up.hide();
            });

        },

        initGDPR: function () {
            var elements = this.elements;

            if(!elements.gdpr_message.length) // do not show
                return;

            // on agreement (close), save cookie for 60 days
            elements.gdpr_message
                .click(function() {
                    $(this)
                        .closest('.message')
                        .transition('fade');

                    $.cookie('ct_gdpr_checked', true, {expires: 60, path: '/'})
                });
        },

        initDonation: function () {
            var elements = this.elements;

            // show donation box on item click
            $('.donation-item')
                .click(function () {
                    elements.donation_box.modal('show');
                });

            // select & copy address on donation box
            elements.donation_box
                .find('.form .field')
                .each(function () {
                    var field = $(this),
                        button = field.find('.button'),
                        input = field.find('input');

                    button.click(function () {
                        input.select();
                        document.execCommand('copy');
                    });
                });
        },

        downloadRates: function (callback) {
            // get all currencies available for price currency
            var url = CONSTANTS.urls.api+'/rates/list';

            $.getJSON(url, function (data) {
                data && callback(data);
            });
        },

        setRates: function () {
            var url = CONSTANTS.urls.api+'/rates/fx'; // get simple rates

            $.getJSON(url, function (data) {
                if(!data) return;

                // setup money.js (http://openexchangerates.github.io/money.js/)
                fx.base = data.base;
                fx.rates = data.rates;
                fx._can_convert = true;

                Frontend.elements.document.trigger('fxready'); // let notice to other scripts
            });
        }
    };


    $(function () {
        var elements = Frontend.elements;

        // collect DOM elements

        elements.document = $(document);
        elements.window = $(window);
        elements.sidebar_menu = $('#sidebar-menu');
        elements.top_menu = $('#top-menu');
        elements.menu_toggle = $('#sidebar-menu-toggle');
        elements.price_currency = $('.price-currency');
        elements.language = $('.language');
        elements.donation_box = $('#donation-box');
        elements.slide_up = $('#slide-up');
        elements.gdpr_message = $('#gdpr-message');

        Frontend.init();
    });


})(window.CoinTableConstants,jQuery,fx);