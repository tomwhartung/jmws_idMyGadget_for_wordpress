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
	 * Boolean: whether the admins want the jQuery Mobile phone header nav on this device
	 * Added pretty much only for demo purposes, so people see why we don't use it.
	 */
	public $phoneHeaderNavThisDevice = FALSE;
	/**
	 * Boolean: whether the admins want the jQuery Mobile phone footer nav on this device
	 * Added pretty much only for demo purposes, so people see why we don't use it.
	 */
	public $phoneFooterNavThisDevice = FALSE;

	/**
	 * *** Relevant to the twentyfifteen theme only ***
	 * Options for where the phone nav can appear in the markup.
	 * Page works best on phones and really you should not be using this nav on non-phones
	 * (but if you want to that is really none of my business).
	 */
	public static $pageOrSidebar2015Options = array( 'Page', 'Sidebar' );

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

		if ( $this->jqmDataThemeLetter == null )    // supposedly set in constructor but let's be safe
		{
			$this->setJqmDataThemeLetter();
		}
		$returnValue .= '/' . $this->jqmDataThemeLetter;
		$returnValue .= '/' . $extra;
		$returnValue .= '</p>';
		return $returnValue;
	}

	/**
	 * Returns either the site title or the site name, as appropriate
	 * @return string
	 */
	public function getSiteTitleOrName()
	{
		if ( $this->isPhone() )
		{
			$siteTitle = get_option( 'idmg_site_title_phone' );
			$showSiteName = get_option( 'idmg_show_site_name_phone' );
		}
		else if ( $this->isTablet() )
		{
			$siteTitle = get_option('idmg_site_title_tablet');
			$showSiteName = get_option( 'idmg_show_site_name_tablet' );
		}
		else
		{
			$siteTitle = get_option('idmg_site_title_desktop');
			$showSiteName = get_option( 'idmg_show_site_name_desktop' );
		}

		$siteTitleOrName = '';   // return value
		if ( $showSiteName == 'yes' )
		{
			$siteTitleOrName = get_bloginfo('name');
		}
		if ( strlen($siteTitle) > 0 )
		{
			$siteTitleOrName = $siteTitle;
		}
		else
		{
			$siteTitleOrName = get_bloginfo('name');
		}
		return $siteTitleOrName;
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
		$anchorTagPrelude = '<a href="' . esc_url( home_url('/') ) . '" rel="home" ';
		$anchorTagClose = '</a>';

		if ( $this->isPhone() )
		{
			$logoFile = get_option( 'idmg_logo_file_phone' );
			$siteTitle = get_option( 'idmg_site_title_phone' );
			$siteDescription = get_option('idmg_site_description_phone');
			if ( strlen($logoFile) > 0 )
			{
				$anchorTagWithClass = $anchorTagPrelude . 'class="logo-file-phone">';
				$logoTitleDescription .= $anchorTagWithClass;
				$logoTitleDescription .= '<img src="' . $logoFile . '" class="logo-file-phone" alt="' . $siteName . '" />';
				$logoTitleDescription .= $anchorTagClose;
			}
			if ( get_option('idmg_show_site_name_phone') == 'yes' )
			{
				$anchorTagWithClass = $anchorTagPrelude . 'class="site-name-phone">';
				$logoTitleDescription .= '<' . get_option('idmg_site_name_element_phone') . ' class="site-name-phone">';
				$logoTitleDescription .= $anchorTagWithClass;
				$logoTitleDescription .= $siteName;
				$logoTitleDescription .= $anchorTagClose;
				$logoTitleDescription .= '</' . get_option('idmg_site_name_element_phone') . '>';
			}
			if ( strlen($siteTitle) > 0 )
			{
				$anchorTagWithClass = $anchorTagPrelude . 'class="site-title-phone">';
				$logoTitleDescription .= '<' . get_option('idmg_site_title_element_phone') . ' class="site-title-phone">';
				$logoTitleDescription .= $anchorTagWithClass;
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
				$anchorTagWithClass = $anchorTagPrelude . 'class="logo-file-tablet">';
				$logoTitleDescription .= $anchorTagWithClass;
				$logoTitleDescription .= '<img src="' . $logoFile . '" class="logo-file-tablet" alt="' . $siteName . '" />';
				$logoTitleDescription .= $anchorTagClose;
			}
			if ( get_option('idmg_show_site_name_tablet') == 'yes' )
			{
				$anchorTagWithClass = $anchorTagPrelude . 'class="site-name-tablet">';
				$logoTitleDescription .= '<' . get_option('idmg_site_name_element_tablet') . ' class="site-name-tablet">';
				$logoTitleDescription .= $anchorTagWithClass;
				$logoTitleDescription .= $siteName;
				$logoTitleDescription .= $anchorTagClose;
				$logoTitleDescription .= '</' . get_option('idmg_site_name_element_tablet') . '>';
			}
			if ( strlen($siteTitle) > 0 )
			{
				$anchorTagWithClass = $anchorTagPrelude . 'class="site-title-tablet">';
				$logoTitleDescription .= '<' . get_option('idmg_site_title_element_tablet') . ' class="site-title-tablet">';
				$logoTitleDescription .= $anchorTagWithClass;
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
				$anchorTagWithClass = $anchorTagPrelude . 'class="logo-file-desktop">';
				$logoTitleDescription .= $anchorTagWithClass;
				$logoTitleDescription .= '<img src="' . $logoFile . '" class="logo-file-desktop" alt="' . $siteName . '" />';
				$logoTitleDescription .= $anchorTagClose;
			}
			if ( get_option('idmg_show_site_name_desktop') == 'yes' )
			{
				$anchorTagWithClass = $anchorTagPrelude . 'class="site-name-desktop">';
				$logoTitleDescription .= '<' . get_option('idmg_site_name_element_desktop') . ' class="site-name-desktop">';
				$logoTitleDescription .= $anchorTagWithClass;
				$logoTitleDescription .= $siteName;
				$logoTitleDescription .= $anchorTagClose;
				$logoTitleDescription .= '</' . get_option('idmg_site_name_element_desktop') . '>';
			}
			if ( strlen($siteTitle) > 0 )
			{
				$anchorTagWithClass = $anchorTagPrelude . 'class="site-title-desktop">';
				$logoTitleDescription .= '<' . get_option('idmg_site_title_element_desktop') . ' class="site-title-desktop">';
				$logoTitleDescription .= $anchorTagWithClass;
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
	 * Returns attributes that we want to add to the footer tag, as appropriate for each gadget type
	 * @return string
	 */
	public function getFooterAttributes()
	{
		$footerAttributes = '';
		if ( $this->usingJQueryMobile )
		{
			$footerAttributes = $this->jqmDataRole['footer'] . ' ' . $this->jqmDataThemeAttribute;
			if ( $this->phoneFooterNavThisDevice )
			{
				$footerAttributes .= 'class="ui-bar" data-position="fixed" ';
			}
		}
		else
		{
			$footerAttributes = 'id="footer"';
		}

		return $footerAttributes;
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
		if ( $this->jqmDataThemeLetter == null )    // supposedly set in constructor but let's be safe
		{
			$this->setJqmDataThemeLetter();
		}
		$this->jqmDataThemeAttribute = 'data-theme="' . $this->jqmDataThemeLetter . '"';
	}
	/**
	 * Use the admin option to set the jQuery Mobile Data Theme Letter
	 */
	protected function setJqmDataThemeLetter()
	{
		$jqmDataThemeIndex = get_theme_mod( 'idmg_jqm_data_theme' );
		$this->jqmDataThemeLetter = JmwsIdMyGadget::$jqueryMobileThemeChoices[$jqmDataThemeIndex];
	}
}
