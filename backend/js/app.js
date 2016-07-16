$(function() {
    /*
    "use strict";

    //Make the dashboard widgets sortable Using jquery UI
    $(".connectedSortable").sortable({
        placeholder: "sort-highlight",
        connectWith: ".connectedSortable",
        handle: ".box-header, .nav-tabs",
        forcePlaceholderSize: true,
        zIndex: 999999
    }).disableSelection();
    $(".connectedSortable .box-header, .connectedSortable .nav-tabs-custom").css("cursor", "move");

    // Disable iCheck
    $("input[type='checkbox']:not(.simple), input[type='radio']:not(.simple)").iCheck('destroy');
    */

   // save toggle navigation
   $('.sidebar-toggle').click(function () {
       if ($('body').hasClass('sidebar-collapse')) {
           //make full
           Cookies('sidebar-toggle-status', 'full');
       }else{
           //make mini
           Cookies('sidebar-toggle-status', 'mini');
       }
   });

});