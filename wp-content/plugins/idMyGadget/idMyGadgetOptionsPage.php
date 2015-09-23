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
	print '<h2>IdMyGadget Options</h2>';
	print '<p>Device-specific options for use by themes that know what to do with them.</p>';
	print '<form action="options.php" method="post">';

	settings_fields( 'idMyGadget_options' );
	do_settings_sections( 'idMyGadget_options' );
 
	submit_button();
	print '</form></div>';
}
