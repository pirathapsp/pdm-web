( function( $ ) {

  $(document).ready(function($){

  	// Masonry post grid.
  	if ( $('#masonry-loop').length > 0 ) {
		var masonry_loop_container = document.querySelector('#masonry-loop');
		var msnry_loop;
		imagesLoaded( masonry_loop_container, function() {
			msnry_loop = new Masonry( masonry_loop_container, {
				itemSelector: '.masonry-entry'
			});
		});

		// Popup.
		$('#masonry-loop a.popup-link').magnificPopup({
			type: 'image',
			removalDelay: 300,
			gallery: {
				enabled: true
			}
		});
  	}

  	$('.portfolio-filter a', '#photomania-portfolio').on('click',function(e){
  		e.preventDefault();
  		var $this = $(this);
  		var term_id = $this.data('term_id');
  		var term_url = $this.data('term_url');
  		var data = {
  			'action': 'photomania_portfolio_fetch_posts',
  			'term_id': term_id
  		};

  		// Make active.
  		$('.portfolio-filter a', '#photomania-portfolio').each(function(e){
  			$(this).removeClass('current');
  		});
  		$this.addClass('current');

  		// Show loading.
  		$('#portfolio-loading').show();

  		// AJAX.
  		$.post( Photomania_Custom_Options.ajaxurl, data, function(response){

  			// Hide loading.
  			$('#portfolio-loading').hide();

  			if ( 1 === response.status ) {

	  			var post_template = wp.template( 'portfolio-post' );
	  			var posts = response.posts;
	  			var markup = '';
  				_.each( posts, function( item ){
		  			markup += post_template( item );
  				});
	  			jQuery( '#portfolio-output' ).html( markup );

	  			// Update browse more button.
	  			$('#portfolio-browse-more').attr( 'href', term_url );

	  			// Masonry implementation.
	  			var masonry_container = document.querySelector('#portfolio-output');
	  			var portfolio_msnry;
				imagesLoaded( masonry_container, function() {
					portfolio_msnry = new Masonry( masonry_container, {
						itemSelector: '.portfolio-item'
					});
				});

				// Popup.
				$('#portfolio-output a.popup-link').magnificPopup({
					type: 'image',
					removalDelay: 300,
					gallery: {
						enabled: true
					}
				});
  			}
  		} );
  	});

  	// Trigger first portfolio category.
  	$('.portfolio-filter a', '#photomania-portfolio').first().trigger('click');

  	// Team hover.
  	$('.thumb-summary-wrap').hover( function(){
  		$(this).children('.our-team-summary').stop().slideDown(300);
  	}, function(){
  		$(this).children('.our-team-summary').stop().slideUp(300);
  	});

  	// Search in Header.
  	if( $('.search-icon').length > 0 ) {
  		$('.search-icon').click(function(e){
  			e.preventDefault();
  			$('.search-box-wrap').slideToggle();
  		});
  	}

    // Trigger mobile menu.
    $('#mobile-trigger').sidr({
		timing: 'ease-in-out',
		speed: 500,
		source: '#mob-menu',
		name: 'sidr-main'
    });

    // Implement go to top.
    if ( 1 === parseInt( Photomania_Custom_Options.go_to_top_status, 10 ) ) {
    	var $scroll_obj = $( '#btn-scrollup' );
    	$( window ).scroll(function(){
    		if ( $( this ).scrollTop() > 100 ) {
    			$scroll_obj.fadeIn();
    		} else {
    			$scroll_obj.fadeOut();
    		}
    	});

    	$scroll_obj.click(function(){
    		$( 'html, body' ).animate( { scrollTop: 0 }, 600 );
    		return false;
    	});
    } // End if go to top.

  });

} )( jQuery );
