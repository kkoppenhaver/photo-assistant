<?php
/*
  Plugin Name: Photo Assistant
  Plugin URI: https://github.com/kkoppenhaver/photo-assistant
  Description: A WordPress plugin that helps you pick the right stock photo based on the content of your post
  Author: Keanan Koppenhaver
  Twitter: @kkoppenhaver
  Author URI: http://levelupwp.net
  Version: 0.1
  License: GPL
  Copyright: Keanan Koppenhaver
*/

/**
 * Main plugin class, namespace [Author Handle]_[Plugin Text Domain]_ to avoid conflicts.
 */
class PhotoAssistantPlugin {

	/**
	 * Static Singleton
	 * @action plugins_loaded
	 * @return PhotoAssistantPlugin
	 * @static
	 */
	public static function init() {
		static $instance = false;
		if ( ! $instance ) {
			load_plugin_textdomain( 'photo_assistant', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
			$instance = new PhotoAssistantPlugin;
		}

		return $instance;
	}

	/**
	 * Constructor.  Adds a hook to display a panel with supporting CSS and JS on the edit page/post screen,
	 * as well as an AJAX hook to retrieve templates.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', $this->marshall( 'add_scripts' ) );
		add_action( 'media_buttons_context', $this->marshall( 'add_pa_button' ) );
		// Adding the templates to the post.php and post-new.php only as that's the only place
		// our meta box is added in this plugin.  You can also use the wp-footer action if you need
		// the templates globally.
		add_action( 'admin_footer-post-new.php', $this->marshall( 'add_templates' ) );
		add_action( 'admin_footer-post.php', $this->marshall( 'add_templates' ) );

	}

	/**
	 * Dumps the contents of template-data.php into the foot of the document.
	 * WordPress itself function-wraps the script tags rather than including them directly
	 * ( example: https://github.com/WordPress/WordPress/blob/master/wp-includes/media-template.php )
	 * but this isn't necessary for this example.
	 */
	public function add_templates() {
		include 'template-data.php';
	}

	/**
	 * Supplies the internal content for the panel. In this case, a simple button used as the primary target for
	 * the backbone application.
	 *
	 * @param $post Post a WordPress post object
	 */
	public function add_pa_button( $post ) {
		print sprintf(
			'<a href="#" id="open-photo_assistant_modal" class="button photo-assistant" title="Photo Assistant - Find a stock photo for your post">
    			<span class="dashicons dashicons-format-gallery"></span> %1$s</a>',
			esc_html__( 'Photo Assistant', 'photo_assistant' )
		);
	}

	/**
	 * Enqueue the script and styles necessary to for the modal.
	 *
	 * @param $hook string script-name of the current page.
	 *
	 * @internal I considered using the WordPress supplied "MediaModal" styles, but rejected them because
	 *           a) They're only available in WordPress 3.5+,
	 *           b) The code is subject to change ( I'd be surprised if it didn't ), and
	 *           b) They are Media Modal specific and have a certain amount of "baggage" as they're the basis for
	 *              javascript hooks and actions.
	 * Obviously YMMV.
	 */
	public function add_scripts( $hook ) {
		if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
			$base = plugin_dir_url( __FILE__ );
			wp_enqueue_script( 'photo_assistant', $base . 'js/modal.js', array(
				'jquery',
				'backbone',
				'underscore',
				'wp-util',
			) );

			wp_enqueue_style( 'photo_assistant', $base . 'css/modal.css' );

			wp_localize_script( 'photo_assistant', 'photo_assistant_l10n',
				array(
					'api_key' => get_option( 'photo_assistant_api_key' ),
				)
			);
		}
	}

	/**
	 * AJAX method that returns an HTML-fragment containing the various templates used by Backbone
	 * to construct the UI.
	 * @internal Obviously, this is part of a particular Backbone pattern that I enjoy using.
	 *           Feel free to remove this method ( and the associated action hook ) if you assemble the
	 *           UI using direct DOM manipulation, jQuery objects, or HTML strings.
	 */
	public function get_template_data() {
		include( 'template-data.php' );
		die(); // you must die from an ajax call
	}

	/**
	 * @param $method_name
	 *
	 * @return callable An array-wrapped PHP callable suitable for calling class methods when working with
	 * WordPress add_action/add_filter.
	 */
	public function marshall( $method_name ) {
		return array( &$this, $method_name );
	}

	function get_post_keywords( $post_content ) {

		//Strip out any HTML
		$post_content = strip_tags( $post_content );

		//Lowercase all words
		$post_content = strtolower( $post_content );

		//Strip out any special characters
		$post_content = preg_replace( '/[^a-zA-Z ]+/', ' ', $post_content );

		//Split into words
		$words = explode( ' ', $post_content );

		//Remove stopwords https://en.wikipedia.org/wiki/Stop_words
		//Source: https://gist.github.com/keithmorris/4155220
		$common_words = array( 'a', 'able', 'about', 'above', 'abroad', 'according', 'accordingly', 'across', 'actually', 'adj', 'after', 'afterwards', 'again', 'against', 'ago', 'ahead', 'ain\'t', 'all', 'allow', 'allows', 'almost', 'alone', 'along', 'alongside', 'already', 'also', 'although', 'always', 'am', 'amid', 'amidst', 'among', 'amongst', 'an', 'and', 'another', 'any', 'anybody', 'anyhow', 'anyone', 'anything', 'anyway', 'anyways', 'anywhere', 'apart', 'appear', 'appreciate', 'appropriate', 'are', 'aren\'t', 'around', 'as', 'a\'s', 'aside', 'ask', 'asking', 'associated', 'at', 'available', 'away', 'awfully', 'b', 'back', 'backward', 'backwards', 'be', 'became', 'because', 'become', 'becomes', 'becoming', 'been', 'before', 'beforehand', 'begin', 'behind', 'being', 'believe', 'below', 'beside', 'besides', 'best', 'better', 'between', 'beyond', 'both', 'brief', 'but', 'by', 'c', 'came', 'can', 'cannot', 'cant', 'can\'t', 'caption', 'cause', 'causes', 'certain', 'certainly', 'changes', 'clearly', 'c\'mon', 'co', 'co.', 'com', 'come', 'comes', 'concerning', 'consequently', 'consider', 'considering', 'contain', 'containing', 'contains', 'corresponding', 'could', 'couldn\'t', 'course', 'c\'s', 'currently', 'd', 'dare', 'daren\'t', 'definitely', 'described', 'despite', 'did', 'didn\'t', 'different', 'directly', 'do', 'does', 'doesn\'t', 'doing', 'done', 'don\'t', 'down', 'downwards', 'during', 'e', 'each', 'edu', 'eg', 'eight', 'eighty', 'either', 'else', 'elsewhere', 'end', 'ending', 'enough', 'entirely', 'especially', 'et', 'etc', 'even', 'ever', 'evermore', 'every', 'everybody', 'everyone', 'everything', 'everywhere', 'ex', 'exactly', 'example', 'except', 'f', 'fairly', 'far', 'farther', 'few', 'fewer', 'fifth', 'first', 'five', 'followed', 'following', 'follows', 'for', 'forever', 'former', 'formerly', 'forth', 'forward', 'found', 'four', 'from', 'further', 'furthermore', 'g', 'get', 'gets', 'getting', 'given', 'gives', 'go', 'goes', 'going', 'gone', 'got', 'gotten', 'greetings', 'h', 'had', 'hadn\'t', 'half', 'happens', 'hardly', 'has', 'hasn\'t', 'have', 'haven\'t', 'having', 'he', 'he\'d', 'he\'ll', 'hello', 'help', 'hence', 'her', 'here', 'hereafter', 'hereby', 'herein', 'here\'s', 'hereupon', 'hers', 'herself', 'he\'s', 'hi', 'him', 'himself', 'his', 'hither', 'hopefully', 'how', 'howbeit', 'however', 'hundred', 'i', 'i\'d', 'ie', 'if', 'ignored', 'i\'ll', 'i\'m', 'immediate', 'in', 'inasmuch', 'inc', 'inc.', 'indeed', 'indicate', 'indicated', 'indicates', 'inner', 'inside', 'insofar', 'instead', 'into', 'inward', 'is', 'isn\'t', 'it', 'it\'d', 'it\'ll', 'its', 'it\'s', 'itself', 'i\'ve', 'j', 'just', 'k', 'keep', 'keeps', 'kept', 'know', 'known', 'knows', 'l', 'last', 'lately', 'later', 'latter', 'latterly', 'least', 'less', 'lest', 'let', 'let\'s', 'like', 'liked', 'likely', 'likewise', 'little', 'look', 'looking', 'looks', 'low', 'lower', 'ltd', 'm', 'made', 'mainly', 'make', 'makes', 'many', 'may', 'maybe', 'mayn\'t', 'me', 'mean', 'meantime', 'meanwhile', 'merely', 'might', 'mightn\'t', 'mine', 'minus', 'miss', 'more', 'moreover', 'most', 'mostly', 'mr', 'mrs', 'much', 'must', 'mustn\'t', 'my', 'myself', 'n', 'name', 'namely', 'nd', 'near', 'nearly', 'necessary', 'need', 'needn\'t', 'needs', 'neither', 'never', 'neverf', 'neverless', 'nevertheless', 'new', 'next', 'nine', 'ninety', 'no', 'nobody', 'non', 'none', 'nonetheless', 'noone', 'no-one', 'nor', 'normally', 'not', 'nothing', 'notwithstanding', 'novel', 'now', 'nowhere', 'o', 'obviously', 'of', 'off', 'often', 'oh', 'ok', 'okay', 'old', 'on', 'once', 'one', 'ones', 'one\'s', 'only', 'onto', 'opposite', 'or', 'other', 'others', 'otherwise', 'ought', 'oughtn\'t', 'our', 'ours', 'ourselves', 'out', 'outside', 'over', 'overall', 'own', 'p', 'particular', 'particularly', 'past', 'per', 'perhaps', 'placed', 'please', 'plus', 'possible', 'presumably', 'probably', 'provided', 'provides', 'q', 'que', 'quite', 'qv', 'r', 'rather', 'rd', 're', 'really', 'reasonably', 'recent', 'recently', 'regarding', 'regardless', 'regards', 'relatively', 'respectively', 'right', 'round', 's', 'said', 'same', 'saw', 'say', 'saying', 'says', 'second', 'secondly', 'see', 'seeing', 'seem', 'seemed', 'seeming', 'seems', 'seen', 'self', 'selves', 'sensible', 'sent', 'serious', 'seriously', 'seven', 'several', 'shall', 'shan\'t', 'she', 'she\'d', 'she\'ll', 'she\'s', 'should', 'shouldn\'t', 'since', 'six', 'so', 'some', 'somebody', 'someday', 'somehow', 'someone', 'something', 'sometime', 'sometimes', 'somewhat', 'somewhere', 'soon', 'sorry', 'specified', 'specify', 'specifying', 'still', 'sub', 'such', 'sup', 'sure', 't', 'take', 'taken', 'taking', 'tell', 'tends', 'th', 'than', 'thank', 'thanks', 'thanx', 'that', 'that\'ll', 'thats', 'that\'s', 'that\'ve', 'the', 'their', 'theirs', 'them', 'themselves', 'then', 'thence', 'there', 'thereafter', 'thereby', 'there\'d', 'therefore', 'therein', 'there\'ll', 'there\'re', 'theres', 'there\'s', 'thereupon', 'there\'ve', 'these', 'they', 'they\'d', 'they\'ll', 'they\'re', 'they\'ve', 'thing', 'things', 'think', 'third', 'thirty', 'this', 'thorough', 'thoroughly', 'those', 'though', 'three', 'through', 'throughout', 'thru', 'thus', 'till', 'to', 'together', 'too', 'took', 'toward', 'towards', 'tried', 'tries', 'truly', 'try', 'trying', 't\'s', 'twice', 'two', 'u', 'un', 'under', 'underneath', 'undoing', 'unfortunately', 'unless', 'unlike', 'unlikely', 'until', 'unto', 'up', 'upon', 'upwards', 'us', 'use', 'used', 'useful', 'uses', 'using', 'usually', 'v', 'value', 'various', 'versus', 'very', 'via', 'viz', 'vs', 'w', 'want', 'wants', 'was', 'wasn\'t', 'way', 'we', 'we\'d', 'welcome', 'well', 'we\'ll', 'went', 'were', 'we\'re', 'weren\'t', 'we\'ve', 'what', 'whatever', 'what\'ll', 'what\'s', 'what\'ve', 'when', 'whence', 'whenever', 'where', 'whereafter', 'whereas', 'whereby', 'wherein', 'where\'s', 'whereupon', 'wherever', 'whether', 'which', 'whichever', 'while', 'whilst', 'whither', 'who', 'who\'d', 'whoever', 'whole', 'who\'ll', 'whom', 'whomever', 'who\'s', 'whose', 'why', 'will', 'willing', 'wish', 'with', 'within', 'without', 'wonder', 'won\'t', 'would', 'wouldn\'t', 'x', 'y', 'yes', 'yet', 'you', 'you\'d', 'you\'ll', 'your', 'you\'re', 'yours', 'yourself', 'yourselves', 'you\'ve', 'z', 'zero' );

		$words = preg_replace( '/\b('.implode( '|', $common_words ).')\b/', '', $words );

		//Remove any empty array elements
		$words = array_filter( $words );

		//Determine frequency of words
		$unique_word_counts = array_count_values( $words );

		$words_sorted = array();
		foreach ( $unique_word_counts as $word => $frequency ) {
			//Calculate density of words
			$density = $frequency / count( $words ) * 100;
			array_push( $words_sorted, array( $word, $density ) );
		}

		//Sort the array by a keyword's value
		usort( $words_sorted, array( $this, 'word_value_sort' ) );

		//Return the best 5 keywords
		return array_slice( $words_sorted, 0, 5 );
	}

	function word_value_sort( $a, $b ) {

		if ( $a[1] > $b[1] ) {
			return -1;
		} elseif ( $a[1] < $b[1] ) {
			return 1;
		} else {
			return strcmp( $b[1], $a[1] );
		}

	}

	/**
	 * Creating Plugin Settings Page and field for API Key
	 */
	function photo_assistant_settings_page() {
		?>
	    <div class="wrap">
	    <h1>Photo Assistant Settings</h1>
	    <form method="post" action="options.php">
	        <?php
	            settings_fields( 'section' );
	            do_settings_sections( 'photo-assistant-options' );
	            submit_button();
	        ?>          
	    </form>
		</div>
	<?php
	}

	function add_photo_assistant_menu_item() {
		add_submenu_page( 'options-general.php', 'Photo Assistant', 'Photo Assistant', 'manage_options', 'photo-assistant', array( 'PhotoAssistantPlugin', 'photo_assistant_settings_page' ) );
	}

	function display_apikey_element() {
		?>
	   	<input type="text" name="photo_assistant_api_key" id="photo_assistant_api_key" value="<?php echo esc_attr( get_option( 'photo_assistant_api_key' ) ); ?>" />
		<?php
	}

	function display_photo_assistant_fields() {
		add_settings_section( 'section', 'API Settings', null, 'photo-assistant-options' );

		add_settings_field( 'photo_assistant_api_key', 'Getty Images API Key', array( 'PhotoAssistantPlugin', 'display_apikey_element' ), 'photo-assistant-options', 'section' );
		register_setting( 'section', 'photo_assistant_api_key' );
	}
}

/**
 * Instantiates the plugin singleton during plugins_loaded action.
 */
add_action( 'plugins_loaded', array( 'PhotoAssistantPlugin', 'init' ) );

/**
 * Handle the loading of the plugin settings page.
 */
add_action( 'admin_menu', array( 'PhotoAssistantPlugin', 'add_photo_assistant_menu_item' ) );
add_action( 'admin_init', array( 'PhotoAssistantPlugin', 'display_photo_assistant_fields' ) );
