'use strict';

/****************************************************************
 *                                                              *
 *                         Trends Script                        *
 *                                                              *
 ****************************************************************/

(function (CONSTANTS,$) {


    var Trends = {
        elements: {
            gainers_table: null,
            losers_table: null
        },

        tableOptions: {
            paging: false,
            scrollX: true,
            searching: false,
            ordering: false,
            info: false
        },

        init: function () {
            var elements = this.elements;

            // init DataTable with horizontal scroll
            elements.gainers_table.DataTable(this.tableOptions);

            elements.losers_table.DataTable(this.tableOptions);
        },
    };

    $(function () {
        var elements = Trends.elements;

        // collect DOM elements

        elements.gainers_table = $('#gainers-table');
        elements.losers_table = $('#losers-table');

        Trends.init();
    });


})(window.CoinTableConstants,jQuery);