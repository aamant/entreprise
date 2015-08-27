(function($){

    var Quotation   = function (el, option) {

        if (option == 'addItem'){
            Quotation.prototype.addItem(el);
            return;
        }

        var node = $(el);
        Quotation.itemsContainer = node.find('.items >tbody');
        Quotation.total = node.find('#quotation_total');

        node.find('table > tbody').children().each(function(key, element){
            Quotation.prototype.addItem(element);
        });
    }

    Quotation.DEFAULTS = {}

    Quotation.prototype.addItem = function (element) {

        var $element = $(element);

        var calculate = function(){
            var $element = $(this).parents('tr');
            var qty = $element.find('[id$=_quantity]');
            var price = $element.find('[id$=_price]');
            var total = $element.find('[id$=_total]');

            total.val(price.val() * qty.val());

            var quotation_total = 0;
            Quotation.itemsContainer.children().each(function(key, node){
                quotation_total = parseFloat(quotation_total) + parseFloat($(node).find('[id$=_total]').val());
                if (quotation_total == NaN){
                    quotation_total = 0;
                }
            });
            Quotation.total.val(parseFloat(quotation_total));
        }

        $element.find('[id$=_quantity]').change(calculate);
        $element.find('[id$=_price]').change(calculate);

        $element.find('[data-action="remove-item"]').click(Quotation.prototype.removeItem);
    }

    Quotation.prototype.removeItem = function(){
        $element = $(this).parents('tr');
        $element.hide(1000, function(){
            $element.remove();
            Quotation.advance.trigger('change');
        });
    }

    $.fn.quotation = function(option){
        Quotation.DEFAULTS = option
        return this.each(function(){
            new Quotation($(this), option);
        })
    }

})(jQuery);