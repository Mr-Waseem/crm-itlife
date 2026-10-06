//[Horizontal Menu Javascript]

//Project:	SoftMaterial admin - Responsive Admin Template
//Primary use:   SoftMaterial admin - Responsive Admin Template

//should be included in all pages. It controls some layout


+function ($) {
  'use strict'

	jQuery(document).on('click', '.mega-dropdown', function (e) {
	e.stopPropagation();
	});
  
 
	var $window = $(window),
		$html = $('html');

	$window.resize(function resize() {
		if ($window.width() < 990) {
			return $html.removeClass('horizontal-menu');
		}

		$html.addClass('horizontal-menu');
	}).trigger('resize');
	
}(jQuery)

$(function () {

  'use strict';
		$('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
		  if (!$(this).next().hasClass('show')) {
			$(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
		  }
		  var $subMenu = $(this).next(".dropdown-menu");
		  $subMenu.toggleClass('show');


		  $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
			$('.dropdown-submenu .show').removeClass("show");
		  });


		  return false;
		});
}); // End of use strict