/* eslint-disable no-undef */
/* eslint-disable eqeqeq */
/* eslint-disable no-console */
( function( $ ) {
	// Burger menu change style
	$( '.burger-menu' ).on( 'click', function() {
		this.classList.toggle( 'change' );
		$( '.main-navigation' ).toggleClass( 'active' );
		$( '.bg-overlay.black' ).fadeToggle( 1000 );
		$( 'body' ).toggleClass( 'active' );
	} );

	// Site Preloader
	$( '#ctn-preloader' ).fadeOut(); // will first fade out the loading animation
	$( '#preloader' ).delay( 350 ).fadeOut( 'slow' ); // will fade out the white DIV that covers the website.
	$( 'body' ).delay( 350 ).css( {
		overflow: 'visible',
	} );

	// Counter Function
	const timer = $( '.counter' );
	if ( timer.length ) {
		$( '.counter' ).counterUp( {
			delay: 10,
			time: 1200,
		} );
	}

	// AOS Animation
	if ( $( '[data-aos]' ).length ) {
		console.log( 'aos' );
		AOS.init( {
			duration: 800,
			mirror: true,
			once: true,
			offset: 50,
		} );
	}

	// just example how to use slick slider
	if ( $( '.slider' ).length ) {
		$( '.slider' ).slick( {
			dots: false,
			arrows: true,
			centerPadding: '0px',
			slidesToShow: 4,
			slidesToScroll: 1,
			autoplay: true,
			autoplaySpeed: 3000,
			infinite: true,
			prevArrow: $( '.prev_p1' ),
			nextArrow: $( '.next_p1' ),
			responsive: [ {
				breakpoint: 768,
				settings: {
					slidesToShow: 3,
				},
			},
			{
				breakpoint: 576,
				settings: {
					slidesToShow: 1,
				},
			} ],
		} );
	}
}( jQuery ) );
