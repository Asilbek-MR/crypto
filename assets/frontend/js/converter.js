'use strict';

/****************************************************************
 *                                                              *
 *                       Converter Script                       *
 *                                                              *
 ****************************************************************/

(function (CONSTANTS,$,fx) {


    var Converter = {
        elements: {
            input: null,
            quantity: null,
            output: null,
            panel: null
        },
        rates: {},
        input: null,
        quantity: null,
        output: ['USD','GBP','EUR','JPY','CNY','RUB','AUD','CHF','INR','ethereum','ripple','litecoin','stellar','eos'],

        init: function () {
             var elements = this.elements;

             // from input dropdown
             elements.input
                 .dropdown({
                     forceSelection: false,
                     onChange: function (value) {
                         Converter.input = value;
                         Converter.update(); // update output
                     }
                 });

             // quantity input
            elements.quantity.on('change paste keyup', function () {
                Converter.quantity = parseFloat($(this).val()) || 0;
                Converter.update(); // update output
            });

            // output currencies dropdown
            elements.output
                .dropdown({
                    forceSelection: false,
                    onChange: function (output) {
                        Converter.output = output;
                        Converter.update(); // update output
                    }
                });

            // initial values
            Converter.input = elements.input.val();
            Converter.quantity = elements.quantity.val();


            // wait for rates
            $(document).on('fxready', function () {
                Converter.downloadFullRates(function () {
                    Converter.update();
                });
            });

        },

        update: function () {
            var input = Converter.input,
                quantity = Converter.quantity,
                output = Converter.output;

            var html = ''; // holds the content html

            // calculates the conversion for each output currency
            output.forEach(function (code) {
                if(!Converter.rates[code]) return;

                var value = fx._priceFormat(fx(quantity).from(input).to(code), 4),
                    unit = Converter.rates[code].unit;

                html += '<div class="eight wide column"><div class="ui big fluid label">'+unit+'<div class="detail">'+value+'</div></div></div>';
            });


            Converter.elements.panel.html(html); // updates panel with new html content
        },

        downloadFullRates: function (callback) {
            var url = CONSTANTS.urls.api+'/rates/'; // get simple rates

            $.getJSON(url, function (data) {
                Converter.rates = data;
                callback()
            })
        }
    };

    $(function () {
        var elements = Converter.elements;

        // collect DOM elements

        elements.input = $('#converter-input');
        elements.quantity = $('#converter-quantity');
        elements.output = $('#converter-output');
        elements.panel = $('#converter-panel');

        Converter.init();
    });


})(window.CoinTableConstants,jQuery,fx);