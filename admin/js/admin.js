(function($, window, document) {

	$(document).ready(function(){
		
		$('.popup-inner > .images').on('click', 'img', function(){
			$('.popup-inner > .images > img').removeClass('selected');
			$(this).addClass('selected');
		});

		$('#add-featured-image').click(function(){
			alert('add featured iamge');
		});

		$('#embed-into-post').click(function(){
			alert('embed into post');
		});

	});
  
}(window.jQuery, window, document));