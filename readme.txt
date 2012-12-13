=== Posts in Sidebar ===
Contributors: aldolat
Donate link: http://www.aldolat.it/wordpress/wordpress-plugins/posts-in-sidebar/
Tags: post, sidebar, widget
Requires at least: 3.3
Tested up to: 3.4.2
Stable tag: 1.2.1
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Adds a widget to display a list of posts in sidebar. The list can be created using author and/or category and/or tag.

== Description ==

This plugin creates a new widget for your sidebar. In this widget you can display a list of post from a given author and/or category and/or tag. You can also display the featured image, the tags and also a link to the archive page. A bunch of useful options are also available.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload  the `posts-in-sidebar` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the Plugins menu in WordPress
1. Go to the widgets manager and add the newly available widget into the sidebar
1. Adjust the options to fit your needs
1. Save and test your results.

== Frequently Asked Questions ==

= May I retrieve posts using category and tag? =

Yes. you can retrieve posts using author archive and/or category archive and/or tag archive.

= How can I float my images? =

You have to edit your CSS file (usually `style.css`). You can target the image adding a new style like this at the end of your CSS file: `.pis-excerpt img { float: left; margin-right: 10px; }`

= How can I add new size for my images? =

You have to edit your `functions.php` file. [Ask in the forum](http://wordpress.org/support/plugin/posts-in-sidebar) of this plugin, for more informations.

== Screenshots ==

1. The widget panel
2. Three widgets in action. *Top widget*: a single post with all the informations. *Middle widget*: a single post with few informations. *Bottom widget*: a single post displayed only with the featured image.
3. The widget displays three posts from a particular category. At the bottom you can view the link to the taxonomy which we retrieved the posts from.

== Changelog ==

= 1.2.1 =

* Changed the minimum required WordPress version to 3.3.
* Added Persian language, thanks to AlirezaJamali.

= 1.2 =

* Enhancement: Now the user can display the entire content for each post. Feature request from [sjmsing](http://wordpress.org/support/topic/plugin-posts-in-sidebar-great-plugin-feature-request)
* Moved screenshots to `/assets/` directory.

= 1.1 =

* Enhancement: Now it is possible to show the categories of the post
* Enhancement: Now it is possible to exclude posts coming from some categories and/or tags
* Moved the widget section into a separate file.

= 1.0.2 =

* Updated *Credits* section.

= 1.0.1 =

* Small typo in `readme.txt`.

= 1.0 =

* First release of the plugin.

== Upgrade Notice ==

= 1.2 =

Version 1.2 has changed the option to display the text of the post. When upgrading to version 1.2, check every Posts in Sidebar widget at section The text of the post to make sure that the option fits your needs.

= 1.0.2 =

No notice to display.

= 1.0.1 =

No notice to display.

= 1.0 =

No notice to display.

== Credits ==

I would like to say *Thank You* to all the people who helped me, in particular [Jeff](http://profiles.wordpress.org/specialk/ "Jeff's profile page") for helping me in revisioning this plugin.