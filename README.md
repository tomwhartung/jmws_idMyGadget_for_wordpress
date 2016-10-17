# jmws_idMyGadget_for_wordpress

A version of idMyGadget tailored for use with the WordPress CMS.

This code is experimental and, although I am using it on [tomwhartung.com](http://tomwhartung.com),
I have not reviewed it for quality and hence do not feel it is "production-ready."

## Themes

This code serves no purpose unless you use it in conjunction with a theme that knows how to use its functionality.
I have created the following themes to experiment with and use this functionality:

* https://github.com/tomwhartung/jmws_wp_idmygadget_twentythirteen - the most successful of these efforts
* https://github.com/tomwhartung/jmws_wp_idmygadget_twentyfifteen - the way I chose to use device detection does not work well with this theme's responsiveness
* https://github.com/tomwhartung/jmws_wp_idmygadget_vqsg_ot - integrating idMyGadget with a minimal theme found in the book [WordPress Visual Quick Start Guide](http://www.wpvisualquickstart.com/), by Jessica Neuman Beck and Matt Beck

For me, the key take-away from this work is that it is important, if not necessary, to have a specific goal or use case in mind for using device detection on a specific site.

That is, just "playing around" with the idea, as I have done here, may be fun and enlightening, but it is not very practical without a specific reason.

And it should go without saying that in this case, playing around, having fun, and being enlightened was indeed **my** goal for doing this. And having satisfied that goal I see no reason to spend more time working and making this code "production-ready."  Hope that makes sense!

## Notes

When used with an already-responsive theme, the theme retains its responsiveness,
while also serving slightly different content to different device types.

JQuery Mobile in general, and the phone header/footer nav in particular, really look good only on phones.
Hence the name: *Phone* Header/Footer Nav.
I designed the options so that you can see them on tablets and desktops and judge for yourself.
Who am I to predict the use cases you will want to satisfy?

Yes you enable both header and footer phone navs at the same time.
If you only want one, say the header, simply do not assign a menu to the Phone Footer Nav theme position.

