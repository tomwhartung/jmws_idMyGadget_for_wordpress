<?php
/*
 * @package jmws_idMyGadget_for_wordpress
 *
 * Plugin Name: jmws_idMyGadget_for_wordpress
 * Plugin URI: 
 * Description: Integrate idMyGadget with a couple of wordpress themes (to start).
 * Author: Tom Hartung
 * Version: 1.0
 * Author URI: http://tomwhartung.com/
 */
define( 'IDMYGADGET__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
require_once( IDMYGADGET__PLUGIN_DIR . '/JmwsIdMyGadgetWordpress.php' );
// require_once 'jmws_idMyGadget_for_joomla/PhoneBurgerMenuIcon.php';

/*
 * Array of Gadget Detectors
 */
$gadget_detectors_array = array(
	'detect_mobile_browsers',   // note that this is used as the default throughout
	'mobile_detect',
	'tera_wurfl'
);

$jmwsIdMyGadget = null;

/**
 * @global array $gadget_detectors_array
 * @global object $jmwsIdMyGadget
 */
function jmws_idMyGadget_for_wordpress()
{
	global $gadget_detectors_array;
//	print 'Hello World from jmws_idMyGadget_for_wordpress.php .';
//	$gadgetDetector = $this->params->get('gadgetDetector');
//	$gadgetDetector = $gadget_detectors_array[0];
//	$gadgetDetector = $gadget_detectors_array[1];
//	$gadgetDetector = $gadget_detectors_array[2];

	global $gadgetDetectorIndex;
	global $gadgetDetectorString;
//	$gadgetDetectorIndex = get_theme_mod('gadget_detector_select');
//	$gadgetDetectorIndex = get_theme_mod('gadget_detector_radio');
	$gadgetDetectorIndex = get_theme_mod('gadget_detector');
	$gadgetDetectorString = $gadget_detectors_array[$gadgetDetectorIndex];

	global $jmwsIdMyGadget;
	$jmwsIdMyGadget = new JmwsIdMyGadgetWordpress($gadgetDetector);

	$jmwsIdMyGadget->usingJQueryMobile = FALSE;


	global $idMyGadgetClass;
	$idMyGadgetClass = get_class( $jmwsIdMyGadget->getIdMyGadget() );

}

add_action( 'wp', 'jmws_idMyGadget_for_wordpress' );

function jmws_idmygadget_customize_register( $wp_customize )
{
	global $gadget_detectors_array;
	//
	// Add a section to the theme's Customize side bar that contains
	// radio buttons that allow the admin to set the device detector.
	//
	$wp_customize->add_section( 'gadget_detector' , array(
		'title'      => __( 'IdMyGadget', 'jmws_wp_vqsg_fs_idMyGadget' ),
		'description' => __( 'Select the 3rd party device detector to use for this theme.' ),
		'priority'   => 9999,
	) );

	$wp_customize->add_setting( 'gadget_detector' , array(
		'default'     => $gadget_detectors_array[0],
		'transport'   => 'refresh',
	) );

	$wp_customize->add_control( 'gadget_detector', array(
		'label'    => __( 'Gadget Detector', 'jmws_wp_vqsg_fs_idMyGadget' ),
		'section'  => 'gadget_detector',
		'type'     => 'radio',
		'choices'  => $gadget_detectors_array,
		'priority' => 100,
	) );
}

$theme_object = wp_get_theme();
$theme_object_stylesheet = $theme_object->stylesheet;

add_action( 'customize_register', 'jmws_idmygadget_customize_register' );

