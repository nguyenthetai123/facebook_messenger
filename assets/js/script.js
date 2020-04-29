(function($) {
    'use strict'; 
    
    $(document).ready(function() {
        
        (function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = '//connect.facebook.net/'+my_options.language+'/sdk.js#xfbml=1&version=v2.5';
			fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
        if(typeof(my_options.type) && my_options.type == 'page'){
            if(my_options.display_pages == 'yes'){
                $('body').append('<div id="wpe_fb-mess">\
                    <div class="wpe_button-fm"></div>\
                    <div id="fb-root"></div>\
                    <div class="fb-page wpe_fm-page" data-tabs="messages" data-href="'+my_options.url_page+'" data-width="300" data-height="300" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"></div>\
                </div>');
            }else{
                $.each( my_options.display_page, function( key, value ) {
                    $('body.page-id-'+value+'').append('<div id="wpe_fb-mess">\
                        <div class="wpe_button-fm"></div>\
                        <div id="fb-root"></div>\
                        <div class="fb-page wpe_fm-page" data-tabs="messages" data-href="'+my_options.url_page+'" data-width="300" data-height="300" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"></div>\
                    </div>');
                });
                $('body.single-post').append('<div id="wpe_fb-mess">\
                    <div class="wpe_button-fm"></div>\
                    <div id="fb-root"></div>\
                    <div class="fb-page wpe_fm-page" data-tabs="messages" data-href="'+my_options.url_page+'" data-width="300" data-height="300" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"></div>\
                </div>');
            }
            $('#wpe_fb-mess').draggable();
            $('.wpe_button-fm').on('click',function(){
                $('.wpe_fm-page').toggleClass('open reopen');
            });
            //position
            if(typeof(my_options.position_h) && my_options.position_h == 'left'){
                $('#wpe_fb-mess').addClass('wpe_positon_left');
                if(typeof(my_options.h_space)){
                    $('#wpe_fb-mess').css('left',my_options.h_space);
                }
            }else{
                $('#wpe_fb-mess').css('right',my_options.h_space);
            }
            if(typeof(my_options.position_v) && my_options.position_v == 'top'){
                $('#wpe_fb-mess').addClass('wpe_positon_top');
                if(typeof(my_options.v_space)){
                    $('#wpe_fb-mess').css('top',my_options.v_space);
                }
            }else if(my_options.position_v == 'bottom'){
                $('#wpe_fb-mess').addClass('wpe_positon_bottom');
                if(typeof(my_options.v_space)){
                    $('#wpe_fb-mess').css('bottom',my_options.v_space);
                }
            }
            if(typeof(my_options.icon_color)){
                $('.wpe_button-fm').css('background-color',my_options.icon_color);
            }
            if(typeof(my_options.icon_width)){
                $('.wpe_button-fm').css('width',my_options.icon_width+'px');
            }
            if(typeof(my_options.icon_height)){
                $('.wpe_button-fm').css('height',my_options.icon_height+'px');
            }
            if(typeof(my_options.icon_radius)){
                $('.wpe_button-fm').css('border-radius',my_options.icon_radius+'px');
            }
            if(typeof(my_options.image_icon)){
                $('.wpe_button-fm').css('background-image','url(' + my_options.image_icon + ')');
            }
            
        }
    });
}(window.jQuery));
