<?php global $post; ?>

<div class="popup-inner">
	<div class="spinner"></div>
	<div class="keywords">
		<?php 
			$keyword = get_post_keyword($post->post_content); 
		?>
		<p>Keyword: <em><?php var_dump( $keyword ); ?></em></p>
	</div>
	<div class="images">
		
	</div>
</div>

<script>
	var API_BASE = 'https://api.gettyimages.com/v3/';

	jQuery(document).ready(function(){
		var search_term = '<?php echo $keyword ?>';

		var search_url = API_BASE + 'search/images?phrase=' + search_term;

		jQuery.ajaxSetup({
    		"headers" : { "Api-Key": "2qymv3fyem9rz5uff9724vtz" }
		});

		jQuery.ajax({
			url: search_url, 
			success: function(response){
        		response.images.forEach(function(el, index, array){
        			jQuery('.popup-inner > .images').append(
        				jQuery('<img>', {
        					id:  el.id,
        					src: el.display_sizes[0].uri
        				})
        			);
        		});
    		}
    	});
    });
</script>