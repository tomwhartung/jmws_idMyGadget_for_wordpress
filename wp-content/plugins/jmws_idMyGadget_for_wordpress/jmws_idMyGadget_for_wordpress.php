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
 * @global type $jmwsIdMyGadget
 */
function jmws_idMyGadget_for_wordpress()
{
//	print 'Hello World from jmws_idMyGadget_for_wordpress.php .';
//	$gadgetDetector = $this->params->get('gadgetDetector');
//	$gadgetDetector = 'detect_mobile_browsers';
	$gadgetDetector = 'mobile_detect';
//	$gadgetDetector = 'tera_wurfl';
	global $jmwsIdMyGadget;
	$jmwsIdMyGadget = new JmwsIdMyGadgetWordpress($gadgetDetector);

	$jmwsIdMyGadget->usingJQueryMobile = FALSE;


	global $idMyGadgetClass;
	$idMyGadgetClass = get_class( $jmwsIdMyGadget->getIdMyGadget() );

}

add_action( 'wp', 'jmws_idMyGadget_for_wordpress' );

