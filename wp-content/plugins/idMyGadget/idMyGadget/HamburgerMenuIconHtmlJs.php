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
	 * Directory in which to put an image that will override drawing of the hamburger menu icon
	 * If we want to use this feature, this must be passed in to the constructor
	 */
	protected $imageOverrideDir = '';

	/**
	 * Constructor: use the parameters set in the joomla back end to set the data members
	 */
	public function __construct( $leftOrRight, $iconSettings, $gadgetString, $imageOverrideDir='' )
	{
		$this->leftOrRight = $leftOrRight;
		$this->iconSize = $iconSettings['size'];
		$this->iconColor = $iconSettings['color'];
		$this->iconLineCap = $iconSettings['line_cap'];
		$this->iconLineSize = $iconSettings['line_size'];
		$this->imageOverrideDir = $imageOverrideDir;
		$this->setUseImage( $gadgetString );
	//	$this->useImage = FALSE;
	}

	/**
	 * Return the Html needed to properly render the hamburger menu icon
	 */
	public function getHtml()
	{
		$this->html = '';
		if ( $this->leftOrRight === self::LEFT )
		{
		//	$this->html .= '<div role="main" class="ui-content">';
		//	$this->html .= '<a href="#hamburger-menu-left" data-rel="dialog">';
			$this->html .= '<a href="#idmg-hamburger-menu-left" data-rel="popup">';
			if ( $this->useImage )
			{
				$this->html .=
					'<img id="hamburger-icon-image-left" ' .
						'width="' . $this->iconSize . '" ' .
						'height="' . $this->iconSize . '" ' .
						'src="' . $this->fileName . '" />';
			}
			else
			{
				$this->html .=
					'<canvas id="hamburger-icon-canvas-left" ' .
						'width="' . $this->iconSize . '" ' .
						'height="' . $this->iconSize . '">' .
						'&nbsp;Menu&nbsp;' . '</canvas>';
			}
			$this->html .= '</a>';
		//	$this->html .= '</div>';
		}
		else if ( $this->leftOrRight === self::RIGHT )
		{
		//	$this->html .= '<div role="main" class="ui-content">';
			$this->html .=
				'<a href="#idmg-hamburger-menu-right" class="pull-right" data-rel="popup">';
			if ( $this->useImage )
			{
				$this->html .=
					'<img id="hamburger-icon-image-right"' .
						'width="' . $this->iconSize . '" ' .
						'height="' . $this->iconSize . '" ' .
						' src="' . $this->fileName . '" />';
			}
			else
			{
				$this->html .=
					'<canvas id="hamburger-icon-canvas-right" ' .
						'width="' . $this->iconSize . '" ' .
						'height="' . $this->iconSize . '">' .
						'&nbsp;Menu&nbsp;' . '</canvas>';
			}
			$this->html .= '</a>';
		//	$this->html .= '</div>';
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
	protected function setUseImage( $gadgetString )
	{
		$relativeFileName = 'images/idMyGadget/hamburgerMenuIcon' .
			ucfirst($this->leftOrRight) .
			ucfirst($gadgetString) .
			'.png';
		// $this->fileName = 'wp-content/plugins/idMyGadget/' . $relativeFileName;
		$this->fileName = $this->imageOverrideDir . '/' . $relativeFileName;
		$fileNameToCheck = IDMYGADGET_MODULE_DIR . '/' . $relativeFileName;
	//	error_log( '$fileNameToCheck: ' . $fileNameToCheck );

		if ( file_exists($fileNameToCheck) )
		{
		//	error_log( 'fileName: ' . $this->fileName );
			$this->useImage = TRUE;
		}
	}
}
