(function($, window, document) {

	$(document).ready(function(){
		$('.popup-inner > .images').on('click', 'img', function(){
			$('.popup-inner > .images > img').removeClass('selected');
			$(this).addClass('selected');
		});
	});
  
}(window.jQuery, window, document));