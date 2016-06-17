$(function() {
    var move = false;
    
    var moving = function(el, speed) {
        if ( ! move) return false;
        
        el.animate({marginLeft: 2}, speed, function() {
            el.animate({marginLeft: 0}, speed, function() {
                moving(el);
            });
        });
    };
    
    $('.rubrics.sortable').sortable({
        disabled: true,
        start: function() {
            return true;
        },
        stop: function(event, ui) {
            if ( ! ui.item.context.parentElement) return false;

            var result = $(ui.item.context.parentElement).sortable('serialize');

            $.post(
                favoriteUrl+'?action=orderRubrics&'+result,
                {},
                function(data) {},
                'json'
            );
    
            return true;
        }
    }).disableSelection();

    $('.elements.sortable').sortable({
        disabled: true,
        start: function() {
            return true;
        },
        stop: function(event, ui) {
            if ( ! ui.item.context.parentElement) return false;

            var result = $(ui.item.context.parentElement).sortable('serialize');

            $.post(
                favoriteUrl+'?action=order&'+result,
                {},
                function(data) {},
                'json'
            );
    
            return true;
        }
    }).disableSelection();

    $('body').on('click', '.remove[rubricId]', function() {
        var remove = $(this);
        var rubricId = $(this).attr('rubricId');

        $.post(favoriteUrl, {
            rubricId: rubricId,
            action: 'dropRubric'
        }, function(data) {
            if (data.deleted) {
                remove.parents('.block-elements').fadeOut('fast').remove();
            }
        });
    });

    $('.remove[classId]').click(function() {
        var remove = $(this);
        var classId = $(this).attr('classId');

        $.post(favoriteUrl, {
            classId: classId,
            action: 'drop'
        }, function(data) {
            if (data.deleted) {
                var ul = remove.parents('ul');
                var block = remove.parents('.block-elements');
                var h2 = block.find('h2');
                var rubricId = ul.attr('rubricId');

                remove.parents('li').fadeOut('fast').remove();

                if ( ! ul.children('li').length) {    
                    ul.remove();
                    h2.append('<span class="remove" rubricId="'+rubricId+'"><div><span class="halflings halflings-remove-circle"></span></div></span>');
                    $('.remove[rubricId="'+rubricId+'"]').children().fadeIn('fast');
                }
            }
        });
    });

    $('.edit-favorites-toggler').click(function() {
        var toggler = $(this);
        var enabled = toggler.attr('enabled');

        if (enabled == 'true') {
            $('.remove').children().fadeOut(200);
            $('.sortable').sortable({ disabled: true });
            
            move = false;

            toggler.attr('enabled', 'false');
        } else {
            $('.remove').children().fadeIn(200);
            $('.sortable').sortable({ disabled: false });
            
            move = true;
            
            $('.sortable > li').each(function(index) {
                var el = $(this);

                setTimeout(function() {
                    moving(el, 200);
                }, index * 200);
            });

            $('.sortable h2').each(function(index) {
                var el = $(this);

                setTimeout(function() {
                    moving(el, 400);
                }, index * 200);
            });
            
            toggler.attr('enabled', 'true');
        };
    });
});