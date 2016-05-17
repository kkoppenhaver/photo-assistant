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
			// Get the first instance of TinyMCE
			ed = tinyMCE.get()[0];

			var range = ed.selection.getRng();   

			var newNode = ed.getDoc().createElement ( 'img' );

			if( $('.popup-inner > .images > img.selected').attr('src') ) {
				newNode.src= $('.popup-inner > .images > img.selected').attr('src');
				range.insertNode(newNode);
				self.parent.tb_remove();
			}
			else {
				alert( 'No image selected!' );
			}

		});

	});
  
}(window.jQuery, window, document));