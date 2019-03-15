=== Opti MozJpeg Guetzli WebP ===
Contributors: ihorsl
Tags: free image optimization, SEO, PageSpeed, pingdom, WordPress images optimization
Donate link: http://ihorsl.com/en/wordpress-opti-mozjpeg-guetzli-webp/home/#donate-wpmjgu
Requires at least: 4.7
Tested up to: 4.9.7
Requires PHP: 5.4
Stable tag: 1.16
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress Opti MozJpeg Guetzli WebP - is the FREE plugin for high quality image optimization in WordPress website. It was created to meet latest requirements of Google Pagespeed Insights. Google will "like" all your images after using this plugin, and you will expend $0 for that.

== Description ==
WordPress Opti MozJpeg Guetzli WebP - is the FREE plugin for high quality image optimization in WordPress website. It was created to meet latest requirements of Google Pagespeed Insights. Google will "like" all your images after using this plugin, and you will expend $0 for that.

This plugin uses flowing image encoders: Mozilla MozJpeg, Google Guetzli and Google WebP. But don't worry. There is no necessity to install them into your hosting. The plugin has built-in ssh client. It can access encoders remotely via ssh tunnel.

Plugin's author created a virtual machine for Oracle VirtualBox. It contains Linux, ssh server and Mozilla MozJpeg, Google Guetzli and Google WebP. Simply run this virtual machine in your computer, establish ssh connection between the plugin and the virtual machine and optimize your images for free without any limitations.

Of course the plugin can use locally installed encoders too.

The plugin can create webp duplicates for all your images and serve them to webp compatible browsers. Lately Google Pagespeed changed the rules of the game. It wants now all images to be smaller then webp with quality 75 + 10%. And this plugin can do it for you.

The plugin can convert non alpha png images into jpeg. Automatically generates thumbnails and cleans not used thumbnail files.

All your images will be backed up before changing. You can run batch optimization many times, experiment with settings. And every time original images will be used as source. No quality degradation.

There is also the ability "Revert everything". It can bring back the initial state of your images. All your images will be the same, as they were before this plugin use.

Please read [this brief manual](http://ihorsl.com/en/wordpress-opti-mozjpeg-guetzli-webp/brief-man/), before using the plugin.

The virtual machine with encoders is [here](http://ihorsl.com/redirect.php?t=wpmjgu-vm)

If you have difficulties with use of the virtual machine in your computer, you may try the plugin author's free online server. [Click here](http://ihorsl.com/en/wordpress-opti-mozjpeg-guetzli-webp/free-server/) to read more.

== Installation ==
Read plugin's [brief manual](http://ihorsl.com/en/wordpress-opti-mozjpeg-guetzli-webp/brief-man/)

== Frequently Asked Questions ==
[Frequently Asked Questions page](https://ihorsl.com/en/wordpress-opti-mozjpeg-guetzli-webp/frequently-asked-questions-about-opti-mozjpeg-guetzli-webp/)

== Changelog ==

= 1.1 =
* Full unicode support
* Added two filters *wpmjgu_gd_resize_crop* and *wpmjgu_gd_after_resize*. You can use *wpmjgu_gd_resize_crop* to create your own improved resize function. And *wpmjgu_gd_after_resize* to refine images after resize (for example to increase sharpen)

= 0.95 =
* Added "Save console log" button

= 0.6 =
* Added "Intensive resources consumption mode" in Batch optimization

= 0.5 =
* First plugin release

