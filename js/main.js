$(document).ready(function() {
    $('#phone').mask('(999) 999-99-99');
});

$('#registration').submit(function(event) {

    var OK = true;

    event.preventDefault();

    $('#submit').hide();

    $(this).find("span").removeClass('red');

    if ($('#name').val() != '') { //name
        val = $('#name').val();

        var regexp = /^([є-їa-zа-я'-]+)\s+([є-їa-zа-я'-]+)$/i;

        if (!regexp.test(val)) {
            $('#name').parent().find("span.note").removeClass('visuallyhidden');
            $('#name').parent().find("span:first").addClass('red');

            OK = false;
        }

    } else {
        $('#name').parent().find("span.note").removeClass('visuallyhidden');
        $('#name').parent().find("span:first").addClass('red');
        OK = false;
    }

    if ($('#city').val() == '') { //city
        $('#city').parent().find("span.note").removeClass('visuallyhidden');
        $('#city').parent().find("span:first").addClass('red');
        OK = false;
    }

    if ($('#phone').val() == '') {
        $('#phone').parent().find("span.note").removeClass('visuallyhidden');
        $('#phone').parent().find("span:first").addClass('red');
        OK = false;
    }

    if ($('#email').val() != '') { //email
        val = $('#email').val();

        var regexp = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

        if (!regexp.test(val)) {
            $('#email').parent().find("span.note").removeClass('visuallyhidden');
            $('#email').parent().find("span:first").addClass('red');
            OK = false;
        }

    } else {
        $('#email').parent().find("span.note").removeClass('visuallyhidden');
        $('#email').parent().find("span:first").addClass('red');
        OK = false;
    }

    if (OK) {

        console.log('AJAX-request start');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function(data) {
                if ('OK' == data.RESULT) {
                    $('#reg').addClass('visuallyhidden');
                    $('#reg-error').addClass('visuallyhidden');
                    $('#reg-id').text(data.id);
                    $('#reg-ok').removeClass('visuallyhidden');
                } else {
                    $('#reg-error').removeClass('visuallyhidden');
                    $('#reg').removeClass('visuallyhidden');
                }
            },
            error: function() {
                console.log('AJAX-request error');
                $('#reg-error').removeClass('visuallyhidden');
                $('#reg').removeClass('visuallyhidden');
            }
        });
    }

    $('#submit').show();

    return OK;
});