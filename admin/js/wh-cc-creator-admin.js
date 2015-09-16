(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-specific JavaScript source
	 * should reside in this file.
	 *
	 * Note that this assume you're going to use jQuery, so it prepares
	 * the $ function reference to be used within the scope of this
	 * function.
	 *
	 * From here, you're able to define handlers for when the DOM is
	 * ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * Or when the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and so on.
	 *
	 * Remember that ideally, we should not attach any more than a single DOM-ready or window-load handler
	 * for any particular page. Though other scripts in WordPress core, other plugins, and other themes may
	 * be doing this, we should try to minimize doing that in our own work.
	 */
	 
	 $(document).ready(function() {
		 
		 
		 var _custom_media = true;
		 var _orig_send_attachment = wp.media.editor.send.attachment;

		 $('#upload_image').click(function(e) {

		 	var send_attachment_bkp = wp.media.editor.send.attachment;
		 	var button = $(this);
		 	var id = button.attr('id').replace('_button', '');

		 	_custom_media = true;
		 	wp.media.editor.send.attachment = function(props, attachment) {
	
			 	if ( _custom_media ) {
			 		$("#image_path").val(attachment.url);
	      		} else {
		  			return _orig_send_attachment.apply( this, [props, attachment] );
	      		};

    		}

			wp.media.editor.open(button);

			return false;
  		});

  		$('.add_media').on('click', function() {
  			_custom_media = false;
  		});
  		
  		$('#remove_image').click(function() {
	  		
	  		$('#show_upload_preview').html( '' );
	  		$('#image_path').val( '' );
	  	
	  	});
	});
	
})( jQuery );
