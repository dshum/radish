$(function() {
    $.blockUI = function(handle) {
        $('.block-ui').fadeIn(100, handle);
    };
    
    $.unblockUI = function(handle) {
        setTimeout(function() {
            $('.block-ui').fadeOut(100, handle); 
        }, 200);
    };
    
    $.alert = function(content, handle) {
        if (content) {
            $('.alert .content').html(content);
        }
        $('.alert').fadeIn('fast', handle);
    };
    
    $.alertDefaultError = function(handle) {
        $('.alert .content').html('Произошла какая-то ошибка.<br>Обновите страницу.');
        $('.alert').fadeIn('fast', handle);
    };
    
    $.alertClose = function(handle) {
        $('.alert').fadeOut('fast', handle);
    };
    
    $.confirm = function(content, selector, handle) {
        var container = selector ? $(selector) : $('.confirm');
        
        if (content) {
            container.find('.content').html(content);
        }
        
        container.fadeIn('fast', handle);
    };
    
    $.confirmClose = function(selector, handle) {
        var container = selector ? $(selector) : $('.confirm');
        
        container.fadeOut('fast', handle);
    };
    
    $('body').on('click', '.hamburger', function() {
        var mode = $(this).attr('mode');
        
        if (mode == 'active') {
            $('.sidebar').animate({
                left: '-100%', 
                backgroundColor: '#eee'
            });
            $(this).attr('mode', 'none');
        } else {
            $('.sidebar').animate({
                left: 0, 
                backgroundColor: 'white'
            });
            $(this).attr('mode', 'active');
        }

        return false;
    });
    
    $('.alert .container').click(function(e) {
        return false;
    });
    
    $('.alert .hide').click(function() {
        $('.alert').fadeOut('fast');
    });
    
    $('.alert').click(function() {
        $('.alert').fadeOut('fast');
    });

    $('.confirm .container').click(function(e) {
        return false;
    });
    
    $('.confirm .cancel').click(function() {
        $('.confirm').fadeOut('fast');
    });
});