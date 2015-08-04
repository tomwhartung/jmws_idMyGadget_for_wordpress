<?php
/**
 * Creates an object of the desired idMyGadget subclass and uses it for device detection.
 * NOTE:
 * *IF* we can keep all the joomla-specific code here,
 * *THEN* we can reuse the rest of the code in this project for WP and Drupal (and...?)
 */
require_once 'JmwsIdMyGadget.php';

class JmwsIdMyGadgetJoomla extends JmwsIdMyGadget
{
	/**
	 * Boolean: Using jQuery Mobile changes everything, so we need to know when we are using it.
	 * Although we always use it on phones, we do not always use it on tablets.
	 */
	public $usingJQueryMobile = FALSE;
	/**
	 * Boolean: determines whether we want the hamburger menu in the upper left corner
	 * of this page for this device.
	 * Set by the template, based on options set in the back end.
	 * Kept here so that modules can access it without us polluting the global namespace.
	 */
	public $phoneBurgerIconThisDeviceLeft = FALSE;
	/**
	 * Boolean: analogous to phoneBurgerIconThisDeviceLeft, but for the right side.
	 */
	public $phoneBurgerIconThisDeviceRight = FALSE;

	/**
	 * Constructor: for best results, install and use a gadgetDetector other than the default
	 */
	public function __construct( $gadgetDetector=null, $debugging=FALSE, $allowOverridesInUrl=TRUE )
	{
		$application = JFactory::getApplication();
		$templateName = $application->getTemplate();
		$this->idMyGadgetDir = JPATH_LIBRARIES . DS . 'vendor' . DS . 'jmws_idMyGadget_for_joomla';
		set_include_path( get_include_path() . PATH_SEPARATOR . $this->idMyGadgetDir );

		parent::__construct( $gadgetDetector, $debugging, $allowOverridesInUrl );
	}
}
