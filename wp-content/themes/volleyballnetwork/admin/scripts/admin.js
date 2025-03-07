jQuery(document).ready(function($) {
    if($('body').hasClass('post-type-division')){
        // do nothing
    }
    else{
        $("#taxonomy-city input[type=checkbox]").each(function() {
            $(this).attr("type", "radio");
        });
        $("#taxonomy-state input[type=checkbox]").each(function() {
            $(this).attr("type", "radio");
        });
    }
    $("#taxonomy-country input[type=checkbox]").each(function() {
        $(this).attr("type", "radio");
    });
});