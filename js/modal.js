/**
 * Backbone Application File
 * @internal Obviously, I've dumped all the code into one file. This should probably be broken out into multiple
 * files and then concatenated and minified but as it's an example, it's all one lumpy file.
 * @package photo_assistant.modal
 */

/**
 * @type {Object} JavaScript namespace for our application.
 */
var photo_assistant = {
	modal: {
		__instance: undefined
	}
};

/**
 * Primary Modal Application Class
 */
photo_assistant.modal.Application = Backbone.View.extend(
	{
		id: "photo_assistant_dialog",
		events: {
			"click .photo_assistant-close": "closeModal",
			"click #btn-cancel": "closeModal",
			"click #btn-ok": "saveModal",
			"click .navigation-bar a": "doNothing"
		},

		/**
		 * Simple object to store any UI elements we need to use over the life of the application.
		 */
		ui: {
			nav: undefined,
			content: undefined
		},

		/**
		 * Object to hold all API information
		 */
		api: {
			base: 'https://api.gettyimages.com/v3/',
			apiKey: ''
		},

		/**
		 * Container to store our compiled templates. Not strictly necessary in such a simple example
		 * but might be useful in a larger one.
		 */
		templates: {},

		/**
		 * Instantiates the Template object and triggers load.
		 */
		initialize: function () {
			"use strict";

			_.bindAll( this, 'render', 'preserveFocus', 'closeModal', 'saveModal', 'doNothing' );
			this.initialize_templates();
			this.render();
		},


		/**
		 * Creates compiled implementations of the templates. These compiled versions are created using
		 * the wp.template class supplied by WordPress in 'wp-util'. Each template name maps to the ID of a
		 * script tag ( without the 'tmpl-' namespace ) created in template-data.php.
		 */
		initialize_templates: function () {
			this.templates.window = wp.template( "photo-assistant-modal-window" );
			this.templates.backdrop = wp.template( "photo-assistant-modal-backdrop" );
			this.templates.menuItem = wp.template( "photo-assistant-modal-menu-item" );
			this.templates.menuItemSeperator = wp.template( "photo-assistant-modal-menu-item-separator" );
		},

		/**
		 * Assembles the UI from loaded templates.
		 * @internal Obviously, if the templates fail to load, our modal never launches.
		 */
		render: function () {
			"use strict";

			// Build the base window and backdrop, attaching them to the $el.
			// Setting the tab index allows us to capture focus and redirect it in Application.preserveFocus
			this.$el.attr( 'tabindex', '0' )
				.append( this.templates.window() )
				.append( this.templates.backdrop() );

			// Handle any attempt to move focus out of the modal.
			jQuery( document ).on( "focusin", this.preserveFocus );

			// set overflow to "hidden" on the body so that it ignores any scroll events while the modal is active
			// and append the modal to the body.
			// TODO: this might better be represented as a class "modal-open" rather than a direct style declaration.
			jQuery( "body" ).css( {"overflow": "hidden"} ).append( this.$el );

			// Set focus on the modal to prevent accidental actions in the underlying page
			// Not strictly necessary, but nice to do.
			this.$el.focus();
		},

		/**
		 * Ensures that keyboard focus remains within the Modal dialog.
		 * @param e {object} A jQuery-normalized event object.
		 */
		preserveFocus: function ( e ) {
			"use strict";
			if ( this.$el[0] !== e.target && ! this.$el.has( e.target ).length ) {
				this.$el.focus();
			}
		},

		/**
		 * Closes the modal and cleans up after the instance.
		 * @param e {object} A jQuery-normalized event object.
		 */
		closeModal: function ( e ) {
			"use strict";

			e.preventDefault();
			this.undelegateEvents();
			jQuery( document ).off( "focusin" );
			jQuery( "body" ).css( {"overflow": "auto"} );
			this.remove();
			photo_assistant.modal.__instance = undefined;
		},

		/**
		 * Responds to the btn-ok.click event
		 * @param e {object} A jQuery-normalized event object.
		 * @todo You should make this your own.
		 */
		saveModal: function ( e ) {
			"use strict";
			this.closeModal( e );
		},

		/**
		 * Ensures that events do nothing.
		 * @param e {object} A jQuery-normalized event object.
		 * @todo You should probably delete this and add your own handlers.
		 */
		doNothing: function ( e ) {
			"use strict";
			e.preventDefault();
		}

	} );

jQuery( function ( $ ) {
	"use strict";
	/**
	 * Attach a click event to the meta-box button that instantiates the Application object, if it's not already open.
	 */
	$( "#open-photo_assistant_modal" ).click( function ( e ) {
		e.preventDefault();
		if ( photo_assistant.modal.__instance === undefined ) {
			photo_assistant.modal.__instance = new photo_assistant.modal.Application();
		}
	} );
} );