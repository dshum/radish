$(function() {
    $('left a').click(function() {
        history.back(1);
        
        return false;
    });
    
    $('#options-toggler').click(function() {
        $('.bottom-context-menu').fadeToggle('fast');
    });
    
    $(':file').wrap(
        $('<div />').css({height: 0, width: 0, overflow: 'hidden'})
    );

    $(':file').change(function(e) {
        var name = $(this).attr('name');
        var path = e.target.files[0] ? e.target.files[0].name : 'Выберите файл';

        $('.file[name="'+name+'"]').html(path);
        
        $('[name="'+name+'_drop"]').prop('checked', false);
    });

    $('.file[name]').click(function() {
        var name = $(this).attr('name');
        var fileInput = $(':file[name="'+name+'"]');

        fileInput.click();
    });
    
    $('.reset[file]').click(function() {
        var name = $(this).attr('name');
        
        $('.file[name="'+name+'"]').html('Выберите файл');
        $(':file[name="'+name+'"]').val('');
    });
});