jQuery(document).ready(function($){
    
    var timeout = '',
        time    = 500;
    
    // Shortcode builder.
    var build_shortcode = function(){

        var markup = $("#wpcalel-shortcode-tmpl").html(),
            params = [],
            output = '';
        
        $('.wpcalel-builder tr:visible').each(function(){
            
            var obj = $('input[type="text"], input[type="checkbox"]:checked, select', this);
            
            var name = $(obj).prop('id'),
                value;
                
            value = $(obj).val();
            
            if ( value ) {
                params.push(name + '="' + value + '"');
            }
        });
        
        output = markup.replace(/\{\{params\}\}/, params.join(' '));
        $("#wpcalel-shortcode").html( output );
    };
    
    
    // Add color picker.
    var added_clearer = false;
    $('.color-field').wpColorPicker({
        change: function(){
            clearTimeout(timeout);
            timeout = setTimeout(build_shortcode, time);
            if ( ! added_clearer ) {
                $('.wp-picker-clear').on('click', function(){
                    $(this).parent('.wp-picker-input-wrap').find('.color-field').change();
                });
                added_clearer = true;
            }
        }
    });
    
    
    // Handle form changes.
    $(document).on('keyup change', '#Wp_Cal_Embed_Lite', function(){
        clearTimeout(timeout); 
        timeout = setTimeout(build_shortcode, time); 
    });

    $("#wpcalel-shortcode").on('focus clear', function(e){
        e.stopPropagation();
    });
    

    // Show/hide landing page.
    var show_hide_landing_page = function(){
        
        clearTimeout(timeout);
        
        if ( $('#url option:selected').data('is-profile') ) {
            $('tr.landing_page_details:not(:visible)').show();    
        }
        else {
            $('tr.landing_page_details:visible').hide();
        }
        
        timeout = setTimeout(build_shortcode, time);
    };
    
    $("#url").on( 'change', function(){ show_hide_landing_page(); });
    show_hide_landing_page();
    
    
    // Show/hide rows.
    var show_hide_rows = function(obj){
        var type  = $('option:selected', obj).val(),
            speed = 500;
            
        clearTimeout(timeout);
        
        if ( 'inline' === type ) {
            $('tr.popup-widget, tr.link-widget, tr.widget-settings').hide(speed);
        }
        else if ( 'popup' === type ){
            $('tr.link-widget:not(.popup-widget):visible').hide(speed);
            $('tr.popup-widget, tr.widget-settings:not(:visible)').show(speed);
        }
        else { // link
            $('tr.popup-widget:not(.link-widget):visible').hide(speed);
            $('tr.link-widget:not(:visible), tr.widget-settings:not(:visible)').show(speed);
        }
      
        timeout = setTimeout(build_shortcode, time);
    };
    
    $('#widget').on( 'change', function(){
        show_hide_rows(this);
    });
    show_hide_rows( $('#widget') );
    
    
    // Select and copy Textarea
    $("#wpcalel-shortcode").on( 'focus', function(){
        this.select();
    });
    
    $("#wpcalel-click-to-copy").on( 'click', function(e){
        e.preventDefault();
        
        var click_to = $(this).data('click-to'),
            copied   = $(this).data('copied'),
            self     = this;
       
        $("#wpcalel-shortcode").focus();
        document.execCommand("Copy");
        $(self).text( copied );
        $("#wpcalel-shortcode").blur();
        setTimeout( function() { $(self).text( click_to ); }, 2000 );
    });
});