$(function() {    
    var checked = [];
    var opened = open;
    var move = false;
    
    var moving = function(el, speed) {
        if ( ! move) return false;
        
        el.animate({marginLeft: 3}, speed, function() {
            el.animate({marginLeft: 0}, speed, function() {
                moving(el);
            });
        });
    };
    
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
        $.bottomMenuClose();

        $('ul.elements > li.checked')
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
                $('.list-container[item="'+item+'"]').html(data.html).slideDown(200, function() {
                    
                });
                
                opened = item;
            }
            
            if (data.onesCopy) {
                $('.confirm.copy .content').html(data.onesCopy);
            }
            
            if (data.onesMove) {
                $('.confirm.move .content').html(data.onesMove);
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
                    var addnew = $('li[item="'+item+'"] .addnew');
                    var total = $('<span class="dnone total">'+data.count+'</span>');
                    var container = $('<div item="'+item+'" class="dnone list-container"></div>');

                    addnew.before(total);
                    li.append(container);
                    total.fadeIn(200);
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
            $.confirm(null, '.confirm.favorite');
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
    
    $('body').on('click', '.order-toggler', function() {
        var toggler = $(this);
        var enabled = toggler.attr('enabled');
        var item = toggler.attr('item');

        if (enabled == 'true') {
            $('.sortable[item="'+item+'"]').sortable({ disabled: true });
            
            move = false;

            toggler.attr('enabled', 'false');
        } else {
            $('.sortable[item="'+item+'"]').sortable({
                disabled: false,
                stop: function(event, ui) {
                    var result = $('.sortable[item="'+item+'"]').sortable('serialize');

                    $.post(
                        orderUrl+'?'+result,
                        {},
                        function(data) {},
                        'json'
                    );
                }
            }).disableSelection();
            
            move = true;
            
            $('.sortable[item="'+item+'"] > li div[main="true"]').each(function(index) {
                var el = $(this);

                setTimeout(function() {
                    moving(el, 200);
                }, index * 200);
            });
            
            toggler.attr('enabled', 'true');
        }
    });
});