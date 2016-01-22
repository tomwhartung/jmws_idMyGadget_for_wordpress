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
		'idmygadget_vqsg_ot',
		'idmygadget_twentyfifteen',
		'idmygadget_twentythirteen',
	);

	/**
	 * Options for where the phone nav can appear in the markup.
	 * Sidebar works best on phones and really you should not be using this nav on non-phones
	 * (but if you want to that is really none of my business).
	 * Relevant to the twentyfifteen theme only
	 */
	public static $pageOrSidebar2015Options = array( 'Page', 'Sidebar' );
	//
	// These are relevant to the twentyfifteen theme only and set based on a variety of options and conditions
	//
	/**
	 * Boolean indicating whether the phone header nav should be in the page on the current device
	 */
	public $phoneHeaderNavIn2015Page = FALSE;
	/**
	 * Boolean indicating whether the phone header nav should be in the sidebar on the current device
	 */
	public $phoneHeaderNavIn2015Sidebar = FALSE;
	/**
	 * Boolean indicating whether the phone footer nav should be in the page on the current device
	 */
	public $phoneFooterNavIn2015Page = FALSE;
	/**
	 * Boolean indicating whether the phone footer nav should be in the sidebar on the current device
	 */
	public $phoneFooterNavIn2015Sidebar = FALSE;

	/**
	 * Constructor: for best results, install and use a gadgetDetector other than the default
	 */
	public function __construct( $gadgetDetectorString=null, $debugging=FALSE, $allowOverridesInUrl=TRUE )
	{
		$this->idMyGadgetDir = IDMYGADGET__PLUGIN_DIR;
		parent::__construct( $gadgetDetectorString, $debugging, $allowOverridesInUrl );
	}


	/**
	 * For development only! Please remove when code is stable.
	 * Displaying some values that can help us make sure we haven't inadvertently
	 * broken something while we are actively working on this.
	 * @return string
	 */
	public function getSanityCheckString( $extra='' )
	{
		$returnValue = '<p>';
		$returnValue .= parent::getSanityCheckString() . '/';

		$jqmDataThemeIndex = get_theme_mod( 'idmg_jqm_data_theme' );  // WARNING: wp-specific (but we are just checking sanity)
		$returnValue .= '/' . $jqmDataThemeIndex;
		$returnValue .= '/' . $this->jqmDataThemeAttribute;
		$returnValue .= '/' . $extra;
		$returnValue .= '</p>';
		return $returnValue;
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

	/**
	 * Decide whether we are using the jQuery Mobile js library,
	 * based on the device we are on and the values of device-dependent options set by the admin
	 */
	protected function setUsingJQueryMobile()
	{
		$this->usingJQueryMobile = FALSE;
		$this->phoneHeaderNavThisDevice = FALSE;
		$this->phoneFooterNavThisDevice = FALSE;
		$this->phoneBurgerIconThisDeviceLeft = FALSE;
		$this->phoneBurgerIconThisDeviceRight = FALSE;
		$phoneNavOnThisDevice = FALSE;
		//
		// Not worrying about the phone burger stuff right now,
		// so this logic will probably change as time progresses
		//
		if ( $this->isPhone() )
		{
			$this->usingJQueryMobile = TRUE;
			$phoneNavOnThisDevice = get_theme_mod( 'idmg_phone_nav_on_phones' );
		}
		else if ( $this->isTablet() )
		{
			$phoneNavOnThisDevice = get_theme_mod( 'idmg_phone_nav_on_tablets' );
			if ( $phoneNavOnThisDevice ) {
				$this->usingJQueryMobile = TRUE;
			}
		}
		else
		{
			$phoneNavOnThisDevice = get_theme_mod( 'idmg_phone_nav_on_desktops' );
			if ( $phoneNavOnThisDevice )
			{
				$this->usingJQueryMobile = TRUE;
			}
		}
		if( $phoneNavOnThisDevice )
		{
			$this->phoneHeaderNavThisDevice = TRUE;
			$this->phoneFooterNavThisDevice = TRUE;
		}
	}
	/**
	 * Use the admin option to set the jQuery Mobile Data Theme attribute (if necessary)
	 */
	protected function setJqmDataThemeAttribute()
	{
		$jqmDataThemeIndex = get_theme_mod( 'idmg_jqm_data_theme' );
		$jqmDataThemeLetter = JmwsIdMyGadget::$jqueryMobileThemeChoices[$jqmDataThemeIndex];
		$this->jqmDataThemeAttribute = 'data-theme="' . $jqmDataThemeLetter . '"';
	}
}
