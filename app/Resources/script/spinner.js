/**
 * Created by arnaud on 15/04/2016.
 */
(function ($) {
    $.fn.spinner = function (action, options) {

        container = $(this);

        var settings = $.extend({
            spinner: '<span id="spinner" class="spinner"><i class="fa fa-spinner fa-spin"></i></span>'
        }, options);

        if (action === "append") {
            container.append(settings.spinner);
        }

        if (action === "prepend") {
            container.prepend(settings.spinner);
        }

        if (action === "replace") {
            container.html('');
            container.append(settings.spinner);
        }

        if (action === "remove") {
            container.find('#spinner').remove();
        }

        return this;
    };
})(jQuery);