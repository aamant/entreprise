(function($){

    var Quotation   = function (el, option) {

        if (option == 'addItem'){
            Quotation.prototype.addItem(el);
            return;
        }

        var node = $(el);
        Quotation.itemsContainer = node.find('.items >tbody');
        Quotation.total = node.find('#quotation_total');
        Quotation.heures = node.find('#quotation_heure');

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

            Quotation.prototype.total();
        }

        $element.find('[id$=_quantity]').change(calculate);
        $element.find('[id$=_price]').change(calculate);

        $element.find('[data-action="remove-item"]').click(Quotation.prototype.removeItem);
    }

    Quotation.prototype.total = function(){
        var heures = 0;
        var quotation_total = 0;
        Quotation.itemsContainer.children().each(function(key, node){
            quotation_total = parseFloat(quotation_total) + parseFloat($(node).find('[id$=_total]').val());
            if (quotation_total == NaN){
                quotation_total = 0;
            }

            heures = parseFloat(heures) + parseFloat($(node).find('[id$=_quantity]').val());
            if (isNaN(heures)){
                heures = 0;
            }
        });

        Quotation.total.val(parseFloat(quotation_total));
        Quotation.heures.val(parseFloat(heures));
    }

    Quotation.prototype.removeItem = function(){
        $element = $(this).parents('tr');
        $element.hide(500, function(){
            $element.remove();
            Quotation.prototype.total();
        });
    }

    $.fn.quotation = function(option){
        Quotation.DEFAULTS = option
        return this.each(function(){
            new Quotation($(this), option);
        })
    }

})(jQuery);