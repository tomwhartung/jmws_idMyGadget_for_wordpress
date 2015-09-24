<?php
/*
 * @package idMyGadget
 *
 * Markup for the plugin's admin option page
 *
 * Author URI: http://tomwhartung.com/
 */
global $jmwsIdMyGadget;

/**
 * Display the idMyGadget admin options page
 */
function idMyGadget_options_page()
{
	print '<div>';
	idMyGadget_dump_options();
	print '</div>';
	print '<div>';
	print '<h2>IdMyGadget Options</h2>';
	print '<p>Device-specific options for use by themes that know what to do with them.</p>';
	print '<form action="options.php" method="post">';

	settings_fields( 'idMyGadget_options' );
	do_settings_sections( 'idMyGadget_options' );
?>
   <table class="form-table">
      <tr valign="top">
         <th scope="row">Header Options for Phones</th>
         <td><input type="text" name="show_site_name_phone" value="<?php echo esc_attr( get_option('show_site_name_phone') ); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row">Header Options for Tablets</th>
        <td><input type="text" name="show_site_name_tablet" value="<?php echo esc_attr( get_option('show_site_name_tablet') ); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row">Header Options for Desktops</th>
        <td><input type="text" name="show_site_name_desktop" value="<?php echo esc_attr( get_option('show_site_name_desktop') ); ?>" /></td>
        </tr>
    </table>

<?php
	submit_button();
	print '</form></div>';
}
//
// Code to sanitize user input
//
function idMyGadget_sanitize_boolean( $input )
{
	error_log( 'ToDo: implement function idMyGadget_sanitize_boolean()' );
	return $input;
}
function idMyGadget_sanitize_element( $input )
{
	error_log( 'ToDo: implement function idMyGadget_sanitize_element()' );
	return $input;
}
function idMyGadget_sanitize_radio( $input )
{
	error_log( 'ToDo: implement function idMyGadget_sanitize_radio()' );
	return $input;
}
function idMyGadget_sanitize_string( $input )
{
	error_log( 'ToDo: implement function idMyGadget_sanitize_string()' );
	return $input;
}

//
// DEBUG CODE - remove when everything is working just exactly perfect
//
// Dump of options, copied from https://codex.wordpress.org/Creating_Options_Pages
// (way down at the bottom of the page).
//
function idMyGadget_dump_options()
{
	global $options;

	if ( isset($options) )
	{
		print '<p>$options are below:</p>';
		foreach ($options as $value)
		{
			if ( get_option($value['id']) === FALSE )
			{
				$$value['id'] = $value['std'];
			}
			else
			{
				$$value['id'] = get_option( $value['id'] );
			 }
		}
		print '<p>$options are above!</p>';
	}
	else
	{
		print '<p>Oops, no $options to dump!</p>';
	}
}
?>
