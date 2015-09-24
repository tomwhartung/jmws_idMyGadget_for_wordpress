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
 
	submit_button();
	print '</form></div>';
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