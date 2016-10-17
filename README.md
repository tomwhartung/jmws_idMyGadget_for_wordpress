# jmws_idMyGadget_for_wordpress

A version of idMyGadget tailored for use with the WordPress CMS.

This code is experimental and, although I am using it on [tomwhartung.com](http://tomwhartung.com),
I have not reviewed it for quality and hence do not feel it is "production-ready."

## Themes

This code serves no purpose unless you use it in conjunction with a theme that knows how to use its functionality.
I have created the following themes to experiment with and use this functionality:

* https://github.com/tomwhartung/jmws_wp_idmygadget_twentythirteen - the most successful of these efforts
* https://github.com/tomwhartung/jmws_wp_idmygadget_twentyfifteen - the way I chose to use device detection does not work well with this theme's responsiveness
* https://github.com/tomwhartung/jmws_wp_idmygadget_vqsg_ot - result of updating a minimal theme found in the book [WordPress Visual Quick Start Guide](http://www.wpvisualquickstart.com/), by Jessica Neuman Beck and Matt Beck, to use idMyGadget

Integrating idMyGadget with the minimal theme from the Becks' book was a great way to get started on this project and made updating the other two much easier.

## Goals and the Key Take-Away

For me, the key take-away from this work is that it is important, if not necessary, to have a specific goal or use case in mind for using device detection on a specific site.

That is, just "playing around" with the idea, as I have done here, may be fun and enlightening, but it is not very practical without a specific reason.

And it should go without saying that in this case, playing around, having fun, and being enlightened was indeed **my** goal for doing this. And having satisfied that goal I see no reason to spend more time working and making this code "production-ready."  Hope that makes sense!

## For More Information

The [RESS page](https://tomwhartung.github.io/ress) on my [github.io site](https://tomwhartung.github.io/) gives a good overview of this entire project.

### Demo Site

This code is live on my site [tomwhartung.com](http://tomwhartung.com) .

To see its effect, you will need to visit the site on more than one device type (phone, tablet, or desktop).

### Related Repos

This is only one of many repos where I explore the viability of integrating Device Detection with LAMP CMSes.

* https://github.com/tomwhartung/jmws_idMyGadget_for_joomla - repo for the Joomla work
* https://github.com/tomwhartung/jmws_idMyGadget_for_drupal-d8 - repo for the Drupal 8 work
* https://github.com/tomwhartung/jmws_idMyGadget_for_drupal-d7 - repo for the Drupal 7 work

Each of these repos has corresponding repos with Joomla templates and Drupal themes.

You can find by these by searching the [list of my github repos](https://github.com/tomwhartung?tab=repositories).

## Notes

When used with an already-responsive theme, the theme retains its responsiveness,
while also serving slightly different content to different device types.

JQuery Mobile in general, and the phone header/footer nav in particular, really look good only on phones.
Hence the name: *Phone* Header/Footer Nav.
I designed the options so that you can see them on tablets and desktops and judge for yourself.
Who am I to predict the use cases you will want to satisfy?

Yes you enable both header and footer phone navs at the same time.
If you only want one, say the header, simply do not assign a menu to the Phone Footer Nav theme position.

