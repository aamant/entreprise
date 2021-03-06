(function($){

    var Invoice   = function (el, option) {

        if (option == 'addItem'){
            Invoice.prototype.addItem(el);
            return;
        }

        var node = $(el);
        Invoice.itemsContainer = node.find('.items >tbody');
        Invoice.sub_total = node.find('#invoice_sub_total');
        Invoice.advance = node.find('#invoice_advance');
        Invoice.total = node.find('#invoice_total');

        node.find('table > tbody').children().each(function(key, element){
            Invoice.prototype.addItem(element);
        });
    }

    Invoice.DEFAULTS = {}

    Invoice.prototype.addItem = function (element) {

        var $element = $(element);

        var calculate = function(){
            var $element = $(this).parents('tr');
            var qty = $element.find('[id$=_quantity]');
            var price = $element.find('[id$=_price]');
            var total = $element.find('[id$=_total]');

            total.val(price.val() * qty.val());

            var invoice_total = 0;
            Invoice.itemsContainer.children().each(function(key, node){
                invoice_total = parseFloat(invoice_total) + parseFloat($(node).find('[id$=_total]').val());
                if (invoice_total == NaN){
                    invoice_total = 0;
                }
            });
            console.log('advance ' + Invoice.advance.val());
            Invoice.sub_total.val(invoice_total);
            Invoice.total.val(parseFloat(invoice_total) - parseFloat(Invoice.advance.val()));
        }

        $element.find('[id$=_quantity]').change(calculate);
        $element.find('[id$=_price]').change(calculate);
        Invoice.advance.change(calculate);

        $element.find('[data-action="remove-item"]').click(Invoice.prototype.removeItem);
    }

    Invoice.prototype.removeItem = function(){
        $element = $(this).parents('tr');
        $element.hide(1000, function(){
            $element.remove();
            Invoice.advance.trigger('change');
        });
    }

    $.fn.invoice = function(option){
        Invoice.DEFAULTS = option
        return this.each(function(){
            new Invoice($(this), option);
        })
    }

})(jQuery);