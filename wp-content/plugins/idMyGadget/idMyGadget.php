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
	$page = add_plugins_page(
		'IdMyGadget Options',
		'IdMyGadget',
		'manage_options',
		__FILE__,
		'idMyGadget_options_page_html_fcn'    // Form markup is in idMyGadgetOptionsPage.php
	);
	add_action( 'admin_print_styles-' . $page, 'idMyGadget_include_admin_styles' );
}
function idMyGadget_include_admin_styles()
{
	wp_enqueue_style( 'idMyGadgetStylesheet' );
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
	$radioChoices = array( 'Yes', 'No' );
	$validElements = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div', 'span' );

	wp_register_style( 'idMyGadgetStylesheet', plugins_url('idMyGadget.css', __FILE__) );

	//
	// ---------------------
	// Phone option settings
	// ---------------------
	//
	add_settings_section( 'idMyGadget_phone_options',
		'Phones',
		'idMyGadget_section_html_fcn',
		'idMyGadget_option_settings' );

	register_setting( 'idMyGadget_option_settings', 'logo_file_phone', 'idMyGadget_sanitize_image_file_fcn' );

	register_setting( 'idMyGadget_option_settings', 'show_site_name_phone', 'idMyGadget_sanitize_boolean_fcn' );
	add_settings_field( 'show_site_name_phone',
		'Show Site Name Phone',
		'show_site_name_radio_buttons_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_phone_options',
		array(
			'name' => 'show_site_name_phone',
			'value' => get_option('show_site_name_phone'),
			'choices' => $radioChoices
		)
	);

	register_setting( 'idMyGadget_option_settings', 'site_name_element_phone', 'idMyGadget_sanitize_element_fcn' );
	add_settings_field( 'site_name_element_phone',
		'Site Name Element Phone',
		'header_element_select_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_phone_options',
		array(
			'name' => 'site_name_element_phone',
			'value' => get_option('site_name_element_phone'),
			'choices' => $validElements
		)
	);

	register_setting( 'idMyGadget_option_settings', 'site_title_phone', 'idMyGadget_sanitize_string_fcn' );
	add_settings_field( 'site_title_phone',
		'Site Title Phone',
		'header_text_box_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_phone_options',
		array(
			'name' => 'site_title_phone',
			'value' => get_option('site_title_phone'),
		)
	);

	register_setting( 'idMyGadget_option_settings', 'site_title_element_phone', 'idMyGadget_sanitize_element_fcn' );
	add_settings_field( 'site_title_element_phone',
		'Site Title Element Phone',
		'header_element_select_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_phone_options',
		array(
			'name' => 'site_title_element_phone',
			'value' => get_option('site_title_element_phone'),
			'choices' => $validElements
		)
	);

	register_setting( 'idMyGadget_option_settings', 'site_description_phone', 'idMyGadget_sanitize_string_fcn' );
	add_settings_field( 'site_description_phone',
		'Tag Line Phone',
		'header_text_box_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_phone_options',
		array(
			'name' => 'site_description_phone',
			'value' => get_option('site_description_phone'),
		)
	);

	register_setting( 'idMyGadget_option_settings', 'site_description_element_phone', 'idMyGadget_sanitize_element_fcn' );
	add_settings_field( 'site_description_element_phone',
		'Tag Line Element Phone',
		'header_element_select_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_phone_options',
		array(
			'name' => 'site_description_element_phone',
			'value' => get_option('site_description_element_phone'),
			'choices' => $validElements
		)
	);

	//
	// ----------------------
	// Tablet option settings
	// ----------------------
	//
	add_settings_section( 'idMyGadget_tablet_options',
		'Tablets',
		'idMyGadget_section_html_fcn',
		'idMyGadget_option_settings' );

	register_setting( 'idMyGadget_option_settings', 'logo_file_tablet', 'idMyGadget_sanitize_image_file_fcn' );

	register_setting( 'idMyGadget_option_settings', 'show_site_name_tablet', 'idMyGadget_sanitize_boolean_fcn' );
	add_settings_field( 'show_site_name_tablet',
		'Show Site Name Tablet',
		'show_site_name_radio_buttons_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_tablet_options',
		array(
			'name' => 'show_site_name_tablet',
			'value' => get_option('show_site_name_tablet'),
			'choices' => $radioChoices
		)
	);

	register_setting( 'idMyGadget_option_settings', 'site_name_element_tablet', 'idMyGadget_sanitize_element_fcn' );
	add_settings_field( 'site_name_element_tablet',
		'Site Name Element Tablet',
		'header_element_select_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_tablet_options',
		array(
			'name' => 'site_name_element_tablet',
			'value' => get_option('site_name_element_tablet'),
			'choices' => $validElements
		)
	);

	register_setting( 'idMyGadget_option_settings', 'site_title_tablet', 'idMyGadget_sanitize_string_fcn' );
	add_settings_field( 'site_title_tablet',
		'Site Title Tablet',
		'header_text_box_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_tablet_options',
		array(
			'name' => 'site_title_tablet',
			'value' => get_option('site_title_tablet'),
		)
	);

	register_setting( 'idMyGadget_option_settings', 'site_title_element_tablet', 'idMyGadget_sanitize_element_fcn' );
	add_settings_field( 'site_title_element_tablet',
		'Site Title Element Tablet',
		'header_element_select_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_tablet_options',
		array(
			'name' => 'site_title_element_tablet',
			'value' => get_option('site_title_element_tablet'),
			'choices' => $validElements
		)
	);

	register_setting( 'idMyGadget_option_settings', 'site_description_tablet', 'idMyGadget_sanitize_string_fcn' );
	add_settings_field( 'site_description_tablet',
		'Tag Line Tablet',
		'header_text_box_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_tablet_options',
		array(
			'name' => 'site_description_tablet',
			'value' => get_option('site_description_tablet'),
		)
	);

	register_setting( 'idMyGadget_option_settings', 'site_description_element_tablet', 'idMyGadget_sanitize_element_fcn' );
	add_settings_field( 'site_description_element_tablet',
		'Tag Line Element Tablet',
		'header_element_select_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_tablet_options',
		array(
			'name' => 'site_description_element_tablet',
			'value' => get_option('site_description_element_tablet'),
			'choices' => $validElements
		)
	);

	//
	// -----------------------
	// Desktop option settings
	// -----------------------
	//
	add_settings_section( 'idMyGadget_desktop_options',
		'Desktops',
		'idMyGadget_section_html_fcn',
		'idMyGadget_option_settings' );

	register_setting( 'idMyGadget_option_settings', 'logo_file_desktop', 'idMyGadget_sanitize_image_file_fcn' );
	register_setting( 'idMyGadget_option_settings', 'show_site_name_desktop', 'idMyGadget_sanitize_boolean_fcn' );
	add_settings_field( 'show_site_name_desktop',
		'Show Site Name Desktop',
		'show_site_name_radio_buttons_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_desktop_options',
		array(
			'name' => 'show_site_name_desktop',
			'value' => get_option('show_site_name_desktop'),
			'choices' => $radioChoices
		)
	);

	register_setting( 'idMyGadget_option_settings', 'site_name_element_desktop', 'idMyGadget_sanitize_element_fcn' );
	add_settings_field( 'site_name_element_desktop',
		'Site Name Element Desktop',
		'header_element_select_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_desktop_options',
		array(
			'name' => 'site_name_element_desktop',
			'value' => get_option('site_name_element_desktop'),
			'choices' => $validElements
		)
	);

	register_setting( 'idMyGadget_option_settings', 'site_title_desktop', 'idMyGadget_sanitize_string_fcn' );
	add_settings_field( 'site_title_desktop',
		'Site Title Desktop',
		'header_text_box_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_desktop_options',
		array(
			'name' => 'site_title_desktop',
			'value' => get_option('site_title_desktop'),
		)
	);

	register_setting( 'idMyGadget_option_settings', 'site_title_element_desktop', 'idMyGadget_sanitize_element_fcn' );
	add_settings_field( 'site_title_element_desktop',
		'Site Title Element Desktop',
		'header_element_select_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_desktop_options',
		array(
			'name' => 'site_title_element_desktop',
			'value' => get_option('site_title_element_desktop'),
			'choices' => $validElements
		)
	);

	register_setting( 'idMyGadget_option_settings', 'site_description_desktop', 'idMyGadget_sanitize_string_fcn' );
	add_settings_field( 'site_description_desktop',
		'Tag Line Desktop',
		'header_text_box_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_desktop_options',
		array(
			'name' => 'site_description_desktop',
			'value' => get_option('site_description_desktop'),
		)
	);

	register_setting( 'idMyGadget_option_settings', 'site_description_element_desktop', 'idMyGadget_sanitize_element_fcn' );
	add_settings_field( 'site_description_element_desktop',
		'Tag Line Element Desktop',
		'header_element_select_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_desktop_options',
		array(
			'name' => 'site_description_element_desktop',
			'value' => get_option('site_description_element_desktop'),
			'choices' => $validElements
		)
	);

}

// if ( is_admin() )  // Add the options only when we are logged in as an admin
// {
	add_action( 'admin_init', 'idMyGadget_admin_init' );
// }

function idMyGadget_section_html_fcn( $section_data )
{
	echo '<p>Device-specific options for ' . $section_data['title'] . '</p>';
}

function show_site_name_radio_buttons_html_fcn( $field_data )
{
	$name = $field_data['name'];
	$value = $field_data['value'];
	$choices = $field_data['choices'];
	foreach( $choices as $choice )
	{
		$checked =  $value == strtolower($choice) ? 'checked' : '';
		echo '<label class="idMyGadget-radio" for="' . $name . '-' . $choice . '">';
		echo '<input name="' . $name . '" id="' . $name . '-' . $choice . '" ' .
			$checked . ' type="radio" value="' . strtolower($choice) . '">' . $choice;
		echo '</input></label>';
	}
}
/**
 *
 * @param type $field_data
 */
function header_element_select_html_fcn( $field_data )
{
	$name = $field_data['name'];
	$value = $field_data['value'];
	$validElements = $field_data['choices'];
	// echo 'value: ' . $value;
	echo '<select name="' . $name . '" id="' . $name . '">';

	foreach( $validElements as $elt )
	{
		$selected = $value == $elt ? 'selected' : '';
		echo '<option value="' . $elt . '" ' . $selected . '>' . $elt;
		echo '</option>';
	}

	echo '</select>';
}
function header_text_box_html_fcn( $field_data )
{
	$name = $field_data['name'];
	$value = $field_data['value'];
	echo '<label for="' . $name . '">';
	echo '<input type="text" name="' . $name . '" id="' . $name . '" ' .
		'value="' . esc_attr($value) . '" />';
	echo '</label>';
}
//
// Functions to sanitize user input
//
function idMyGadget_sanitize_boolean_fcn( $input )
{
	error_log( 'ToDo: implement function idMyGadget_sanitize_boolean_fcn()' );
	return $input;
}
function idMyGadget_sanitize_element_fcn( $input )
{
	error_log( 'ToDo: implement function idMyGadget_sanitize_element_fcn()' );
	return $input;
}
function idMyGadget_sanitize_image_file_fcn( $input )
{
	error_log( 'ToDo: implement function idMyGadget_sanitize_image_file_fcn()' );
	return $input;
}
function idMyGadget_sanitize_radio_fcn( $input )
{
	error_log( 'ToDo: implement function idMyGadget_sanitize_radio_fcn()' );
	return $input;
}
function idMyGadget_sanitize_string_fcn( $input )
{
	error_log( 'ToDo: implement function idMyGadget_sanitize_string_fcn()' );
	return $input;
}

