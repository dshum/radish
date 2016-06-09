$(function() {
    $('left a').click(function() {
        history.back(1);
        
        return false;
    });
    
    $('#options-toggler').click(function() {
        $('.bottom-context-menu').fadeToggle('fast');
    });
    
    $('body').on('change', ':file', function(e) {
        var name = $(this).attr('name');
        var path = e.target.files[0] ? e.target.files[0].name : 'Выберите файл';

        $('.file[name="'+name+'"]').html(path);
        
        $('[name="'+name+'_drop"]').prop('checked', false);
    });

    $('body').on('click', '.file[name]', function() {
        var name = $(this).attr('name');
        var fileInput = $(':file[name="'+name+'"]');

        fileInput.click();
    });

    $('body').on('click', '.reset[file]', function() {
        var name = $(this).attr('name');
        
        $('.file[name="'+name+'"]').html('Выберите файл');
        $(':file[name="'+name+'"]').val('');
    });
    
    $('form').submit(function() {
        $('[name]').removeClass('invalid');
        $.blockUI();

        $(this).ajaxSubmit({
            url: this.action,
            dataType: 'json',
            success: function(data) {
                $.unblockUI();

                if (data.error) {
                    $.alert(data.error);
                } else if (data.errors) {
                    var message = '';

                    for (var field in data.errors) {
                        $('[name="'+field+'"]').addClass('invalid');

                        message += data.errors[field]+'<br />';
                    }

                    $.alert(message);  
                } else if (data.saved && data.views) {
                    for (var propertyName in data.views) {
                        var view = data.views[propertyName];
                        
                        $('div.row[property="'+propertyName+'"]').html(view);
                    }
                } else if (data.added && data.url) {
                    history.back(1);
                }
            },
            error: function() {
                $.unblockUI();
                
                $.alertDefaultError();
            }
        });

        return false;
    });
    
    $('.button.copy').click(function() {
        $.confirm(null, '.confirm.copy');
    });
    
    $('.btn.copy').click(function() {
        $.confirmClose();
        $.blockUI();
        
        var ones = {};
        
        $(':hidden[copy]').each(function() {
            var name = $(this).attr('copy');
            var value = $(this).val();
            
            ones[name] = value;
        });
        
        $.post(copyUrl, {
            ones: ones
        }, function(data) {
            $.unblockUI();
            $('.bottom-context-menu').fadeOut('fast');
            
            if (data.error) {
                $.alert(data.error);
            } else if (data.copied) {
                history.back(1);
            }
        }).fail(function() {
            $.unblockUI();
                
            $.alertDefaultError();
        });
    });
    
    $('.button.move').click(function() {
        $.confirm(null, '.confirm.move');
    });
    
    $('.btn.move').click(function() {
        $.confirmClose();
        $.blockUI();
        
        var ones = {};
        
        $(':hidden[move]').each(function() {
            var name = $(this).attr('move');
            var value = $(this).val();
            
            ones[name] = value;
        });
        
        $.post(moveUrl, {
            ones: ones
        }, function(data) {
            $.unblockUI();
            $('.bottom-context-menu').fadeOut('fast');
            
            if (data.error) {
                $.alert(data.error);
            } else {
                document.location.href = document.location.href;
            }
        }).fail(function() {
            $.unblockUI();
                
            $.alertDefaultError();
        });
    });
    
    $('.button.delete').click(function() {
        $.confirm(null, '.confirm.delete');
    });
    
    $('.btn.delete').click(function() {
        $.confirmClose();
        $.blockUI();
        
        $.post(deleteUrl, {}, function(data) {
            $.unblockUI();
            
            if (data.error) {
                $.alert(data.error);
            } else if (data.deleted) {
                history.back(1);
            }
        }).fail(function() {
            $.unblockUI();
                
            $.alertDefaultError();
        });
    });
});