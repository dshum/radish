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
          container.find('select').removeAttr('disabled');
      } else {
          container.addClass('dnone');
          container.find('input').attr('disabled', 'disabled');
          container.find('select').attr('disabled', 'disabled');
      }
    });

    var cancelSelection = function() {
        $('left').html('<span class="hamburger"><span class="glyphicons glyphicons-menu-hamburger"></span></span>');
        $('center').html('<a href="'+homeUrl+'">'+title+'</a>');
        $('right').html('<a href="'+searchUrl+'"><span class="glyphicons glyphicons-search"></span></a>');
        $.bottomMenuClose();

        $('ul.elements > li.checked')
            .prop('checked', false)
            .removeClass('checked');

        checked = [];
    };

    $('body').on('click', '.next', function() {
        var next = $(this);
        var page = next.attr('page');
        var item = next.attr('item');

        next.addClass('waiting');
        $.blockUI();
        
        $('form').ajaxSubmit({
            url: elementsUrl,
            dataType: 'json',
            data: {
                item: item,
                page: page
            },
            success: function(data) {
                $.unblockUI();
            
                next.remove();

                if (data.html) {
                    $('.list-container').append(data.html);
                }
            },
            error: function() {
                $.unblockUI();
            
                next.removeClass('waiting');

                $.alertDefaultError();
            }
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
            $.bottomMenu();
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
            ones: ones,
            checked: checked
        }, function(data) {
            $.unblockUI();
            $.bottomMenuClose();
            
            if (data.error) {
                $.alert(data.error);
            } else if (data.copied) {
                document.location.href = document.location.href;
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
            ones: ones,
            checked: checked
        }, function(data) {
            $.unblockUI();
            $.bottomMenuClose();
            
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