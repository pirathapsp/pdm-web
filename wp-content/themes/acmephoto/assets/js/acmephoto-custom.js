jQuery(document).ready(function($) {
    /*responsive menu toggle*/
    $('.navbar-toggle').click(function () {
        $('#navbar .main-navigation').slideToggle();
    });

    /*sticky menu*/
    function stickyMenu() {
        var scrollTop = $(window).scrollTop();
        var at_navbar_controller = $('.at-navbar-controller');
        var slider_feature_wrap = $('.slider-feature-wrap');
        if ( at_navbar_controller.length){
            if($(window).width() > 1023 ){
                var offset = slider_feature_wrap.height();
                if ( scrollTop > offset ) {
                    $('.at-navbar').addClass('navbar-small');
                    $('#top-header').hide();
                }
                else {
                    $('.at-navbar').removeClass('navbar-small');
                    $('#top-header').show();
                }
            }
            else{
                $('.at-navbar').addClass('navbar-small');
                $('#top-header').hide();
            }
        }

    }
    //What happen on window scroll
    stickyMenu();
    $(window).on("scroll", function (e) {
        setTimeout(function () {
            stickyMenu();
        }, 300)
    });
    $(window).resize(function () {
        stickyMenu();
    });

    /*fixed menu in margin*/
    function at_fixed_content_margin() {
        if( $('body').hasClass('not-front-page') ){
            var header_height = $('.at-navbar').height();
            $('.site-content').css('margin-top',header_height + 10);
        }
    }
    at_fixed_content_margin();
    $(window).resize(function () {
        at_fixed_content_margin();
    });

    /*featured slider*/
    if( $(".feature-slider > .item").length < 2 ){
        $(".feature-slider > .cycle-prev").hide();
        $(".feature-slider > .cycle-next").hide();
    }
    
    /*drop-down menu fixed in ipad*/
    jQuery('.menu-item-has-children > a').click(function(){
        var at_this = jQuery(this);
        if( at_this.hasClass('at-clicked')){
            return true;
        };
        var at_width = jQuery(window).width();
        if( at_width > 992 && at_width <= 1230 ){
            at_this.addClass('at-clicked');
            return false;
        }
    });

    // MASSONRY
    $(window).load(function(){
        var $masonry_boxes = $( '.masonry-start' );
        $masonry_boxes.hide();

        var $container = $( '#masonry-loop' );
        $container.imagesLoaded( function(){
            $masonry_boxes.fadeIn( 'slow' );
            $container.masonry({
                itemSelector : '.masonry-post'
            });
        });
        $(window).resize(function () {
            $container.masonry('bindResize')
        });
    });

    /*new pagination style*/
    var paged = parseInt(acmephoto_ajax.paged) + 1;
    var max_num_pages = parseInt(acmephoto_ajax.max_num_pages);
    var next_posts = acmephoto_ajax.next_posts;


    $(document).on( 'click', '.show-more', function( event ) {
        event.preventDefault();
        var show_more = $(this);
        var click = show_more.attr('data-click');

        if( (paged-1) >= max_num_pages){
            show_more.html(acmephoto_ajax.no_more_posts)
        }

        if( click == 0 || (paged-1) >= max_num_pages){
            return false;
        }
        show_more.html('<i class="fa fa-spinner fa-spin fa-fw"></i>');
        show_more.attr("data-click", 0);
        var page = parseInt( show_more.attr('data-number') );


        $('#acmephoto-temp-post').load(next_posts + ' article.post', function() {
            /*http://stackoverflow.com/questions/17780515/append-ajax-items-to-masonry-with-infinite-scroll*/
            paged++;/*next page number*/
            next_posts = next_posts.replace(/(\/?)page(\/|d=)[0-9]+/, '$1page$2'+ paged);

            var html = $('#acmephoto-temp-post').html();
            $('#acmephoto-temp-post').html('');

            // Make jQuery object from HTML string
            var $moreBlocks = $( html ).filter('article.masonry-post');

            // Append new blocks to container
            $('#masonry-loop').append( $moreBlocks ).imagesLoaded(function(){
                // Have Masonry position new blocks
                $('#masonry-loop').masonry( 'appended', $moreBlocks );
            });

            show_more.attr("data-number", page+1);
            show_more.attr("data-click", 1);
            show_more.html("<i class='fa fa-refresh'></i>"+acmephoto_ajax.show_more)

        });
        return false;
    });


    //Sickey Sidebar
    if($('body').hasClass('at-sticky-sidebar')){
        if($('body').hasClass('both-sidebar')){
            $('#primary-wrap, #secondary-right, #secondary-left').theiaStickySidebar();
        }
        else{
            $('.secondary-sidebar, #primary').theiaStickySidebar();
        }
    }
});
