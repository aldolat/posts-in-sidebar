/**
 * Posts in Sidebar javascript for duplicating a widget.
 * This file is a modified version of Duplicate Widgets' js file from @themesfactory
 * released under GPLv3.
 *
 * @package PostsInSidebar
 * @since 4.13
 */

(function ($) {
    if (!window.Ag) window.Ag = {};

    Ag.CloneWidgets = {
        // Initialize
        init: function () {
            $('body').on('click', '.widget-control-actions .clone-me', Ag.CloneWidgets.Clone);
            Ag.CloneWidgets.Bind();
        },

        // Add Clone button to widgets control buttons
        Bind: function () {
            $('#widgets-right').off('DOMSubtreeModified', Ag.CloneWidgets.Bind);
            $('*[id*=pis_posts_in_sidebar-]:visible .widget-control-actions:not(.meks-cloneable)').each(function () {
                var $widget = $(this);

                var $clone = $('<a>');
                var clone = $clone.get()[0];
                $clone.addClass('clone-me meks-clone-action')
                    .attr('title', pis_js_duplicate_widget.title)
                    .attr('href', '#')
                    .html(pis_js_duplicate_widget.text);


                $widget.addClass('meks-cloneable');
                $clone.insertAfter($widget.find('.alignleft .widget-control-remove'));

                //Separator |
                clone.insertAdjacentHTML('beforebegin', ' | ');
            });

            $('#widgets-right').on('DOMSubtreeModified', Ag.CloneWidgets.Bind);
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
            $widget.find('input.widget-control-save').removeAttr('disabled').val('Save');
            $widget.hide();
            $original.after($widget);
            $widget.fadeIn();
            // Not exactly sure what multi_number is used for.
            $widget.find('.multi_number').val(newnum);

            // Support for text widget
            if ($widget.find('.text-widget-fields').length > 0) {
                var iframeId = $widget.find('.mce-edit-area > iframe').attr('id');
                var tinyMceId = iframeId.substring(0, iframeId.length - 4);
                var textAreaValue = '';
                if($widget.find('.wp-core-ui.wp-editor-wrap').hasClass('tmce-active')){
                    textAreaValue = tinyMCE.get(tinyMceId).getContent();
                }else{
                    textAreaValue = $widget.find('.widefat.text.wp-editor-area').val();
                }
                var timeStamp = Math.floor(Date.now() / 1000);
                var $tmceActive = $widget.find('.wp-editor-wrap');

                $tmceActive.parent().html('<textarea id="e_' + timeStamp + '_text">' + textAreaValue + '</textarea>');
                wp.editor.initialize('e_' + timeStamp + '_text', {tinymce: true, quicktags: true});
                $('#e_' + timeStamp + '_text').addClass('widefat text wp-editor-area');
                $widget.find('.text-widget-fields > p label').attr('for', 'e_' + timeStamp + '_title');
                $widget.find('.text-widget-fields > p input').attr('id', 'e_' + timeStamp + '_title');
            }
            wpWidgets.save($widget, 0, 0, 1);

            ev.stopPropagation();
            ev.preventDefault();
        }
    };

    $(Ag.CloneWidgets.init);
})(jQuery);
