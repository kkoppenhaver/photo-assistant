<?php
/**
 * Backbone Templates
 * This file contains all of the HTML used in our modal and the workflow itself.
 *
 * Each template is wrapped in a script block ( note the type is set to "text/html" ) and given an ID prefixed with
 * 'tmpl'. The wp.template method retrieves the contents of the script block and converts these blocks into compiled
 * templates to be used and reused in your application.
 */


/**
 * The Modal Window, including sidebar and content area.
 * Add menu items to ".navigation-bar nav ul"
 * Add content to ".backbone_modal-main article"
 */
?>
<script type="text/html" id='tmpl-photo-assistant-modal-window'>
	<div class="backbone_modal">
		<a class="photo_assistant-close" href="#"
		   title="<?php echo esc_attr__( 'Close', 'photo_assistant' ); ?>"><span
				class="screen-reader-text">Close</span></a>

		<div class="backbone_modal-content">
			<section class="backbone_modal-main" role="main">
				<header><h1><?php echo esc_html__( 'Photo Assistant', 'photo_assistant' ); ?></h1></header>
				<div class="keywords">
					<p>Keywords:</p>
					<?php
						global $post;
						$keywords = PhotoAssistantPlugin::get_post_keywords( $post->post_content );
					?>
					<ul>
						<?php foreach ( $keywords as $key => $value ) : ?>
							<li class="search-term">
								<em><?php echo esc_html( $value[0] ) ?></em>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="pa-thumbnails">
					<div class="spinner"></div>
				</div>
				<div class="pa-sidebar">
					<div class="sidebar-inner">
						<h4>ATTACHMENT DETAILS</h4>

						<img src="" alt="">
						<div class="img-title"></div>

						<div class="img-caption"></div>
					</div>
				</div>
				<footer>
					<div class="inner text-right">
						<button id="btn-post-img"
						        class="button button-primary button-large" disabled="true"><?php echo esc_html__( 'Embed Image', 'photo_assistant' ); ?></button>
						<button id="btn-feat-img"
						        class="button button-primary button-large" disabled="true"><?php echo esc_html__( 'Set Featured Image', 'photo_assistant' ); ?></button>
					</div>
				</footer>
			</section>
		</div>
	</div>
</script>

<?php
/**
 * The Modal Backdrop
 */
?>
<script type="text/html" id='tmpl-photo-assistant-modal-backdrop'>
	<div class="backbone_modal-backdrop">&nbsp;</div>
</script>
<?php
/**
 * A menu item separator.
 */
?>
<script type="text/html" id='tmpl-photo-assistant-modal-menu-item-separator'>
	<li class="separator">&nbsp;</li>
</script>
