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
	 * Used by when this plugin is not installed or active, etc.
	 * Set only when there's an error.
	 */
	public $errorMessage = '';

	/**
	 * Boolean: Using jQuery Mobile changes everything, so we need to know when we are using it.
	 * Although we always use it on phones, we do not always use it on tablets.
	 */
	public $usingJQueryMobile = TRUE;
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

	/**
	 * Use the admin option to set the jQuery Mobile Data Theme attribute (if necessary)
	 */
	public function setJqmDataThemeAttribute()
	{
		if ( $this->usingJQueryMobile )
		{
			$jqmDataThemeIndex = get_theme_mod( 'idmg_jqm_data_theme' );
			$jqmDataThemeLetter = JmwsIdMyGadget::$jqueryMobileThemeChoices[$jqmDataThemeIndex];
			$this->jqmDataThemeAttribute = 'data-theme="' . $jqmDataThemeLetter . '"';
		}
	}

	/**
	 * Based on the current device, access the device-dependent options set in the admin console 
	 * and use them to generate most of the markup for the heading
	 * @return string Markup for site heading (name, logo, title, and description, each of which is optional)
	 */
	public function getLogoTitleDescriptionHtml(  )
	{
		$logoTitleDescription = '';
		$logoFile = '';
		$siteName = get_bloginfo('name' );
		$siteTitle = '';
		$siteDescription = '';
		$anchorTagOpen = '<a href="' . esc_url( home_url('/') ) . '" rel="home">';
		$anchorTagClose = '</a>';

		if ( $this->isPhone() )
		{
			$logoFile = get_option( 'idmg_logo_file_phone' );
			$siteTitle = get_option( 'idmg_site_title_phone' );
			$siteDescription = get_option('idmg_site_description_phone');
			if ( strlen($logoFile) > 0 )
			{
				$logoTitleDescription .= $anchorTagOpen;
				$logoTitleDescription .= '<img src="' . $logoFile . '" class="logo-file-phone" alt="' . $siteName . '" />';
				$logoTitleDescription .= $anchorTagClose;
			}
			if ( get_option('idmg_show_site_name_phone') == 'yes' )
			{
				$logoTitleDescription .= '<' . get_option('idmg_site_name_element_phone') . ' class="site-name-phone">';
				$logoTitleDescription .= $anchorTagOpen;
				$logoTitleDescription .= $siteName;
				$logoTitleDescription .= $anchorTagClose;
				$logoTitleDescription .= '</' . get_option('idmg_site_name_element_phone') . '>';
			}
			if ( strlen($siteTitle) > 0 )
			{
				$logoTitleDescription .= '<' . get_option('idmg_site_title_element_phone') . ' class="site-title-phone">';
				$logoTitleDescription .= $anchorTagOpen;
				$logoTitleDescription .= $siteTitle;
				$logoTitleDescription .= $anchorTagClose;
				$logoTitleDescription .= '</' . get_option('idmg_site_title_element_phone') . '>';
			}
			if ( strlen($siteDescription) > 0 )
			{
				$logoTitleDescription .= '<' . get_option('idmg_site_description_element_phone') . ' class="site-description-phone">';
				$logoTitleDescription .= $siteDescription;
				$logoTitleDescription .= '</' . get_option('idmg_site_description_element_phone') . '>';
			}
		}
		else if ( $this->isTablet() )
		{
			$logoFile = get_option( 'idmg_logo_file_tablet' );
			$siteTitle = get_option('idmg_site_title_tablet');
			$siteDescription = get_option('idmg_site_description_tablet');
			if ( strlen($logoFile) > 0 )
			{
				$logoTitleDescription .= $anchorTagOpen;
				$logoTitleDescription .= '<img src="' . $logoFile . '" class="logo-file-tablet" alt="' . $siteName . '" />';
				$logoTitleDescription .= $anchorTagClose;
			}
			if ( get_option('idmg_show_site_name_tablet') == 'yes' )
			{
				$logoTitleDescription .= '<' . get_option('idmg_site_name_element_tablet') . ' class="site-name-tablet">';
				$logoTitleDescription .= $anchorTagOpen;
				$logoTitleDescription .= $siteName;
				$logoTitleDescription .= $anchorTagClose;
				$logoTitleDescription .= '</' . get_option('idmg_site_name_element_tablet') . '>';
			}
			if ( strlen($siteTitle) > 0 )
			{
				$logoTitleDescription .= '<' . get_option('idmg_site_title_element_tablet') . ' class="site-title-tablet">';
				$logoTitleDescription .= $anchorTagOpen;
				$logoTitleDescription .= $siteTitle;
				$logoTitleDescription .= $anchorTagClose;
				$logoTitleDescription .= '</' . get_option('idmg_site_title_element_tablet') . '>';
			}
			if ( strlen($siteDescription) > 0 )
			{
				$logoTitleDescription .= '<' . get_option('idmg_site_description_element_tablet') . ' class="site-description-tablet">';
				$logoTitleDescription .= $siteDescription;
				$logoTitleDescription .= '</' . get_option('idmg_site_description_element_tablet') . '>';
			}
		}
		else
		{
			$logoFile = get_option( 'idmg_logo_file_desktop' );
			$siteTitle = get_option('idmg_site_title_desktop');
			$siteDescription = get_option('idmg_site_description_desktop');
			if ( strlen($logoFile) > 0 )
			{
				$logoTitleDescription .= $anchorTagOpen;
				$logoTitleDescription .= '<img src="' . $logoFile . '" class="logo-file-desktop" alt="' . $siteName . '" />';
				$logoTitleDescription .= $anchorTagClose;
			}
			if ( get_option('idmg_show_site_name_desktop') == 'yes' )
			{
				$logoTitleDescription .= '<' . get_option('idmg_site_name_element_desktop') . ' class="site-name-desktop">';
				$logoTitleDescription .= $anchorTagOpen;
				$logoTitleDescription .= $siteName;
				$logoTitleDescription .= $anchorTagClose;
				$logoTitleDescription .= '</' . get_option('idmg_site_name_element_desktop') . '>';
			}
			if ( strlen($siteTitle) > 0 )
			{
				$logoTitleDescription .= '<' . get_option('idmg_site_title_element_desktop') . ' class="site-title-desktop">';
				$logoTitleDescription .= $anchorTagOpen;
				$logoTitleDescription .= $siteTitle;
				$logoTitleDescription .= $anchorTagClose;
				$logoTitleDescription .= '</' . get_option('idmg_site_title_element_desktop') . '>';
			}
			if ( strlen($siteDescription) > 0 )
			{
				$logoTitleDescription .= '<' . get_option('idmg_site_description_element_desktop') . ' class="site-description-desktop">';
				$logoTitleDescription .= $siteDescription;
				$logoTitleDescription .= '</' . get_option('idmg_site_description_element_desktop') . '>';
			}
		}

		return $logoTitleDescription;
	}

}
