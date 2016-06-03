$(function() {    
    var checked = [];
    var opened = open;
    
    $('#form-toggler').click(function() {
      $('#form-container').toggle();
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

        $('ul.items > li.checked')
            .prop('checked', false)
            .removeClass('checked');

        checked = [];
    };

    var loadElements = function(li) {
        var classId = li.attr('classId');
        var item = li.attr('item');
        
        $.getJSON(elementsUrl, {
            classId: classId,
            item: item
        }, function(data) {
            if (data.html) {
                $('.list-container[item="'+item+'"]').html(data.html).slideDown(200);
                
                opened = item;
            }
        }).fail(function() {
            $.alertDefaultError();
        });
    };

    $('ul.items > li').each(function() {
        var li = $(this);
        var classId = $(this).attr('classId');
        var item = $(this).attr('item');

        if (item != open) {
            $.getJSON(countUrl, {
                classId: classId, 
                item: item
            }, function(data) {
               if (data && data.count) {
                    var span = $('<span class="dnone total">'+data.count+'</span>');
                    var div = $('<div item="'+item+'" class="dnone list-container"></div>');

                    li.append(span).append(div);
                    span.fadeIn(200);
                } else {
                    li.addClass('grey');
                }
            });
        }
    });

    $('ul.items > li span.a').click(function() {
        var li = $(this).parents('li');
        var item = li.attr('item');

        if (li.hasClass('grey')) return false;
        if (opened == item) return false;

        if (opened) {
            $('.list-container[item="'+opened+'"]').slideUp(200, function() {
                cancelSelection();
                loadElements(li);
            });
        } else {
            loadElements(li);
        }

        return false;
    });

    $('body').on('click', '.next', function() {
        var next = $(this);
        var page = next.attr('page');
        var classId = next.attr('classId');
        var item = next.attr('item');

        next.addClass('waiting');
        $.blockUI();

        $.getJSON(elementsUrl, {
            classId: classId,
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
    
    $('#favorite-toggler').click(function() {
        var enabled = $(this).attr('enabled');
        var classId = $(this).attr('classId');

        if (enabled == 'true') {
            $.post(favoriteUrl, {
                classId: classId,
                action: 'drop'
            }, function(data) {
                $('#favorite-toggler').attr('enabled', false).removeClass('active'); 
            });
        } else {
            $.confirm();
        }
    });

    $(':text[name="rubric"]').autocomplete({
        serviceUrl: favoritesUrl,
        appendTo: $('span.autocomplete-container[name="rubric_auto"]'),
        minChars: 0
    });

    $('.ok').click(function() {
        var classId = $('#favorite-toggler').attr('classId');
        var rubric = $(':text[name="rubric"]').val();
        
        $.post(favoriteUrl, {
            classId: classId,
            rubric: rubric,
            action: 'add'
        }, function(data) {
            if (data.error) {
                $.alert(data.error);
            } else if (data.added) {
                $('#favorite-toggler').attr('enabled', true).addClass('active');
            }
            
            $(':text[name="rubric"]').val('');
            $.confirmClose();
        }).fail(function() {
            $.alertDefaultError();
        });
    });

    $('.cancel').click(function() {
        $('#favorite-toggler').attr('enabled', false).removeClass('active');
        $(':text[name="rubric"]').val('');
        $.confirmClose();
    });
});