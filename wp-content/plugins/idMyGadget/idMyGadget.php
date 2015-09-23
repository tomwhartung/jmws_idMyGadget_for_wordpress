<?php
/*
 * @package idMyGadget
 *
 * Plugin Name: idMyGadget
 * Plugin URI: 
 * Description: Integrate idMyGadget with a couple of wordpress themes (to start).
 * Author: Tom Hartung
 * Version: 1.0
 * Author URI: http://tomwhartung.com/
 */
define( 'IDMYGADGET__PLUGIN_DIR', plugin_dir_path( __FILE__ ) . DIRECTORY_SEPARATOR . 'idMyGadget' );
require_once( IDMYGADGET__PLUGIN_DIR . DIRECTORY_SEPARATOR . 'JmwsIdMyGadgetWordpress.php' );
// require_once 'jmws_idMyGadget_for_joomla/PhoneBurgerMenuIcon.php';

$jmwsIdMyGadget = null;
/*
 * Get the theme name (aka. "stylesheet" in Wordpress speak)
 */
$theme_object = wp_get_theme();
$theme_object_stylesheet = $theme_object->stylesheet;

/**
 *
 * @global object $jmwsIdMyGadget
 */
function idMyGadget()
{
	global $gadgetDetectorIndex;    // global for debugging purposes, consider "locking it down?"
	global $gadgetDetectorString;   // global for debugging purposes, consider "locking it down?"
	global $idMyGadgetClass;        // global for debugging purposes, consider "locking it down?"

	$gadgetDetectorIndex = get_theme_mod('gadget_detector');
	$supportedGadgetDetectors = JmwsIdMyGadgetWordpress::$supportedGadgetDetectors;
	$gadgetDetectorString = $supportedGadgetDetectors[$gadgetDetectorIndex];

	global $jmwsIdMyGadget;
	$jmwsIdMyGadget = new JmwsIdMyGadgetWordpress($gadgetDetectorString);

	$jmwsIdMyGadget->usingJQueryMobile = FALSE;

	$idMyGadgetClass = get_class( $jmwsIdMyGadget->getIdMyGadget() );
}
add_action( 'wp', 'idMyGadget' );

function jmws_idmygadget_customize_register( $wp_customize )
{
	global $theme_object_stylesheet;   // aka. the theme "name"

	//
	// Add a section to the theme's Customize side bar that contains
	// radio buttons that allow the admin to set the device detector.
	//
	$wp_customize->add_section( 'gadget_detector' , array(
		'title'      => __( 'IdMyGadget', $theme_object_stylesheet ),
		'description' => __( 'Select the 3rd party device detector to use for this theme.' ),
		'priority'   => 9999,
	) );

	$wp_customize->add_setting( 'gadget_detector' , array(
		'default'     => JmwsIdMyGadgetWordpress::$supportedGadgetDetectors[0],
		'transport'   => 'refresh',
	) );

	$wp_customize->add_control( 'gadget_detector', array(
		'label'    => __( 'Gadget Detector', $theme_object_stylesheet ),
		'section'  => 'gadget_detector',
		'type'     => 'radio',
		'choices'  => JmwsIdMyGadgetWordpress::$supportedGadgetDetectors,
		'priority' => 100,
	) );
}

/*
 * Check the theme name (aka. "stylesheet") and add the idMyGadget options to it only
 *   if the theme actually "knows" how to use it.
 */
if ( in_array($theme_object_stylesheet,JmwsIdMyGadgetWordpress::$supportedThemes) )
{
	add_action( 'customize_register', 'jmws_idmygadget_customize_register' );
}

/**
 * Add the plugin's admin option page
 */
function idMyGadget_admin_add_page()
{
//	add_options_page('IdMyGadget Options Page', 'IdMyGadget', 'manage_options', 'idMyGadget', 'idMyGadget_options_page');
	add_plugins_page('IdMyGadget Plugins Page', 'IdMyGadget', 'manage_options', 'idMyGadget', 'idMyGadget_options_page');
}
add_action('admin_menu', 'idMyGadget_admin_add_page');

/**
 * Display the idMyGadget admin options page
 */
function idMyGadget_options_page()
{
?>
<div>
<h2>IdMyGadget Options</h2>
Options relating to the Custom Plugin.
<form action="options.php" method="post">
<?php settings_fields('plugin_options'); ?>
<?php do_settings_sections('plugin'); ?>
 
<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
</form></div>
 
<?php
}
?>
