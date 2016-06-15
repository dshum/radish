$(function() {
    $('body').fadeIn(400);
    
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
    
    $.bottomMenu = function() {
        var height = $('.bottom-context-menu').height() + 11;
        
        $('.bottom-context-menu').css({
            bottom: '-'+height+'px',
            display: 'block'
        }).animate({
            bottom: 0
        }, 200);
    };
    
    $.bottomMenuClose = function() {
        var height = $('.bottom-context-menu').height() + 11;
        
        $('.bottom-context-menu').animate({
            bottom: '-'+height+'px'
        }, 200);
    };
    
    $('body').on('click', '.hamburger', function() {
        var mode = $(this).attr('mode');
        
        if (mode == 'active') {
            $('.sidebar').animate({
                left: '-100%', 
                backgroundColor: '#cccccc'
            }, 200);
            $(this).attr('mode', 'none');
        } else {
            $('.sidebar').animate({
                left: 0, 
                backgroundColor: '#ffffff'
            }, 300);
            $(this).attr('mode', 'active');
        }
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