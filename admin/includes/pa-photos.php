<?php global $post; ?>

<div class="popup-inner">
	<div class="keywords">
		<p>Keywords:</p>
		<?php 
			$keywords = get_post_keywords($post->post_content); 
		?>
		<ul>
			<?php foreach( $keywords as $key => $value ): ?>
				<li class="search-term">
					<em><?php echo $value[0] ?></em>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<div class="images">
		<div class="spinner"></div>
	</div>
</div>

<script>
	var API_BASE = 'https://api.gettyimages.com/v3/';

	function gettyAjax(search_url){
		jQuery('.images > .spinner').css('display', 'block');

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

        		jQuery('.images > .spinner').css('display', 'none');
    		}
    	});
	}

	jQuery(document).ready(function(){
		var search_term = '<?php echo $keywords[0][0] ?>';

		var search_url = API_BASE + 'search/images?phrase=' + search_term;

		jQuery.ajaxSetup({
    		"headers" : { "Api-Key": "2qymv3fyem9rz5uff9724vtz" }
		});

		gettyAjax(search_url);

		jQuery('.keywords li').first().addClass('active');

		jQuery('.search-term').click(function(){
			var term = jQuery(this).text().replace(/ /g,'');
			
			//Clear the photos currently in place
			jQuery('.popup-inner > .images > img').remove()

			//Set up the new URL
			var search_url = API_BASE + 'search/images?phrase=' + term;

			//Run the Ajax Request
			gettyAjax(search_url);

			//Bold the new active term
			jQuery('.keywords li').removeClass('active');

			jQuery(this).addClass('active');
		});
    });
</script>