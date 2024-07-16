'use strict';

/****************************************************************
 *                                                              *
 *                         ICOs Script                          *
 *                                                              *
 ****************************************************************/

(function ($) {

    var ICOs = {
        elements: {
            list: null
        },

        init: function () {
            var elements = this.elements;

            elements.list
                .find('.ui.progress')
                .progress();
        }

    };

    $(function () {
        var elements = ICOs.elements;

        // collect DOM elements

        elements.list = $('#icos-list');

        ICOs.init();
    });

})(jQuery);