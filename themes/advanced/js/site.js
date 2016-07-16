$(function(){
    // Toggle hints
    $(".form-control")
        .focusin(function () {
            $(this).parent().next('div.app-help-block').fadeIn();
        })
        .focusout(function () {
            $(this).parent().next('div.app-help-block').hide();
        });

    // Toggle errors
    // $(".form-control").change(function () {
    //     $(this).parent().parent().removeClass('has-error');
    //     $(this).next('.help-block-error').hide();
    // });
});