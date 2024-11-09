'use strict';

(function ($) {
 
  /**
   * Header menu mobile only parents - dropdown after click on the parent menu
   */
   var isMenuOpen = false;

   function isMobile() {
       return window.matchMedia('(max-width: 1068px)').matches;
   }

   //menu about
   $('.mobile-about').click(function(event) {
    if (isMobile() && !isMenuOpen) {
        event.preventDefault(); 
        $('.mega-menu').removeClass('is-show');
        var $menu = $('#about_menu_mobile');

        $menu.css({
            'display': 'block',
            'right': '-100%'
        });

        $menu.animate({
            right: '0' 
        }, 300, function() {
            isMenuOpen = true; 
        });
    }
    });

   //menu charms
   $('.mobile-charms').click(function(event) {
    if (isMobile() && !isMenuOpen) {
        event.preventDefault(); 
        $('.mega-menu').removeClass('is-show');
        var $menu = $('#charms_menu_mobile');

        $menu.css({
            'display': 'block',
            'right': '-100%'
        });

        $menu.animate({
            right: '0' 
        }, 300, function() {
            isMenuOpen = true; 
        });
    }
    });

    //menu collections
    $('.mobile-collections').click(function(event) {
     if (isMobile() && !isMenuOpen) {
         event.preventDefault(); 
         $('.mega-menu').removeClass('is-show');
         var $menu = $('#collections_menu_mobile');
 
         $menu.css({
             'display': 'block',
             'right': '-100%'
         });
 
         $menu.animate({
             right: '0' 
         }, 300, function() {
             isMenuOpen = true; 
         });
     }
     });

     //menu jewellery
     $('.mobile-jewellery').click(function(event) {
      if (isMobile() && !isMenuOpen) {
          event.preventDefault(); 
          $('.mega-menu').removeClass('is-show');
          var $menu = $('#jewellery_menu_mobile');
  
          $menu.css({
              'display': 'block',
              'right': '-100%'
          });
  
          $menu.animate({
              right: '0' 
          }, 300, function() {
              isMenuOpen = true; 
          });
      }
      });

      //menu gifts
      $('.mobile-gifts').click(function(event) {
       if (isMobile() && !isMenuOpen) {
           event.preventDefault(); 
           $('.mega-menu').removeClass('is-show');
           var $menu = $('#gifts_menu_mobile');
   
           $menu.css({
               'display': 'block',
               'right': '-100%'
           });
   
           $menu.animate({
               right: '0' 
           }, 300, function() {
               isMenuOpen = true; 
           });
       }
       });

       //menu accessories
       $('.mobile-accessories').click(function(event) {
        if (isMobile() && !isMenuOpen) {
            event.preventDefault(); 
            $('.mega-menu').removeClass('is-show');
            var $menu = $('#accessories_menu_mobile');
    
            $menu.css({
                'display': 'block',
                'right': '-100%'
            });
    
            $menu.animate({
                right: '0' 
            }, 300, function() {
                isMenuOpen = true; 
            });
        }
        });

   // Close menu after click on .mega-menu-header
   $('.mega-menu-header').click(function() {
    if (isMobile() && isMenuOpen) {
        var $menu = $(this).parent().parent();

        $menu.animate({
            right: '-100%' 
        }, 300, function() {
            $menu.css('display', 'none'); 
            isMenuOpen = false; 
        });
    }
    });

}(jQuery));