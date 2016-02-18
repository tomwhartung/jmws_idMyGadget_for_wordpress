/**
 * @package     jmws_protostar_tomh_idMyGadget
 * @subpackage  Templates.jmws_protostar_tomh_idMyGadget; Modules.jmws_mod_menu_idMyGadget
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       3.2
 */

var hamburgerMenu = {};

(function($)
{
	$(document).ready(function()
	{
		hamburgerMenu.drawHamburgerMenuIcons();
	})
})(jQuery);
/**
 * Driver function to draw zero, one, or both menu icons, as appropriate
 * @returns {undefined}
 */
hamburgerMenu.drawHamburgerMenuIcons = function () {
	hamburgerMenu.leftElement = document.getElementById( 'hamburger-icon-left' );
	hamburgerMenu.rightElement = document.getElementById( 'hamburger-icon-right' );

	if ( hamburgerMenu.leftElement !== null &&
	     typeof hamburgerIconLeftOptions !== 'undefined' ) {     // options are set in the admin console
		hamburgerMenu.drawHamburgerMenuIcon(
			hamburgerMenu.leftElement, hamburgerIconLeftOptions );
	}
	if ( hamburgerMenu.rightElement !== null &&
	     typeof hamburgerIconRightOptions !== 'undefined' ) {     // options are set in the admin console
		hamburgerMenu.drawHamburgerMenuIcon(
			hamburgerMenu.rightElement, hamburgerIconRightOptions );
	}
};

/**
 * Draw three lines, using the options specified in the admin console
 * @param {type} canvasElement
 * @param {type} hamburgerIconOptions
 * @returns {undefined}
 */
hamburgerMenu.drawHamburgerMenuIcon = function (canvasElement, hamburgerIconOptions ) {
	if ( canvasElement === null ) {
		console.log( 'hamburgerMenu.drawThinRoundedHamburgerMenu error: passed-in canvasElement is null!' );
		return;
	}

	var context = canvasElement.getContext( '2d' );
	hamburgerMenu.setHamburgerIconDimensions( canvasElement, hamburgerIconOptions );
	var leftMargin = hamburgerMenu.leftMargin;
	var topMargin = hamburgerMenu.topMargin;
	var barHeight = hamburgerMenu.barHeight;
	var barWidth = hamburgerMenu.barWidth;
	var gapHeight = hamburgerMenu.gapHeight;
	var firstBarMidpoint = topMargin;
	var secondBarMidpoint = topMargin+barHeight+gapHeight;
	var thirdBarMidpoint = topMargin + 2*(barHeight+gapHeight);

	context.save();
	context.beginPath();
	context.strokeStyle = hamburgerIconOptions.color;
	context.lineCap = hamburgerIconOptions.lineCap;
	context.lineWidth = barHeight;

	context.moveTo( leftMargin, firstBarMidpoint );
	context.lineTo( leftMargin+barWidth, firstBarMidpoint );

	context.moveTo( leftMargin, secondBarMidpoint );
	context.lineTo( leftMargin+barWidth, secondBarMidpoint );

	context.moveTo( leftMargin, thirdBarMidpoint );
	context.lineTo( leftMargin+barWidth, thirdBarMidpoint );

	context.stroke();
	context.restore();
};
/**
 * Use the options specified in the admin console to set the dimensions of lines in the icon
 * @param {type} canvasElement
 * @param {type} hamburgerIconOptions
 * @returns {undefined}
 */
hamburgerMenu.setHamburgerIconDimensions = function ( canvasElement, hamburgerIconOptions ) {
	var topMargin;
	var barHeight;
	var gapHeight;
	var canvasWidth = canvasElement.width;
	var canvasHeight = canvasElement.height;
	var oneEleventh = Math.round( canvasHeight / 11 );

	if ( hamburgerIconOptions.lineSize === 'fat' ) {
		barHeight = oneEleventh * 3;
		gapHeight = oneEleventh;
		topMargin = Math.ceil( barHeight / 2 );
	}
	else if ( hamburgerIconOptions.lineSize === 'normal' ) {
		barHeight = oneEleventh * 2;
		gapHeight = oneEleventh * 2;
		topMargin = oneEleventh + Math.ceil( barHeight / 2 );
	}
	else {   // hamburgerIconOptions.lineSize === 'thin'
		barHeight = oneEleventh;
		gapHeight = oneEleventh * 2;
		topMargin = (oneEleventh * 2) + Math.ceil( barHeight / 2 );
	}

	hamburgerMenu.topMargin = topMargin;
	hamburgerMenu.barHeight = barHeight;
	hamburgerMenu.gapHeight = gapHeight;
	hamburgerMenu.leftMargin = Math.ceil( barHeight / 2 );
	hamburgerMenu.barWidth = Math.round( canvasWidth - (barHeight*2) );
};
