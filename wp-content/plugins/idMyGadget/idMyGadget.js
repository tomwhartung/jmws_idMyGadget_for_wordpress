/**
 * From: http://www.webmaster-source.com/2013/02/06/using-the-wordpress-3-5-media-uploader-in-your-plugin-or-theme/
 */
jQuery(document).ready(function($) {
	var custom_uploader;

	$('.idMyGadget_upload_image').click(function(e) {
		e.preventDefault();
		var buttonIdLength = this.id.length;
		var nameInputIdLength = buttonIdLength - 7;
		var nameInputId = this.id;
	//	alert( 'nameInputId  = ' + nameInputId );
		nameInputId = nameInputId.substring( 0, nameInputIdLength );  // removes '_button' from end of id
		alert( 'nameInputId  = ' + nameInputId );
		//
		// If the uploader object has already been created, reopen the dialog
		//
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}
		//
		// Extend the wp.media object
		//
		custom_uploader = wp.media.frames.file_frame = wp.media( {
			title: 'Choose Image',
			button: {
				text: 'Choose Image'
			},
			multiple: false
		});
		//
		// When a file is selected, grab the URL and set it as the text field's value
		//
		custom_uploader.on('select', function() {
			attachment = custom_uploader.state().get('selection').first().toJSON();
			$('#'+nameInputId).val(attachment.url);
		});
		//
		// Open the uploader dialog
		//
		custom_uploader.open();
		});
});
