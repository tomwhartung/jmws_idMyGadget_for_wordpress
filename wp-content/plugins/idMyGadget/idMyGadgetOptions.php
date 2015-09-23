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
?>
<div>
<h2>IdMyGadget Options</h2>
Device-specific options for use by themes that know what to do with them.
<form action="options.php" method="post">
<?php settings_fields('plugin_options'); ?>
<?php do_settings_sections('plugin'); ?>
 
<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
</form></div>
 
<?php
}
?>

