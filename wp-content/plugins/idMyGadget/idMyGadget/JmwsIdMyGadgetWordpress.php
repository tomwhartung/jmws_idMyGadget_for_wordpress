<?php
/**
 * Creates an object of the desired idMyGadget subclass and uses it for device detection.
 * NOTE:
 * *IF* we can keep all the wordpress-specific code here,
 * *THEN* we can reuse the rest of the code in this project for joomla and Drupal (and...?)
 */
require_once 'JmwsIdMyGadget.php';
require_once 'HamburgerMenuIconHtmlJs.php';

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
		$this->idMyGadgetDir = IDMYGADGET_PLUGIN_DIRECTORY;
		$this->imageOverrideDir = 'wp-content/plugins/idMyGadget/';
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
			//	$logoTitleDescription .= $anchorTagWithClass;
				$logoTitleDescription .= '<img src="' . $logoFile . '" class="logo-file-phone" alt="' . $siteName . '" />';
			//	$logoTitleDescription .= $anchorTagClose;
			}
			$logoTitleDescription .= '<div class="hamburger-icons-name-title-phone">';
			$logoTitleDescription .= $this->hamburgerIconLeftHtml . $this->hamburgerIconLeftJs;
			if ( get_option('idmg_show_site_name_phone') == 'yes' )
			{
				$anchorTagWithClass = $anchorTagPrelude . 'class="site-name-phone">';
				$logoTitleDescription .= '<' . get_option('idmg_site_name_element_phone') . ' class="site-name-phone">';
			//	$logoTitleDescription .= $anchorTagWithClass;
				$logoTitleDescription .= $siteName;
			//	$logoTitleDescription .= $anchorTagClose;
				$logoTitleDescription .= '</' . get_option('idmg_site_name_element_phone') . '>';
			}
			if ( strlen($siteTitle) > 0 )
			{
				$anchorTagWithClass = $anchorTagPrelude . 'class="site-title-phone">';
				$logoTitleDescription .= '<' . get_option('idmg_site_title_element_phone') . ' class="site-title-phone">';
			//	$logoTitleDescription .= $anchorTagWithClass;
				$logoTitleDescription .= $siteTitle;
			//	$logoTitleDescription .= $anchorTagClose;
				$logoTitleDescription .= '</' . get_option('idmg_site_title_element_phone') . '>';
			}
			$logoTitleDescription .= $this->hamburgerIconRightHtml . $this->hamburgerIconRightJs;
			$logoTitleDescription .= '</div><!-- .hamburger-icons-name-title-phone -->';
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
			//	$logoTitleDescription .= $anchorTagWithClass;
				$logoTitleDescription .= '<img src="' . $logoFile . '" class="logo-file-tablet" alt="' . $siteName . '" />';
			//	$logoTitleDescription .= $anchorTagClose;
			}
			$logoTitleDescription .= '<div class="hamburger-icons-name-title-tablet">';
			$logoTitleDescription .= $this->hamburgerIconLeftHtml . $this->hamburgerIconLeftJs;
			if ( get_option('idmg_show_site_name_tablet') == 'yes' )
			{
				$anchorTagWithClass = $anchorTagPrelude . 'class="site-name-tablet">';
				$logoTitleDescription .= '<' . get_option('idmg_site_name_element_tablet') . ' class="site-name-tablet">';
			//	$logoTitleDescription .= $anchorTagWithClass;
				$logoTitleDescription .= $siteName;
			//	$logoTitleDescription .= $anchorTagClose;
				$logoTitleDescription .= '</' . get_option('idmg_site_name_element_tablet') . '>';
			}
			if ( strlen($siteTitle) > 0 )
			{
				$anchorTagWithClass = $anchorTagPrelude . 'class="site-title-tablet">';
				$logoTitleDescription .= '<' . get_option('idmg_site_title_element_tablet') . ' class="site-title-tablet">';
			//	$logoTitleDescription .= $anchorTagWithClass;
				$logoTitleDescription .= $siteTitle;
			//	$logoTitleDescription .= $anchorTagClose;
				$logoTitleDescription .= '</' . get_option('idmg_site_title_element_tablet') . '>';
			}
			$logoTitleDescription .= $this->hamburgerIconRightHtml . $this->hamburgerIconRightJs;
			$logoTitleDescription .= '</div><!-- .hamburger-icons-name-title-tablet-->';
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
			//	$logoTitleDescription .= $anchorTagWithClass;
				$logoTitleDescription .= '<img src="' . $logoFile . '" class="logo-file-desktop" alt="' . $siteName . '" />';
			//	$logoTitleDescription .= $anchorTagClose;
			}
			$logoTitleDescription .= '<div class="hamburger-icons-name-title-desktop">';
			$logoTitleDescription .= $this->hamburgerIconLeftHtml . $this->hamburgerIconLeftJs;
			if ( get_option('idmg_show_site_name_desktop') == 'yes' )
			{
				$anchorTagWithClass = $anchorTagPrelude . 'class="site-name-desktop">';
				$logoTitleDescription .= '<' . get_option('idmg_site_name_element_desktop') . ' class="site-name-desktop">';
			//	$logoTitleDescription .= $anchorTagWithClass;
				$logoTitleDescription .= $siteName;
			//	$logoTitleDescription .= $anchorTagClose;
				$logoTitleDescription .= '</' . get_option('idmg_site_name_element_desktop') . '>';
			}
			if ( strlen($siteTitle) > 0 )
			{
				$anchorTagWithClass = $anchorTagPrelude . 'class="site-title-desktop">';
				$logoTitleDescription .= '<' . get_option('idmg_site_title_element_desktop') . ' class="site-title-desktop">';
			//	$logoTitleDescription .= $anchorTagWithClass;
				$logoTitleDescription .= $siteTitle;
			//	$logoTitleDescription .= $anchorTagClose;
				$logoTitleDescription .= '</' . get_option('idmg_site_title_element_desktop') . '>';
			}
			$logoTitleDescription .= $this->hamburgerIconRightHtml . $this->hamburgerIconRightJs;
			$logoTitleDescription .= '</div><!-- .hamburger-icons-name-title-desktop -->';
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
	public function getFooterAttributes($originalAttributes='')
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
			$footerAttributes = $originalAttributes;
		}

		return $footerAttributes;
	}
	/**
	 * Return a boolean indicating whether we want the jQuery Mobile Phone Nav on this device
	 */
	protected function getPhoneNavOnThisDevice()
	{
		$phoneNavOnThisDevice = FALSE;

		if ( $this->isPhone() )
		{
			$phoneNavOnThisDevice = get_theme_mod( 'idmg_phone_nav_on_phones' );
		}
		else if ( $this->isTablet() )
		{
			$phoneNavOnThisDevice = get_theme_mod( 'idmg_phone_nav_on_tablets' );
		}
		else
		{
			$phoneNavOnThisDevice = get_theme_mod( 'idmg_phone_nav_on_desktops' );
		}

		return $phoneNavOnThisDevice;
	}
	/**
	 * Return a boolean indicating whether we want the hamburger menu icon
	 *   (left or right or both) on this device
	 */
	protected function getHamburgerIconOnThisDevice( $leftOrRight=HamburgerMenuIcon::LEFT )
	{
		$hamburgerIconOnThisDevice = FALSE;

		if ( $this->isPhone() )
		{
			$hamburgerIconOnThisDevice = get_theme_mod( 'idmg_hamburger_icon_' . $leftOrRight . '_on_phones' );
		}
		else if ( $this->isTablet() )
		{
			$hamburgerIconOnThisDevice = get_theme_mod( 'idmg_hamburger_icon_' . $leftOrRight . '_on_tablets' );
		}
		else
		{
			$hamburgerIconOnThisDevice = get_theme_mod( 'idmg_hamburger_icon_' . $leftOrRight . '_on_desktops' );
		}

		return $hamburgerIconOnThisDevice;
	}
	/**
	 * Returns an array of icon settings, set in the admin page
	 */
	protected function getHamburgerIconSettings( $leftOrRight )
	{
		$iconSizeIndex = get_theme_mod( 'idmg_hamburger_icon_' . $leftOrRight . '_size' );
		$iconLineCapIndex = get_theme_mod( 'idmg_hamburger_icon_' . $leftOrRight . '_line_cap' );
		$iconLineSizeIndex  = get_theme_mod( 'idmg_hamburger_icon_' . $leftOrRight . '_line_size' );
		$iconSettings = array();
		$dimensions = parent::$hamburgerMenuIconSizeChoices[$iconSizeIndex];
		$iconSettings['size'] = (integer) substr($dimensions, 0, 2 );
		$iconSettings['color'] = get_theme_mod( 'idmg_hamburger_icon_' . $leftOrRight . '_color' );
		$iconSettings['line_cap'] = parent::$hamburgerMenuIconLineCapChoices[$iconLineCapIndex];
		$iconSettings['line_size'] = parent::$hamburgerMenuIconLineSizeChoices[$iconLineSizeIndex];
	//	$debug = 'size: ' . $iconSettings['size'];
		$debug = '$dimensions: ' . $dimensions;
		error_log( $debug );
		return $iconSettings;
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
	 * Use the admin option to set the index of the jQuery Mobile Data Theme Letter
	 */
	protected function getJqmDataThemeIndex()
	{
		$jqmDataThemeIndex = get_theme_mod( 'idmg_jqm_data_theme' );
		return $jqmDataThemeIndex;
	}
}
