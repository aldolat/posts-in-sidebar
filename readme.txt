=== Posts in Sidebar ===
Contributors: aldolat
Donate link: http://dev.aldolat.it/projects/posts-in-sidebar/
Tags: post, sidebar, widget
Requires at least: 3.3
Tested up to: 3.6
Stable tag: 1.11
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

This plugin adds a widget to display a list of posts in the WordPress sidebar.

== Description ==

This plugin creates a new widget for your sidebar. In this widget you can display a list of post using author, category, tag, post format, custom post type, and so on. You can also display the featured image, the tags and also a link to the archive page. A bunch of useful options are also available.

= Documentation, Help & Bugs =

The plugin's documentation is hosted on [GitHub](https://github.com/aldolat/posts-in-sidebar/wiki). Please refer to it before asking for support.

If you need help, please use [WordPress forum](http://wordpress.org/support/plugin/posts-in-sidebar). Do not send private email unless it is really necessary.

If you have found a bug, please report it on [GitHub](https://github.com/aldolat/posts-in-sidebar/issues).

This plugin is developed using [GitHub](https://github.com/aldolat/posts-in-sidebar). If you wrote an enhancement and would share it with the world, please send me a [Pull request](https://github.com/aldolat/posts-in-sidebar/pulls).

= Credits =

I would like to say *Thank You* to all the people who helped me in making this plugin better, and in particular (in chronological order, from older to newer):

* [Jeff](http://profiles.wordpress.org/specialk/) for helping me in revisioning this plugin;
* [sjmsing](http://wordpress.org/support/profile/sjmsing) for a feature request;
* AlirezaJamali for the Persian translation;
* [Mike Churcher](http://wordpress.org/support/profile/mike-churcher) for a couple of feature requests;
* [lleroy](http://wordpress.org/support/profile/lleroy) for a feature request;
* Thérèse Lachance for the first French translation;
* [cilya](http://wordpress.org/support/profile/cilya) for the revised French translation.
* Ahrale for the Hebrew translation.

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

You have to edit your `functions.php` file. [Ask in the forum](http://wordpress.org/support/plugin/posts-in-sidebar) of this plugin, for more informations. Or you can use [a plugin](http://wordpress.org/plugins/simple-image-sizes/) that can do that for you.

== Screenshots ==

1. The widget panel
2. A simple output of the widget: title, excerpt and link to the entire archive.
3. Displaying the featured image, floating left.
4. The same image as before, but in larger size.
5. The introductory text for the widget.
6. Displaying the full set of items (categories, date, author, tags, and so on).

== Changelog ==

= 1.12-dev =

* NEW: added option for rich content.
* Code improvements.

= 1.11 =

* FIX: image align has been fixed (thanks to Clarry).

= 1.10 =

* FIX: If the post is password protected, now the post password form is displayed before showing the post.
* NEW: Now the user-defined excerpt can display a paragraph break, if any.
* NEW: Added Hebrew translation, thanks to Ahrale.
* Other minor changes.

= 1.9 =

* NEW: The space after each line can be defined via widget interface.
* NEW: The featured image can be aligned with text.
* NEW: Added `apply_filters` where needed.
* FIX: HTML structure for the archive link is now W3C valid, thanks to [cilya](http://wordpress.org/support/profile/cilya) for reporting it.
* Updated French translation, thanks to [cilya](http://wordpress.org/support/profile/cilya).
* Minor bug fixings.

= 1.8 =

* New: added post format as option to get posts.
* New: added option for link to custom post type archive.
* New: added option for link to post format archive.
* Other minor changes.

= 1.7 =

* New: The widget can display the author of the post.
* New: Now the user can choose which type of posts to display: posts, pages, custom post types, etc.
* New: The widget can display the full content (as in single posts).
* New: Now the user can add a custom "Read more" text.
* Added French translation by Thérèse Lachance.
* Code improvements and sanitization.

= 1.6 =

* New: if in single post, the user can now stylize the current post in the sidebar (feature request from lleroy).

= 1.5 =

* New: Now the title of the widget can be linked to a user-defined URL (feature request from Mike).

= 1.4 =

* New: Now the user can add an introductory text to the widget (feature request from Mike).

= 1.3 =

* New: The date can be linkified or not.
* New: The widget panel now shows empty categories and tags.
* New: The 'No posts yet.' text can be customized.
* Bug fix: The markup no longer shows empty containers.
* Some minor enhancements.

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

= 1.10 =

Bugfix for password-protected posts.

= 1.2 =

Version 1.2 has changed the option to display the text of the post. When upgrading to version 1.2, check every Posts in Sidebar widget at section The text of the post to make sure that the option fits your needs.

= 1.0.2 =

No notice to display.

= 1.0.1 =

No notice to display.

= 1.0 =

No notice to display.
