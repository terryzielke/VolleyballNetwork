/**
 * Calendly JS handler.
 *
 * @see https://help.calendly.com/hc/en-us/articles/360020052833-Advanced-embed-options
 */
jQuery(document).ready(function($){

    var needs_ajax = false;
    
    var data;

    // Shortcut for the data names.
    var prefixed = function(param) { return 'wpcalel-' + param; }

    // Read a page's GET URL variables and return them as an associative array.
    var getUrlVars = function()
    {
        var vars = {}, hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            //vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    };
    
    var dyn_functions = {};
    
    // Function that initializes the calendly widgets.
    init_calendly = function( params ){
        $(params).each(function(i,data){
            var method = 'init_calendly_' + data.type;
            dyn_functions[method]( data );
        });
    };
    
    // Inline widget rendering.
    dyn_functions.init_calendly_inline = function( data ) {
        Calendly.initInlineWidget({
            url: data.url,
            parentElement: document.getElementById(data.id),
            prefill: 'undefined' !== typeof wpcalel.prefill ? wpcalel.prefill : data.prefill
        });
    };
    
    // Popup widget rendering.
    dyn_functions.init_calendly_popup = function( data ) {
        Calendly.initBadgeWidget({
            url: data.url,
            parentElement: document.getElementById(data.id),
            prefill: 'undefined' !== wpcalel.prefill ? wpcalel.prefill : data.prefill,
            text: data.text, 
            color: data.color, 
            textColor: data.textColor, 
            branding: data.branding
        });
    };
    
    // Calendy link rendering.
    init_link_widget = function( data ) {
        Calendly.initPopupWidget({
            url: data.url,
            prefill: 'undefined' !== typeof wpcalel.prefill ? wpcalel.prefill : data.prefill
        });    
    };
    
    // Parse the element's data to use with Calendly.
    var parse_data = function( obj ){

        var query_vars = getUrlVars();
        
        // Set up the div's params
        data = {
            id:  $(obj).prop('id'),
            url: $(obj).data(  prefixed( 'url') ),
            type: $(obj).data(  prefixed( 'type' ) ),
            text: $(obj).data(  prefixed( 'text' ) ),
            color: $(obj).data(  prefixed( 'color' ) ),
            textColor: $(obj).data(  prefixed( 'textColor' ) ),
            branding: $(obj).data(  prefixed( 'branding' ) ),
            prefill: {
                customAnswers: {}
            }
        };

            
        // Custom Answers
        $([...Array(11).keys()].splice(1)).each(function(i,ax){
            ax = 'a' + ax;
            // Query strings, if enabled, take precedence
            if ( true === $(obj).data( prefixed( 'query-str' ) ) && 'undefined' !== typeof query_vars[ax] ) {
                data.prefill.customAnswers[ ax ] = decodeURIComponent( query_vars[ax] );
            }
            else if ( 'undefined' !== typeof $(obj).data( prefixed( ax ) ) ) {
                data.prefill.customAnswers[ ax ] = $(obj).data( prefixed( ax ) );
            }
        });
        
        
        // Prefills
        if ( true === $(obj).data( prefixed( 'prefill' ) ) ) {
            needs_ajax = true; // get user information if logged in.
        }
        
        // Query vars enabled and present?
        // This gets overridden by ajax if user is logged in.
        if ( true === $(obj).data( prefixed( 'query-str' ) ) ) {
            $(['name', 'firstName', 'lastName', 'email']).each( function(i,key){
                if ( 'undefined' !== typeof query_vars[key] ) {
                    data.prefill[key] = decodeURIComponent(query_vars[key]);
                }
            });
        }

        return data;    
    };
    
    // Runs the ajax call to get 
    var do_ajax = function( callback ){
        
        $.ajax({
            url: wpcalel.ajax_url,
            method: 'POST',
            data: {
                action: 'wpcalel-userinfo',
                nonce:  wpcalel.nonce
            }
        })
        .done(function( out ){
            var user_data = out.data;

            if ( out.success && user_data ) {
                data.prefill = {...data.prefill, ...user_data};
            }
        })
        .fail(function( out ){
            if ( console && console.log ) {
                console.log( out.data );
            }
        })
        .always(function(){
            // Call Calendly.initInlineWidget for each div
            if ( 'function' === typeof callback ) {
                callback();
            }
        });
    };
    
    /***** The meat *****/
    var els = $('div[id^="wpcalel-calendly-"],a[id^="wpcalel-calendly-"]');
    
    if ( els.length ) {
        var params = { 'DIV': [], 'A': [] },
            link_callback = function(){}, // redefined if there are calendly links
            div_callback  = function(){}; // redefined if there are calendly divs        

        // Prepare the prefill and customAnswers objects
        $(els).each(function(i,obj){
            data = parse_data( obj ), // Sets needs_ajax
            tag  = $(obj).prop('tagName');

            params[tag].push(data);
        });
        
        if ( params.DIV.length ) {
            div_callback = function(){
                init_calendly( params.DIV );
            };
        }
        
        if( params.A.length ) {
            link_callback = function() {
                $(params.A).each(function(i,data) {
                    $(document).on( 'click', '#'+data.id, function(e){
                        e.preventDefault();
                        init_link_widget( data );
                    });
                });
            };
        }
        var init = function(){
            link_callback();
            div_callback();
        };
        
        if ( true === needs_ajax ) {
            do_ajax( function(){
                init();
            });
        }
        else{
            init();
        }
    }
});
