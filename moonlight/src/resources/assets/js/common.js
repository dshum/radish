$(function() {
    $.blockUI = function(handle) {
        $('.block-ui').fadeIn('fast', handle);
    };
    
    $.unblockUI = function(handle) {
        $('.block-ui').fadeOut('fast', handle);
    };
    
    $.alert = function(content, handle) {
        $('.alert .content').html(content);
        $('.alert').fadeIn('fast', handle);
    };
    
    $.alertDefaultError = function(handle) {
        $('.alert .content').html('Произошла какая-то ошибка.<br>Обновите страницу.');
        $('.alert').fadeIn('fast', handle);
    };
    
    $.alertClose = function(content, handle) {
        $('.alert .content').html('');
        $('.alert').fadeOut('fast', handle);
    };
    
    $.confirm = function(content, handle) {
        $('.confirm .content').html(content);
        $('.confirm').fadeIn('fast', handle);
    };
    
    $.confirmClose = function(content, handle) {
        $('.confirm').fadeOut('fast', handle);
    };
    
    $('.hamburger').click(function() {
        $('.sidebar').fadeToggle('fast');

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