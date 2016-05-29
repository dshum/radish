$(function() {    
    $('.sortable').disableSelection();

    $('body').on('click', '.remove[rubricId]', function() {
        var remove = $(this);
        var rubricId = $(this).attr('rubricId');

        $.post(favoriteUrl, {
            rubricId: rubricId,
            action: 'dropRubric'
        }, function(data) {
            if (data.deleted) {
                remove.parents('h2').fadeOut('fast').remove();
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
                    h2.append('<span class="remove" rubricId="'+rubricId+'"><span><span class="halflings halflings-remove-circle"></span></span></span>');
                    $('.remove[rubricId="'+rubricId+'"]').fadeIn('fast');
                }
            }
        });
    });

    $('.edit-favorites-toggler').click(function() {
        var toggler = $(this);
        var enabled = toggler.attr('enabled');

        console.log($('.remove').length);

        if (enabled == 'true') {
            $('.remove').fadeOut('fast');
            $('.sortable').sortable({ disabled: true });

            toggler.attr('enabled', 'false');
        } else {
            $('.remove').fadeIn('fast');
            $('.sortable').sortable({ disabled: false });

            toggler.attr('enabled', 'true');
        }
    });
});