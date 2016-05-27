$(function() {
    $(':file').wrap(
        $('<div />').css({height: 0, width: 0, overflow: 'hidden'})
    );

    $(':file').change(function(e) {
        var fileInput = $(this);
        var property = $(this).attr('name');
        var path = e.target.files[0] ? e.target.files[0].name : 'Выберите файл';

        $('.file[property="'+property+'"]').html(path);
        
        $('[name="'+property+'_drop"]').prop('checked', false);
    });

    $('.file[property]').click(function() {
        var property = $(this).attr('property');
        var fileInput = $(':file[name="'+property+'"]');

        fileInput.click();
    });
});