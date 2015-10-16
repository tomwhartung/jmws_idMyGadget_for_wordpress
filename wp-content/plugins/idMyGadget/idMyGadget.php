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
	global $jmwsIdMyGadget;

	$gadgetDetectorIndex = get_theme_mod('idmg_gadget_detector');
	$supportedGadgetDetectors = JmwsIdMyGadget::$supportedGadgetDetectors;
	$gadgetDetectorString = $supportedGadgetDetectors[$gadgetDetectorIndex];

	$jmwsIdMyGadget = new JmwsIdMyGadgetWordpress($gadgetDetectorString);
	$jmwsIdMyGadget->usingJQueryMobile = FALSE;
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
	// IdMyGadget Detector Options: Select Device Detector
	// ----------------------------------------------------
	// Add a section to the theme's Customize side bar that contains
	// radio buttons that allow the admin to set the device detector.
	//
	$wp_customize->add_section( 'gadget_detector' , array(
		'title'      => __( 'IdMyGadget Detector', $theme_object_stylesheet ),
		'description' => __( 'Select the 3rd party device detector to use for this theme.' ),
		'priority'   => 9990,
	) );

	$wp_customize->add_setting( 'idmg_gadget_detector' , array(
		'default'     => JmwsIdMyGadget::$supportedGadgetDetectors[0],
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmg_gadget_detector', array(
		'label'    => __( 'Gadget Detector', $theme_object_stylesheet ),
		'section'  => 'gadget_detector',
		'type'     => 'radio',
		'choices'  => JmwsIdMyGadget::$supportedGadgetDetectors,
		'priority' => 100,
	) );
	//
	// IdMyGadget Header and Footer Menu Options: Which Device(s)?
	// -----------------------------------------------------------
	// Add a section to the theme's Customize side bar that contains
	// radio buttons that allow the admin to set which device(s) should .
	//
	$wp_customize->add_section( 'header_footer_menus' , array(
		'title'      => __( 'IdMyGadget Header/Footer Menus', $theme_object_stylesheet ),
		'description' => __( 'These jQuery Mobile menus look best on mobile devices, especially phones.'),
		'priority'   => 9993,
	) );

	$wp_customize->add_setting( 'idmg_jqm_data_theme' , array(
		'default'     => JmwsIdMyGadget::$jqueryMobileThemeChoices[0],
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmg_jqm_data_theme', array(
		'label'    => __( 'jQuery Mobile Menu Theme', $theme_object_stylesheet ),
		'section'  => 'header_footer_menus',
		'type'     => 'select',
		'choices'  => JmwsIdMyGadget::$jqueryMobileThemeChoices,
		'priority' => 100,
	) );

	$wp_customize->add_setting( 'idmg_phone_nav_on_phones' , array(
		'default'     => JmwsIdMyGadget::$radioChoices[0],
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmg_phone_nav_on_phones', array(
		'label'    => __( 'Header/Footer Nav on Phones?', $theme_object_stylesheet ),
		'section'  => 'header_footer_menus',
		'type'     => 'radio',
		'choices'  => JmwsIdMyGadget::$radioChoices,
		'priority' => 300,
	) );

	$wp_customize->add_setting( 'idmg_phone_nav_on_tablets' , array(
		'default'     => JmwsIdMyGadget::$radioChoices[0],
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmg_phone_nav_on_tablets', array(
		'label'    => __( 'Header/Footer Nav on Tablets?', $theme_object_stylesheet ),
		'section'  => 'header_footer_menus',
		'type'     => 'radio',
		'choices'  => JmwsIdMyGadget::$radioChoices,
		'priority' => 400,
	) );

	$wp_customize->add_setting( 'idmg_phone_nav_on_desktops' , array(
		'default'     => JmwsIdMyGadget::$radioChoices[0],
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmg_phone_nav_on_desktops', array(
		'label'    => __( 'Header/Footer Nav on Desktops?', $theme_object_stylesheet ),
		'section'  => 'header_footer_menus',
		'type'     => 'radio',
		'choices'  => JmwsIdMyGadget::$radioChoices,
		'priority' => 500,
	) );
	//
	// If the current theme is IdMyGadget in TwentyFifteen
	//    add a few more options pertaining to the header image
	// NOTE: WE DECIDED THIS IS MORE TROUBLE THAN IT'S WORTH
	//    SAVING THE CODE THOUGH FOR POSSIBLE FUTURE REFERENCE
	//
	//	if ( $theme_object_stylesheet == 'jmws_wp_twentyfifteen_idMyGadget' )
	//	{
	//		idmygadget_add_twentyfifteen_idMyGadget_options( $wp_customize );
	//	}
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

	register_setting( 'idMyGadget_option_settings',
		'idmg_logo_file_phone',
		'idMyGadget_sanitize_image_file_fcn' );
	add_settings_field( 'idmg_logo_file_phone',
		'Logo Image Phone',
		'image_file_picker_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_phone_options',
		array(
			'name' => 'idmg_logo_file_phone',
			'value' => get_option('idmg_logo_file_phone'),
		)
	);

	register_setting( 'idMyGadget_option_settings',
		'idmg_show_site_name_phone',
		'idMyGadget_sanitize_radio_buttons_fcn' );
	add_settings_field( 'idmg_show_site_name_phone',
		'Show Site Name Phone',
		'show_site_name_radio_buttons_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_phone_options',
		array(
			'name' => 'idmg_show_site_name_phone',
			'value' => get_option('idmg_show_site_name_phone'),
			'choices' => JmwsIdMyGadgetWordpress::$radioChoices
		)
	);

	register_setting( 'idMyGadget_option_settings',
		'idmg_site_name_element_phone',
		'idMyGadget_sanitize_html_element_tag_fcn' );
	add_settings_field( 'idmg_site_name_element_phone',
		'Site Name Element Phone',
		'header_element_select_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_phone_options',
		array(
			'name' => 'idmg_site_name_element_phone',
			'value' => get_option('idmg_site_name_element_phone'),
			'choices' => JmwsIdMyGadgetWordpress::$validElements
		)
	);

	register_setting( 'idMyGadget_option_settings',
		'idmg_site_title_phone',
		'idMyGadget_sanitize_string_fcn' );
	add_settings_field( 'idmg_site_title_phone',
		'Site Title Phone',
		'header_text_box_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_phone_options',
		array(
			'name' => 'idmg_site_title_phone',
			'value' => get_option('idmg_site_title_phone'),
		)
	);

	register_setting( 'idMyGadget_option_settings',
		'idmg_site_title_element_phone',
		'idMyGadget_sanitize_html_element_tag_fcn' );
	add_settings_field( 'idmg_site_title_element_phone',
		'Site Title Element Phone',
		'header_element_select_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_phone_options',
		array(
			'name' => 'idmg_site_title_element_phone',
			'value' => get_option('idmg_site_title_element_phone'),
			'choices' => JmwsIdMyGadgetWordpress::$validElements
		)
	);

	register_setting( 'idMyGadget_option_settings',
		'idmg_site_description_phone',
		'idMyGadget_sanitize_string_fcn' );
	add_settings_field( 'idmg_site_description_phone',
		'Tag Line Phone',
		'header_text_box_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_phone_options',
		array(
			'name' => 'idmg_site_description_phone',
			'value' => get_option('idmg_site_description_phone'),
		)
	);

	register_setting( 'idMyGadget_option_settings',
		'idmg_site_description_element_phone',
		'idMyGadget_sanitize_html_element_tag_fcn' );
	add_settings_field( 'idmg_site_description_element_phone',
		'Tag Line Element Phone',
		'header_element_select_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_phone_options',
		array(
			'name' => 'idmg_site_description_element_phone',
			'value' => get_option('idmg_site_description_element_phone'),
			'choices' => JmwsIdMyGadgetWordpress::$validElements
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

	register_setting( 'idMyGadget_option_settings',
		'idmg_logo_file_tablet',
		'idMyGadget_sanitize_image_file_fcn' );
	add_settings_field( 'idmg_logo_file_tablet',
		'Logo Image Tablet',
		'image_file_picker_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_tablet_options',
		array(
			'name' => 'idmg_logo_file_tablet',
			'value' => get_option('idmg_logo_file_tablet'),
		)
	);

	register_setting( 'idMyGadget_option_settings',
		'idmg_show_site_name_tablet',
		'idMyGadget_sanitize_radio_buttons_fcn' );
	add_settings_field( 'idmg_show_site_name_tablet',
		'Show Site Name Tablet',
		'show_site_name_radio_buttons_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_tablet_options',
		array(
			'name' => 'idmg_show_site_name_tablet',
			'value' => get_option('idmg_show_site_name_tablet'),
			'choices' => JmwsIdMyGadgetWordpress::$radioChoices
		)
	);

	register_setting( 'idMyGadget_option_settings',
		'idmg_site_name_element_tablet',
		'idMyGadget_sanitize_html_element_tag_fcn' );
	add_settings_field( 'idmg_site_name_element_tablet',
		'Site Name Element Tablet',
		'header_element_select_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_tablet_options',
		array(
			'name' => 'idmg_site_name_element_tablet',
			'value' => get_option('idmg_site_name_element_tablet'),
			'choices' => JmwsIdMyGadgetWordpress::$validElements
		)
	);

	register_setting( 'idMyGadget_option_settings',
		'idmg_site_title_tablet',
		'idMyGadget_sanitize_string_fcn' );
	add_settings_field( 'idmg_site_title_tablet',
		'Site Title Tablet',
		'header_text_box_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_tablet_options',
		array(
			'name' => 'idmg_site_title_tablet',
			'value' => get_option('idmg_site_title_tablet'),
		)
	);

	register_setting( 'idMyGadget_option_settings',
		'idmg_site_title_element_tablet',
		'idMyGadget_sanitize_html_element_tag_fcn' );
	add_settings_field( 'idmg_site_title_element_tablet',
		'Site Title Element Tablet',
		'header_element_select_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_tablet_options',
		array(
			'name' => 'idmg_site_title_element_tablet',
			'value' => get_option('idmg_site_title_element_tablet'),
			'choices' => JmwsIdMyGadgetWordpress::$validElements
		)
	);

	register_setting( 'idMyGadget_option_settings',
		'idmg_site_description_tablet',
		'idMyGadget_sanitize_string_fcn' );
	add_settings_field( 'idmg_site_description_tablet',
		'Tag Line Tablet',
		'header_text_box_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_tablet_options',
		array(
			'name' => 'idmg_site_description_tablet',
			'value' => get_option('idmg_site_description_tablet'),
		)
	);

	register_setting( 'idMyGadget_option_settings',
		'idmg_site_description_element_tablet',
		'idMyGadget_sanitize_html_element_tag_fcn' );
	add_settings_field( 'idmg_site_description_element_tablet',
		'Tag Line Element Tablet',
		'header_element_select_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_tablet_options',
		array(
			'name' => 'idmg_site_description_element_tablet',
			'value' => get_option('idmg_site_description_element_tablet'),
			'choices' => JmwsIdMyGadgetWordpress::$validElements
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

	register_setting( 'idMyGadget_option_settings',
		'idmg_logo_file_desktop',
		'idMyGadget_sanitize_image_file_fcn' );
	add_settings_field( 'idmg_logo_file_desktop',
		'Logo Image Desktop',
		'image_file_picker_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_desktop_options',
		array(
			'name' => 'idmg_logo_file_desktop',
			'value' => get_option('idmg_logo_file_desktop'),
		)
	);

	register_setting( 'idMyGadget_option_settings',
		'idmg_show_site_name_desktop',
		'idMyGadget_sanitize_radio_buttons_fcn' );
	add_settings_field( 'idmg_show_site_name_desktop',
		'Show Site Name Desktop',
		'show_site_name_radio_buttons_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_desktop_options',
		array(
			'name' => 'idmg_show_site_name_desktop',
			'value' => get_option('idmg_show_site_name_desktop'),
			'choices' => JmwsIdMyGadgetWordpress::$radioChoices
		)
	);

	register_setting( 'idMyGadget_option_settings',
		'idmg_site_name_element_desktop',
		'idMyGadget_sanitize_html_element_tag_fcn' );
	add_settings_field( 'idmg_site_name_element_desktop',
		'Site Name Element Desktop',
		'header_element_select_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_desktop_options',
		array(
			'name' => 'idmg_site_name_element_desktop',
			'value' => get_option('idmg_site_name_element_desktop'),
			'choices' => JmwsIdMyGadgetWordpress::$validElements
		)
	);

	register_setting( 'idMyGadget_option_settings',
		'idmg_site_title_desktop',
		'idMyGadget_sanitize_string_fcn' );
	add_settings_field( 'idmg_site_title_desktop',
		'Site Title Desktop',
		'header_text_box_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_desktop_options',
		array(
			'name' => 'idmg_site_title_desktop',
			'value' => get_option('idmg_site_title_desktop'),
		)
	);

	register_setting( 'idMyGadget_option_settings',
		'idmg_site_title_element_desktop',
		'idMyGadget_sanitize_html_element_tag_fcn' );
	add_settings_field( 'idmg_site_title_element_desktop',
		'Site Title Element Desktop',
		'header_element_select_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_desktop_options',
		array(
			'name' => 'idmg_site_title_element_desktop',
			'value' => get_option('idmg_site_title_element_desktop'),
			'choices' => JmwsIdMyGadgetWordpress::$validElements
		)
	);

	register_setting( 'idMyGadget_option_settings',
		'idmg_site_description_desktop',
		'idMyGadget_sanitize_string_fcn' );
	add_settings_field( 'idmg_site_description_desktop',
		'Tag Line Desktop',
		'header_text_box_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_desktop_options',
		array(
			'name' => 'idmg_site_description_desktop',
			'value' => get_option('idmg_site_description_desktop'),
		)
	);

	register_setting( 'idMyGadget_option_settings',
		'idmg_site_description_element_desktop',
		'idMyGadget_sanitize_html_element_tag_fcn' );
	add_settings_field( 'idmg_site_description_element_desktop',
		'Tag Line Element Desktop',
		'header_element_select_html_fcn',
		'idMyGadget_option_settings',
		'idMyGadget_desktop_options',
		array(
			'name' => 'idmg_site_description_element_desktop',
			'value' => get_option('idmg_site_description_element_desktop'),
			'choices' => JmwsIdMyGadgetWordpress::$validElements
		)
	);

}
add_action( 'admin_init', 'idMyGadget_admin_init' );

/**
 * Add the JavaScript file we need to use to upload the logo image:
 */
function idMyGadget_admin_enqueue_scripts()
{
	if ( isset($_GET['page']) && $_GET['page'] == 'idMyGadget/idMyGadget.php' )
	{
		wp_enqueue_media();
		wp_register_script( 'idMyGadget-js', WP_PLUGIN_URL . '/idMyGadget/idMyGadget.js', array('jquery') );
		wp_enqueue_script( 'idMyGadget-js' );
	}
}
add_action( 'admin_enqueue_scripts', 'idMyGadget_admin_enqueue_scripts' );

//
// -------------------------------------------------------------------
// Html Fcns: Functions that generate the html for each of the options
// -------------------------------------------------------------------
//
/**
 * Html fcn for the header of each section
 * @param type $section_data name of the section
 */
function idMyGadget_section_html_fcn( $section_data )
{
	echo '<p>Device-specific options for ' . $section_data['title'] . '.</p>';
}
/**
 * Html fcn for the icon allowing user to choose a file (i.e., for logo image)
 * @param type $field_data name and current value of the field
 */
function image_file_picker_html_fcn( $field_data )
{
	$name = $field_data['name'];
	$value = $field_data['value'];
	//
	// Code inspired by this article:
	//   http://www.webmaster-source.com/2013/02/06/using-the-wordpress-3-5-media-uploader-in-your-plugin-or-theme/
	// The link to which I found at the bottom of this page:
	//   http://www.webmaster-source.com/2010/01/08/using-the-wordpress-uploader-in-your-plugin-or-theme/
	// Which was linked to by the first one I found here:
	//   http://wordpress.stackexchange.com/questions/26976/wordpress-file-browser/203819#203819
	//
	echo '<label for="' . $name . '">';
	echo '<input id="' . $name . '" name="' . $name . '" ' .
		'type="text" size="36" value="' . $value . '" />';
	echo '<span class="idMyGadget-text">Enter the image URL</span>';
	echo '</label><br />';
	echo '<label for="' . $name . '_button">';
	echo '<input id="' . $name . '_button" class="button idMyGadget_upload_image" ' .
		'type="button" value="Upload/Select" />';
	echo '<span class="idMyGadget-text">Click to select an image from the WP Media Library</span>';
	echo '</label>';
}
/**
 * Html fcn for the radio buttons
 * @param type $field_data name and current value of the field, and array of valid options
 */
function show_site_name_radio_buttons_html_fcn( $field_data )
{
	$name = $field_data['name'];
	$value = $field_data['value'];
//	$choices = array( 'Yes', 'No', 'Maybe' );   // for testing sanitize fcn
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
 * Html fcn for the drop down select for the element of a heading component
 * (html tag e.g., allows selection of h3 element for site title)
 * @param type $field_data name and current value of the field, and array of valid options
 */
function header_element_select_html_fcn( $field_data )
{
	$name = $field_data['name'];
	$value = $field_data['value'];
//	$validElements = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div', 'span', 'img' );  // for testing sanitize fcn
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
/**
 * Html fcn for the text box input (e.g., for site title)
 * @param type $field_data name and current value of the field
 */
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
// --------------------------------
// Functions to sanitize user input
// --------------------------------
//
/**
 * If the value we get for the radio buttons is invalid,
 *   substitute a valid default value
 * @param string $suspicious_input
 * @return string $sanitized_input
 */
function idMyGadget_sanitize_radio_buttons_fcn( $suspicious_input )
{
	$sanitized_input = strtolower( JmwsIdMyGadgetWordpress::$radioChoices[0] );

	if ( in_array(ucfirst($suspicious_input), JmwsIdMyGadgetWordpress::$radioChoices) )
	{
		$sanitized_input = $suspicious_input;
	}

	return $sanitized_input;
}
/**
 * If the value we get for the html element drop down is invalid,
 *   substitute a valid default value
 * @param string $suspicious_input
 * @return string $sanitized_input
 */
function idMyGadget_sanitize_html_element_tag_fcn( $suspicious_input )
{
	$sanitized_input = strtolower( JmwsIdMyGadgetWordpress::$validElements[0] );

	if ( in_array($suspicious_input, JmwsIdMyGadgetWordpress::$validElements) )
	{
		$sanitized_input = $suspicious_input;
	}

	return $sanitized_input;
}
/**
 * Sanitize the value we get for the image file name
 * @param string $suspicious_input
 * @return string $sanitized_input
 */
function idMyGadget_sanitize_image_file_fcn( $suspicious_input )
{
	$sanitized_input = sanitize_text_field( $suspicious_input );
	return $sanitized_input;
}
/**
 * Sanitize the value we get for an input text string (e.g., the site title)
 * @param string $suspicious_input
 * @return string $sanitized_input
 */
function idMyGadget_sanitize_string_fcn( $suspicious_input )
{
	$sanitized_input = sanitize_text_field( $suspicious_input );
	return $sanitized_input;
}
//
// ========================================
// ***  UNUSED CODE - DELETE OR IGNORE  ***
// ========================================
//
/**
 * THIS FUNCTION CURRENTLY UNUSED - SAVING IT FOR POSSIBLE FUTURE REFERENCE
 * At one point I was thinking that it would be nice to give the admin the option of
 * using the Header Image as a banner on specific gadget types (esp. desktop)
 * It turned out that doing this by quickly making some minor changes was not possible
 * (not worth the effort).
 * Leaving this function (which represents most of the work) here for possible future reference
 * @global type $theme_object_stylesheet
 * @param type $wp_customize
 * @note THIS FUNCTION CURRENTLY UNUSED - SAVING IT FOR POSSIBLE FUTURE REFERENCE
 */
function idmygadget_add_twentyfifteen_idMyGadget_options( $wp_customize )
{
	global $theme_object_stylesheet;   // aka. the theme "name"
	$headerImageChoices = array( 'Do not use', 'Use as banner' );

	//
	// Add a section to the theme's Customize side bar that contains
	// radio buttons that allow the admin to set the device detector.
	//
	$wp_customize->add_section( 'idmygadget_header_image' , array(
		'title'      => __( 'IdMyGadget Header Image', $theme_object_stylesheet ),
		'description' => __( 'Allows using the twentyfifteen header image as a banner.' ),
		'priority'   => 9999,
	) );
	$wp_customize->add_setting( 'idmygadget_header_image_phone' , array(
		'default'     => 0,
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmygadget_header_image_phone', array(
		'label'    => __( 'Use Header Image as Banner on Phones', $theme_object_stylesheet ),
		'section'  => 'idmygadget_header_image',
		'type'     => 'radio',
		'choices'  => $headerImageChoices,
		'priority' => 100,
	) );
	$wp_customize->add_setting( 'idmygadget_header_image_tablet' , array(
		'default'     => 0,
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmygadget_header_image_tablet', array(
		'label'    => __( 'Use Header Image as Banner on Tablets', $theme_object_stylesheet ),
		'section'  => 'idmygadget_header_image',
		'type'     => 'radio',
		'choices'  => $headerImageChoices,
		'priority' => 100,
	) );
	$wp_customize->add_setting( 'idmygadget_header_image_desktop' , array(
		'default'     => 1,
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmygadget_header_image_desktop', array(
		'label'    => __( 'Use Header Image as Banner on Desktops', $theme_object_stylesheet ),
		'section'  => 'idmygadget_header_image',
		'type'     => 'radio',
		'choices'  => $headerImageChoices,
		'priority' => 100,
	) );
}
