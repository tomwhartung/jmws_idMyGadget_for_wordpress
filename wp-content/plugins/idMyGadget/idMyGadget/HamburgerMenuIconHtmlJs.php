<?php
/**
 * Bundles and sets the data that we use to create the phone burger menu icons
 * Note: Everything that uses the phone burger icon file name is part of a hack we need
 *   because using the JS API to draw the phone burger menu is currently not working on phones
 *   except when we reload the page. It would be nice to be able to remove that someday....
 */

class HamburgerMenuIconHtmlJs
{
	const LEFT = 'left';
	const RIGHT = 'right';

	/**
	 * The html markup we want to use for this.
	 * Note that if this particular icon is not being displayed, this remains empty.
	 * @var type String
	 */
	public $html = '';
	/**
	 * The javascript we want to use for this.
	 * Note that if this particular icon is not being displayed, this remains empty.
	 * @var type String
	 */
	public $js = '';
	/**
	 * Using a canvas element and drawing the phoneburger icon
	 * does not work on all devices (e.g. phones) and
	 * with all templates (e.g., beez3), without reloading the page.
	 * Hence this hack, to allow using an image in a file.
	 * To display a file instead of drawing the icon,
	 * (1) put it in images/idMyGadget/ and
	 * (2) name it hamburgerMenuIcon[Left|Right][Device].png .
	 *     where [Device] is "Desktop" "Phone" or "Tablet"
	 * Examples:
	 *    images/idMyGadget/hamburgerMenuIconLeftDesktop.png
	 *    images/idMyGadget/hamburgerMenuIconRightPhone.png
	 *    images/idMyGadget/hamburgerMenuIconRightTablet.png
	 * @var type
	 */
	public $fileName = '';      // used for hack needed for phones and beez3
	/**
	 * If the file is there, we use it, else we generate the html and js needed to draw the icon.
	 * @var type Boolean
	 */
	public $useImage = FALSE;

	/**
	 * Either self::LEFT or self::RIGHT
	 * @var type String
	 */
	protected $leftOrRight = '';
	/**
	 * Size of icon - set in the back end admin page of the CMS
	 */
	protected $iconSize;
	/**
	 * Color of icon - set in the back end admin page of the CMS
	 */
	protected $iconColor;
	/**
	 * Line cap of icon - set in the back end admin page of the CMS
	 */
	protected $iconLineCap;
	/**
	 * Line size of icon - set in the back end admin page of the CMS
	 */
	protected $iconLineSize;
	/**
	 * The object we are using for device detection
	 * @var type Object
	 */
	protected $jmwsIdMyGadget = null;
	/**
	 * Just the template name (not an object, just a string)
	 * Needed to find the image files used for the hack (see above)
	 * @var type String
	 */
	protected $template = '';

	/**
	 * Constructor: use the parameters set in the joomla back end to set the data members
	 */
	public function __construct( $leftOrRight,
							$iconSize, $iconColor, $iconLineCap, $iconLineSize  )
	{
		$this->leftOrRight = $leftOrRight;
		$this->iconSize = $iconSize;
		$this->iconColor = $iconColor;
		$this->iconLineCap = $iconLineCap;
		$this->iconLineSize = $iconLineSize;
	//	$this->setUseImage();
		$this->useImage = FALSE;
	}

	/**
	 * Return the Html needed to properly render the hamburger menu icon
	 */
	public function getHtml()
	{
		$this->html .= '';
		if ( $this->leftOrRight === self::LEFT )
		{
			$this->html = '<a href="#hamburger-menu-left" data-rel="dialog">';
			if ( $this->useImage )
			{
				$this->html .=
					'<img id="hamburger-icon-image-left" ' .
						'width="' . $this->iconSize . '" ' .
						'height="' . $this->iconSize . '" ' .
						'src="templates/' . $this->fileName . '" />';
			}
			else
			{
				$this->html .=
					'<canvas id="hamburger-icon-left" ' .
						'width="' . $this->iconSize . '" ' .
						'height="' . $this->iconSize . '">' .
						'&nbsp;Menu&nbsp;' . '</canvas>';
			}
			$this->html .= '</a>';
		}
		else if ( $this->leftOrRight === self::RIGHT )
		{
			$this->html =
				'<a href="#hamburger-menu-right" class="pull-right" data-rel="dialog">';
			if ( $this->useImage )
			{
				$this->html .=
					'<img id="hamburger-icon-image-right"' .
						'width="' . $this->iconSize . '" ' .
						'height="' . $this->iconSize . '" ' .
						' src="templates/' . $this->fileName . '" />';
			}
			else
			{
				$this->html .=
					'<canvas id="hamburger-icon-right" ' .
						'width="' . $this->iconSize . '" ' .
						'height="' . $this->iconSize . '">' .
						'&nbsp;Menu&nbsp;' . '</canvas>';
			}
			$this->html .= '</a>';
		}
		return $this->html;
	}
	/**
	 * Return the JavaScript needed to properly render the hamburger menu icon
	 */
	public function getJs()
	{
		$this->js .= '';
		if ( $this->leftOrRight === self::LEFT )
		{
			$this->js =
				'<script>' .
					'var hamburgerIconLeftOptions = {};' .
					'hamburgerIconLeftOptions.color = "' . $this->iconColor . '";' .
					'hamburgerIconLeftOptions.lineCap = "' . $this->iconLineCap . '";' .
					'hamburgerIconLeftOptions.lineSize = "' . $this->iconLineSize . '";' .
				'</script>';
		}
		else if ( $this->leftOrRight === self::RIGHT )
		{
			$this->js =
				'<script>' .
					'var hamburgerIconRightOptions = {};' .
					'hamburgerIconRightOptions.color = "' . $this->iconColor. '";' .
					'hamburgerIconRightOptions.lineCap = "' . $this->iconLineCap . '";' .
					'hamburgerIconRightOptions.lineSize = "' . $this->iconLineSize . '";' .
				'</script>';
		}
		return $this->js;
	}
	/**
	 * Determine whether an appropriate image is available
	 */
	protected function setUseImage()
	{
		$this->fileName = $this->template . '/images/idMyGadget/hamburgerMenuIcon' .
			ucfirst($this->leftOrRight) .
			ucfirst($this->jmwsIdMyGadget->getGadgetString()) .
			'.png';
		if ( file_exists(JPATH_THEMES . DS . $this->fileName) )
		{
			$this->useImage = TRUE;
		}
	}
	/**
	 * *** OBSOLETE *** OBSOLETE *** OBSOLETE *** OBSOLETE *** OBSOLETE ***
	 */
	protected function setPublicDataMembers()
	{
		$this->fileName = $this->template . '/images/idMyGadget/hamburgerMenuIcon' .
			ucfirst($this->leftOrRight) .
			ucfirst($this->jmwsIdMyGadget->getGadgetString()) .
			'.png';
		if ( file_exists(JPATH_THEMES . DS . $this->fileName) )
		{
			$this->useImage = TRUE;
		}
		if ( $this->leftOrRight === self::LEFT )
		{
			$this->html = '<a href="#hamburger-menu-left" data-rel="dialog">';
			if ( $this->useImage )
			{
				$this->html .=
					'<img id="hamburger-icon-image-left" ' .
						'width="' . $this->iconSize . '" ' .
						'height="' . $this->iconSize . '" ' .
						'src="templates/' . $this->fileName . '" />';
			}
			else
			{
				$this->html .=
					'<canvas id="hamburger-icon-left" ' .
						'width="' . $this->iconSize . '" ' .
						'height="' . $this->iconSize . '">' .
						'&nbsp;Menu&nbsp;' . '</canvas>';
			}
			$this->html .= '</a>';
			$this->js =
				'<script>' .
					'var hamburgerIconLeftOptions = {};' .
					'hamburgerIconLeftOptions.color = "' . $this->iconColor . '";' .
					'hamburgerIconLeftOptions.lineCap = "' . $this->iconLineCap . '";' .
					'hamburgerIconLeftOptions.lineSize = "' . $this->iconLineSize . '";' .
				'</script>';
		}
		else if ( $this->leftOrRight === self::RIGHT )
		{
			$this->html =
				'<a href="#hamburger-menu-right" class="pull-right" data-rel="dialog">';
			if ( $this->useImage )
			{
				$this->html .=
					'<img id="hamburger-icon-image-right"' .
						'width="' . $this->iconSize . '" ' .
						'height="' . $this->iconSize . '" ' .
						' src="templates/' . $this->fileName . '" />';
			}
			else
			{
				$this->html .=
					'<canvas id="hamburger-icon-right" ' .
						'width="' . $this->iconSize . '" ' .
						'height="' . $this->iconSize . '">' .
						'&nbsp;Menu&nbsp;' . '</canvas>';
			}
			$this->html .= '</a>';
			$this->js =
				'<script>' .
					'var hamburgerIconRightOptions = {};' .
					'hamburgerIconRightOptions.color = "' . $this->iconColor. '";' .
					'hamburgerIconRightOptions.lineCap = "' . $this->iconLineCap . '";' .
					'hamburgerIconRightOptions.lineSize = "' . $this->iconLineSize . '";' .
				'</script>';
		}
	}
}
