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

$jmwsIdMyGadget = null;

/**
 *
 * @global object $jmwsIdMyGadget
 */
function jmws_idMyGadget_for_wordpress()
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
add_action( 'wp', 'jmws_idMyGadget_for_wordpress' );

function jmws_idmygadget_customize_register( $wp_customize )
{
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
		'default'     => JmwsIdMyGadgetWordpress::$supportedGadgetDetectors[0],
		'transport'   => 'refresh',
	) );

	$wp_customize->add_control( 'gadget_detector', array(
		'label'    => __( 'Gadget Detector', 'jmws_wp_vqsg_fs_idMyGadget' ),
		'section'  => 'gadget_detector',
		'type'     => 'radio',
		'choices'  => JmwsIdMyGadgetWordpress::$supportedGadgetDetectors,
		'priority' => 100,
	) );
}

/*
 * Check the theme name and add the idMyGadget options to it only if it knows how to use it.
 */
$theme_object = wp_get_theme();
$theme_object_stylesheet = $theme_object->stylesheet;

add_action( 'customize_register', 'jmws_idmygadget_customize_register' );

