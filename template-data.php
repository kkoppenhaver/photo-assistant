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
		<a class="photo_assistant-close dashicons dashicons-no" href="#"
		   title="<?php echo __( 'Close', 'photo_assistant' ); ?>"><span
				class="screen-reader-text"><?php echo __( 'Close', 'photo_assistant' ); ?></span></a>

		<div class="backbone_modal-content">
			<section class="backbone_modal-main" role="main">
				<header><h1><?php echo __( 'Photo Assistant', 'photo_assistant' ); ?></h1></header>
				<article></article>
				<footer>
					<div class="inner text-right">
						<button id="btn-cancel"
						        class="button button-large"><?php echo __( 'Cancel', 'photo_assistant' ); ?></button>
						<button id="btn-ok"
						        class="button button-primary button-large"><?php echo __( 'Save &amp; Continue', 'photo_assistant' ); ?></button>
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
