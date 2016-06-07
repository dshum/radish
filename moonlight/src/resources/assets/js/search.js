$(function() {    
    var checked = [];
    
    $('.submit-button').click(function() {
        $('form').submit();
    });
    
    $('#form-toggler').click(function() {
      $('#form-container').toggle();
    });
    
    $(':text[name="search"]').autocomplete({
        serviceUrl: autocompleteUrl,
        params: {
            item: itemName
        },
        onSelect: function (suggestion) {
            $(':hidden[name="search_id"]').val(suggestion.id);
            $('form').submit();
        },
        appendTo: $('span.autocomplete-container[name="search_auto"]'),
        minChars: 0
    });

    $('.label[property]').click(function() {
      var property = $(this).attr('property');
      var container = $('[container="property"][property="'+property+'"]');

      if (container.hasClass('dnone')) {
          container.removeClass('dnone');
          container.find('input').removeAttr('disabled');
      } else {
          container.addClass('dnone');
          container.find('input').attr('disabled', 'disabled');
      }
    });

    var cancelSelection = function() {
        $('left').html('<span class="hamburger"><span class="glyphicons glyphicons-menu-hamburger"></span></span>');
        $('center').html('<a href="'+homeUrl+'">'+title+'</a>');
        $('right').html('<a href="'+searchUrl+'"><span class="glyphicons glyphicons-search"></span></a>');
        $('.bottom-context-menu').fadeOut('fast');

        $('ul.elements > li.checked')
            .prop('checked', false)
            .removeClass('checked');

        checked = [];
    };

    $('body').on('click', '.next', function() {
        var next = $(this);
        var page = next.attr('page');
        var item = next.attr('item');
        
        console.log(elementsUrl);

        next.addClass('waiting');
        $.blockUI();

        $.getJSON(elementsUrl, {
            item: item,
            page: page
        }, function(data) {
            $.unblockUI();
            
            next.remove();
            
            if (data.html) {
                $('.list-container').append(data.html);
            }
        }).fail(function() {
            $.unblockUI();
            
            next.removeClass('waiting');
            
            $.alertDefaultError();
        });
    });
    
    $('body').on('click', '.check', function() {
        var classId = $(this).attr('classId');

        $('ul.elements > li[classId="'+classId+'"]').toggleClass('checked');

        if ($(this).prop('checked') == true) {
            $(this).prop('checked', false);

            var i = checked.indexOf(classId);
            if (i > -1) {
                checked.splice(i, 1);
            }
        } else {
            $(this).prop('checked', true);

            checked.push(classId);
        }

        if (checked.length == 1) {
            $('left').html('<span>Выделено</span>');
            $('right').html('<span id="cancelSelection">Отмена</span>');
            $('.bottom-context-menu').fadeIn('fast');
        }

        if (checked.length) {
            $('center').html(checked.length);
        } else {
            cancelSelection();
        }
    });
    
    $('body').on('click', '#cancelSelection', function() {
        cancelSelection();
    });
    
    $('.button.restore').click(function() {
        $.confirm(null, '.confirm.restore');
    });
    
    $('.btn.restore').click(function() {
        $.confirmClose();
        $.blockUI();
        
        $.post(restoreUrl, {
            checked: checked
        }, function(data) {
            $.unblockUI();
            
            if (data.error) {
                $.alert(data.error);
            } else if (data.restored) {
                cancelSelection();
                
                for (var i in data.restored) {
                    var classId = data.restored[i];
                    
                    $('ul.elements > li[classId="'+classId+'"]').slideUp(200, function() {
                        $(this).remove();
                    });
                }
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
        
        $.post(deleteUrl, {
            checked: checked
        }, function(data) {
            $.unblockUI();
            
            if (data.error) {
                $.alert(data.error);
            } else if (data.deleted) {
                cancelSelection();
                
                for (var i in data.deleted) {
                    var classId = data.deleted[i];
                    
                    $('ul.elements > li[classId="'+classId+'"]').slideUp(200, function() {
                        $(this).remove();
                    });
                }
            }
        }).fail(function() {
            $.unblockUI();
                
            $.alertDefaultError();
        });
    });
});