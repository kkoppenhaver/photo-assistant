<?php 

add_action( 'media_buttons_context',  'pa_media_popup' );
function pa_media_popup($context) {

  //our popup's title
  $title = 'Photo Assistant';

  //append the icon
  $context .= "<a href='#TB_inline?width=1200&height=800%&inlineId=pa-photos-modal'
    class='button thickbox photo-assistant' title='Photo Assistant - Find a stock photo for your post'>
    <span class='dashicons dashicons-format-gallery'></span> Photo Assistant</a>";

  return $context;
}


?>