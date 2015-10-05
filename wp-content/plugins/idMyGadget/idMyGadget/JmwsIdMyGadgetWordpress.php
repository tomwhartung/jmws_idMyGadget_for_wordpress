<?php
/**
 * Creates an object of the desired idMyGadget subclass and uses it for device detection.
 * NOTE:
 * *IF* we can keep all the wordpress-specific code here,
 * *THEN* we can reuse the rest of the code in this project for joomla and Drupal (and...?)
 */
require_once 'JmwsIdMyGadget.php';

class JmwsIdMyGadgetWordpress extends JmwsIdMyGadget
{
	/**
	 * Array of themes that know how to use idMyGadget
	 */
	public static $supportedThemes = array(
		'jmws_wp_vqsg_ot_idMyGadget',
		'jmws_wp_twentyfifteen_idMyGadget'
	);
	/**
	 * Array of choices for yes/no radio buttons (e.g., show site name)
	 */
	public static $radioChoices = array( 'Yes', 'No' );
	/**
	 * Array of choices for lists of elements (e.g., site name, site title)
	 */
	public static $validElements = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div', 'span' );
	/**
	 * Used by when this plugin is not installed or active, etc.
	 * Set only when there's an error.
	 */
	public $errorMessage = '';

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
	public function __construct( $gadgetDetectorString=null, $debugging=FALSE, $allowOverridesInUrl=TRUE )
	{
		$this->idMyGadgetDir = IDMYGADGET__PLUGIN_DIR;
		parent::__construct( $gadgetDetectorString, $debugging, $allowOverridesInUrl );
	}

	public function getLogoTitleDescriptionHtml(  )
	{
		$logoTitleDescription = '';
		$logo_file = '';
		$site_name = get_bloginfo('name' );
		$site_title = '';
		$site_description = '';

		if ( $this->isPhone() )
		{
			$logo_file = get_option( 'idmg_logo_file_phone' );
			$site_title = get_option( 'idmg_site_title_phone' );
			$site_description = get_option('idmg_site_description_phone');
			if ( strlen($logo_file) > 0 )
			{
				$logoTitleDescription .= '<img src="' . $logo_file . '" class="logo-file-phone" alt="' . $site_name . '" />';
			}
			if ( get_option('idmg_show_site_name_phone') == 'yes' )
			{
				$logoTitleDescription .= '<' . get_option('idmg_site_name_element_phone') . ' class="site-name-phone">';
				$logoTitleDescription .= $site_name;
				$logoTitleDescription .= '</' . get_option('idmg_site_name_element_phone') . '>';
			}
			if ( strlen($site_title) > 0 )
			{
				$logoTitleDescription .= '<' . get_option('idmg_site_title_element_phone') . ' class="site-title-phone">';
				$logoTitleDescription .= $site_title;
				$logoTitleDescription .= '</' . get_option('idmg_site_title_element_phone') . '>';
			}
			if ( strlen($site_description) > 0 )
			{
				$logoTitleDescription .= '<' . get_option('idmg_site_description_element_phone') . ' class="site-description-phone">';
				$logoTitleDescription .= $site_description;
				$logoTitleDescription .= '</' . get_option('idmg_site_description_element_phone') . '>';
			}
		}
		else if ( $this->isTablet() )
		{
			$logo_file = get_option( 'idmg_logo_file_tablet' );
			$site_title = get_option('idmg_site_title_tablet');
			$site_description = get_option('idmg_site_description_tablet');
			if ( strlen($logo_file) > 0 )
			{
				$logoTitleDescription .= '<img src="' . $logo_file . '" class="logo-file-tablet" alt="' . $site_name . '" />';
			}
			if ( get_option('idmg_show_site_name_tablet') == 'yes' )
			{
				$logoTitleDescription .= '<' . get_option('idmg_site_name_element_tablet') . ' class="site-name-tablet">';
				$logoTitleDescription .= $site_name;
				$logoTitleDescription .= '</' . get_option('idmg_site_name_element_tablet') . '>';
			}
			if ( strlen($site_title) > 0 )
			{
				$logoTitleDescription .= '<' . get_option('idmg_site_title_element_tablet') . ' class="site-title-tablet">';
				$logoTitleDescription .= $site_title;
				$logoTitleDescription .= '</' . get_option('idmg_site_title_element_tablet') . '>';
			}
			if ( strlen($site_description) > 0 )
			{
				$logoTitleDescription .= '<' . get_option('idmg_site_description_element_tablet') . ' class="site-description-tablet">';
				$logoTitleDescription .= $site_description;
				$logoTitleDescription .= '</' . get_option('idmg_site_description_element_tablet') . '>';
			}
		}
		else
		{
			$logo_file = get_option( 'idmg_logo_file_desktop' );
			$site_title = get_option('idmg_site_title_desktop');
			$site_description = get_option('idmg_site_description_desktop');
			if ( strlen($logo_file) > 0 )
			{
				$logoTitleDescription .= '<img src="' . $logo_file . '" class="logo-file-desktop" alt="' . $site_name . '" />';
			}
			if ( get_option('idmg_show_site_name_desktop') == 'yes' )
			{
				$logoTitleDescription .= '<' . get_option('idmg_site_name_element_desktop') . ' class="site-name-desktop">';
				$logoTitleDescription .= $site_name;
				$logoTitleDescription .= '</' . get_option('idmg_site_name_element_desktop') . '>';
			}
			if ( strlen($site_title) > 0 )
			{
				$logoTitleDescription .= '<' . get_option('idmg_site_title_element_desktop') . ' class="site-title-desktop">';
				$logoTitleDescription .= $site_title;
				$logoTitleDescription .= '</' . get_option('idmg_site_title_element_desktop') . '>';
			}
			if ( strlen($site_description) > 0 )
			{
				$logoTitleDescription .= '<' . get_option('idmg_site_description_element_desktop') . ' class="site-description-desktop">';
				$logoTitleDescription .= $site_description;
				$logoTitleDescription .= '</' . get_option('idmg_site_description_element_desktop') . '>';
			}
		}

		return $logoTitleDescription;
	}

}
