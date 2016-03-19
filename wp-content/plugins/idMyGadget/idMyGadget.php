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
//
// -------------------------------------------------------------
// Initialize Device Detection and Theme Locations for the Menus
// -------------------------------------------------------------
//
define( 'IDMYGADGET_PLUGIN_URL', plugin_dir_url( __FILE__ ) );   // used to load local js
define( 'IDMYGADGET_PLUGIN_DIRECTORY', plugin_dir_path( __FILE__ ) . DIRECTORY_SEPARATOR . 'idMyGadget' );
require_once( IDMYGADGET_PLUGIN_DIRECTORY . DIRECTORY_SEPARATOR . 'JmwsIdMyGadgetWordpress.php' );
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
function idMyGadget_wp()
{
	global $jmwsIdMyGadget;
	$gadgetDetectorIndex = get_theme_mod('idmg_gadget_detector');
	$supportedGadgetDetectors = JmwsIdMyGadget::$supportedGadgetDetectors;
	$gadgetDetectorString = $supportedGadgetDetectors[$gadgetDetectorIndex];

	$jmwsIdMyGadget = new JmwsIdMyGadgetWordpress($gadgetDetectorString);
}
add_action( 'wp', 'idMyGadget_wp' );
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
	// IdMyGadget Header and Footer Menu Options: Which Device Type(s)?
	// ----------------------------------------------------------------
	// Add a section to the theme's Customize side bar that contains
	// radio buttons that allow the admin to set which device(s) should display.
	// For info. about the jmws_wp_twentyfifteen_idMyGadget-specific options,
	//   see the README.md for that repo.
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
		'priority' => 200,
	) );

	if ( $theme_object_stylesheet == 'idmygadget_twentyfifteen' )
	{
		$wp_customize->add_setting( 'idmg_nav_in_page_or_sidebar_phones' , array(
			'default'     => JmwsIdMyGadgetWordpress::$pageOrSidebar2015Options[1],
			'transport'   => 'refresh',
		) );
		$wp_customize->add_control( 'idmg_nav_in_page_or_sidebar_phones', array(
			'label'    => __( 'In Page or Sidebar on Phones?', $theme_object_stylesheet ),
			'section'  => 'header_footer_menus',
			'type'     => 'radio',
			'choices'  => JmwsIdMyGadgetWordpress::$pageOrSidebar2015Options,
			'priority' => 300,
		) );
	}

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

	if ( $theme_object_stylesheet == 'jmws_wp_twentyfifteen_idMyGadget' )
	{
		$wp_customize->add_setting( 'idmg_nav_in_page_or_sidebar_tablets' , array(
			'default'     => JmwsIdMyGadgetWordpress::$pageOrSidebar2015Options[0],
			'transport'   => 'refresh',
		) );
		$wp_customize->add_control( 'idmg_nav_in_page_or_sidebar_tablets', array(
			'label'    => __( 'In Page or Sidebar on Tablets?', $theme_object_stylesheet ),
			'section'  => 'header_footer_menus',
			'type'     => 'radio',
			'choices'  => JmwsIdMyGadgetWordpress::$pageOrSidebar2015Options,
			'priority' => 500,
		) );
	}

	$wp_customize->add_setting( 'idmg_phone_nav_on_desktops' , array(
		'default'     => JmwsIdMyGadget::$radioChoices[0],
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmg_phone_nav_on_desktops', array(
		'label'    => __( 'Header/Footer Nav on Desktops?', $theme_object_stylesheet ),
		'section'  => 'header_footer_menus',
		'type'     => 'radio',
		'choices'  => JmwsIdMyGadget::$radioChoices,
		'priority' => 600,
	) );

	if ( $theme_object_stylesheet == 'jmws_wp_twentyfifteen_idMyGadget' )
	{
		$wp_customize->add_setting( 'idmg_nav_in_page_or_sidebar_desktops' , array(
			'default'     => JmwsIdMyGadgetWordpress::$pageOrSidebar2015Options[0],
			'transport'   => 'refresh',
		) );
		$wp_customize->add_control( 'idmg_nav_in_page_or_sidebar_desktops', array(
			'label'    => __( 'In Page or Sidebar on Desktops?', $theme_object_stylesheet ),
			'section'  => 'header_footer_menus',
			'type'     => 'radio',
			'choices'  => JmwsIdMyGadgetWordpress::$pageOrSidebar2015Options,
			'priority' => 700,
		) );
	}
	//
	// IdMyGadget Hamburger Menu Icon Options: Icon on Left Side
	// ---------------------------------------------------------
	// Add a section to the theme's Customize side bar containing controls that allow the
	// admin to customize how the hamburger menu icon on the right side should display.
	//
	$wp_customize->add_section( 'hamburger_menu_icon_left' , array(
		'title'      => __( 'IdMyGadget Left Hamburger Menu Icon', $theme_object_stylesheet ),
		'description' => __( 'Customize the hamburger menu icon that appears on the left side of the heading.'),
		'priority'   => 9995,
	) );

	$wp_customize->add_setting( 'idmg_hamburger_icon_left_on_phones' , array(
		'default'     => JmwsIdMyGadget::$radioChoices[0],
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmg_hamburger_icon_left_on_phones', array(
		'label'    => __( 'Left Hamburger Menu Icon on Phones?', $theme_object_stylesheet ),
		'section'  => 'hamburger_menu_icon_left',
		'type'     => 'radio',
		'choices'  => JmwsIdMyGadget::$radioChoices,
		'priority' => 200,
	) );
	$wp_customize->add_setting( 'idmg_hamburger_icon_left_on_tablets' , array(
		'default'     => JmwsIdMyGadget::$radioChoices[0],
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmg_hamburger_icon_left_on_tablets', array(
		'label'    => __( 'Left Hamburger Menu Icon on Tablets?', $theme_object_stylesheet ),
		'section'  => 'hamburger_menu_icon_left',
		'type'     => 'radio',
		'choices'  => JmwsIdMyGadget::$radioChoices,
		'priority' => 400,
	) );
	$wp_customize->add_setting( 'idmg_hamburger_icon_left_on_desktops' , array(
		'default'     => JmwsIdMyGadget::$radioChoices[0],
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmg_hamburger_icon_left_on_desktops', array(
		'label'    => __( 'Left Hamburger Menu Icon on Desktops?', $theme_object_stylesheet ),
		'section'  => 'hamburger_menu_icon_left',
		'type'     => 'radio',
		'choices'  => JmwsIdMyGadget::$radioChoices,
		'priority' => 600,
	) );

	$wp_customize->add_setting( 'idmg_hamburger_icon_left_size' , array(
		'default'     => 0,
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmg_hamburger_icon_left_size', array(
		'label'    => __( 'Left Hamburger Menu Icon Size', $theme_object_stylesheet ),
		'section'  => 'hamburger_menu_icon_left',
		'type'     => 'select',
		'choices'  => JmwsIdMyGadget::$hamburgerMenuIconSizeChoices,
		'priority' => 1000,
	) );
	//
	// TODO: Update to use a color picker
	// Possible References:
	//    https://codex.wordpress.org/Creating_Options_Pages
	//    https://make.wordpress.org/core/2012/11/30/new-color-picker-in-wp-3-5/
	//
	$wp_customize->add_setting( 'idmg_hamburger_icon_left_color' , array(
		'default'     => '#CCCCCC',
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmg_hamburger_icon_left_color', array(
		'label'    => __( 'Left Hamburger Menu Icon: Color', $theme_object_stylesheet ),
		'section'  => 'hamburger_menu_icon_left',
		'type'     => 'text',
		'priority' => 1020,
	) );
	$wp_customize->add_setting( 'idmg_hamburger_icon_left_line_cap' , array(
		'default'     => 0,
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmg_hamburger_icon_left_line_cap', array(
		'label'    => __( 'Left Hamburger Menu Icon: Line Cap', $theme_object_stylesheet ),
		'section'  => 'hamburger_menu_icon_left',
		'type'     => 'select',
		'choices'  => JmwsIdMyGadget::$hamburgerMenuIconLineCapChoices,
		'priority' => 1030,
	) );
	$wp_customize->add_setting( 'idmg_hamburger_icon_left_line_size' , array(
		'default'     => 0,
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmg_hamburger_icon_left_line_size', array(
		'label'    => __( 'Left Hamburger Menu Icon: Line Size', $theme_object_stylesheet ),
		'section'  => 'hamburger_menu_icon_left',
		'type'     => 'select',
		'choices'  => JmwsIdMyGadget::$hamburgerMenuIconLineSizeChoices,
		'priority' => 1040,
	) );
	//
	// IdMyGadget Hamburger Menu Icon Options: Icon on Right Side
	// ----------------------------------------------------------
	// Add a section to the theme's Customize side bar containing controls that allow the
	// admin to customize how the hamburger menu icon on the right side should display.
	//
	$wp_customize->add_section( 'hamburger_menu_icon_right' , array(
		'title'      => __( 'IdMyGadget Right Hamburger Menu Icon', $theme_object_stylesheet ),
		'description' => __( 'Customize the hamburger menu icon that appears on the right side of the heading.'),
		'priority'   => 9997,
	) );

	$wp_customize->add_setting( 'idmg_hamburger_icon_right_on_phones' , array(
		'default'     => JmwsIdMyGadget::$radioChoices[0],
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmg_hamburger_icon_right_on_phones', array(
		'label'    => __( 'Right Hamburger Menu Icon on Phones?', $theme_object_stylesheet ),
		'section'  => 'hamburger_menu_icon_right',
		'type'     => 'radio',
		'choices'  => JmwsIdMyGadget::$radioChoices,
		'priority' => 200,
	) );
	$wp_customize->add_setting( 'idmg_hamburger_icon_right_on_tablets' , array(
		'default'     => JmwsIdMyGadget::$radioChoices[0],
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmg_hamburger_icon_right_on_tablets', array(
		'label'    => __( 'Right Hamburger Menu Icon on Tablets?', $theme_object_stylesheet ),
		'section'  => 'hamburger_menu_icon_right',
		'type'     => 'radio',
		'choices'  => JmwsIdMyGadget::$radioChoices,
		'priority' => 400,
	) );
	$wp_customize->add_setting( 'idmg_hamburger_icon_right_on_desktops' , array(
		'default'     => JmwsIdMyGadget::$radioChoices[0],
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmg_hamburger_icon_right_on_desktops', array(
		'label'    => __( 'Right Hamburger Menu Icon on Desktops?', $theme_object_stylesheet ),
		'section'  => 'hamburger_menu_icon_right',
		'type'     => 'radio',
		'choices'  => JmwsIdMyGadget::$radioChoices,
		'priority' => 600,
	) );

	$wp_customize->add_setting( 'idmg_hamburger_icon_right_size' , array(
		'default'     => 0,
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmg_hamburger_icon_right_size', array(
		'label'    => __( 'Right Hamburger Menu Icon Size', $theme_object_stylesheet ),
		'section'  => 'hamburger_menu_icon_right',
		'type'     => 'select',
		'choices'  => JmwsIdMyGadget::$hamburgerMenuIconSizeChoices,
		'priority' => 1000,
	) );
	//
	// TODO: Update to use a color picker
	// Possible References:
	//    https://codex.wordpress.org/Creating_Options_Pages
	//    https://make.wordpress.org/core/2012/11/30/new-color-picker-in-wp-3-5/
	//
	$wp_customize->add_setting( 'idmg_hamburger_icon_right_color' , array(
		'default'     => '#CCCCCC',
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmg_hamburger_icon_right_color', array(
		'label'    => __( 'Right Hamburger Menu Icon: Color', $theme_object_stylesheet ),
		'section'  => 'hamburger_menu_icon_right',
		'type'     => 'text',
		'priority' => 1020,
	) );
	$wp_customize->add_setting( 'idmg_hamburger_icon_right_line_cap' , array(
		'default'     => 0,
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmg_hamburger_icon_right_line_cap', array(
		'label'    => __( 'Right Hamburger Menu Icon: Line Cap', $theme_object_stylesheet ),
		'section'  => 'hamburger_menu_icon_right',
		'type'     => 'select',
		'choices'  => JmwsIdMyGadget::$hamburgerMenuIconLineCapChoices,
		'priority' => 1030,
	) );
	$wp_customize->add_setting( 'idmg_hamburger_icon_right_line_size' , array(
		'default'     => 0,
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 'idmg_hamburger_icon_right_line_size', array(
		'label'    => __( 'Right Hamburger Menu Icon: Line Size', $theme_object_stylesheet ),
		'section'  => 'hamburger_menu_icon_right',
		'type'     => 'select',
		'choices'  => JmwsIdMyGadget::$hamburgerMenuIconLineSizeChoices,
		'priority' => 1040,
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
//
// ------------------------------------------------------------------
// Adding CSS and JS files for use on the SITE and in the ADMIN panel
// ------------------------------------------------------------------
//
/**
 * Add the SITE CSS files
 */
function idMyGadget_include_site_styles()
{
//	wp_register_style( 'idMyGadgetStylesheet', plugins_url('idMyGadget.css', __FILE__) );
//	wp_enqueue_style( 'idMyGadgetStylesheet' );
}
/**
 * Add the SITE JavaScript files
 * Add the SITE CSS files
 * Reference:
 *   http://wordpress.stackexchange.com/questions/82490/when-should-i-use-wp-register-script-with-wp-enqueue-script-vs-just-wp-enque
 */
function idMyGadget_wp_enqueue_scripts()
{
	global $jmwsIdMyGadget;
	if ( $jmwsIdMyGadget->usingJQueryMobile )
	{
		// The register_style calls might be useful someday (for more complicated conditionals?)
		// If not, please delete
		//
		//	wp_register_style( 'jquerymobilecss', JmwsIdMyGadget::JQUERY_MOBILE_CSS_URL );
		//	wp_enqueue_style( 'jquerymobilecss' );
		//	wp_register_script( 'jquerymobile-js', JmwsIdMyGadget::JQUERY_MOBILE_JS_URL, array('jquery') );
		//	wp_enqueue_script( 'jquerymobile-js' );
		wp_enqueue_style( 'jquerymobile-css', JmwsIdMyGadget::JQUERY_MOBILE_CSS_URL );
		wp_enqueue_script( 'jquerymobile-js', JmwsIdMyGadget::JQUERY_MOBILE_JS_URL, array('jquery') );
		if ( $jmwsIdMyGadget->hamburgerIconLeftOnThisDevice ||
		     $jmwsIdMyGadget->hamburgerIconRightOnThisDevice )
		{
			wp_register_script( 'hamburgerMenuIcon-js',
					 IDMYGADGET_PLUGIN_URL . DIRECTORY_SEPARATOR . 'idMyGadget/hamburgerMenuIcon.js' );
			wp_enqueue_script( 'hamburgerMenuIcon-js' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'idMyGadget_wp_enqueue_scripts' );
/**
 * Add the ADMIN CSS file to help make the Plugins -> IdMyGadget page look decent:
 */
function idMyGadget_admin_print_styles()
{
	wp_register_style( 'idMyGadgetStylesheet', plugins_url('idMyGadget.css', __FILE__) );
	wp_enqueue_style( 'idMyGadgetStylesheet' );
}
if ( is_admin() )  // Add the options only when we are logged in as an admin
{
	add_action( 'admin_print_styles' . $page, 'idMyGadget_admin_print_styles' );

}
/**
 * Add the ADMIN JavaScript file we need to use to upload the logo image:
 */
function idMyGadget_admin_enqueue_scripts()
{
	global  $jmwsIdMyGadget;

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
