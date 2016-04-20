/**
 * Created by arnaud on 15/04/2016.
 */
+function ($) {
    'use strict';
    $('[data-widget]').each(function(k, el){
        console.log(el);
        var $this = $(el);
        var heading = $this.find('.panel-heading');
        var button = $('<button class="pull-right"><i class="fa fa-refresh" aria-hidden="true"></i></button>');

        heading.append(button);

        button.on('click', function(event){
            event.stopPropagation();
            $this.removeClass('loaded');
            $this.trigger('widget.load.data');
        });
    });

    $('[data-widget="text"]').on('widget.load.data', function(e){

        var $this = $(this);
        var body = $this.find('.panel-body');
        var button = $this.find('.panel-heading > button');

        $.ajax({
            url: $this.data('url'),
            method: 'GET',
            beforeSend: function () {
                button.addClass('fa-spin');
            },
            success: function (data) {
                body.html(data);
            },
            complete: function () {
                button.removeClass('fa-spin');
                $this.addClass('loaded');
            }
        })
    });

    $('[data-widget="table"]').on('widget.load.data', function(e){

        var $this = $(this);
        var body = $this.find('.panel-body')
        var button = $this.find('.panel-heading > button');

        $.ajax({
            url: $this.data('url'),
            method: 'GET',
            beforeSend: function () {
                button.addClass('fa-spin');
            },
            success: function (data) {
                $this.find('table').remove();
                $this.append(data);
            },
            complete: function () {
                button.removeClass('fa-spin');
                $this.addClass('loaded');
            }
        })
    });

    $('[data-widget]').trigger('widget.data.load');
}(jQuery);