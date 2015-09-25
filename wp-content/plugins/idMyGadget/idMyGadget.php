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
// require_once 'idMyGadget/PhoneBurgerMenuIcon.php';

$jmwsIdMyGadget = null;
/*
 * Get the theme name (aka. "stylesheet" in Wordpress speak)
 */
$theme_object = wp_get_theme();
$theme_object_stylesheet = $theme_object->stylesheet;

/**
 * Instantiate the IdMyGadget Device Detection object
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

//
// ------------------------------------------------
// Adding options to the theme's Customization page
// ------------------------------------------------
//
/**
 * Add some options to the theme customization page's menu
 * (Others get added to a separate page containing the plugin's options.)
 */
function idmygadget_customize_register( $wp_customize )
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
	add_action( 'customize_register', 'idmygadget_customize_register' );
}

//
// ---------------------------------------------------
// Adding options to the plugin's special Options page
// ---------------------------------------------------
//
/**
 * Add the admin option page to display the idMyGadget options
 */
require_once 'idMyGadgetOptionsPage.php';
function idMyGadget_admin_menu_add_options_page()
{
	add_plugins_page(
		'IdMyGadget Options',
		'IdMyGadget',
		'manage_options',
		__FILE__,
		'idMyGadget_options_page_html_fn'    // Form markup is in idMyGadgetOptions.php
	);
}

if ( is_admin() )  // Add the options only when we are logged in as an admin
{
	add_action( 'admin_menu', 'idMyGadget_admin_menu_add_options_page' );
}

/**
 * Add the idMyGadget admin options
 */
function idMyGadget_admin_init()
{
	add_settings_section( 'idMyGadget_phone_options',
		'Phones',
		'idMyGadget_section_html_fn',
		'idMyGadget_options' );

	add_settings_section( 'idMyGadget_tablet_options',
		'Tablets',
		'idMyGadget_section_html_fn',
		'idMyGadget_options' );

	add_settings_section( 'idMyGadget_desktop_options',
		'Desktops',
		'idMyGadget_section_html_fn',
		'idMyGadget_options' );

	register_setting( 'idMyGadget_options', 'logo_file_phone', 'idMyGadget_sanitize_image_file_fn' );
	register_setting( 'idMyGadget_options', 'show_site_name_phone', 'idMyGadget_sanitize_boolean_fn' );
	register_setting( 'idMyGadget_options', 'site_name_element_phone', 'idMyGadget_sanitize_element_fn' );
	register_setting( 'idMyGadget_options', 'site_title_phone', 'idMyGadget_sanitize_string_fn' );
	register_setting( 'idMyGadget_options', 'site_title_element_phone', 'idMyGadget_sanitize_element_fn' );
	register_setting( 'idMyGadget_options', 'site_description_phone', 'idMyGadget_sanitize_string_fn' );
	register_setting( 'idMyGadget_options', 'site_description_element_phone', 'idMyGadget_sanitize_element_fn' );

	add_settings_field( 'show_site_name_phone',
		'Show Site Name Phone',
		'show_site_name_radio_buttons_fn',
		'idMyGadget_options',
		'idMyGadget_phone_options',
		array( 'label_for' => 'show_site_name_phone' ) );

	// ...
	register_setting( 'idMyGadget_options', 'logo_file_tablet', 'idMyGadget_sanitize_image_file_fn' );
	register_setting( 'idMyGadget_options', 'show_site_name_tablet', 'idMyGadget_sanitize_boolean_fn' );

	register_setting( 'idMyGadget_options', 'logo_file_desktop', 'idMyGadget_sanitize_image_file_fn' );
	register_setting( 'idMyGadget_options', 'show_site_name_desktop', 'idMyGadget_sanitize_boolean_fn' );
}

// if ( is_admin() )  // Add the options only when we are logged in as an admin
// {
	add_action( 'admin_init', 'idMyGadget_admin_init' );
// }

function idMyGadget_section_html_fn( $section_data )
{
	echo '<p>Device-specific options for ' . $section_data['title'] . '</p>';
}

function show_site_name_radio_buttons_fn( $field_data )
{
//	echo '<p>print_r($field_data):</p>';
//	print_r( $field_data );
	$choices = array( 'Yes', 'No' );
	foreach( $choices as $choice )
	{
		$checked = '';
		echo '<label><input ' . $checked . ' type="radio" value="' . $choice . '" />' . $choice;
		echo '</label>';
	}
}

//
// Functions to sanitize user input
//
function idMyGadget_sanitize_boolean_fn( $input )
{
	error_log( 'ToDo: implement function idMyGadget_sanitize_boolean_fn()' );
	return $input;
}
function idMyGadget_sanitize_element_fn( $input )
{
	error_log( 'ToDo: implement function idMyGadget_sanitize_element_fn()' );
	return $input;
}
function idMyGadget_sanitize_radio_fn( $input )
{
	error_log( 'ToDo: implement function idMyGadget_sanitize_radio_fn()' );
	return $input;
}
function idMyGadget_sanitize_string_fn( $input )
{
	error_log( 'ToDo: implement function idMyGadget_sanitize_string_fn()' );
	return $input;
}

