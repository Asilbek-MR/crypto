'use strict';

/****************************************************************
 *                                                              *
 *                        Mining Script                         *
 *                                                              *
 ****************************************************************/

(function (CONSTANTS,$) {

    var Mining = {
        elements: {
            form: null,
            table: null,
            order: null
        },
        params_names: ['cryptocurrencies','types','sources'],

        init: function () {
            var elements = this.elements,
                form = elements.form;

            // handlers for select all & clear button for each param
            this.params_names.forEach(function (param) {
                var group = form.find('.ui.accordion > .'+param),
                    checkboxes = group.find('input[type=checkbox]');

                group.find('.select-all-button').click(function () {
                    checkboxes.prop('checked', true);
                });

                group.find('.clear-button').click(function () {
                    checkboxes.prop('checked', false);
                });
            });

            form.find('.ui.accordion').accordion();

            form.find('.submit-button')
                .click(function () {
                    Mining.search();
                });

            // redirect in order by change
            elements.order.dropdown({
                forceSelection: false,
                onChange: function (value) {
                    Mining.order(value);
                }
            });

            // if enabled, clicking row will open cryptocompare page
            elements.table.find('> tbody > tr')
                .click(function () {
                    this.dataset.url && window.open(this.dataset.url);
                })
        },

        encodeParams: function (new_params) {
            var params = CONSTANTS.params;

            Object.keys(new_params).forEach(function (param) {
                params[param] = new_params[param];
            });

            params.desc = params.desc ? '1' : '0';

            return Object.keys(params).map(function (param) {
                var val = params[param];
                return param + '=' + encodeURIComponent(Array.isArray(val) ? val.join(',') : val);
            }).join('&');
        },

        // collect all selected fields by params
        search: function () {
            var form = this.elements.form,
                params = [];

            this.params_names.forEach(function (param) {
                var data = [];

                form.find("." + param + " input[name='" + param + "[]']")
                    .each(function () {
                        var checkbox = $(this);

                        if(checkbox.prop('checked')) {
                            data.push(checkbox.val());
                        }
                    });

                params[param] = data;
            });

            this.redirect(this.encodeParams(params));
        },

        // change order field and/or direction
        order: function (field) {
            this.redirect(this.encodeParams({desc: field[0] === '-', order: field.substr(1)}));
        },

        // refresh page
        redirect: function (query) {
            location.href = CONSTANTS.urls.mining_page + '?' + query;
        }
    };

    $(function () {
        var elements = Mining.elements;

        // collect DOM elements

        elements.table = $('#mining-table');
        elements.form = $('#mining-form');
        elements.order = $('#mining-order');

        Mining.init();
    });

})(window.CoinTableConstants,jQuery);