/**
 * Posts in Sidebar Javascript for duplicating a widget.
 * This file is a modified version of Duplicate Widgets' JS file by @themesfactory
 * released under GPLv3.
 *
 * @package PostsInSidebar
 * @since 4.13.0
 * @since 4.14.0 Changed functions names to avoid conflicts with Duplicate Widgets plugin.
 */

(function ($) {
    if (!window.Pis) window.Pis = {};

    Pis.CloneWidget = {
        // Initialize
        init: function () {
            $('body').on('click', '.widget-control-actions .pis-clone-me', Pis.CloneWidget.Clone);
            Pis.CloneWidget.Bind();
        },

        // Add Clone button to widgets control buttons
        Bind: function () {
            $('#widgets-right').off('DOMSubtreeModified', Pis.CloneWidget.Bind);
            $('*[id*=pis_posts_in_sidebar-]:visible .widget-control-actions:not(.pis-cloneable)').each(function () {
                var $widget = $(this);

                var $clone = $('<a>');
                var clone = $clone.get()[0];
                $clone.addClass('pis-clone-me pis-clone-action')
                    .attr('title', pis_js_duplicate_widget.title)
                    .attr('href', '#')
                    .html(pis_js_duplicate_widget.text);


                $widget.addClass('pis-cloneable');
                $clone.insertAfter($widget.find('.alignleft .widget-control-remove'));

                //Separator |
                clone.insertAdjacentHTML('beforebegin', ' | ');
            });

            $('#widgets-right').on('DOMSubtreeModified', Pis.CloneWidget.Bind);
        },

        // Cloning the widget with support for text widget with tinyMce (Wp Editor)
        Clone: function (ev) {
            var $original = $(this).parents('.widget');
            var $widget = $original.clone();

            // Find this widget's ID base. Find its number, duplicate.
            var idbase = $widget.find('input[name="id_base"]').val();
            var number = $widget.find('input[name="widget_number"]').val();
            var mnumber = $widget.find('input[name="multi_number"]').val();
            var highest = 0;

            $('input.widget-id[value|="' + idbase + '"]').each(function () {
                var match = this.value.match(/-(\d+)$/);
                if (match && parseInt(match[1]) > highest)
                    highest = parseInt(match[1]);
            });

            var newnum = highest + 1;

            $widget.find('.widget-content label,input,select,textarea').each(function () {
                var replace_what = mnumber > 0 ? mnumber : number;
                if ($(this).attr('name')) {
                    $(this).attr('name', $(this).attr('name').replace(replace_what, newnum));
                }
                if ($(this).attr('id')) {
                    $(this).attr('id', $(this).attr('id').replace(replace_what, newnum));
                }
                if ($(this).attr('for')) {
                    $(this).attr('for', $(this).attr('for').replace(replace_what, newnum));
                }
            });

            // assign a unique id to this widget:
            var highest = 0;
            $('.widget').each(function () {
                var match = this.id.match(/^widget-(\d+)/);

                if (match && parseInt(match[1]) > highest)
                    highest = parseInt(match[1]);
            });
            var newid = highest + 1;

            $widget[0].id = 'widget-' + newid + '_' + idbase + '-' + newnum;
            $widget.find('input.widget-id').val(idbase + '-' + newnum);
            $widget.find('input.widget_number').val(newnum);
            //$widget.find('input.widget-control-save').removeAttr('disabled').val('Save');
            $widget.hide();
            $original.after($widget);
            $widget.fadeIn();
            // Not exactly sure what multi_number is used for.
            $widget.find('.multi_number').val(newnum);

            wpWidgets.save($widget, 0, 0, 1);

            ev.stopPropagation();
            ev.preventDefault();
        }
    };

    $(Pis.CloneWidget.init);
})(jQuery);
