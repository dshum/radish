$(function() {
    $('[name="comments"]').addClear({
        right: 10,
        paddingRight: "25px"
    });
    
    $('.submit-button').click(function() {
        $('form').submit();
    });

    $('[name="date-from"]').calendar({
        dateFormat: '%Y-%m-%d',
        selectHandler: function() {
            $('[name="date-from"]').val(this.date.print(this.dateFormat));
        }
    });

    $('[name="date-to"]').calendar({
        dateFormat: '%Y-%m-%d',
        selectHandler: function() {
            $('[name="date-to"]').val(this.date.print(this.dateFormat));
        }
    });

    $('#form-toggler').click(function() {
        $('#form-container').slideToggle('fast');
    });

    $('.reset').click(function() {
        $('[name="date-from"]').val(null);
        $('[name="date-to"]').val(null);
    });

    $('form').submit(function() {
        var url = $(this).attr('action');
        var comments = $('[name="comments"]').val();
        var user = $('[name="user"]').val();
        var type = $('[name="type"]').val();
        var dateFrom = $('[name="date-from"]').val();
        var dateTo = $('[name="date-to"]').val();

        $('#form-container').slideUp('fast', function() {
            $.blockUI();

            $.getJSON(url, {
                comments: comments,
                user: user,
                type: type,
                dateFrom: dateFrom,
                dateTo: dateTo
            }, function(data) {
                $.unblockUI();

                if (data.html) {
                    $('.list-container').html(data.html);
                }
            }).fail(function() {
                $.unblockUI();

                $.alertDefaultError();
            });
        });

        return false;
    });

    $('body').on('click', '.next', function() {
        var next = $(this);
        var page = next.attr('page');
        var url = $('form').attr('action');
        var comments = $('[name="comments"]').val();
        var user = $('[name="user"]').val();
        var type = $('[name="type"]').val();
        var dateFrom = $('[name="date-from"]').val();
        var dateTo = $('[name="date-to"]').val();

        next.addClass('waiting');
        $.blockUI();

        $.getJSON(url, {
            comments: comments,
            user: user,
            type: type,
            dateFrom: dateFrom,
            dateTo: dateTo,
            page: page
        }, function(data) {
            $.unblockUI();

            next.remove();

            if (data.html) {
                $('.list-container').append(data.html);
            }
        }).fail(function() {
            $.unblockUI();

            $.alertDefaultError();
        });
    });
});