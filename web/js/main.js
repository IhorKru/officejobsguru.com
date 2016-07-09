$(function() {

    $('.order_BTN').click(function() {
        $('.window-BG').addClass('active');
        $('.window-BG').css({'height': $(document).height()});
        $('.window-BG.active  .windows-help-table-cell').css({'height': $(window).height()});
    });

    $('.window-close').click(function() {
        $('.window-BG').toggle();
    });

    if ($('#alert-text ul li').length > 0) {
        $('.window-BG').toggle();
    }

    $('.swith-red').click(function() {
        $('.form-red').addClass('active');
    });


});

